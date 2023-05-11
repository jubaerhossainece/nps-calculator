@extends('admin.layouts.app')
@push('styles')

<style>
    /* profile image */
    .image-wrapper{
        height: 154px;
        width: 154px;
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
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-6 form-body">
                    <div class="image-wrapper" style="background-image: url({{ $admin->image ?? asset('assets/images/image.jpg')}});">
                        <input type="file" id="input-image" class="profile-image">
                    </div>
                </div>
            </div>
        </div>
        


@endsection

@push('scripts')
<script>
    
</script>
@endpush