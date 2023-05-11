@extends('admin.layouts.app')
@push('styles')

<style>
    /* profile image */
    .form-body{
        background-color: white;
        padding: 2rem 2.5rem;
    }

    .avatar-upload {
        position: relative;
        max-width: 114px;
        margin: 0px auto;
    }
    
    .avatar-upload .avatar-preview {
        width: 114px;
        height: 114px;
        position: relative;
        border-radius: 100%;
        border: 4px solid #1287E6;
        box-shadow: 0px 2px 4px 0px rgba(0, 0, 0, 0.1);
    }
    .avatar-upload .avatar-preview > div {
        width: 100%;
        height: 100%;
        border-radius: 100%;
        background-size: cover;
        background-repeat: no-repeat;
        background-position: center;
    }
</style>
@endpush

@section('content')
        <div class="container mt-5 mb-5">
            <div class="row justify-content-center">
                <div class="col-md-6 form-body rounded">
                    <div class="form-group">
                        
                        <div class="avatar-upload">
                            <div class="avatar-preview">
                                <div id="imagePreview" style="background-image: url({{ $admin->image ? Storage::url('public/admin/'. $admin->image) : asset('assets/images/7074311_3551739.jpg')}});">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="name">Name</label>
                        <div>
                        <input type="text" class="form-control" id="name" name="name" value="{{$admin->name ?? ''}}" readonly>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="email">Email</label>
                        <div>
                        <input type="text" class="form-control" id="name" name="name" value="{{$admin->email ?? ''}}" readonly>


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
    
</script>
@endpush