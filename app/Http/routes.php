<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

//Home related routes
Route::get(
    '/', ['as' => 'home', function () {
        return response()->view('home');
    }]
);

// User related routes
Route::get(
    '/user',
    ['as' => 'user-index',
     'middleware' => 'auth',
     'uses' => 'UserController@show']
);

Route::get(
    '/user/new', ['as' => 'user-new', function() {
        return response()->view('newUser');
    }]
);

Route::post(
    '/user/create',
    ['uses' => 'UserController@createNewUser', 'as' => 'user-create', ]
);

Route::get(
    '/user/verify', ['as' => 'user-show-verify', function() {
        return response()->view('verifyUser');
    }]
);

Route::post(
    '/user/verify',
    ['uses' => 'UserController@verify', 'as' => 'user-verify', ]
);

Route::post(
    '/user/verify/resend',
    ['uses' => 'UserController@verifyResend',
     'middleware' => 'auth',
     'as' => 'user-verify-resend']
);
