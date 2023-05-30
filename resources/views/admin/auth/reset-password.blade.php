@extends('admin.layouts.auth')
@section('page-title', 'Password reset to NPS')
@section('form-title', 'Password reset to NPS')
@section('content')

    <form method="POST" action="{{ route('password.update') }}">
        @csrf
        
        @include('components.error-list')

        <input type="hidden" name="token" value="{{ $request->route('token') }}">

        <div class="form-group">
            <label for="email">Email address:</label>
            <input type="email" name="email" class="form-control" placeholder="Enter Email" required="">
        </div>
        <div class="form-group">
            <label for="pwd">Password:</label>
            <input type="password" name="password" class="form-control" placeholder="Enter password" required="">
        </div>
        <div class="form-group">
            <label for="pwd">Password:</label>
            <input type="password" name="password_confirmation" class="form-control" placeholder="Enter confirm password"
                required="">
        </div>
        <div class="form-group  mb-2">
            <button type="submit" class="btn btn-primary waves-effect waves-light">Reset</button>
        </div>
    </form>

@endsection
