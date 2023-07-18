@extends('admin.layouts.auth')
@section('page-title', 'Sign In to NPS')
@section('form-title','Sign In to NPS')

<style>
    label{
        color: #4d4d4d;
    }

    .header{
        background-color: white;
        text-align: center;
    }

    .header h3{
        background: -webkit-linear-gradient(right, #2558dc, #00b4ef);
        background: -o-linear-gradient(right, #2558dc, #00b4ef);
        background: -moz-linear-gradient(right, #2558dc, #00b4ef);
        background: linear-gradient(right, #2558dc, #00b4ef); 
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }

    button.login{
        width: 100%;
        background-image: -webkit-linear-gradient(to left, #2558dc, #00b4ef);
        background-image: -o-linear-gradient(to left, #2558dc, #00b4ef);
        background-image: -moz-linear-gradient(to left, #2558dc, #00b4ef);
        background-image: linear-gradient(to left, #2558dc, #00b4ef);
        padding: 15px, 28px, 15px, 28px;
        border: none;
        border-radius: 4px;
        height: 48px;
        color: white;
        font-size: 16px;
        line-height: 19px;
    }
    
    #password-container{
        position: relative;
    }
    
    #password-container input[type="password"],
    #password-container input[type="text"]{
        width: 100%;
        padding: 12px 36px 12px 12px;
        box-sizing: border-box;
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
        top: 60%;
        right: 3%;
        cursor: pointer;
        color: lightgray;
    }
</style>
@section('content')

    <div class="header p-2 position-relative">
        <h3>Login</h3>
    </div>
    <form action="{{ route('login') }}" method="post" class="pl-4 pr-4 m-0">
        @csrf
        <div class="form-group mb-3">
            <label for="">Your Email</label>
            <input class="form-control" name="email" type="email" required="" placeholder="Your Email Address">
        </div>

        <div class="form-group mb-3" id="password-container">
            <label for="">Your Password</label>
            <input class="form-control" name="password" type="password" required="" placeholder="Password">
            <i class="fas fa-eye-slash" id="password-icon"></i>
        </div>

        <!-- <div class="form-group mb-3">
            <div class="custom-control custom-checkbox">
                <input type="checkbox" name="remember" class="custom-control-input" id="checkbox-signin">
                <label class="custom-control-label" for="checkbox-signin">Remember me</label>
            </div>
        </div> -->

        <div class="form-group text-center mt-5 mb-4">
            <button class="login" type="submit"> Login
            </button>
        </div>

        <!-- <div class="form-group row mb-0">
            <div class="col-sm-7">
                <a href="{{ route('password.request') }}"><i class="fa fa-lock mr-1"></i> Forgot your password?</a>
            </div>
            <div class="col-sm-5 text-right">
                <a href="pages-register.html">Create an account</a>
            </div>
        </div> -->
    </form>
@endsection

@push('scripts')
<script>
    $(document).ready(function(){

        $("input[name='password']+i").click(function(){
            console.log("clicked");
            const passInput = ($(this).prev());

            let type =  passInput.attr("type") === "password" ? "text" : "password";

            $(this).toggleClass("fa-eye fa-eye-slash");

            passInput.attr("type", type);
        });
    });
</script>
@endpush