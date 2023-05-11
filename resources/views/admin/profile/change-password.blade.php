@extends('admin.layouts.app')
@push('styles')

<style>
    /* profile image */
    .form-body{
        background-color: white;
        padding: 2rem 2.5rem;
    }
    .password-container{
        position: relative;
    }
    .password-container input[type="password"],
    .password-container input[type="text"]{
        width: 100%;
        padding: 12px 36px 12px 12px;
        box-sizing: border-box;
    }
    .fa-eye, .fa-eye-slash{
        position: absolute;
        top: 28%;
        right: 4%;
        cursor: pointer;
        color: lightgray;
    }
</style>
@endpush

@section('content')
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-6 form-body rounded">
                <div class="form-group text-center">
                    <h3>Reset Password</h3>
                </div>
                <div class="form-group">
                    <label for="old-pass">Old Password</label>
                    <div class="password-container">
                    <input type="password" class="form-control" id="old-pass" name="old_password" placeholder="old password">
                    <i class="fas fa-eye-slash" id="eye-old"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="new-pass">New Password</label>
                    <div class="password-container">
                    <input type="password" class="form-control" id="new-pass" name="new_password" placeholder="new password">
                    <i class="fas fa-eye-slash" id="eye-new"></i>
                    </div>
                </div>

                <div class="form-group">
                    <label for="confirm-pass">Confirm Password</label>
                    <div class="password-container">
                    <input type="password" class="form-control" id="confirm-pass" name="new_password_confirmation" placeholder="confirm password">
                    <i class="fas fa-eye-slash" id="eye-confirm"></i>
                    </div>
                </div>

                <div class="form-row d-flex justify-content-center">
                    <a href="{{route('admin.profile.edit')}}" class="btn btn-lg btn-info">Edit Profile</a>
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
<script>
    const passwordInput = $("input[type='password']");
    $("input[type='password']+i").click(function(){
        const passInput = ($(this).prev());

        let type =  passInput.attr("type") === "password" ? "text" : "password";

        $(this).toggleClass("fa-eye fa-eye-slash");

        passInput.attr("type", type);
    });
</script>
@endpush