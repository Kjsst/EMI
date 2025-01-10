<!-- Main Content -->
@extends('layouts.app')
@section('pageTitle', 'Merchants')
@section('content')
<div id="main-content">
    <div class="banner-head">
        <p>Merchants</p>
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
                            <td>Action</td>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($merchants as $key=>$merchant)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>
                                {{$merchant->user->name??"-"}}
                            </td>
                            <td>
                                {{$merchant->user->username??"-"}}
                            </td>
                            <td>
                               {{$merchant->user->email ??"-"}}
                            </td>
                            <td>
                                {{$merchant->user->mobile?? "-"}}
                            </td>
                            <td>
                               {{$merchant->user->brahmastra_coin ??"-"}}
                            </td>
                            <td>
                                {{$merchant->user->rambaan_coin?? "-"}}
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($merchant->user->created_at)->format('m/d/y h:i:s A') ?? "-"}}
                            </td>
                            <td>
                                <a href="{{route('merchant.view',$merchant->user->id)}}"> <img src="/assets/img/eye.svg" alt=""></a>
                                <a href="{{route('merchant.edit',$merchant->user->id)}}"> <img src="/assets/img/edit.svg" alt=""></a>
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