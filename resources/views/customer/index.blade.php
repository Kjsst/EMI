<!-- Main Content -->
@extends('layouts.app')
@section('pageTitle', 'Customer')
@section('content')
<div id="main-content">
    <div class="banner-head">
        <p>Customer</p>
{{--        <a href="{{route('customer.add')}}"><Button class="open-popup-btn">+ Add New Customer</Button></a>--}}

    </div>

    <form method="GET" id="filterForm">
        <div class="row">
            <div class="col-md-4 mb-1">
                <div class="form-group">
                    <label for="exampleFormControlInput1">Name</label>
                    <input name="name" type="text" class="form-control" id="name-filter" placeholder="Search name">
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="form-group">
                    <label for="exampleFormControlInput1">Mobile</label>
                    <input name="mobile" type="number" class="form-control" id="mobile-filter" placeholder="Search mobile number">
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="form-group">
                    <label for="exampleFormControlInput1">Alternative Mobile Number</label>
                    <input name="alternative-mobile" type="number" class="form-control" id="alternative-mobile-filter" placeholder="Search alternative Mobile Number">
                </div>
            </div>
            {{--<div class="col-md-4">
                <select name="merchant" class="form-control" id="merchant-filter">
                    <option value="all">Select Merchant</option>
                    @foreach($merchants as $key => $customer)
                        <option value="{{$customer->merchant_id}}">{{$customer->merchant->name}}</option>
                    @endforeach
                </select>
            </div>--}}
            <div class="col-md-4">
                <div class="form-group">
                    <label for="exampleFormControlInput1">Finance Type</label>
                    <select name="finance" class="form-control" id="finance-filter">
                        <option value="all">Select Finance Type</option>
                            <option value="NONE">NONE</option>
                            <option value="PERSONAL FINANCE">PERSONAL FINANCE</option>
                            <option value="BAJAJ FINSERV">BAJAJ FINSERV</option>
                            <option value="IDFC">IDFC</option>
                            <option value="HBD">HBD</option>
                            <option value="HDFC">HDFC</option>
                            <option value="ICICI FINANCE">ICICI FINANCE</option>
                            <option value="TVS FINANCE">TVS FINANCE</option>
                            <option value="DM FINSERV">DM FINSERV</option>
                            <option value="PG FINSERV">PG FINSERV</option>
                            <option value="HOME CREDIT">HOME CREDIT</option>
                            <option value="AEON FINANCE">AEON FINANCE</option>
                            <option value="ZEST MONEY">ZEST MONEY</option>
                    </select>
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="form-group">
                    <label for="exampleFormControlInput1">IMEI1</label>
                    <input name="imei1-mobile" type="number" class="form-control" id="imei1-filter" placeholder="Search IMEI1 Number">
                </div>
            </div>
            <div class="col-md-4 mb-1">
                <div class="form-group">
                    <label for="exampleFormControlInput1">IMEI2</label>
                    <input name="imei2-mobile" type="number" class="form-control" id="imei2-filter" placeholder="Search IMEI2 Number">
                </div>
            </div>
            <div class="col-md-4">
                <div class="form-group">
                    <label for="exampleFormControlInput1">&nbsp;&nbsp;</label><br>
                    <button class="btn btn-info" type="submit" id="filter" name="Filter">Filter</button>
                    <button class="btn btn-danger" type="button" id="clear" name="Clear">Clear</button>
                </div>
            </div>
        </div>
    </form> <br>
    <div class="revies">
        <div class="reveiw-content">
            <div class="review-content-table">
                <table id="customer" class="table table-striped">
                    <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Name</th>
                        <th>Type</th>
                        <th>IMEI1</th>
                        <th>IMEI2</th>
                        <th>Merchant Name</th>
                        <th>Email</th>
                        <th>Mobile number</th>
                        <th>Alternative Mobile Number</th>
                        <th>Reg. Date</th>
                        <th>Action</th>
                    </tr>
                    </thead>
                    <tbody>
                    <!-- Data will be populated via AJAX -->
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
            var table = $('#customer').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route("customer.ajax-data") }}',
                    data: function(d) {
                        d.merchant = $('#merchant-filter').val();
                        d.finance = $('#finance-filter').val();
                        d.name = $('#name-filter').val();
                        d.alternative_mobile = $('#alternative-mobile-filter').val();
                        d.mobile = $('#mobile-filter').val();
                        d.imei1 = $('#imei1-filter').val();
                        d.imei2 = $('#imei2-filter').val();
                    }
                },
                columns: [
                    { data: 'sr_no' },
                    { data: 'name' },
                    { data: 'type' },
                    { data: 'imei1' },
                    { data: 'imei2' },
                    { data: 'merchant_name' },
                    { data: 'email' },
                    { data: 'mobile' },
                    { data: 'alter_mobile' },
                    { data: 'reg_date' },
                    {
                        data: 'actions',
                        render: function(data, type, row) {
                            return '<a href="' + data.view + '"> <img src="./assets/img/eye.svg" alt=""></a>' +
                                '<a href="' + data.edit + '"> <img src="./assets/img/edit.svg" alt=""></a>' +
                                '<a href="' + data.device + '"> <img src="./assets/img/device_03.svg" alt=""></a>' +
                                '<a onclick="return confirm(\'Are you sure You want to delete this customer?\')" href="' + data.delete + '"><img src="./assets/img/delete.svg" alt=""></a>';
                        }
                    }
                ],
                pageLength: 10, // Default number of rows per page
                lengthMenu: [ [10, 25, 50, 100], [10, 25, 50, 100] ], // Pagination options
                searchDelay: 500, // Delay for search
                dom: 'lfrtip', // DataTable control elements (pagination included here)
                searching: false, // Enable search functionality
                ordering: false,
                stateSave: true, // Save the state of the table (pagination, search, etc.)
                responsive: true,
                language: {
                    paginate: {
                        first: "First",
                        last: "Last",
                        next: "Next",
                        previous: "Previous"
                    },
                    lengthMenu: "Show _MENU_ records per page", // Show number of records per page
                    info: "Showing _START_ to _END_ of _TOTAL_ entries", // Number of records displayed
                    infoEmpty: "No records available", // When there are no records
                }
            });

            // Re-filter data when form is submitted
            $('#filterForm').on('submit', function(e) {
                e.preventDefault();
                table.ajax.reload(null, false); // Reload data without resetting pagination
            });

            $('#clear').click(function(){
                $('#filterForm')[0].reset();
                table.ajax.reload(null, false); // Reload data without resetting pagination
            });
        });
    </script>
@endpush
