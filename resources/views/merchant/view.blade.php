<!-- Main Content -->
@extends('layouts.app')
@section('pageTitle', 'merchant')

@section('content')
    <div id="main-content">
        <div class="container">
            <div class="setting-page">

                <div id="Tab1" class="tabcontent" style="display: block;">
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <p>Merchant detail</p>
                            <div class="select-product">
                                <span><b>Name:</b> {{$merchant->user->name?? "-"}}</span>
                            </div>
                            <div class="select-product">
                                <span><b>Username: </b>{{$merchant->user->username ?? "-"}}</span>
                            </div>
                            <div class="select-product">
                                <span><b>Mobile: </b>{{$merchant->user->mobile?? "-"}}</span>
                            </div>
{{--                            <div class="select-product">--}}
{{--                                <span><b>FRP email: </b>{{$merchant->frp_email?? "-"}}</span>--}}
{{--                            </div>--}}
                            <div class="select-product">
                                <span><b>Email: </b>{{$merchant->user->email?? "-"}}</span>
                            </div>
                            <div class="select-product">
                                <span><b>Password: </b>{{$merchant->user->password_text?? "-"}}</span>
                            </div>
                        </div>
                        <div class="change-password col-lg-6 col-md-12 ">
                            <p>Bank detail</p>
                            <div class="select-product">
                                <span><b>Bank name: </b>{{$merchant->bank_name??"-"}}</span>
                            </div>
                            <div class="select-product">
                                <span><b>Account name: </b>{{$merchant->account_name??"-"}}</span>

                            </div>
                            <div class="select-product">
                                <span><b>Account number: </b>{{$merchant->account_number??"-"}}</span>

                            </div>
                            <div class="select-product">
                                <span><b>IFSC: </b>{{$merchant->ifsc??"-"}}</span>
                            </div>
                            <div class="select-product">
                                <span><b>UPI ID: </b>{{$merchant->upi_id??"-"}}</span>
                            </div>

                            <div class="select-product">
                                <span><b>QR Image </b>
                                        @if($merchant->QR_image)
                                        <img src="{{url("storage/images/".$merchant->QR_image)}}" alt="" width="70" height="70"
                                             id="image-profile"/>
                                    @else
                                        <img src="{{asset('/assets/img/photo_camera.svg')}}" alt="" width="70" height="70"/>
                                    @endif
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-lg-6 col-md-12">
                            <p>Address detail</p>
                            <div class="select-product">
                                <span><b>Shop Name: </b>{{$merchant->shop_name}}</span>
                            </div>
                            <div class="select-product">
                                <span><b>Address: </b>{{$merchant->address}}</span>
                            </div>
                            <div class="select-product">
                                <span><b>City: </b>{{$merchant->city}}</span>
                            </div>
                            <div class="select-product">
                                <span><b>Postal Code: </b>{{$merchant->postal_code}}</span>
                            </div>
                        </div>
                        <div class="change-password col-lg-6 col-md-12 ">
                            <p> Coin Details</p>
                            <div class="select-product">
                                <span><b>Brahmastra Coin: </b>{{$merchant->user->brahmastra_coin}}</span>
                            </div>
                            <div class="select-product">
                                <span><b>Rambaan Coin: </b>{{$merchant->user->rambaan_coin}}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
