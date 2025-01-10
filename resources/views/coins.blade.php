<!-- Main Content -->
@extends('layouts.app')
@section('pageTitle', 'coins')

@section('content')

    <!-- Main Content -->

    <div id="main-content">
        <div class="container">
            <div class="setting-page">
                <div class="tab">
                    <button class="tablinks settingtab @if($errors->has('from_user_id') || $errors->has('from_coin_type') || $errors->has('from_coins') || $errors->has('from_remarks')) @else active @endif" onclick="openTab(event, 'Tab1')">Credit</button>
                    @if(auth()->user()->hasRole('admin'))
                    <button class="tablinks settingtab @if($errors->has('from_user_id') || $errors->has('from_coin_type') || $errors->has('from_coins') || $errors->has('from_remarks')) active @endif" onclick="openTab(event, 'Tab2')">Debit</button>
                    @endif
                </div>

{{--                <div id="Tab1" class="tabcontent" style="display: block;">--}}
                <div id="Tab1" class="tabcontent @if(!$errors->has('from_user_id') && !$errors->has('from_coin_type') && !$errors->has('from_coins') && !$errors->has('from_remarks'))" style="display: block;" @else style="display: none;" @endif>
                    <form action="{{route('credit')}}" method="post">
                        @csrf
                        <div class="profile-setting">
                            <div class="form-control-s" style="gap: 0px; width: 100%">
                                <label for="Username" style="width: 15%">User Type</label>
                                <select id="role" class="role" name="user_type" style="width: 85%">
                                    <option value="">Select User Type</option>
                                    @foreach($roles as $role)
                                        <option value="{{$role->id}}">{{ ucfirst(trans($role->name)) }}</option>
                                    @endforeach
                                </select>
                                @error('user_type')
                                <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-control-s" style="gap: 0px; width: 100%">
                                <label for="user" style="width: 15%">User</label>
                                <select id="user" class="user" name="user_id" style="width: 85%">
                                    <option value="">Select User</option>
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}">{{$user->name}} - [{{$user->username }}] - [{{$user->mobile }}]</option>
                                    @endforeach
                                </select>
                                @error('user_id')
                                <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-control-s" style="gap: 0px; width: 100%; justify-content: normal !important;">
                                <label for="Coin_type" style="width: 15%">Coin Type</label>
                                <select id="coinType" name="coin_type"  style="width: 25%" >
                                    <option value="">Select coin</option>
                                    <option value="brahmastra_coin">Brahmastra Coin</option>
                                    <option value="rambaan_coin">Rambaan Coin</option>
                                </select>
                                @error('coin_type')
                                <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                                <label for="offer" style="width: 10%; margin-left: 40px;">Balance</label>
                                <input type="text" name="balance" id="balance" class="balance" value="{{$balance}}" placeholder="Coin Balance" style="width: 46%" disabled>
                                @error('balance')
                                <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-control-s" style="gap: 0px; width: 100%; justify-content: normal !important;">
                                <label for="Coins" style="width: 15%">Coins</label>
                                <input type="text" class="offerBuy" name="coins" placeholder="Add Coins" id="coins" style="width: 25%">
                                @error('coins')
                                <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                                <!-- <label for="offer" style="width: 10%; margin-left: 40px;">Coin Offer</label>
                                <select id="offerBuy" class="offerBuy"  name="buy_coin" style="width: 16.5%">
                                    <option value="">Select Coin</option>
                                    @foreach($offers as $offer)
                                        <option value="{{$offer->id}}">{{$offer->buy_coin}}</option>
                                    @endforeach
                                </select>
                                @error('buy_coin')
                                <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror -->

                                <label for="offer" style="width: 10%; margin-left: 40px;">Coin Offer</label>
                                <select id="offerGet" class="offerGet" name="get_coin" style="width: 46%">
                                    <option value="">Select Extra Coin</option>
                                    @foreach($offers as $offer)
                                        <option value="{{$offer->id}}">{{$offer->get_coin}}</option>
                                    @endforeach
                                </select>
                                @error('offer_id')
                                <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-control-s" style="gap: 0px; width: 100%">
                                <label for="Remarks" style="width: 15%">Remarks</label>
                                <input type="text" name="remarks" placeholder="Add Remarks" id="remarks" style="width: 85%">
                                @error('remarks')
                                <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="setting-page-button">
                                <button type="submit" class="btn-dark">Credit</button>
                            </div>
                        </div>
                    </form>
                </div>
{{--            <div id="Tab2" class="tabcontent">--}}
                <div id="Tab2" class="tabcontent @if($errors->has('from_user_id') || $errors->has('from_coin_type') || $errors->has('from_coins') || $errors->has('from_remarks'))" style="display: block;" @else style="display: none;" @endif>
                    <form action="{{route('debit')}}" method="post">
                        @csrf
                        <div class="profile-setting">
                            <div class="form-control-s" style="gap: 0px; width: 100%">
                                <label for="Username">User Type</label>
                                <select id="roleDebit" class="roleDebit" name="user_type" style="width: 85%">
                                    <option value="">Select User Type</option>
                                    @foreach($roles as $role)
                                        <option value="{{$role->id}}">{{ucfirst(trans($role->name))}}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-control-s" style="gap: 0px; width: 100%">
                                <label for="userDebit">User</label>
                                <select id="userDebit" class="userDebit" name="from_user_id" style="width: 85%">
                                    <option value="">Select User</option>
                                    @foreach($users as $user)
                                        <option value="{{$user->id}}">{{$user->name }} - [{{$user->username }}] - [{{$user->mobile }}]</option>
                                    @endforeach
                                </select>
                                @error('from_user_id')
                                <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-control-s" style="gap: 0px; width: 100%; justify-content: normal !important;">
                                <label for="Coin type" style="width: 15%">Coin Type</label>
                                <select id="coinDebit" name="from_coin_type" style="width: 25%">
                                    <option value="">Select coin</option>
                                    <option value="brahmastra_coin">Brahmastra Coin</option>
                                    <option value="rambaan_coin">Rambaan Coin</option>
                                </select>
                                @error('from_coin_type')
                                <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror

                                <label for="offer" style="width: 10%; margin-left: 40px;">Balance</label>
                                <input type="text" name="balanceDebit" id="balanceDebit" class="balanceDebit" value="{{$balance}}" placeholder="Coin Balance" style="width: 46%" disabled>
                                @error('balanceDebit')
                                <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-control-s" style="gap: 0px; width: 100%">
                                <label for="Coins">Coins</label>
                                <input type="text" name="from_coins" placeholder="Add Coins" id="coins" style="width: 85%">
                                @error('from_coins')
                                <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="form-control-s" style="gap: 0px; width: 100%">
                                <label for="Remarks">Remarks</label>
                                <input type="text" name="from_remarks" placeholder="Add Remarks" id="remarks" style="width: 85%">
                                @error('from_remarks')
                                <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>
                            <div class="setting-page-button">
                                <button type="submit" class="btn-dark">Debit</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $(document).ready(function() {
            $('.role').change(function() {
                var roleId = $(this).val();
                $.ajax({
                    url: '{{ route('coins') }}',
                    type: 'GET',
                    data: { role_id: roleId },
                    success: function(data) {
                        $('.user').empty();
                        $('.user').append('<option value="">Select User</option>');
                        $.each(data[0], function(key, value) {
                            $('.user').append('<option value="' + value.id + '">' + value.name + `- [` + value.username + `] - [` + value.mobile + `]`  + '</option>');
                        });
                    }
                });
            });

            $('.roleDebit').change(function() {
                var roleId = $(this).val();
                console.log(roleId);
                $.ajax({
                    url: '{{ route('coins') }}',
                    type: 'GET',
                    data: { role_id: roleId },
                    success: function(data) {
                        $('.userDebit').empty();
                        $('.userDebit').append('<option value="">Select User</option>');
                        $.each(data[0], function(key, value) {
                            $('.userDebit').append('<option value="' + value.id + '">' + value.name + `- [` + value.username + `] - [` + value.mobile + `]`  + '</option>');
                        });
                    }
                });
            });

            $('.user').change(function() {
                var coinType = $('#coinType').val();
                var id = $('.user').val();;
                $.ajax({
                    url: '{{ route('coins') }}',
                    type: 'GET',
                    data: { coin_type: coinType, id: id },
                    success: function(data) {
                        $('.balance').val(0);
                        $('.balance').val(data[2]);
                    }
                });
            });

            $('.userDebit').change(function() {
                var coinType = $('#coinDebit').val();
                var id = $('.userDebit').val();
                console.log(id);
                $.ajax({
                    url: '{{ route('coins') }}',
                    type: 'GET',
                    data: { coin_type: coinType, id: id },
                    success: function(data) {
                        $('.balanceDebit').val(0);
                        $('.balanceDebit').val(data[2]);
                    }
                });
            });

            $('.offerBuy').change(function() {
                var coin = $(this).val();
                $.ajax({
                    url: '{{ route('coins') }}',
                    type: 'GET',
                    data: { buy_coin: coin },
                    success: function(data) {
                        $('.offerGet').empty();
                        $('.offerGet').append('<option value="">Select Extra Coin</option>');
                        $.each(data[1], function(key, value) {
                            $('.offerGet').append('<option value="' + value.get_coin + '">' + value.get_coin + '</option>');
                        });
                    }
                });
            });



        });

        $('#coinType').on('change',function(){
                var coinType = $(this).val();
                var id = $('.user').val();
                console.log(coinType, id);
                $.ajax({
                    url: '{{ route('coins') }}',
                    type: 'GET',
                    data: { coin_type: coinType, id: id },
                    success: function(data) {
                        console.log(data[2]);
                        $('.balance').val(0);
                        $('.balance').val(data[2]);
                    }
                });
        });

        $('#coinDebit').on('change',function(){
                var coinType = $(this).val();
                var id = $('#userDebit').val();
                console.log(id);
                $.ajax({
                    url: '{{ route('coins') }}',
                    type: 'GET',
                    data: { coin_type: coinType, id: id },
                    success: function(data) {
                        console.log(data[2]);
                        $('.balanceDebit').val(0);
                        $('.balanceDebit').val(data[2]);
                    }
                });
        });

    </script>
@endpush
