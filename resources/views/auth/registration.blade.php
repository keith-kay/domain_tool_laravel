<!-- resources/views/register.blade.php -->

@extends('layouts.app')

@section('content')

<div class="formContainer text-center">
    <form class="signupForm" action="{{route('register-user')}}" method='POST'>
            @if(Session::has('success'))
        <div class="alert alert-success">{{Session::get('success')}}</div>
            @endif
            @if(Session::has('fail'))
        <div class="alert alert-danger">{{Session::get('fail')}}</div>
            @endif
        <div class="form-group my-3">
            <img src="{{ asset('images/bslogo.png') }}" alt="Logo" style="max-width: 70%;">
            <h4>Login</h4>

            @csrf
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text"  class="form-control" id="username" name="name" placeholder="Username" value="{{old('name')}}">
                <span class="text-danger">@error('name') {{$message}} @enderror</span>
            </div>

            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" required class="form-control" id="email" name="email" placeholder="Enter Email" value="{{old('email')}}">
                <span class="text-danger">@error('email') {{$message}} @enderror</span>
            </div>

            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" required class="form-control" id="password" name="password" placeholder="Enter Password">
                <span class="text-danger">@error('password') {{$message}} @enderror</span>
            </div>

            <button class="btn btn-nav fw-bold" type="submit">Register</button>
        </div>
    </form>
</div>

@endsection