@extends('layouts.master')

@section('title')
    Sign Up
@endsection

@section('content')
    <div class="container">
      <h1>We're going to be *BEST* friends</h1>
      <p> Thanks for your interest in signing up! Can you tell us a bit about yourself?</p>

      {!! Form::open(['url' => route('user-create')]) !!}
          <div class="form-group">
              {!! Form::label('name') !!}
              {!! Form::text('name', '', ['class' => 'form-control', 'placeholder' => 'Zingelbert Bembledack']) !!}
          </div>
          <div class="form-group">
              {!! Form::label('email') !!}
              {!! Form::text('email', '', ['class' => 'form-control', 'placeholder' => 'me@mydomain.com']) !!}
          </div>
          <div class="form-group">
              {!! Form::label('password') !!}
              {!! Form::password('password', ['class' => 'form-control']) !!}
          </div>
          <div class="form-group">
              {!! Form::label('country_code', 'Country Code') !!}
              {!! Form::text('country_code', '', ['class' => 'form-control', 'id' => 'authy-countries']) !!}
          </div>
          <div class="form-group">
              {!! Form::label('phone_number', 'Phone number') !!}
              {!! Form::text('phone_number', '', ['class' => 'form-control', 'id' => 'authy-cellphone']) !!}
          </div>
          <div class="form-group">
              <button type="submit" class="btn btn-primary">Sign up</button>
          </div>
      {!! Form::close() !!}
    </div>
@endsection
