@extends('layouts.app')
@section('pageTitle', 'Banner')

@section('content')
    <div id="main-content">
        <div class="container">
            <div class="setting-page">
                <div class="tab">
                    <button class="tablinks settingtab @if($errors->has('from_user_id') || $errors->has('from_coin_type') || $errors->has('from_coins') || $errors->has('from_remarks')) @else active @endif" onclick="openTab(event, 'Tab1')">Create Banner</button>
                    @if(auth()->user()->hasRole('admin'))
                    <button class="tablinks settingtab @if($errors->has('from_user_id') || $errors->has('from_coin_type') || $errors->has('from_coins') || $errors->has('from_remarks')) active @endif" onclick="openTab(event, 'Tab2')">List of Banner</button>
                    @endif
                </div>

{{--            <div id="Tab1" class="tabcontent" style="display: block;">--}}
                <div id="Tab1" class="tabcontent @if(!$errors->has('from_user_id') && !$errors->has('from_coin_type') && !$errors->has('from_coins') && !$errors->has('from_remarks'))" style="display: block;" @else style="display: none;" @endif>
                    <form action="{{route('banner.store')}}" method="post" enctype="multipart/form-data">
                        @csrf
                        <div class="form-control-s">
                            <label for="banner">Banner</label>
                                <div class="admin-profile" onclick="addPhoto('banner')">
                                    <div class="image-upload">
                                        <img src="{{asset('/assets/img/photo_camera.svg')}}" height="70px" width="70px" id="banner_image"/>
                                    </div>
                                    <div class="upload-camera">
                                        <img src="" alt=""/>
                                    </div>
                                    <input style="display: none" type="file" name="banner" id="banner" accept="image/*"
                                        onchange="displaySelectedImage('banner')"/>
                                    </div>
                                </div>

                                <div class="form-control-s">
                                    <label for="url">Url</label>
                                    <input name="url" value="{{old('url')}}" type="text" placeholder="Add URL" />
                                </div>

                                <div class="setting-page-button">
                                <button type="submit" class="btn-dark">Create</button>
                            </div>
                    </form>
                </div>

{{--            <div id="Tab2" class="tabcontent">--}}
                <div id="Tab2" class="tabcontent @if($errors->has('from_user_id') || $errors->has('from_coin_type') || $errors->has('from_coins') || $errors->has('from_remarks'))" style="display: block;" @else style="display: none;" @endif>
                    <div class="revies">
                        <div class="reveiw-content">
                            <div class="review-content-table">
                                <table id="customer" class="table table-striped" style="width:100% !important">
                                    <thead>
                                        <tr>
                                        <th>S.No</th>
                                        <th>Banners</th>
                                        <th>Url</th>
                                        <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($banners as $key=>$banner)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>
                                                @if($banner->banner)
                                                    <img src="{{url("storage/images/".$banner->banner)}}" height="70px" width="70px" id="banner_image"/>
                                                @else
                                                    <img src="{{asset('/assets/img/photo_camera.svg')}}" height="70px" width="70px" id="banner_image"/>
                                                @endif
                                            </td>
                                            <td>{{ $banner->url ?? "-" }}</td>
                                            <td>
                                                <a href="{{route('banner.edit', [$banner->id])}}"> <img src="./assets/img/edit.svg" alt=""></a>
                                                <a onclick="return confirm('Are you sure You want to delete this banner?')" href="{{route('banner.delete', [$banner->id])}}"><img src="./assets/img/delete.svg" alt=""></a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $('#customer').DataTable();
        } );

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
