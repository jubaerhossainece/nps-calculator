@extends('admin.layouts.auth')
@section('page-title', '2fa Verification In to NPS')
@section('form-title','2fa Verification In to NPS')
@section('content')
    <form action="{{ route('twoFaVerification.verify') }}" method="post" class="p-3">
        @csrf
        <div class="form-group mb-3">
            <input class="form-control" name="code" type="text" required="" placeholder="Enter 2fa Verification Code">
        </div>
        <div class="form-group text-center mt-5 mb-4">
            <button class="btn btn-primary waves-effect width-md waves-light" type="submit"> Verify
            </button>
        </div>
    </form>
@endsection
