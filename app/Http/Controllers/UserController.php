<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Hash;
use App\User;
use Auth;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\MessageBag;
use Services_Twilio as TwilioRestClient;
use Authy\AuthyApi as AuthyApi;

class UserController extends Controller
{
    /**
     * Store a new user
     *
     * @param  \Illuminate\Http\Request  $request
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

        $newUser = new User($values);
        Auth::login($newUser);

        $authyUser = $authyApi->registerUser($newUser->email, $newUser->phone_number, $newUser->country_code);
        if($authyUser->ok())
        {
            $newUser->authy_id = $authyUser->id();
            $newUser->save();
            $request->session()->flash(
                'status',
                "User created successfully"
            );

            $sms = $authyApi->requestSms($newUser->authy_id);
        }
        else
        {
            $errors = [];
            foreach($authyUser->errors() as $field => $message) {
                array_push($errors, $field . ': ' . $message);
            }
            return view('newUser',['errors' => new MessageBag($errors)]);
        }

        return redirect()->route('user-show-verify');
    }

    public function verify(Request $request, Authenticatable $user, AuthyApi $authyApi)
    {
        return redirect()->route('user-show-verify');
    }
}
