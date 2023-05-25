@extends('admin.layouts.app')
@push('styles')
@endpush

@section('content')
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-6 form-body">
                <div class="form-group text-center">
                    <h3>2FA Setting</h3>
                </div>

                <div class="form-row d-flex justify-content-center">
                    @if(session('message'))
                        <div class="alert alert-success">
                            <strong>Success!</strong> {{session('message')}}
                        </div>
                    @endif
                    @if (auth()->user()->google2fa_enable_status)
                        <p class="card-text alert alert-info">Use this secret key <span class="text-info">{{ $secret }}</span> or scan
                            this QR code with  any authenticator app to connect. You can use any authenticator app to connect.</p>
                        <div>
                            {!! $QR_Image !!}
                            <div class="text-center mt-3">
                                <button type="submit" class="btn btn-lg btn-danger"
                                    onclick="changeGoogleTwoFaEnableStatus()">Disable 2FA
                                </button>
                            </div>
                        </div>
                        
                    @else
                       <div>
                        <p class="alert alert-info">
                            This dramatically improves the security of login attempts. 2FA has also been shown to block nearly all automated bot-related attacks.Thanks.
                        </br>
                            Please make sure you are using 2FA and connect with any authenticator app on your device.
                        </p>
                        <div class="text-center mt-3">
                            <button type="submit" class="btn btn-lg btn-success" onclick="changeGoogleTwoFaEnableStatus()">Enable 2FA
                            </button>
                        </div>
                       </div>
                    @endif
                </div>

            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function changeGoogleTwoFaEnableStatus() {
            // console.log(flag);
            $.ajax({
                type: 'GET',
                url: '/2fa-status-modify',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                success: function(response) {
                    location.reload();
                },
            })

        }
    </script>
@endpush
