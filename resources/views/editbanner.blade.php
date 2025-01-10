<!-- Main Content -->
@extends('layouts.app')
@section('pageTitle', 'merchant')
@section('content')
    <div id="main-content">
        <div class="container">
            <div class="setting-page">

                <div id="Tab1" class="tabcontent" style="display: block;">
                    <div class="profile-setting">
                        <p>Edit Banner</p>
                        <form action="{{route('banner.update',$banner->id)}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input name="url" type="hidden" value="{{$banner->id}}">

                            <div class="form-control-s">
                                <label for="Profile Photo">Aadhar Front</label>
                                <div class="admin-profile" onclick="addPhoto('banner')">
                                    <div class="image-upload">
                                        @if($banner->banner)
                                            <img src="{{url("storage/images/".$banner->banner)}}" height="70px" width="70px" id="banner"/>
                                        @else
                                            <img src="{{asset('/assets/img/photo_camera.svg')}}" height="70px" width="70px" id="banner"/>
                                        @endif
                                    </div>
                                    <div class="upload-camera">
                                        <img src="{{asset('/images/camera.png')}}" alt=""/>
                                    </div>
                                    <input style="display: none" type="file" name="banner" id="banner" accept="image/*"
                                            onchange="displaySelectedImage('banner')"/>
                                </div>
                            </div>

                            <div class="form-control-s">
                                <label for="url">Url</label>
                                <input name="url" type="text" value="{{$banner->url}}" placeholder="Url">
                                @error('url')
                                <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="setting-page-button">
                                <button type="submit" class="btn-dark">Save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection


@push('script')
    <script>
        function addPhoto($name) {
            document.getElementById($name).click()

        }
        function displaySelectedImage($name) {
            const fileInput = document.getElementById($name);
            const file = fileInput.files[0];

            if (file) {
                const reader = new FileReader();
                reader.onload = function (e) {
                    const image = document.getElementById($name+'_image');
                    image.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        }

    </script>
@endpush
