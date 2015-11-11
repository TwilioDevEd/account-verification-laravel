<?php
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\User;

class UserControllerTest extends TestCase
{
    use DatabaseTransactions;

    function testNewUser() {
        // Given
        $this->startSession();
        $validName = 'Some name';
        $validEmail = 'sname@enterprise.awesome';
        $validPassword = 'strongpassword';
        $validCountryCode = '1';
        $validPhoneNumber = '5558180101';
        $this->assertCount(0, User::all());

        $mockAuthyApi = Mockery::mock('Authy\AuthyApi')
                            ->makePartial();
        $mockAuthyUser = Mockery::mock();

        $mockAuthyApi
            ->shouldReceive('registerUser')
            ->with($validEmail,
                   $validPhoneNumber,
                   $validCountryCode
            )
            ->once()
            ->andReturn($mockAuthyUser);
        $mockAuthyUser
            ->shouldReceive('ok')
            ->once()
            ->andReturn(true);
        $mockAuthyUser
            ->shouldReceive('id')
            ->once()
            ->andReturn('authy_id');
        $mockAuthyApi
            ->shouldReceive('requestSms')
            ->with('authy_id')
            ->once();

        $this->app->instance(
            'Authy\AuthyApi',
            $mockAuthyApi
        );

        // When
        $response = $this->call(
            'POST',
            route('user-create'),
            ['name' => $validName,
             'email' => $validEmail,
             'password' => $validPassword,
             'country_code' => $validCountryCode,
             'phone_number' => $validPhoneNumber,
             '_token' => csrf_token()]
        );

        // Then
        $this->assertCount(1, User::all());
        $user = User::first();
        $this->assertEquals($user->name, $validName);
        $this->assertEquals($user->email, $validEmail);
        $this->assertEquals($user->country_code, $validCountryCode);
        $this->assertEquals($user->authy_id, 'authy_id');
        $this->assertEquals($user->verified, false);
        $this->assertRedirectedToRoute('user-show-verify');
        $this->assertSessionHas('status');
        $flashMessage = $this->app['session']->get('status');
        $this->assertEquals(
            $flashMessage,
            "User created successfully"
        );
    }

    function testVerifyResend() {
        // Given
        $this->startSession();
        $userData = [
            'name' => 'Some name',
            'email' => 'sname@enterprise.awesome',
            'password' => 'strongpassword',
            'country_code' => '1',
            'phone_number' => '5558180101'
        ];

        $user = new User($userData);
        $user->authy_id = 'authy_id';
        $user->save();

        $this->be($user);

        $mockAuthyApi = Mockery::mock('Authy\AuthyApi')
                            ->makePartial();
        $mockSms = Mockery::mock();

        $mockAuthyApi
            ->shouldReceive('requestSms')
            ->with('authy_id')
            ->once()
            ->andReturn($mockSms);
        $mockSms
            ->shouldReceive('ok')
            ->once()
            ->andReturn(true);

        $this->app->instance(
            'Authy\AuthyApi',
            $mockAuthyApi
        );

        // When
        $response = $this->call(
            'POST',
            route('user-verify-resend'),
            ['_token' => csrf_token()]
        );

        // Then
        $this->assertRedirectedToRoute('user-show-verify');
        $this->assertSessionHas('status');
        $flashMessage = $this->app['session']->get('status');
        $this->assertEquals(
            $flashMessage,
            'Verification code re-sent'
        );
    }
}
