<!-- Main Content -->
@extends('layouts.app')
@section('pageTitle', 'merchant')
@section('content')
    <div id="main-content">
        <div class="container">
            <div class="setting-page">

                <div id="Tab1" class="tabcontent" style="display: block;">
                    <div class="profile-setting">
                        <p>Edit merchant</p>
                        <form action="{{route('merchant.update',$merchant->id)}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input name="user_id" type="hidden" value="{{$merchant->user->id}}">
                            <div class="form-control-s">
                                <label for="name">Name</label>
                                <input name="name" type="text" value="{{$merchant->user->name}}" placeholder="Name">
                                @error('name')
                                <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-control-s">
                                <label for="Username">Username</label>
                                <input name="username" type="text" value="{{$merchant->user->username}}" placeholder="Username">
                                @error('username')
                                <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-control-s">
                                <label for="mobile Number">Mobile Number</label>
                                <input name="mobile" type="tel" value="{{$merchant->user->mobile}}" placeholder="0987654321">
                                @error('mobile')
                                <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
{{--                            <div class="form-control-s">--}}
{{--                                <label for="Frp Email">Frp Email</label>--}}
{{--                                <input type="email" name="frp_email" value="{{$merchant->frp_email}}" placeholder="Frp Email">--}}
{{--                                @error('frp_email')--}}
{{--                                <span class="invalid-feedback" style="justify-content: end;" role="alert">--}}
{{--                                        <strong>{{ $message }}</strong>--}}
{{--                                    </span>--}}
{{--                                @enderror--}}
{{--                            </div>--}}
                            <div class="form-control-s">
                                <label for="Email">Email</label>
                                <input name="email" type="text" value="{{$merchant->user->email}}" placeholder="hello@company.com">
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
                            <div class="form-control-s">
                                <label for="Distributor">Distributor</label>
                                <select name="distributor_id">
                                    <option value="">Select Distributor</option>
                                    @foreach($distributors as $distributor)
                                        <option value="{{$distributor->id}}" @if($distributor->id == $merchant->distributor_id) selected @endif>{{$distributor->name}}</option>
                                    @endforeach
                                </select>
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
                                    <input name="shop_name" value="{{$merchant->shop_name}}" type="text" placeholder="Shop Name">
                                    @error('shop_name')
                                    <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-control-s">
                                    <label for="Address">Address</label>
                                    <input name="address" value="{{$merchant->address}}" type="text" placeholder="address">
                                    @error('address')
                                    <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-control-s">
                                    <label for="City">City</label>
                                    <input name="city" value="{{$merchant->city}}" type="text" placeholder="City">
                                    @error('city')
                                    <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-control-s">
                                    <label for="Postal Code">Postal Code</label>
                                    <input name="postal_code" value="{{$merchant->postal_code}}" type="text" placeholder="Postal Code">
                                    @error('postal_code')
                                    <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="change-password">
                                <p>Bank detail</p>
                                <div class="form-control-s">
                                    <label for="Bank name">Bank name</label>
                                    <input name="bank_name" value="{{$merchant->bank_name}}" type="text" placeholder="bank name">
                                     @error('bank_name')
                                    <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-control-s">
                                    <label for="Account name">Account name</label>
                                    <input name="account_name" value="{{$merchant->account_name}}" type="text" placeholder="Account name">
                                     @error('account_name')
                                    <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-control-s">
                                    <label for="Account number">Account number</label>
                                    <input name="account_number" value="{{$merchant->account_number}}" type="text" placeholder="Account number">
                                     @error('account_number')
                                    <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-control-s">
                                    <label for="IFSC">IFSC</label>
                                    <input name="ifsc" value="{{$merchant->ifsc}}" type="text" placeholder="IFSC">
                                     @error('ifsc')
                                    <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-control-s">
                                    <label for="Upi ID">Upi ID</label>
                                    <input name="upi_id" value="{{$merchant->upi_id}}" type="text" placeholder="Upi ID">
                                    @error('upi_id')
                                    <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-control-s">
                                <label for="Profile Photo">QR image</label>
                                <div class="admin-profile" onclick="addPhoto('QR_image')">
                                    <div class="image-upload">
                                        @if($merchant->QR_image)
                                        <img src="{{url("storage/images/".$merchant->QR_image)}}" height="70px" width="70px" id="QR_image_image"/>
                                        @else
                                            <img src="{{asset('/assets/img/photo_camera.svg')}}" height="70px" width="70px" id="QR_image_image"/>
                                        @endif
                                    </div>
                                    <div class="upload-camera">
                                        <img src="{{asset('/images/camera.png')}}" alt=""/>
                                    </div>
                                    <input style="display: none" type="file" name="QR_image" id="QR_image" accept="image/*"
                                           onchange="displaySelectedImage('QR_image')"/>
                                </div>
                            </div>
                            </div>
                            <div>
                                {{-- <p>Coins</p>
                                <div class="form-control-s">
                                    <label for="Brahmastra Coin">Brahmastra Coin</label>
                                    <input name="brahmastra_coin" value="{{$merchant->user->brahmastra_coin}}" type="text"
                                           placeholder="Brahmastra Coin">
                                    @error('brahmastra_coin')
                                    <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-control-s">
                                    <label for="Rambaan Coin">Rambaan Coin</label>
                                    <input name="rambaan_coin" value="{{$merchant->user->rambaan_coin}}" type="text"
                                           placeholder="Rambaan Coin">
                                    @error('rambaan_coin')
                                    <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div> --}}
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
