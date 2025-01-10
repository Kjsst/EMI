<!-- Main Content -->
@extends('layouts.app')
@section('pageTitle', 'setting')

@section('content')
    <div id="main-content">
        <div class="container">
            <div class="setting-page">

                <div id="Tab1" class="tabcontent" style="display: block;">
                    <div class="profile-setting">
                        <p>Setting QR Code and APK URL Information</p>
                        <form action="{{route('setting.update')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-control-s">
                                <div>
                                    <label for="name">1. Brahmastra</label>
                                </div>
                                {{--                                    <input name="brahmastra_google" type="text" value="{{$setting['brahmastra_google'] ?? null}}" placeholder="APK Link">--}}

                                <input name="brahmastra_google" type="file"
                                       value="{{$setting['brahmastra_google'] ?? null}}">
                                @if(isset($setting['brahmastra_google']))
                                    <span>
                                        {{ $setting['brahmastra_google'] }}
                                    </span>
                                @endif
                                <div class="admin-profile" onclick="addPhoto('brahmastra_google_image')">
                                    <div class="image-upload">
                                        @if(isset($setting['brahmastra_google_image']))
                                            <img src="{{url('/storage/images/'.$setting['brahmastra_google_image'])}}"
                                                 height="70px" width="70px" id="brahmastra_google_image_image"/>
                                        @else
                                            <img src="{{asset('/assets/img/photo_camera.svg')}}" height="70px"
                                                 width="70px" id="brahmastra_google_image_image"/>
                                        @endif
                                    </div>
                                    <div class="upload-camera">
                                        <img src="{{asset('/images/camera.png')}}" alt=""/>
                                    </div>
                                    <input style="display: none" type="file" name="brahmastra_google_image"
                                           id="brahmastra_google_image" accept="image/*"
                                           onchange="displaySelectedImage('brahmastra_google_image')"/>
                                </div>
                            </div>
                            <div class="form-control-s">
                                <div>
                                    <label for="name">2. Brahmastra prime</label>
                                </div>
                                <input name="brahmastra_quick" type="file"
                                       value="{{$setting['brahmastra_quick'] ?? null}}" placeholder="APK Link">
                                @if(isset($setting['brahmastra_quick']))
                                    <span>
                                        {{ $setting['brahmastra_quick'] }}
                                    </span>
                                @endif
                                <div class="admin-profile" onclick="addPhoto('brahmastra_quick_image')">
                                    <div class="image-upload">
                                        @if(isset($setting['brahmastra_quick_image']))
                                            <img src="{{url('/storage/images/'.$setting['brahmastra_quick_image'])}}"
                                                 height="70px" width="70px" id="brahmastra_quick_image_image"/>
                                        @else
                                            <img src="{{asset('/assets/img/photo_camera.svg')}}" height="70px"
                                                 width="70px" id="brahmastra_quick_image_image"/>
                                        @endif                                        </div>
                                    <div class="upload-camera">
                                        <img src="{{asset('/images/camera.png')}}" alt=""/>
                                    </div>
                                    <input style="display: none" type="file" name="brahmastra_quick_image"
                                           id="brahmastra_quick_image" accept="image/*"
                                           onchange="displaySelectedImage('brahmastra_quick_image')"/>
                                </div>

                            </div>
                            <div class="form-control-s">
                                <div>
                                    <label for="name">3. Rambaan</label>
                                </div>
                                <input name="rambaan_quick" type="file" value="{{$setting['rambaan_quick'] ?? null}}"
                                       placeholder="APK Link">
                                @if(isset($setting['rambaan_quick_image']))
                                    <span>
                                        {{ $setting['rambaan_quick_image'] }}
                                    </span>
                                @endif
                                <div class="admin-profile" onclick="addPhoto('rambaan_quick_image')">
                                    <div class="image-upload">
                                        @if(isset($setting['rambaan_quick_image']))
                                            <img src="{{url('/storage/images/'.$setting['rambaan_quick_image'])}}"
                                                 height="70px" width="70px" id="rambaan_quick_image_image"/>
                                        @else
                                            <img src="{{asset('/assets/img/photo_camera.svg')}}" height="70px"
                                                 width="70px" id="rambaan_quick_image_image"/>
                                        @endif                                        </div>
                                    <div class="upload-camera">
                                        <img src="{{asset('/images/camera.png')}}" alt=""/>
                                    </div>
                                    <input style="display: none" type="file" name="rambaan_quick_image"
                                           id="rambaan_quick_image" accept="image/*"
                                           onchange="displaySelectedImage('rambaan_quick_image')"/>
                                </div>
                            </div>
                            <div class="form-control-s">
                                <label for="Name">4. Welcome message</label>
                                <input value="{{ $setting['welcome_message'] }}" name="welcome_message" type="text" placeholder="Welcome message">
                            </div>
                            <div class="setting-page-button">
                                <button type="submit" class="btn-dark">save</button>
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
                    const image = document.getElementById($name + '_image');
                    image.src = e.target.result;
                }
                reader.readAsDataURL(file);
            }
        }
    </script>
@endpush
