<!-- Main Content -->
@extends('layouts.app')
@section('pageTitle', 'merchant')
@section('content')
    <div id="main-content">
        <div class="container">
            <div class="setting-page">

                <div id="Tab1" class="tabcontent" style="display: block;">
                    <div class="profile-setting">
                        <p>Edit Distributor</p>
                        <form action="{{route('super-admin.update',$superAdmin->id)}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input name="id" type="hidden" value="{{$superAdmin->id}}">
                            <div class="form-control-s">
                                <label for="name">Name</label>
                                <input name="name" type="text" value="{{$superAdmin->name}}" placeholder="Name">
                                @error('name')
                                <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-control-s">
                                <label for="Username">Username</label>
                                <input name="username" type="text" value="{{$superAdmin->username}}" placeholder="Username">
                                @error('username')
                                <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-control-s">
                                <label for="mobile Number">Mobile Number</label>
                                <input name="mobile" type="tel" value="{{$superAdmin->mobile}}" placeholder="0987654321">
                                @error('mobile')
                                <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-control-s">
                                <label for="Email">Email</label>
                                <input name="email" type="text" value="{{$superAdmin->email}}" placeholder="hello@company.com">
                                @error('email')
                                <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-control-s">
                                <label for="Password">Password</label>
                                <input name="password" type="password" placeholder="Enter password">
                                @error('password')
                                <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="change-password">
                                <p>Address</p>
                                <div class="form-control-s">
                                    <label for="Shop name">Shop Name</label>
                                    <input name="company_name" value="{{$superAdmin->userDetail->company_name}}" type="text" placeholder="Shop Name">
                                    @error('company_name')
                                    <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-control-s">
                                    <label for="Address">Address</label>
                                    <input name="address" value="{{$superAdmin->userDetail->address}}" type="text" placeholder="address">
                                    @error('address')
                                    <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-control-s">
                                    <label for="City">City</label>
                                    <input name="city" value="{{$superAdmin->userDetail->city}}" type="text" placeholder="City">
                                    @error('city')
                                    <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-control-s">
                                    <label for="Postal Code">Postal Code</label>
                                    <input name="postal_code" value="{{$superAdmin->userDetail->postal_code}}" type="text" placeholder="Postal Code">
                                    @error('postal_code')
                                    <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="setting-page-button">
                                    <button type="submit" class="btn-dark">save</button>
                                </div>
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
