<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8"/>
    <title>  @yield('page-title', 'NPS')</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Responsive bootstrap 4 admin template" name="description"/>
    <meta content="Coderthemes" name="author"/>
    <meta http-equiv="X-UA-Compatible" content="IE=edge"/>
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{asset('assets/images/fab-icon.png')}}">
    <!-- App css -->
    <link href="{{ asset('assets/css/bootstrap.min.css')}}" rel="stylesheet" type="text/css" id="bootstrap-stylesheet"/>
    <link href="{{ asset('assets/css/icons.min.css')  }}" rel="stylesheet" type="text/css"/>
    <link href="{{ asset('assets/css/app.min.css') }}" rel="stylesheet" type="text/css" id="app-stylesheet"/>
    <link rel="stylesheet" href="https://cdn.bootcss.com/toastr.js/latest/css/toastr.min.css">
    <style>
        .login-header {
            box-sizing: border-box;
            width: 100%;
            padding: 19px;
            background: #FFFFFF;
            box-shadow: 0px 1px 8px rgba(0, 0, 0, 0.1);
        }

        .login-header img {
            padding-left: 120px;
        }

        .authentication-page.enlarged {
            min-height: 100px;
            background: #F4FCFF;
        }

        .login-footer {
            position: absolute;
            width: 100%;
            padding: 28px 0px;
            left: 0px;
            bottom: 0px;
            right: 0px;
            background: #222222;
            border-top: 1px solid #EDF2F4;
            margin: 0px;
        }

        .login-footer .div-right{
            display: flex;
            justify-content: space-around;
        }

        .login-footer .div-left span {
            color: #EEEEEE;
            font-size: 14px;
            font-weight: 400;
            line-height: 16px;
            letter-spacing: 0em;
            padding-left: 150px;
        }


        .login-footer .div-right a{
            font-style: normal;
            font-weight: 400;
            font-size: 14px;
            line-height: 16px;
            color: #EEEEEE;
        }

    </style>
</head>

<body class="authentication-page">
<div class="login-header">
    <img src="{{asset('assets/images/logo.nps.png')}}" alt="">
</div>
<div class="account-pages my-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card mt-4">
                    <div class="card-body p-4 mt-2">

                        @yield('content')
                    </div>
                    <!-- end card-body -->
                </div>
                <!-- end card -->

                <!-- end row -->

            </div>
            <!-- end col -->
        </div>
        <!-- end row -->

    </div>
</div>
<div class="login-footer row">
    <div class="div-left col-md-6">
        <span>© 2023 – Riseup Labs | All Rights Reserved</span>
    </div>
    <div class="div-right col-md-6">
        <a href="">Terms of Service</a>
        <a href="">Privacy Policy</a>
        <a href="">Security Statement</a>
    </div>
</div>
<!-- Vendor js -->
<script src="{{ asset('assets/js/vendor.min.js') }}"></script>

<!-- App js -->
<script src="{{ asset('assets/js/app.min.js') }}"></script>
<script src="https://cdn.bootcss.com/toastr.js/latest/js/toastr.min.js"></script>
{!! Toastr::message() !!}

</body>

</html>
