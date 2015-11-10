@extends('layouts.master')

@section('title')
    Verify
@endsection

@section('content')
    <h1>Just To Be Safe...</h1>
    <p>
        Your account has been created, but we need to make sure you're a human
        in control of the phone number you gave us. Can you please enter the
        verification code we just sent to your phone?
    </p>
    {!! Form::open(['url' => route('user-verify')]) !!}
        <div class="form-group">
            {!! Form::label('token') !!}
            {!! Form::text('token', '', ['class' => 'form-control']) !!}
        </div>
        <button type="submit" class="btn btn-primary">Verify Token</button>
    {!! Form::close() !!}

    <hr>
    {!! Form::open(['url' => route('user-verify-resend')]) !!}
        <button type="submit" class="btn">Resend code</button>
    {!! Form::close() !!}
@endsection
