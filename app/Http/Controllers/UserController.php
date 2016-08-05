<?php
namespace App\Http\Controllers;

use App\Http\Requests;
use App\User;
use Auth;
use Authy\AuthyApi as AuthyApi;
use DB;
use Hash;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Http\Request;
use Illuminate\Support\MessageBag;
use Twilio\Rest\Client;

class UserController extends Controller
{
    /**
     * Store a new user
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function createNewUser(Request $request, AuthyApi $authyApi)
    {
        $this->validate(
            $request, [
                'name' => 'required|string',
                'email' => 'required|unique:users|email',
                'password' => 'required',
                'country_code' => 'required',
                'phone_number' => 'required|numeric'
            ]
        );

        $values = $request->all();
        $values['password'] = Hash::make($values['password']);

        DB::beginTransaction();

        $newUser = new User($values);
        $newUser->save();
        Auth::login($newUser);

        $authyUser = $authyApi->registerUser(
            $newUser->email,
            $newUser->phone_number,
            $newUser->country_code
        );
        if ($authyUser->ok()) {
            $newUser->authy_id = $authyUser->id();
            $newUser->save();
            $request->session()->flash(
                'status',
                "User created successfully"
            );

            $sms = $authyApi->requestSms($newUser->authy_id);
            DB::commit();
            return redirect()->route('user-show-verify');
        } else {
            $errors = $this->getAuthyErrors($authyUser->errors());
            DB::rollback();
            return view('newUser', ['errors' => new MessageBag($errors)]);
        }
    }

    /**
     * This controller function shows the current user status
     *
     * @param Authenticatable $user Current user
     * @return mixed Response view
     */
    public function show(Authenticatable $user)
    {
        return view('showUser', ['user' => $user]);
    }

    /**
     * This controller function handles the submission form
     *
     * @param Request $request Current User Request
     * @param Authenticatable $user Current User
     * @param AuthyApi $authyApi Authy Client
     * @return mixed Response view
     */
    public function verify(Request $request, Authenticatable $user,
                           AuthyApi $authyApi, Client $client)
    {
        $token = $request->input('token');
        $verification = $authyApi->verifyToken($user->authy_id, $token);

        if ($verification->ok()) {
            $user->verified = true;
            $user->save();
            $this->sendSmsNotification($client, $user);

            return redirect()->route('user-index');
        } else {
            $errors = $this->getAuthyErrors($verification->errors());
            return view('verifyUser', ['errors' => new MessageBag($errors)]);
        }
    }

    /**
     * This controller function handles the verification code resent
     *
     * @param Request $request Current User Request
     * @param Authenticatable $user Current User
     * @param AuthyApi $authyApi Authy Client
     * @return mixed Response view
     */
    public function verifyResend(Request $request, Authenticatable $user,
                                 AuthyApi $authyApi)
    {
        $sms = $authyApi->requestSms($user->authy_id);

        if ($sms->ok()) {
            $request->session()->flash(
                'status',
                'Verification code re-sent'
            );
            return redirect()->route('user-show-verify');
        } else {
            $errors = $this->getAuthyErrors($sms->errors());
            return view('verifyUser', ['errors' => new MessageBag($errors)]);
        }
    }

    private function getAuthyErrors($authyErrors)
    {
        $errors = [];
        foreach ($authyErrors as $field => $message) {
            array_push($errors, $field . ': ' . $message);
        }
        return $errors;
    }

    private function sendSmsNotification($client, $user)
    {
        $twilioNumber = config('services.twilio')['number'] or die(
            "TWILIO_NUMBER is not set in the environment"
        );
        $messageBody = 'You did it! Signup complete :)';

        $client->messages->create(
            $user->fullNumber(),    // Phone number which receives the message
            [
                "from" => $twilioNumber, // From a Twilio number in your account
                "body" => $messageBody
            ]
        );
    }
}
