@extends('layouts.app')
@section('pageTitle', 'create user')

@section('content')
    <div id="main-content">
        <div class="container">
            <div class="setting-page">

                <div id="Tab1" class="tabcontent" style="display: block;">
                    <p>Create User</p>
                    <div class="profile-setting">
                        <form action="{{route('user.store')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div>
                                <div class="form-control-s">
                                    <label for="Name">Name</label>
                                    <input value="{{old('name')}}" name="name" type="text" placeholder="Name">
                                    @error('name')
                                    <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>
                                <div class="form-control-s">
                                    <label for="Username">Username</label>
                                    <input value="{{old('username')}}" name="username" type="text" placeholder="Username">
                                    @error('username')
                                    <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>
                                <div class="form-control-s">
                                    <label for="User Role">User Role</label>
                                    <select name="role" id="roleSelect">
                                        <option value="">Select Role</option>
                                    @foreach($roles as $role)
                                            <option value="{{$role->id}}" {{ old('role') == $role->id ? 'selected' : '' }}>{{ucfirst(trans($role->name))}}</option>
                                        @endforeach
                                    </select>
                                    @error('role')
                                    <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>
                                <div class="form-control-s" id="parent-user">
                                    <label for="parent user">Parent user</label>
                                    <select name="parent_user_id">
                                        <option value="">Select parent user</option>
                                    @foreach($users as $user)
                                            <option value="{{$user->id}}" {{ old('parent_user_id') == $user->id ? 'selected' : '' }}>{{ucfirst(trans($user->name))}}</option>
                                        @endforeach
                                    </select>
                                    @error('parent_user_id')
                                    <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>
                                <div class="form-control-s">
                                    <label for="Phone Number">Mobile Number</label>
                                    <input value="{{old('mobile')}}" name="mobile" type="tel" placeholder="0987654321">
                                    @error('mobile')
                                    <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>
                                <div class="form-control-s">
                                    <label for="Email">Email</label>
                                    <input value="{{old('email')}}" name="email" type="email"
                                           placeholder="hello@gmail.com" autocomplete="username">
                                    @error('email')
                                    <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>
                                <div class="form-control-s">
                                    <label for="password">Password</label>
                                    <input value="{{old('password')}}" name="password" type="password" placeholder="password">
                                    @error('password')
                                    <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>
                                <div class="form-control-s">
                                    <label for="Company name">Company name</label>
                                    <input value="{{old('company_name')}}" name="company_name" type="text"
                                           placeholder="Company">
                                    @error('company_name')
                                    <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>
                                <div class="form-control-s">
                                    <label for="City">City</label>
                                    <input value="{{old('city')}}" name="city" type="text"
                                           placeholder="City">
                                    @error('city')
                                    <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>
                                <div class="form-control-s">
                                    <label for="Postal Code">Postal Code</label>
                                    <input value="{{old('postal_code')}}" name="postal_code" type="text"
                                           placeholder="Postal Code">
                                    @error('postal_code')
                                    <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>
                                <div class="form-control-s">
                                    <label for="address">Address</label>
                                    <input value="{{old('address')}}" name="address" type="text"
                                           placeholder="Address">
                                    @error('address')
                                    <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                    @enderror
                                </div>
                                <div class="setting-page-button">
                                    <button class="btn-dark">Create</button>
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
        document.getElementById('roleSelect').addEventListener('change', function() {
            let selectedValue = this.value;  // Get the selected value
            if(selectedValue == 7) {
                $('#parent-user').hide();
            } else {
                $('#parent-user').show();
            }
        });
    </script>
@endpush
