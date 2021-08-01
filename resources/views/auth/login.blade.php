@extends('layouts.app_auth')
@section('title-block')
    Login
@endsection

@section('content')
<div class="login-page">

<form class="login-form" action = "{{route('login-submith')}}" method="post">
    @if(session('message'))
        <div class="alert alert-danger">
            {{session('message')}}
        </div>
    @endif
    @if(session('message_success'))
        <div class="alert alert-success">
            {{session('message_success')}}
        </div>
        @endif
    @csrf
    <div class="form-group" >
        <label for="email">Email</label>
        <input type="text" name="login" placeholder="input email" id="login" class="form-control">
    </div>
    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" name="password" placeholder="input password" id="password" class="form-control">
    </div>
    <div class="form-group">
        <button type="submit" class="btn btn-success">Login</button>
        <p class="message">Not registered? <a href="{{route('registration')}}">Create an account</a></p>
        <p class="message">Forgot your password? <a href="{{route('forgot-password')}}">Reset password</a></p>
    </div>

</form>

</div>
@endsection
