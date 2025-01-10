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
                                <span><b>Name:</b> {{$distributor->name?? "-"}}</span>
                            </div>
                            <div class="select-product">
                                <span><b>Username: </b>{{$distributor->username ?? "-"}}</span>
                            </div>
                            <div class="select-product">
                                <span><b>Mobile: </b>{{$distributor->mobile?? "-"}}</span>
                            </div>
                            <div class="select-product">
                                <span><b>Email: </b>{{$distributor->email?? "-"}}</span>
                            </div>
                            <div class="select-product">
                                <span><b>Password: </b>{{$distributor->password_text?? "-"}}</span>
                            </div>
                            <div class="select-product">
                                <span><b>Master Distributor: </b>{{$MasterDistributorName?? "-"}}</span>
                            </div>
                        </div>
                        <div class="change-password col-lg-6 col-md-12 ">
                            <p>Address</p>
                            <div class="select-product">
                                <span><b>Company Name: </b>{{$distributor->userDetail->company_name??"-"}}</span>
                            </div>
                            <div class="select-product">
                                <span><b>City: </b>{{$distributor->userDetail->city??"-"}}</span>

                            </div>
                            <div class="select-product">
                                <span><b>Postal Code: </b>{{$distributor->userDetail->postal_code??"-"}}</span>

                            </div>
                            <div class="select-product">
                                <span><b>Address: </b>{{$distributor->userDetail->address??"-"}}</span>
                            </div>

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
