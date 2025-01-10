<!-- Main Content -->
@extends('layouts.app')
@section('pageTitle', 'customer')
@section('content')
    <div id="main-content">
        <div class="container">
            <div class="setting-page">

                <div id="Tab1" class="tabcontent" style="display: block;">
                    <div class="profile-setting">
                        <p>Add customer</p>
                        <form action="{{route('customer.store')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <div class="form-control-s">
                                <label for="name">Name</label>
                                <input name="name" type="text" value="{{old('name')}}" placeholder="Name">
                                @error('name')
                                <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-control-s">
                                <label for="mobile Number">Mobile Number</label>
                                <input name="mobile" maxlength="10" type="tel" value="{{old('mobile')}}" placeholder="0987654321">
                                @error('mobile')
                                <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-control-s">
                                <label for="Alternative mobile">Alternative mobile</label>
                                <input type="tel" maxlength="10" name="alter_mobile" value="{{old('alter_mobile')}}" placeholder="Alternative mobile">
                                @error('alter_mobile')
                                <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-control-s">
                                <label for="Email">Email</label>
                                <input type="email" name="email" value="{{old('email')}}" placeholder="Email"
                                       autocomplete="username">
                                @error('email')
                                <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-control-s">
                                <label for="Password">Password</label>
                                <input name="password" type="password" value="{{old('password')}}"
                                       placeholder="Enter password" autocomplete="password">
                                @error('password')
                                <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-control-s">
                                <label for="Address">Address</label>
                                <input name="address" value="{{old('address')}}" type="text" placeholder="address">
                                @error('address')
                                <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-control-s">
                                <label for="Merchant">Merchant</label>
                                <select name="merchant_id">
                                    <option value="" disabled selected> Select merchant</option>
                                    @foreach($merchants as $merchant)
                                        <option value="{{$merchant->id}}"
                                            {{ old('merchant_id', $model->merchant_id ?? '') == $merchant->id ? 'selected' : '' }}>
                                            {{$merchant->name}}</option>
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
                                    <input type="radio" name="coin_type" id="1" value="1"  {{ old('coin_type', $model->coin_type ?? '') == 1 ? 'checked' : '' }}>
                                    <label for="1">Ram baan (V1)</label><br>
                                    <input type="radio" name="coin_type" id ="2" value="2"  {{ old('coin_type', $model->coin_type ?? '') == 2 ? 'checked' : '' }}>
                                    <label for="2">Brahmastra (V2)</label>
                                </div>
                                @error('coin_type')
                                <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div>
                                <p>Device detail</p>
                                <div class="form-control-s">
                                    <label for="IMEI1">IMEI1</label>
                                    <input name="imei1" value="{{old('imei1')}}" type="number" placeholder="IMEI1">
                                    @error('imei1')
                                    <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-control-s">
                                    <label for="IMEI2">IMEI2</label>
                                    <input name="imei2" value="{{old('imei2')}}" type="number" placeholder="IMEI2">
                                    @error('imei2')
                                    <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div>
                                <p>EMI Detail</p>
                                <div class="form-control-s">
                                    <label for="Billing amount">Billing amount</label>
                                    <input name="billing_amount" id="billing_amount" value="{{old('billing_amount')}}" type="text"
                                           placeholder="Billing amount" onkeyup="EMICalculate()">
                                    @error('billing_amount')
                                    <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-control-s">
                                    <label for="File charge">File charge</label>
                                    <input name="file_charge" value="{{old('file_charge')}}" type="text"
                                           placeholder="File charge">
                                    @error('file_charge')
                                    <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-control-s">
                                    <label for="Down payment">Down payment</label>
                                    <input name="down_payment" id="down_payment" value="{{old('down_payment')}}" type="number"
                                           placeholder="Down payment" onkeyup="EMICalculate()">
                                    @error('down_payment')
                                    <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-control-s">
                                    <label for="Loan amount">Loan amount</label>
                                    <input name="loan_amount_hidden" id="loan_amount_hidden" value="{{ old('loan_amount_hidden', 0) }}" type="hidden">
                                    <input name="total_amount" id="total_amount" value="{{ old('total_amount', 0) }}" type="hidden">
                                    <input name="total_interest" id="total_interest" value="{{ old('total_interest', 0) }}" type="hidden">
                                    <input name="loan_amount" id="loan_amount" value="{{ old('loan_amount', 0) }}" disabled type="text"
                                           placeholder="Loan amount">
                                    @error('loan_amount')
                                    <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-control-s">
                                    <label for="Interest">Interest</label>
                                    <input name="interest" id="interest" onkeyup="EMICalculate()" value="1.0" type="number" step="any"
                                           placeholder="Interest">
                                    @error('interest')
                                    <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-control-s">
                                    <label for="Months">Months</label>
                                    <select name="month" id="month" onchange="EMICalculate()">
                                        @for($i=1;$i<=100;$i++)
                                            <option value="{{$i}}">{{$i}}</option>
                                        @endfor
                                    </select>
                                    @error('months')
                                    <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-control-s">
                                    <label for="Monthly amount">Monthly amount</label>
                                    <input name="monthly_amount" id="monthly_amount" disabled type="text" step="any"
                                           placeholder="Monthly amount" value="{{ old('monthly_amount', 0) }}">
                                    <input name="monthly_amount_hidden" id="monthly_amount_hidden"value="{{ old('monthly_amount_hidden') }}" type="hidden">
                                    @error('monthly_amount')
                                    <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                    <div class="form-control-s">
                                        <label for="First EMI Date">First EMI Date</label>
                                    <input name="first_emi_date" id="first_emi_date" value="{{old('first_emi_date')}}" type="date"
                                           placeholder="First EMI Date">
                                    @error('first_emi_date')
                                    <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>
                            <div>
                                <p>Bank detail</p>
                                <div class="form-control-s">
                                    <label for="Bank name">Bank name</label>
                                    <input name="bank_name" value="{{old('bank_name')}}" type="text"
                                           placeholder="bank name">
                                    @error('bank_name')
                                    <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-control-s">
                                    <label for="Account name">Account name</label>
                                    <input name="account_name" value="{{old('account_name')}}" type="text"
                                           placeholder="Account name">
                                    @error('account_name')
                                        <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                                <div class="form-control-s">
                                    <label for="Account number">Account number</label>
                                    <input name="account_number" value="{{old('account_number')}}" type="number"
                                           placeholder="Account number">
                                    @error('account_number')
                                    <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                                <div class="form-control-s">
                                    <label for="IFSC">IFSC</label>
                                    <input name="ifsc" value="{{old('ifsc')}}" type="text" placeholder="IFSC">
                                    @error('ifsc')
                                        <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="form-control-s">
                                    <label for="Profile Photo">Blank cheque</label>
                                    <div class="admin-profile" onclick="addPhoto('blank_cheque')">
                                        <div class="image-upload">
                                            <img src="{{asset('/assets/img/photo_camera.svg')}}" height="70px" width="70px" id="blank_cheque_image"/>
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
                                    <label for="Profile Photo">Aadhar front</label>
                                    <div class="admin-profile" onclick="addPhoto('aadhar_front')">
                                        <div class="image-upload">
                                            <img src="{{asset('/assets/img/photo_camera.svg')}}" height="70px" width="70px" id="aadhar_front_image"/>
                                        </div>
                                        <div class="upload-camera">
                                            <img src="{{asset('/images/camera.png')}}" alt=""/>
                                        </div>
                                        <input style="display: none" type="file" name="aadhar_front" id="aadhar_front" accept="image/*"
                                               onchange="displaySelectedImage('aadhar_front')"/>
                                    </div>
                                </div>
                                <div class="form-control-s">
                                    <label for="Profile Photo">Aadhar back</label>
                                    <div class="admin-profile" onclick="addPhoto('aadhar_back')">
                                        <div class="image-upload" >
                                            <img src="{{asset('/assets/img/photo_camera.svg')}}" height="70px" width="70px" id="aadhar_back_image"/>
                                        </div>
                                        <div class="upload-camera">
                                            <img src="{{asset('/images/camera.png')}}" alt=""/>
                                        </div>
                                        <input style="display: none" type="file" name="aadhar_back" id="aadhar_back" accept="image/*"
                                               onchange="displaySelectedImage('aadhar_back')"/>
                                    </div>
                                </div>
                                <div class="form-control-s">
                                    <label for="Profile Photo">Pan card</label>
                                    <div class="admin-profile" onclick="addPhoto('pan_card')">
                                        <div class="image-upload" >
                                            <img src="{{asset('/assets/img/photo_camera.svg')}}" height="70px" width="70px" id="pan_card_image"/>
                                        </div>
                                        <div class="upload-camera">
                                            <img src="{{asset('/images/camera.png')}}" alt=""/>
                                        </div>
                                        <input style="display: none" type="file" name="pan_card" id="pan_card" accept="image/*"
                                               onchange="displaySelectedImage('pan_card')"/>
                                    </div>
                                </div>
                                <div class="form-control-s">
                                    <label for="Profile Photo">Customer photo</label>
                                    <div class="admin-profile" onclick="addPhoto('customer_photo')">
                                        <div class="image-upload" >
                                            <img src="{{asset('/assets/img/photo_camera.svg')}}" height="70px" width="70px" id="customer_photo_image"/>
                                        </div>
                                        <div class="upload-camera">
                                            <img src="{{asset('/images/camera.png')}}" alt=""/>
                                        </div>
                                        <input style="display: none" type="file" name="customer_photo" id="customer_photo" accept="image/*"
                                               onchange="displaySelectedImage('customer_photo')"/>
                                    </div>
                                </div>
                                <div class="form-control-s">
                                    <label for="Profile Photo">Merchant photo</label>
                                    <div class="admin-profile" onclick="addPhoto('merchant_photo')">
                                        <div class="image-upload" >
                                            <img src="{{asset('/assets/img/photo_camera.svg')}}" height="70px" width="70px" id="merchant_photo_image"/>
                                        </div>
                                        <div class="upload-camera">
                                            <img src="{{asset('/images/camera.png')}}" alt=""/>
                                        </div>
                                        <input style="display: none" type="file" name="merchant_photo" id="merchant_photo" accept="image/*"
                                               onchange="displaySelectedImage('merchant_photo')"/>
                                    </div>
                                </div>
                                <div class="form-control-s">
                                    <label for="Profile Photo">Customer with device</label>
                                    <div class="admin-profile" onclick="addPhoto('customer_with_device')">
                                        <div class="image-upload" >
                                            <img src="{{asset('/assets/img/photo_camera.svg')}}" height="70px" width="70px" id="customer_with_device_image"/>
                                        </div>
                                        <div class="upload-camera">
                                            <img src="{{asset('/images/camera.png')}}" alt=""/>
                                        </div>
                                        <input style="display: none" type="file" name="customer_with_device" id="customer_with_device" accept="image/*"
                                               onchange="displaySelectedImage('customer_with_device')"/>
                                    </div>
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

        function EMICalculate() {
            let billingAmount = document.getElementById("billing_amount");
            let downPayment = document.getElementById("down_payment");
            let month = document.getElementById("month");
            let monthlyAmount = document.getElementById("monthly_amount");
            let monthlyAmountHidden = document.getElementById("monthly_amount_hidden");
            let interest = document.getElementById("interest");
            let loanAmount = document.getElementById("loan_amount");
            let loanAmountHidden = document.getElementById("loan_amount_hidden");
            let totalAmount = document.getElementById("total_amount");
            let totalInterest = document.getElementById("total_interest");

            console.log(billingAmount.value);
            loanAmount.value = billingAmount.value-downPayment.value;
            loanAmountHidden.value = loanAmount.value;
            if((parseFloat(downPayment.value) > parseFloat(billingAmount.value)) || (billingAmount.value == "") || (billingAmount.value == null)){
                loanAmount.value = 0;
                loanAmountHidden.value = 0;
            }
            totalInterest.value = (loanAmount.value)*(interest.value/100)*(month.value);
            totalAmount.value = parseFloat(loanAmount.value) + parseFloat(totalInterest.value);
            monthlyAmount.value = parseFloat(totalAmount.value/month.value);
            monthlyAmountHidden.value = monthlyAmount.value;
        }

        var date = new Date();
        date.setDate(date.getDate());

    </script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const dateInput = document.getElementById('first_emi_date');
            const today = new Date().toISOString().split('T')[0];
            dateInput.setAttribute('min', today);
        });
    </script>

@endpush
