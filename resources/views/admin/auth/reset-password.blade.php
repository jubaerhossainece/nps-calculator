@extends('admin.layouts.auth')
@section('page-title', 'Password reset to NPS')
@section('form-title','Password reset to NPS')
@section('content')

        <form method="POST" action="{{ route('password.email') }}">
            @csrf

            <!-- Password Reset Token -->
            <input type="hidden" name="token" value="{{ $request->route('token') }}">

            <!-- Email Address -->
            <div class="form-group mb-2">
                <div class="input-group">
                    <input type="email" name="email" class="form-control" placeholder="Enter Email" required="">
                </div>
            </div>

            <!-- Password -->
            <div class="form-group mb-2">
                <div class="input-group">
                    <input type="password" name="password" class="form-control" placeholder="Enter password" required="">
                </div>
            </div>

            <!-- Confirm Password -->
            <div class="form-group mb-2">
                <div class="input-group">
                    <input type="password" name="password_confirmation" class="form-control" placeholder="Enter confirm password" required="">
                </div>
            </div>

            <div class="form-group mb-2">
                <div class="input-group">
                    <span class="input-group-append"> <button type="submit" class="btn btn-primary waves-effect waves-light">Reset</button> </span>
                </div>
            </div>

        </form>

@endsection