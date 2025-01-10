<!-- Main Content -->
@extends('layouts.app')
@section('pageTitle', 'Profile')

@section('content')
    <div id="main-content">
        <div class="container">
            <div class="setting-page">

                <div id="Tab1" class="tabcontent" style="display: block;">
                    <div class="profile-setting">
                        <p>Profile</p>
                        <form action="{{route('profile.update')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div>
                                <div class="form-control-s">
                                    <label for="Name">Name</label>
                                    <input name="name" type="text" value="{{$user->name}}" placeholder="Name">
                                    @error('name')
                                    <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>
                                <div class="form-control-s">
                                    <label for="Username">Username</label>
                                    <input name="username" type="text" value="{{$user->username}}" placeholder="Username">
                                    @error('username')
                                    <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>
                                <div class="form-control-s">
                                    <label for="Email">Email</label>
                                    <input name="email" type="email" value="{{$user->email}}"
                                           placeholder="hello@company.com">
                                    @error('email')
                                    <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>
                                <div class="form-control-s">
                                    <label for="Phone Number">Mobile Number</label>
                                    <input name="mobile" type="tel" value="{{$user->mobile}}" placeholder="0987654321">
                                    @error('mobile')
                                    <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>
                                <div class="setting-page-button">
                                    <button class="btn-dark">Change Detail</button>
                                </div>
                            </div>
                        </form>
                        <form action="{{route('password.update')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="change-password">
                                <input type="hidden" name="id" value="{{auth()->user()->id}}">
                                <p>Change Password</p>
                                <div class="form-control-s">
                                    <label for="Old Password">Old Password</label>
                                    <input name="old_password" type="password" placeholder="Old Password">
                                    @error('old_password')
                                    <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-control-s">
                                    <label for="New Password">New Password</label>
                                    <input name="new_password" type="password" placeholder="New Password">
                                    @error('new_password')
                                    <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-control-s">
                                    <label for="Re-Enter New Password">Re-Enter New Password</label>
                                    <input name="confirm_password" type="password" placeholder="Re-Enter New Password">
                                    @error('confirm_password')
                                    <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="setting-page-button">
                                    <button class="btn-dark">Change Password</button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
