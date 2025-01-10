<!-- Main Content -->
@extends('layouts.app')
@section('pageTitle', 'Customers')
@section('content')
<div id="main-content">
    <div class="banner-head">
        <p>Customers</p>
    </div>
    <div class="revies">
        <div class="reveiw-content">
            <div class="review-content-table">
                <table id="merchant" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Name</th>
                            <th>Username</th>
                            <th>Email</th>
                            <th>Mobile Number</th>
                            <th>Brahmastra Coin</th>
                            <th>Rambaan Coin</th>
                            <th>Reg. Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($customers as $key=>$customer)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>
                                {{$customer->user->name??"-"}}
                            </td>
                            <td>
                                {{$customer->user->username??"-"}}
                            </td>
                            <td>
                               {{$customer->user->email ??"-"}}
                            </td>
                            <td>
                                {{$customer->user->mobile?? "-"}}
                            </td>
                            <td>
                               {{$customer->user->brahmastra_coin ??"-"}}
                            </td>
                            <td>
                                {{$customer->user->rambaan_coin?? "-"}}
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($customer->user->created_at)->format('m/d/y h:i:s A')?? "-"}}
                            </td>
                            <td>
                                <a href="{{route('customer.view',$customer->id)}}"> <img src="/assets/img/eye.svg" alt=""></a>
                                <a href="{{route('customer.edit',$customer->id)}}"> <img src="/assets/img/edit.svg" alt=""></a>
                                <a href="{{route('customer.device',$customer->user_id)}}"> <img src="/assets/img/device_03.svg" alt=""></a>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $('#merchant').DataTable();
        } );
    </script>
@endpush
