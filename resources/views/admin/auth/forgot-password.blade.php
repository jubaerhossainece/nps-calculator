@extends('admin.layouts.auth')
@section('page-title', 'Sign In to NPS')
@section('form-title','Sign In to NPS')
@section('content')

    <form method="POST" action="{{ route('password.email') }}" class="p-3">
        @csrf
        <div class="alert alert-info alert-dismissible">
            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
            Enter your <b>Email</b> and instructions will be sent to you!
        </div>
        <div class="form-group mb-0">
            <div class="input-group">
                <input type="email" name="email" class="form-control" placeholder="Enter Email" required="">
                <span class="input-group-append"> <button type="submit" class="btn btn-primary waves-effect waves-light">Reset</button> </span>
            </div>
        </div>

    </form>
@endsection
