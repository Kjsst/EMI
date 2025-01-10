<!-- Main Content -->
@extends('layouts.app')
@section('pageTitle', 'Distributor')

@section('content')
    <div id="main-content">
        <div class="container">
            <div class="setting-page">

                <div id="Tab1" class="tabcontent" style="display: block;">
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <p>Distributor detail</p>
                            <div class="select-product">
                                <span><b>Name:</b> {{$masterDistributor->name?? "-"}}</span>
                            </div>
                            <div class="select-product">
                                <span><b>Username: </b>{{$masterDistributor->username ?? "-"}}</span>
                            </div>
                            <div class="select-product">
                                <span><b>Mobile: </b>{{$masterDistributor->mobile?? "-"}}</span>
                            </div>
                            <div class="select-product">
                                <span><b>Email: </b>{{$masterDistributor->email?? "-"}}</span>
                            </div>
                            <div class="select-product">
                                <span><b>Password: </b>{{$masterDistributor->password_text?? "-"}}</span>
                            </div>
                        </div>
                        <div class="change-password col-lg-6 col-md-12 ">
                            <p>Address</p>
                            <div class="select-product">
                                <span><b>Company Name: </b>{{$masterDistributor->userDetail->company_name??"-"}}</span>
                            </div>
                            <div class="select-product">
                                <span><b>City: </b>{{$masterDistributor->userDetail->city??"-"}}</span>

                            </div>
                            <div class="select-product">
                                <span><b>Postal Code: </b>{{$masterDistributor->userDetail->postal_code??"-"}}</span>

                            </div>
                            <div class="select-product">
                                <span><b>Address: </b>{{$masterDistributor->userDetail->address??"-"}}</span>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
