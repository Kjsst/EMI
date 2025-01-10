@extends('layouts.app')
@section('pageTitle', 'Device Report')

@section('content')
    <style>
        .ui-widget.ui-widget-content {
            max-height: 200px;
            overflow: auto;
        }
    </style>
    <div id="main-content">
        <div class="banner-head">
            <p>Device report</p>
        </div>

        <form method="GET" action="{{ route('device.list') }}" id="filterForm">
            <div class="row">
                <div class="col-md-4 mb-1">
                    <div class="form-group">
                        <label for="exampleFormControlInput1">User Name</label>
                        <input name="name" type="text" class="form-control" id="name-filter" placeholder="Search name">
                    </div>
                </div>
                <div class="col-md-4 mb-1">
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Merchant Name</label>
                        <input name="merchant-name" type="text" class="form-control" id="merchant-name-filter" placeholder="Search merchant name">
                        <input name="user_id" type="hidden" value="" class="form-control" id="user_id-filter" placeholder="Search merchant name">
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

                {{--<div class="col-md-4">
                    <select name="merchant" class="form-control" id="user">
                        <option value="all" {{ $merchant == 'all' ? 'selected' : '' }}>Select Merchant</option>
                        @foreach($customers as $key => $customer)
                            <option value="{{$customer->user_id}}" {{ $merchant == $customer->user_id ? 'selected' : '' }}>{{$customer->merchant->name}} - [{{$customer->merchant->email}}] - [{{$customer->merchant->mobile}}]</option>
                        @endforeach
                    </select>
                </div>--}}
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="exampleFormControlInput1">Device Status</label>
                        <select name="filter" class="form-control" id="drive-status-filter">
                            <option value="all">Select Device Status</option>
                            <option value="enrolled">Enrolled</option>
                            <option value="2">UnLocked</option>
                            <option value="3">Locked</option>
                            <option value="4">Deleted</option>
                            <option value="5">Inactive</option>
                        </select>
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
                    <table id="report" class="table table-striped table-hover dt-responsive" cellspacing="5" width="100%">
                        <thead>
                        <tr>
                            <th>S.No</th>
                            <th>User Name</th>
                            <th>Merchant Name</th>
                            <th>IMEI1</th>
                            <th>IMEI2</th>
                            <th>Model</th>
                            <th>Manufacturer</th>
                            <th>Mobile One</th>
                            <th>Mobile One Network</th>
                            <th>Mobile Two</th>
                            <th>Mobile Two Network</th>
                            <th>Device Status</th>
                            <th>Date</th>
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
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>

    <script>
        $(document).ready(function() {
            // Initialize DataTable with AJAX and server-side processing
            var table = $('#report').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('device.ajaxList') }}",  // URL to fetch data via AJAX
                    data: function(d) {
                        // Send the filter values from the form to the server
                        d.user_id = $('#user_id-filter').val();
                        d.finance = $('#finance-filter').val();
                        d.name = $('#name-filter').val();
                        d.alternative_mobile = $('#alternative-mobile-filter').val();
                        d.mobile = $('#mobile-filter').val();
                        d.imei1 = $('#imei1-filter').val();
                        d.imei2 = $('#imei2-filter').val();
                        d.drive_status = $('#drive-status-filter').val();
                        d._token = "{{ csrf_token() }}"; // Pass CSRF token
                    }
                },
                columns: [
                    { data: 'id', name: 'id' },
                    { data: 'user_name', name: 'user_name' },
                    { data: 'merchant_name', name: 'merchant_name' },
                    { data: 'imei1', name: 'imei1' },
                    { data: 'imei2', name: 'imei2' },
                    { data: 'model', name: 'model' },
                    { data: 'manufacturer', name: 'manufacturer' },
                    { data: 'mobile_one', name: 'mobile_one' },
                    { data: 'mobile_one_network', name: 'mobile_one_network' },
                    { data: 'mobile_two', name: 'mobile_two' },
                    { data: 'mobile_two_network', name: 'mobile_two_network' },
                    { data: 'status', name: 'status', render: function(data) {
                            switch(data) {
                                case 2: return 'UnLocked';
                                case 3: return 'Locked';
                                case 4: return 'Deleted';
                                case 5: return 'Inactive';
                                default: return 'Enrolled';
                            }
                        }},
                    { data: 'created_at', name: 'created_at', render: function(data) {
                            // Check if data exists and is a valid date format
                            if (data) {
                                // If it's an ISO 8601 date string, Moment.js can parse it automatically
                                // Check if the date looks like ISO 8601 format (e.g., '2024-12-05T15:30:00Z')
                                if (moment(data, moment.ISO_8601, true).isValid()) {
                                    return moment(data).format('DD/MM/YY hh:mm:ss A');
                                } else {
                                    // If it's not in ISO format, try parsing it as a Unix timestamp (seconds or milliseconds)
                                    return moment(data).isValid() ? moment(data).format('DD/MM/YY hh:mm:ss A') : '';
                                }
                            } else {
                                return ''; // In case the date is missing or invalid
                            }
                        }}
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
                $('#user_id-filter').val('');
                $('#filterForm')[0].reset();
                table.ajax.reload(null, false); // Reload data without resetting pagination
            });
        });


        $("#merchant-name-filter").on('input', function() {
            $('#user_id-filter').val('');
        });

        $(document).ready(function() {
            $("#merchant-name-filter").autocomplete({
                source: function(request, response) {
                    // Make API call to your backend
                    $.ajax({
                        url: '/merchant/search', // Your API endpoint for fetching merchant names
                        data: {
                            term: request.term // Pass the search term
                        },
                        dataType: 'json',
                        success: function(data) {
                            var formattedData = $.map(data, function(value, key) {
                                return {
                                    label: value,  // This will be shown in the dropdown
                                    value: value,  // Same as label, displayed when selected
                                    user_id: key   // The key (user_id) that you want to store
                                };
                            });
                            response(formattedData);
                        },
                        error: function() {
                            response([]); // Handle error, if needed
                        }
                    });
                },
                minLength: 3, // Minimum number of characters before making an API call
                select: function(event, ui) {
                    // When an item is selected from the autocomplete list
                    console.log("Selected Name/Email: ", ui.item.value); // Merchant name and email
                    console.log("Selected User ID: ", ui.item.user_id);  // The user_id that you want to store

                    // Store the user_id (key) in a hidden input
                    $("#user_id-filter").val(ui.item.user_id); // Assuming you have a hidden input field with id="user_id"
                }
            });
        });
    </script>
@endpush
