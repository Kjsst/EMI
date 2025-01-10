<!-- Main Content -->
@extends('layouts.app')
@section('pageTitle', 'Employee')
@section('content')
<div id="main-content">
    <div class="banner-head">
        <p>Employee</p>
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
                        @foreach($employees as $key=>$user)
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
{{--                                <a href="{{route('merchant.view',$user->id)}}"> <img src="./assets/img/eye.svg" alt=""></a>--}}
{{--                                <a href="{{route('merchant.edit',$user->id)}}"> <img src="./assets/img/edit.svg" alt=""></a>--}}
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
