@extends('layouts.master')

@section('title')
    User
@endsection

@section('content')
    <h1>{{ $user->name }}</h1>
    <p>Account Status:
        @if($user->verified)
            Verified
        @else
            Not Verified
        @endif
    </p>
    @if( !$user->verified )
        <p>
          <a href="{{ route('user-verify') }}">Verify your account now</a>
        </p>
    @endif
@endsection
