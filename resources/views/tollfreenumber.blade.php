@extends('layouts.app')

@section('content')
    <div id="main-content">
        <div class="container">
            <div class="setting-page">
                <div class="tab">
                    <button class="tablinks settingtab @if($errors->has('from_user_id') || $errors->has('from_coin_type') || $errors->has('from_coins') || $errors->has('from_remarks')) @else active @endif" onclick="openTab(event, 'Tab1')">Add Toll Free No.</button>
                    @if(auth()->user()->hasRole('admin'))
                    <button class="tablinks settingtab @if($errors->has('from_user_id') || $errors->has('from_coin_type') || $errors->has('from_coins') || $errors->has('from_remarks')) active @endif" onclick="openTab(event, 'Tab2')">List of Toll Free No.</button>
                    @endif
                </div>

{{--            <div id="Tab1" class="tabcontent" style="display: block;">--}}
                <div id="Tab1" class="tabcontent @if(!$errors->has('from_user_id') && !$errors->has('from_coin_type') && !$errors->has('from_coins') && !$errors->has('from_remarks'))" style="display: block;" @else style="display: none;" @endif>
                    <form action="{{route('tollfreenumber.store')}}" method="post">
                        @csrf
                        <div class="profile-setting">
                            <div class="form-control-s">
                                <label for="toll_free_number">Toll Free Number</label>
                                <input type="text" name="toll_free_number" placeholder="add toll free number" id="toll_free_number">
                                @error('toll_free_number')
                                <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="setting-page-button">
                                <button type="submit" class="btn-dark">Create</button>
                            </div>
                        </div>
                    </form>
                </div>

{{--            <div id="Tab2" class="tabcontent">--}}
                <div id="Tab2" class="tabcontent @if($errors->has('from_user_id') || $errors->has('from_coin_type') || $errors->has('from_coins') || $errors->has('from_remarks'))" style="display: block;" @else style="display: none;" @endif>
                    <div class="revies">
                        <div class="reveiw-content">
                            <div class="review-content-table">
                                <table id="customer" class="table table-striped" style="width:100% !important">
                                    <thead>
                                        <tr>
                                        <th>S.No</th>
                                        <th>Toll Free Number</th>
                                        <th>Action</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @foreach($tollfreenumbers as $key=>$tollfreenumber)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>
                                                <div class="select-product">
                                                    <span>{{$tollfreenumber->toll_free_number??"-"}}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <a onclick="return confirm('Are you sure You want to delete this banner?')" href="{{route('tollfreenumber.delete', [$tollfreenumber->id])}}"><img src="../assets/img/delete.svg" alt="" width="20" height="20"></a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $('#customer').DataTable();
        } );
    </script>
@endpush
