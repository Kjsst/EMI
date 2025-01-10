<!-- Main Content -->
@extends('layouts.app')
@section('pageTitle', 'Merchant')
@section('content')
<div id="main-content">
    <div class="banner-head">
        <p>Merchant</p>
        <a href="{{route('merchant.add')}}"><Button class="open-popup-btn">+ Add New Merchant</Button></a>

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
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($merchants as $key=>$merchant)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>
                                {{$merchant->name??"-"}}
                            </td>
                            <td>
                                {{$merchant->username??"-"}}
                            </td>
                            <td>
                               {{$merchant->email ??"-"}}
                            </td>
                            <td>
                                {{$merchant->mobile?? "-"}}
                            </td>
                            <td>
                                <a href="{{route('merchant.view',$merchant->id)}}"> <img src="./assets/img/eye.svg" alt=""></a>
                                <a href="{{route('merchant.edit',$merchant->id)}}"> <img src="./assets/img/edit.svg" alt=""></a>
{{--                                <img src="./assets/img/delete.svg" alt="">--}}
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
