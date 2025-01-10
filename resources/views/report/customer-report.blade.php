@extends('layouts.app')
@section('pageTitle', 'Account Report')

@section('content')
    <div id="main-content">
        <div class="banner-head">
            <p>Account report</p>
        </div>
        <form method="GET" action="{{ route('customer.report') }}">
            <div class="row">
                <div class="col">
                    <p class="mb-0">From:</p>
                    <input type="date" name="from" class="form-control" value="{{ $from }}">
                </div>
                <div class="col">
                    <p class="mb-0">To:</p>
                    <input type="date" name="to" class="form-control" value="{{ $to }}">
                </div>
                <div class="col">
                    <p class="mb-0">Filter</p>
                    <select name="filter" class="form-control">
                        <option value="all" {{ $filter == 'all' ? 'selected' : '' }}>All</option>
                        <option value="credit" {{ $filter == 'credit' ? 'selected' : '' }}>Credit</option>
                        <option value="debit" {{ $filter == 'debit' ? 'selected' : '' }}>Debit</option>
                        <option value="add customer" {{ $filter == 'add customer' ? 'selected' : '' }}>Add Customer</option>
                    </select>
                </div>
                <div class="col">
                    <p></p>
                    <button class="btn btn-info mt-2" type="submit" id="filter" name="Filter">Filter</button>
                    <a href="{{ route('customer.report.export', ['from' => $from, 'to' => $to, 'filter' => $filter]) }}" class="btn btn-secondary mt-2" id="export" name="Export">Export</a>
                </div>
            </div>
        </form>
        <div class="revies">
            <div class="reveiw-content">
                <div class="review-content-table">
                    <table id="report" class="table table-striped table-bordered">
                        <thead>
                        <tr>
                            <th>S.No</th>
                            <th>From User</th>
                            <th>To User</th>
                            <th>Parent</th>
                            <th>Type</th>
                            <th>Coin</th>
                            <th>From User Balance</th>
                            <th>To User Balance</th>
                            <th>Coin Type</th>
                            <th>Remarks</th>
                            <th>Date</th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach($data as $key => $transaction)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ isset($transaction['from_user_id']) ? $transaction['from_user_id'] : '' }}</td>
                                <td>{{ isset($transaction['to_user_id']) ? $transaction['to_user_id'] : '' }}</td>
                                <td>{{ $transaction['parent'] }}</td>
                                <td>
                                    <div class="reports" style="align-items: center;">
                                        <div>{{ $transaction['type'] ?? '-' }}</div>
                                        @if ($transaction['type'] == 'credit' || $transaction['type'] == 'Credit')
                                            <img src="{{asset('/assets/img/arrow-down.svg')}}"  alt="" width="15px" height="15px">
                                        @elseif ($transaction['type'] == 'debit' || $transaction['type'] == 'Debit')
                                            <img src="{{asset('/assets/img/arrow-up.svg')}}"  alt="" width="15px" height="15px">
                                        @endif
                                    </div>
                                </td>
                                <td>{{ $transaction['coin_quantity'] }}</td>
                                <td>{{ $transaction['balance_from_user'] }}</td>
                                <td>{{ $transaction['balance_to_user'] }}</td>
                                <td>{{ $transaction['coin_type'] }}</td>
                                <td>{{ $transaction['remarks'] }}</td>
                                <td>{{ $transaction['created_at'] }}</td>
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
            $('#report').DataTable();
        });
    </script>
@endpush
