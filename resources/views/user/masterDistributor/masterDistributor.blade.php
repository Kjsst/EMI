<!-- Main Content -->
@extends('layouts.app')
@section('pageTitle', 'Master distributor')
@section('content')
<div id="main-content">
    <div class="banner-head">
        <p>Master Distributor</p>
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
                            <th>Rambaan Coin</th>
                            <th>Brahmastra Coin</th>
                            <th>Reg. Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($MasterDistributors as $key=>$user)
                        <tr>
                            <td>{{$key+1}}</td>
                            <td>
                                {{$user->name??"-"}}
                            </td>
                            <td>
                                {{$user->username??"-"}}
                            </td>
                            <td>
                               {{$user->email ??"-"}}
                            </td>
                            <td>
                                {{$user->mobile?? "-"}}
                            </td>
                            <td>
                                {{$user->rambaan_coin?? "-"}}
                            </td>
                            <td>
                                {{$user->brahmastra_coin?? "-"}}
                            </td>
                            <td>
                                {{ \Carbon\Carbon::parse($user->created_at)->format('d/m/y h:i:s A') ?? "-"}}
                            </td>
                            <td>
                                <a href="{{route('masterDistributor.view',$user->id)}}"> <img src="./assets/img/eye.svg" alt=""></a>
                                <a href="{{route('masterDistributor.users',$user->id)}}"> <img src="./assets/img/Categories.png" alt=""></a>

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
