@extends('admin.layouts.app')
@push('styles')

<style>
    .form-body{
        background-color: white;
        padding: 2rem 2.5rem;
    }

    /* profile image */
    .image-wrapper{
        height: 114px;
        width: 114px;
        border-radius: 50%;
        position: relative;
        border: 4px solid #1287E6;
        margin: 0px auto;
        background-repeat: no-repeat;
        background-size: 100% 100%;
        overflow: hidden;
    }

    .camera{
        position: absolute;
        bottom: 7px;
        left: 45%;
        transition: 0.5s;
        color: #e6e6e6;
        z-index: 1;
        font-size: 20px;
        cursor: pointer;
    }

    .profile-image{
        position: absolute;
        bottom: 0;
        left: 0;
        outline: none;
        color: transparent;
        width: 100%;
        box-sizing: border-box;
        padding: 0px;
        cursor: pointer;
        transition: 0.5s;
        background-color: rgba(0,0,0,0.5);
        height: 30px;
    }

    .profile-image::-webkit-file-upload-button{
        visibility: hidden;
    }

    .profile-image::file-selector-button{
        visibility: hidden;
    }

    .profile-image::-ms-browse{
        visibility: hidden;
    }

    .profile-image::before {
        content: '\f030';
        font: var(--fa-font-solid);
        margin-left: 40px;
        color: white;
        position: absolute;
        bottom: 5px;
        right: 43%;
        font-size: 19px;
    }
</style>
@endpush

@section('content')
    <div class="container mt-5 mb-5">
        <div class="row justify-content-center">
            <div class="col-md-6 form-body rounded">
                @if(session('message'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Success!</strong> {{session('message')}}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @elseif(session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> {{session('error')}}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @endif
            <form action="{{route('admin.profile.update')}}" method="post" role="form" class="php-email-form" enctype="multipart/form-data"> 
                @csrf
                @method('PUT')
                
                <div class="form-group">
                    <div class="image-wrapper" style="background-image: url({{ $admin->image ? Storage::url('public/admin/'. $admin->image) : asset('assets/images/7074311_3551739.jpg')}});">
                        <input type="file" id="input-image" name="image" class="profile-image">
                    </div>
                    @error('image')
                    <div class="text-danger d-flex justify-content-center">
                        <strong>{{$message}}</strong>
                    </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="name">Name</label>
                    <input type="text" class="form-control" id="name" name="name" value="{{$admin->name ?? ''}}" placeholder="Your name">
                    @error('name')
                    <div class="text-danger">
                    <strong>{{$message}}</strong>
                    </div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" class="form-control" id="email" name="email" value="{{$admin->email ?? ''}}" placeholder="Your email address">
                    @error('email')
                    <div class="text-danger">
                    <strong>{{$message}}</strong>
                    </div>
                    @enderror
                </div>

                <div class="form-row d-flex justify-content-between">
                    <a href="{{route('admin.profile.show')}}" class="btn btn-lg btn-secondary">Cancel</a>
                    <button type="submit" class="btn btn-lg btn-info">Save</button>
                </div>
            </form>

            </div>
        </div>
    </div>
        
@endsection

@push('scripts')
<script>
    function readURL(input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                console.log(e.target.result);
                $('.image-wrapper').css('background-image', 'url('+e.target.result +')');
                $('.image-wrapper').hide();
                $('.image-wrapper').fadeIn(650);
            }
            $("#avatar-btn").css('display','block');
            reader.readAsDataURL(input.files[0]);
        }
    }

    //Image upload (Profile image- user)
    $("#input-image").change(function() {
        console.log(this);
        readURL(this);
    });
</script>
@endpush