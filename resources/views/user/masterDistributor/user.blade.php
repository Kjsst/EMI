<!-- Main Content -->
@extends('layouts.app')
@section('pageTitle', 'Distributors')
@section('content')
<div id="main-content">
    <div class="banner-head">
        <p>Distributors</p>
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
                        @foreach($distributors as $key=>$distributor)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>
                                {{$distributor->user->name??"-"}}
                            </td>
                            <td>
                                {{$distributor->user->username??"-"}}
                            </td>
                            <td>
                               {{$distributor->user->email ??"-"}}
                            </td>
                            <td>
                                {{$distributor->user->mobile?? "-"}}
                            </td>
                            <td>
                               {{$distributor->user->brahmastra_coin ??"-"}}
                            </td>
                            <td>
                                {{$distributor->user->rambaan_coin?? "-"}}
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($distributor->user->created_at)->format('d/m/y h:i:s A') ?? "-"}}
                            </td>
                            <td>
                                <a href="{{route('distributor.view',$distributor->user->id)}}"> <img src="/assets/img/eye.svg" alt=""></a>
                                <a href="{{route('distributor.edit',$distributor->user->id)}}"> <img src="/assets/img/edit.svg" alt=""></a>
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
