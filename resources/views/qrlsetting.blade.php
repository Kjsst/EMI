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
                        <form action="{{route('qrlsetting.update')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            @foreach($settings as $key=>$setting)
                                <div class="form-control-s" style="margin-bottom: 0 !important">
                                    <div>
                                        <label for="name">{{$key+1}}.
                                            @if ($setting->name == 'brahmastra_google')
                                                Brahmastra
                                            @elseif ($setting->name == 'brahmastra_quick')
                                                Brahmastra Prime
                                            @elseif ($setting->name == 'rambaan_quick_image')
                                                Rambaan
                                            @endif
                                        </label>
                                    </div>

                                    <input name="{{$setting->name}}" type="file" style="width: 225px !important"
                                       value="{{$setting->value ?? null}}" placeholder="APK Link">
                                    @if(isset($setting->value))
                                        <span>
                                            {{ $setting->value }}
                                        </span>
                                    @endif
                                    <div class="admin-profile" onclick="addPhoto('{{$setting->name}}_image')">
                                        <div class="image-upload">
                                        @if(isset($setting->value))
                                            <img src="{{url('/storage/images/'.$setting->value)}}"
                                                 height="70px" width="70px" id="{{$setting->name}}_image_image"/>
                                        @else
                                            <img src="{{asset('/assets/img/photo_camera.svg')}}" height="70px"
                                                width="70px" id="{{$setting->name}}_image_image"/>
                                        @endif
                                        </div>
                                        <div class="upload-camera">
                                            <img src="{{asset('/images/camera.png')}}" alt=""/>
                                        </div>
                                        <input style="display: none" type="file" name="{{$setting->name}}_image"
                                           id="{{$setting->name}}_image" accept="image/*"
                                           onchange="displaySelectedImage('{{$setting->name}}_image')"/>
                                    </div>

                                </div>
                                <div class="form-control-s" style="justify-content: flex-start !important; gap: 10px !important; margin-top: 0 !important">
                                        <span class="ps-3">
                                            URL :
                                        </span>
                                        @if($setting->url)
                                            <input name="{{$setting->name}}_url" value="{{$setting->url}}" type="text" placeholder="Add URL" />
                                        @else
                                            <input name="{{$setting->name}}_url" value="{{old('url')}}" type="text" placeholder="Add URL" />
                                        @endif
                                    </div>
                            @endforeach

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
        $(document).ready(function() {
            var title = {!! json_encode($setting) !!};
            console.log(title);
        });
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
