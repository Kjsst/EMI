<!-- Main Content -->
@extends('layouts.app')
@section('pageTitle', 'customer')

@section('content')
    <div id="main-content">
        <div class="container">
            <div class="setting-page">

                <div id="Tab1" class="tabcontent" style="display: block;">
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <p>Customer detail</p>
                            <div class="select-product">
                                <span><b>Name:-</b> {{$customer->user->name?? "-"}}</span>
                            </div>
                            <div class="select-product">
                                <span><b>Username:- </b>{{$customer->user->username ?? "-"}}</span>
                            </div>
                            <div class="select-product">
                                <span><b>Mobile:- </b>{{$customer->user->mobile?? "-"}}</span>
                            </div>
                            <div class="select-product">
                                <span><b>Alter mobile:- </b>{{$customer->alter_mobile?? "-"}}</span>
                            </div>
                            <div class="select-product">
                                <span><b>Email:- </b>{{$customer->user->email?? "-"}}</span>
                            </div>
                            <div class="select-product">
                                <span><b>Customer ID: </b>{{$customer->user->login_id?? "-"}}</span>
                            </div>
                            <div class="select-product">
                                <span><b>Password: </b>{{$customer->user->password_text?? "-"}}</span>
                            </div>
                            <div class="select-product">
                                <span><b>Address:- </b>{{$customer->address ?? "-"}}</span>
                            </div>
                            <div class="select-product">
                                <span><b>Merchant:- </b>
                                    <a href="{{route('merchant.view',$customer->merchant->id)}}">{{$customer->merchant->name}}</a>
                                </span>
                            </div>
                            <div class="select-product">
                                <span><b>Customer Type:- </b>
                                    @if ($customer->coin_type == 1)
                                        Brahmastra
                                    @elseif ($customer->coin_type == 2)
                                        Brahmastra Prime
                                    @elseif ($customer->coin_type == 3)
                                        Rambaan
                                    @endif
                                </span>
                            </div>
                        </div>
                        <div class="change-password col-lg-6 col-md-12 mt-lg-0 mt-5">
                            <p>Bank detail</p>
                            <div class="select-product">
                                <span><b>Bank name:- </b>{{$customer->bank_name??"-"}}</span>
                            </div>
                            <div class="select-product">
                                <span><b>Account name:- </b>{{$customer->account_name??"-"}}</span>
                            </div>
                            <div class="select-product">
                                <span><b>Account number:- </b>{{$customer->account_number??"-"}}</span>
                            </div>
                            <div class="select-product">
                                <span><b>IFSC:- </b>{{$customer->ifsc??"-"}}</span>
                            </div>
                            <div class="select-product">
                                <span><b>Blank cheque:- </b>
                                        @if($customer->blank_cheque)
                                        <a href="{{url("storage/images/".$customer->blank_cheque)}}" target="_blank">
                                            <img src="{{url("storage/images/".$customer->blank_cheque)}}" alt="" width="80" height="80"
                                                 id="image-profile"/>
                                        </a>
                                        @else
                                            <img src="{{asset('/assets/img/photo_camera.svg')}}" alt=""width="80" height="80"/>
                                        @endif
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="row mt-5">
                        <div class="col-lg-6 col-md-12">
                            <p>Device detail</p>
                            <div class="select-product">
                                <span><b>IMEI1:- </b>{{$customer->imei1}}</span>
                            </div>
                            <div class="select-product">
                                <span><b>IMEI2:- </b>{{$customer->imei2}}</span>
                            </div>
                        </div>
                        <div class="col-lg-6 col-md-12 mt-lg-0 mt-5">
                            <p> Document detail</p>
                            <div class="row">
                                <div class="col-lg-6 col-md-12">
                                    <div class="form-control-s">
                                        <span>
                                            <b>Aadhar Front:- </b>
                                        </span>
                                        <span>
                                             @if($customer->aadhar_front)
                                                <a href="{{url("storage/images/".$customer->aadhar_front)}}" target="_blank">
                                                <img src="{{url("storage/images/".$customer->aadhar_front)}}" alt=""
                                                     width="80" height="80"
                                                     id="image-profile"/>
                                                </a>
                                            @else
                                                <img src="{{asset('/assets/img/photo_camera.svg')}}" alt="" width="80"
                                                     height="80"/>
                                            @endif
                                        </span>
                                    </div>
                                    <div class="form-control-s">
                                        <span>
                                            <b>Aadhar back:- </b>
                                        </span>
                                        <span>
                                             @if($customer->aadhar_back)
                                                <a href="{{url("storage/images/".$customer->aadhar_back)}}" target="_blank">
                                                <img src="{{url("storage/images/".$customer->aadhar_back)}}" alt=""
                                                     width="80" height="80"
                                                     id="image-profile"/>
                                                </a>
                                            @else
                                                <img src="{{asset('/assets/img/photo_camera.svg')}}" alt="" width="80"
                                                     height="80"/>
                                            @endif
                                        </span>
                                    </div>
                                    <div class="form-control-s">
                                        <span>
                                            <b>Pan card:- </b>
                                        </span>
                                        <span>
                                             @if($customer->pan_card)
                                                <a href="{{url("storage/images/".$customer->pan_card)}}" target="_blank">
                                                <img src="{{url("storage/images/".$customer->pan_card)}}" alt=""
                                                     width="80" height="80"
                                                     id="image-profile"/>
                                                </a>a
                                            @else
                                                <img src="{{asset('/assets/img/photo_camera.svg')}}" alt="" width="80"
                                                     height="80"/>
                                            @endif
                                        </span>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-md-12">
                                    <div class="form-control-s">
                                        <span>
                                            <b>Customer Photo:- </b>
                                        </span>
                                        <span>
                                             @if($customer->customer_photo)
                                                <a href="{{url("storage/images/".$customer->customer_photo)}}" target="_blank">
                                                <img src="{{url("storage/images/".$customer->customer_photo)}}" alt=""
                                                     width="80" height="80"
                                                     id="image-profile"/>
                                                </a>
                                            @else
                                                <img src="{{asset('/assets/img/photo_camera.svg')}}" alt="" width="80"
                                                     height="80"/>
                                            @endif
                                        </span>
                                    </div>
                                    <div class="form-control-s">
                                        <span>
                                            <b>Merchant Photo:- </b>
                                        </span>
                                        <span> @if($customer->merchant_photo)
                                                <a href="{{url("storage/images/".$customer->merchant_photo)}}" target="_blank">
                                                <img src="{{url("storage/images/".$customer->merchant_photo)}}" alt=""
                                                     width="80" height="80"
                                                     id="image-profile"/>
                                                </a>
                                            @else
                                                <img src="{{asset('/assets/img/photo_camera.svg')}}" alt="" width="80"
                                                     height="80"/>
                                            @endif
                                        </span>
                                    </div>
                                    <div class="form-control-s">
                                        <span>
                                            <b>Customer With Device:- </b>
                                        </span>
                                        <span>
                                            @if($customer->customer_with_device)
                                                <a href="{{url("storage/images/".$customer->customer_with_device)}}" target="_blank"><img src="{{url("storage/images/".$customer->customer_with_device)}}"
                                                     alt=""
                                                     width="80" height="80"
                                                        id="image-profile"/></a>
                                            @else
                                                <img src="{{asset('/assets/img/photo_camera.svg')}}" alt="" width="80"
                                                     height="80"/>
                                            @endif
                                    </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- @if($customer->loan) -->
                        <div class="row mt-5">
                            <p>EMI Details</p>
                            <div class="col-lg-6 col-md-12">
                                <div class="select-product">
                                    <span><b>Product Price:-</b> {{$customer->loan->billing_amount?? "-"}}</span>
                                </div>
                                <div class="select-product">
                                    <span><b>Rate of Interest:-</b> {{$customer->loan->interest?? "-"}}</span>
                                </div>
                                <div class="select-product">
                                    <span><b>Down Payment:-</b> {{$customer->loan->down_payment?? "-"}}</span>
                                </div>
                                <div class="select-product">
                                    <span><b>File Charge:-</b> {{$customer->loan->file_charge?? "-"}}</span>
                                </div>
                                <div class="select-product">
                                    <span><b>Total EMI Amount:-</b> {{$customer->loan->total_amount?? "-"}}</span>
                                </div>
                                <div class="select-product">
                                    <span><b>Total Interest:-</b> {{$customer->loan->total_interest   ?? "-"}}</span>
                                </div>
                                <div class="select-product">
                                    <span><b>EMI Month:-</b> {{$customer->loan->month?? "-"}}</span>
                                </div>
                                <div class="select-product">
                                    <span><b>EMI DATE:-</b> {{$customer->loan->first_emi_date?? "-"}}</span>
                                </div>
                            </div>
                            <div class="col-lg-6 col-md-12 mt-lg-0 mt-5">
                                <table class="table table-striped table-bordered">
                                    <thead>
                                    <tr>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Detail</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($customer->loanPayment as $payment)
                                        <tr>
                                            <td>{{$payment->due_date}}</td>
                                            <td>{{$payment->amount}}</td>
                                            <td>@if($payment->status == "success")
                                                    <a class="btn btn-primary"
                                                    data-bs-detail='{{$payment}}' data-bs-toggle="modal"
                                                    href="#exampleModalToggle" role="button">Details</a>
                                                @else
                                                    <button>{{$payment->status}}</button>
                                                @endif </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                                <div class="modal fade" id="exampleModalToggle" aria-hidden="true"
                                     aria-labelledby="exampleModalToggleLabel" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title" id="exampleModalToggleLabel">EMI Transaction
                                                    History</h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                        aria-label="Close"></button>
                                            </div>
                                            <div class="modal-body">
                                                <div class="select-product">
                                                    <span data-field="date"><b>DATE:-</b> -</span>
                                                </div>
                                                <div class="select-product">
                                                    <span data-field="amount"><b>Amount:-</b> -</span>
                                                </div>
                                                <div class="select-product">
                                                    <span data-field="payment_mode"><b>Payment Mode:-</b> -</span>
                                                </div>
                                                <div class="select-product">
                                                    <span data-field="payment_date"><b>Payment Date:-</b> -</span>
                                                </div>
                                                <div class="select-product">
                                                    <span data-field="status"><b>Status:-</b> -</span>
                                                </div>
                                                <div class="select-product">
                                                    <span data-field="remarks"><b>Remarks:-</b> -</span>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                    Close
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <!-- @endif -->
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const exampleModal = document.getElementById('exampleModalToggle');
            exampleModal.addEventListener('show.bs.modal', function (event) {
                const button = event.relatedTarget; // Button that triggered the modal
                const paymentDetail = JSON.parse(button.getAttribute('data-bs-detail')); // Extract info from data-bs-detail
                console.log(paymentDetail);
                // Update the modal's content with the payment details
                const modalTitle = exampleModal.querySelector('.modal-title');
                const modalBody = exampleModal.querySelector('.modal-body');

                modalBody.querySelector('.select-product span[data-field="date"]').innerHTML = `<b>DATE:-</b> ${paymentDetail.due_date ?? "-"}`;
                modalBody.querySelector('.select-product span[data-field="amount"]').innerHTML = `<b>Amount:-</b> ${paymentDetail.amount ?? "-"}`;
                modalBody.querySelector('.select-product span[data-field="payment_mode"]').innerHTML = `<b>Payment Mode:-</b> ${paymentDetail.payment_mode ?? "-"}`;
                modalBody.querySelector('.select-product span[data-field="payment_date"]').innerHTML = `<b>Payment Date:-</b> ${paymentDetail.paid_date ?? "-"}`;
                modalBody.querySelector('.select-product span[data-field="status"]').innerHTML = `<b>Status:-</b>  ${paymentDetail.status ?? "-"}`;
                modalBody.querySelector('.select-product span[data-field="remarks"]').innerHTML = `<b>Remarks:-</b>  ${paymentDetail.remarks ?? "-"}`;
            });
        });
    </script>
@endpush
