@extends('layouts.master')

@section('title')
    Home
@endsection

@section('content')
    <h1>Account Verification with Laravel</h1>

    <p>This demo application will show you how to implement user account verification with Twilio-powered
        <a href="http://authy.com">Authy</a> (powered by <a href="http://twilio.com">Twilio</a>) in Laravel.
    </p>
    <p><a href="{{ route('user-new') }}">Sign up</a> for an account to see how it works!</p>
@endsection
