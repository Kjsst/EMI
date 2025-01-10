<!-- Main Content -->
@extends('layouts.app')
@section('pageTitle', 'customer')
@section('content')
    <div id="main-content">
        <div class="container">
            <div class="setting-page">

                <div id="Tab1" class="tabcontent" style="display: block;">
                    <div class="profile-setting">
                        <p>Edit customer</p>
                        <form action="{{route('customer.update',$customer->id)}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <input name="user_id" type="hidden" value="{{$customer->user->id}}">
                            <div class="form-control-s">
                                <label for="name">Name</label>
                                <input name="name" type="text" value="{{$customer->user->name}}" placeholder="Name">
                                @error('name')
                                <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-control-s">
                                <label for="mobile Number">Mobile Number</label>
                                <input name="mobile" type="tel" value="{{$customer->user->mobile}}" placeholder="0987654321">
                                @error('mobile')
                                <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-control-s">
                                <label for="Alter mobile">Alternative Mobile</label>
                                <input type="tel" name="alter_mobile" value="{{$customer->alter_mobile}}" placeholder="alternative mobile">
                                @error('alter_mobile')
                                <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-control-s">
                                <label for="Email">Email</label>
                                <input name="email" type="text" value="{{$customer->user->email}}" placeholder="hello@company.com" autocomplete="username">
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
                                <label for="Address">Address</label>
                                <input name="address" value="{{$customer->address}}" type="text" placeholder="address">
                                @error('address')
                                <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-control-s">
                                <label for="Merchant">Merchant</label>
                                <select name="merchant_id">
                                    <option value="" disabled selected> Select Merchant</option>
                                    @foreach($merchants as $merchant)
                                        <option value="{{$merchant->id}}" @if($merchant->id == $customer->merchant_id) selected @endif>{{$merchant->name}}</option>
                                    @endforeach
                                </select>
                                @error('merchant_id')
                                <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-control-s">
                                <label for="Select Type">Select Type</label>
                                <div>
                                    <input type="radio" style="width: 40%;" name="coin_type" id="1" value="1" @if($customer->coin_type == 1) checked @endif>
                                    <label for="1">Brahmastra </label><br>
                                    <input type="radio" style="width: 40%;" name="coin_type" id ="2" value="2" @if($customer->coin_type == 2) checked @endif>
                                    <label for="2">Brahmastra Prime </label>
                                    <input type="radio" style="width: 40%;" name="coin_type" id ="3" value="3" @if($customer->coin_type == 3) checked @endif>
                                    <label for="2">Ram baan </label>
                                </div>
                                @error('coin_type')
                                <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="change-password">
                                <p>Device detail</p>
                                <div class="form-control-s">
                                    <label for="IMEI1">IMEI1</label>
                                    <input name="imei1" value="{{$customer->imei1}}"  type="number" placeholder="IMEI1">
                                    @error('imei1')
                                    <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-control-s">
                                    <label for="IMEI2">IMEI2</label>
                                    <input name="imei2" value="{{$customer->imei2}}"  type="number" placeholder="IMEI2">
                                    @error('imei2')
                                    <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div class="change-password">
                                <p>Bank detail</p>
                                <div class="form-control-s">
                                    <label for="Bank name">Bank Name</label>
                                    <input name="bank_name" value="{{$customer->bank_name}}" type="text" placeholder="bank name">
                                     @error('bank_name')
                                    <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-control-s">
                                    <label for="Account name">Account Name </label>
                                    <input name="account_name" value="{{$customer->account_name}}" type="text" placeholder="Account name">
                                     @error('account_name')
                                    <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-control-s">
                                    <label for="Account number">Account Number</label>
                                    <input name="account_number" value="{{$customer->account_number}}" type="number" placeholder="Account number">
                                     @error('account_number')
                                    <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-control-s">
                                    <label for="IFSC">IFSC</label>
                                    <input name="ifsc" value="{{$customer->ifsc}}" type="text" placeholder="IFSC">
                                     @error('ifsc')
                                    <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-control-s">
                                <label for="Profile Photo">Blank Cheque</label>
                                <div class="admin-profile" onclick="addPhoto('blank_cheque')">
                                    <div class="image-upload">
                                        @if($customer->blank_cheque)
                                            <img src="{{url("storage/images/".$customer->blank_cheque)}}" height="70px" width="70px" id="blank_cheque_image"/>
                                        @else
                                            <img src="{{asset('/assets/img/photo_camera.svg')}}" height="70px" width="70px" id="blank_cheque_image"/>
                                        @endif
                                    </div>
                                    <div class="upload-camera">
                                        <img src="{{asset('/images/camera.png')}}" alt=""/>
                                    </div>
                                    <input style="display: none" type="file" name="blank_cheque" id="blank_cheque" accept="image/*"
                                           onchange="displaySelectedImage('blank_cheque')"/>
                                </div>
                            </div>
                            </div>
                            <div>
                                <p>Documents</p>
                                <div class="form-control-s">
                                    <label for="Profile Photo">Aadhar Front</label>
                                    <div class="admin-profile" onclick="addPhoto('aadhar_front')">
                                        <div class="image-upload">
                                            @if($customer->aadhar_front)
                                                <img src="{{url("storage/images/".$customer->aadhar_front)}}" height="70px" width="70px" id="aadhar_front_image"/>
                                            @else
                                                <img src="{{asset('/assets/img/photo_camera.svg')}}" height="70px" width="70px" id="aadhar_front_image"/>
                                            @endif
                                        </div>
                                        <div class="upload-camera">
                                            <img src="{{asset('/images/camera.png')}}" alt=""/>
                                        </div>
                                        <input style="display: none" type="file" name="aadhar_front" id="aadhar_front" accept="image/*"
                                               onchange="displaySelectedImage('aadhar_front')"/>
                                    </div>
                                </div>
                                <div class="form-control-s">
                                    <label for="Profile Photo">Aadhar Back</label>
                                    <div class="admin-profile" onclick="addPhoto('aadhar_back')">
                                        <div class="image-upload">
                                            @if($customer->aadhar_back)
                                                <img src="{{url("storage/images/".$customer->aadhar_back)}}" height="70px" width="70px" id="aadhar_back_image"/>
                                            @else
                                                <img src="{{asset('/assets/img/photo_camera.svg')}}" height="70px" width="70px" id="aadhar_back_image"/>
                                            @endif
                                        </div>
                                        <div class="upload-camera">
                                            <img src="{{asset('/images/camera.png')}}" alt=""/>
                                        </div>
                                        <input style="display: none" type="file" name="aadhar_back" id="aadhar_back" accept="image/*"
                                               onchange="displaySelectedImage('aadhar_back')"/>
                                    </div>
                                </div>
                                <div class="form-control-s">
                                    <label for="Profile Photo">Pan Card</label>
                                    <div class="admin-profile" onclick="addPhoto('pan_card')">
                                        <div class="image-upload">
                                            @if($customer->pan_card)
                                                <img src="{{url("storage/images/".$customer->pan_card)}}" height="70px" width="70px" id="pan_card_image"/>
                                            @else
                                                <img src="{{asset('/assets/img/photo_camera.svg')}}" height="70px" width="70px" id="pan_card_image"/>
                                            @endif
                                        </div>
                                        <div class="upload-camera">
                                            <img src="{{asset('/images/camera.png')}}" alt=""/>
                                        </div>
                                        <input style="display: none" type="file" name="pan_card" id="pan_card" accept="image/*"
                                               onchange="displaySelectedImage('pan_card')"/>
                                    </div>
                                </div>
                                <div class="form-control-s">
                                    <label for="Profile Photo">Customer Photo</label>
                                    <div class="admin-profile" onclick="addPhoto('customer_photo')">
                                        <div class="image-upload">
                                            @if($customer->customer_photo)
                                                <img src="{{url("storage/images/".$customer->customer_photo)}}" height="70px" width="70px" id="customer_photo_image"/>
                                            @else
                                                <img src="{{asset('/assets/img/photo_camera.svg')}}" height="70px" width="70px" id="customer_photo_image"/>
                                            @endif
                                        </div>
                                        <div class="upload-camera">
                                            <img src="{{asset('/images/camera.png')}}" alt=""/>
                                        </div>
                                        <input style="display: none" type="file" name="customer_photo" id="customer_photo" accept="image/*"
                                               onchange="displaySelectedImage('customer_photo')"/>
                                    </div>
                                </div>
                                <div class="form-control-s">
                                    <label for="Profile Photo">Merchant Photo</label>
                                    <div class="admin-profile" onclick="addPhoto('merchant_photo')">
                                        <div class="image-upload">
                                            @if($customer->merchant_photo)
                                                <img src="{{url("storage/images/".$customer->merchant_photo)}}" height="70px" width="70px" id="merchant_photo_image"/>
                                            @else
                                                <img src="{{asset('/assets/img/photo_camera.svg')}}" height="70px" width="70px" id="merchant_photo_image"/>
                                            @endif
                                        </div>
                                        <div class="upload-camera">
                                            <img src="{{asset('/images/camera.png')}}" alt=""/>
                                        </div>
                                        <input style="display: none" type="file" name="merchant_photo" id="merchant_photo" accept="image/*"
                                               onchange="displaySelectedImage('merchant_photo')"/>
                                    </div>
                                </div>
                                <div class="form-control-s">
                                    <label for="Profile Photo">Customer with Device</label>
                                    <div class="admin-profile" onclick="addPhoto('customer_with_device')">
                                        <div class="image-upload">
                                            @if($customer->customer_with_device)
                                                <img src="{{url("storage/images/".$customer->customer_with_device)}}" height="70px" width="70px" id="customer_with_device_image"/>
                                            @else
                                                <img src="{{asset('/assets/img/photo_camera.svg')}}" height="70px" width="70px" id="customer_with_device_image"/>
                                            @endif
                                        </div>
                                        <div class="upload-camera">
                                            <img src="{{asset('/images/camera.png')}}" alt=""/>
                                        </div>
                                        <input style="display: none" type="file" name="customer_with_device" id="customer_with_device" accept="image/*"
                                               onchange="displaySelectedImage('customer_with_device')"/>
                                    </div>
                                </div>
                                <!-- <div class="setting-page-button">
                                    <button type="submit" class="btn-dark">save</button>
                                </div> -->
                            </div>
                            @if($customer->loan)
                            <div>
                                <p>EMI Details</p>
                                <div class="form-control-s">
                                    <label for="Product Price">Product Price</label>
                                        <input name="billing_amount" value="{{$customer->loan->billing_amount}}" type="text" placeholder="loan billing amount">
                                </div>
                                <div class="form-control-s">
                                    <label for="Rate of interest">Rate of Interest</label>
                                        <input name="interest" value="{{$customer->loan->interest}}" type="text" placeholder="loan interest">
                                </div>
                                <div class="form-control-s">
                                    <label for="Down payment">Down Payment</label>
                                        <input name="down_payment" value="{{$customer->loan->down_payment}}" type="text" placeholder="loan down payment">
                                </div>
                                <div class="form-control-s">
                                    <label for="Loan Amount">Loan Amount</label>
                                        <input name="loan_amount" value="{{$customer->loan->loan_amount}}" type="text" placeholder="loan amount">
                                </div>
                                <div class="form-control-s">
                                    <label for="File charge">File Charge</label>
                                        <input name="file_charge" value="{{$customer->loan->file_charge}}" type="text" placeholder="loan file charge">
                                </div>
                                <div class="form-control-s">
                                    <label for="Total EMI amount">Total EMI Amount</label>
                                        <input name="total_amount" value="{{$customer->loan->total_amount}}" type="text" placeholder="total EMI amount">
                                </div>
                                <div class="form-control-s">
                                    <label for="Total interest">Total Interest</label>
                                        <input name="total_interest" value="{{$customer->loan->total_interest}}" type="text" placeholder="loan total interest">
                                </div>
                                <div class="form-control-s">
                                    <label for="EMI month">EMI Month</label>
                                        <input name="month" value="{{$customer->loan->month}}" type="text" placeholder="total loan month">
                                </div>
                                <div class="form-control-s">
                                    <label for="Monthly Loan Amount">Monthly Loan Amount</label>
                                        <input name="monthly_amount" value="{{$customer->loan->monthly_amount}}" type="text" placeholder="monthly loan amount">
                                </div>
                                <div class="form-control-s">
                                    <label for="EMI DATE">EMI DATE</label>
                                        <input name="first_emi_date" value="{{$customer->loan->first_emi_date}}" type="date" placeholder="loan interest">
                                </div>


                            </div>
                            {{--@else
                            <div>
                                <p>EMI Details</p>
                                <div class="form-control-s">
                                    <label for="Product Price">Product Price</label>
                                        <input name="billing_amount" value="{{old('billing_amount')}}" type="text" placeholder="loan billing amount">
                                </div>
                                <div class="form-control-s">
                                    <label for="Rate of interest">Rate of interest</label>
                                        <input name="interest" value="{{old('interest')}}" type="text" placeholder="loan interest">
                                </div>
                                <div class="form-control-s">
                                    <label for="Down payment">Down payment</label>
                                        <input name="down_payment" value="{{old('down_payment')}}" type="text" placeholder="loan down payment">
                                </div>
                                <div class="form-control-s">
                                    <label for="Loan Amount">Loan Amount</label>
                                        <input name="loan_amount" value="{{old('loan_amount')}}" type="text" placeholder="loan amount">
                                </div>
                                <div class="form-control-s">
                                    <label for="File charge">File charge</label>
                                        <input name="file_charge" value="{{old('file_charge')}}" type="text" placeholder="loan file charge">
                                </div>
                                <div class="form-control-s">
                                    <label for="Total EMI amount">Total EMI amount</label>
                                        <input name="total_amount" value="{{old('total_amount')}}" type="text" placeholder="total EMI amount">
                                </div>
                                <div class="form-control-s">
                                    <label for="Total interest">Total interest</label>
                                        <input name="total_interest" value="{{old('total_interest')}}" type="text" placeholder="loan total interest">
                                </div>
                                <div class="form-control-s">
                                    <label for="EMI month">EMI month</label>
                                        <input name="month" value="{{old('month')}}" type="text" placeholder="total loan month">
                                </div>
                                <div class="form-control-s">
                                    <label for="Monthly Loan Amount">Monthly Loan Amount</label>
                                        <input name="monthly_amount" value="{{old('monthly_amount')}}" type="text" placeholder="monthly amount">
                                </div>
                                <div class="form-control-s">
                                    <label for="EMI DATE">EMI DATE</label>
                                        <input name="first_emi_date" value="{{old('first_emi_date')}}" type="date" placeholder="loan interest">
                                </div>

                                <div class="setting-page-button">
                                    <button type="submit" class="btn-dark">save</button>
                                </div>
                            </div>--}}
                            @endif
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
        // new DataTable('#example');
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
