@extends('layouts.app')

@section('content')
    <div id="main-content">
        <div class="container">
            <div class="setting-page">
                <div class="tab">
                    <button class="tablinks settingtab @if($errors->has('from_user_id') || $errors->has('from_coin_type') || $errors->has('from_coins') || $errors->has('from_remarks')) @else active @endif" onclick="openTab(event, 'Tab1')">Add Coin Offer</button>
                    @if(auth()->user()->hasRole('admin'))
                    <button class="tablinks settingtab @if($errors->has('from_user_id') || $errors->has('from_coin_type') || $errors->has('from_coins') || $errors->has('from_remarks')) active @endif" onclick="openTab(event, 'Tab2')">Coin Offer List</button>
                    @endif
                </div>

{{--            <div id="Tab1" class="tabcontent" style="display: block;">--}}
                <div id="Tab1" class="tabcontent @if(!$errors->has('from_user_id') && !$errors->has('from_coin_type') && !$errors->has('from_coins') && !$errors->has('from_remarks'))" style="display: block;" @else style="display: none;" @endif>
                    <form action="{{route('coinoffer.store')}}" method="post">
                        @csrf
                        <div class="profile-setting">
                            <div class="form-control-s">
                                <label for="buy_coin">Buy Coin</label>
                                <input type="text" name="buy_coin" placeholder="Add buy coin" id="buy_coin">
                                @error('buy_coin')
                                <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-control-s">
                                <label for="get_coin">Get Extra Coin</label>
                                <input type="text" name="get_coin" placeholder="Add extra coin" id="get_coin">
                                @error('get_coin')
                                <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="form-control-s">
                                <label for="Coin type">Coin Type</label>
                                <select id="coin" name="coin_type">
                                    <option value="">Select coin</option>
                                    <option value="brahmastra_coin">Brahmastra Coin</option>
                                    <option value="rambaan_coin">Rambaan Coin</option>
                                </select>
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
                                        <th>Buy Coin</th>
                                        <th>Get Extra Coin</th>
                                        <th>Coin Type</th>
                                        <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($coinoffers as $key=>$coinoffer)
                                        <tr>
                                            <td>{{$key+1}}</td>
                                            <td>
                                                <div class="select-product" style="display: block">
                                                    <span>{{$coinoffer->buy_coin??"-"}}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="select-product" style="display: block">
                                                    <span>{{$coinoffer->get_coin??"-"}}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <div class="select-product" style="display: block">
                                                    <span>{{$coinoffer->coin_type??"-"}}</span>
                                                </div>
                                            </td>
                                            <td>
                                                <a onclick="return confirm('Are you sure You want to delete this coin offer?')" href="{{route('coinoffer.delete', [$coinoffer->id])}}"><img src="../assets/img/delete.svg" alt="" width="20" height="20"></a>
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
