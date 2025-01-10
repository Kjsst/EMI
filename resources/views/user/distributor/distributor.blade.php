@extends('layouts.app')
@section('pageTitle', 'Distributor')
@section('content')
<div id="main-content">
    <div class="banner-head">
        <p>Distributor</p>
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
                            <th>Parent</th>
                            <th>Mobile Number</th>
                            <th>Rambaan Coin</th>
                            <th>Brahmastra Coin</th>
                            <th>Reg. Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Data will be loaded dynamically via AJAX -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

@endsection

@push('script')
<script src="https://cdn.jsdelivr.net/npm/moment@2.29.1/moment.min.js"></script>
<script>
    $(document).ready(function() {
        var table = $('#merchant').DataTable({
            processing: true,
            serverSide: true,
            ajax: {
                url: "{{ route('distributor.ajaxList') }}", // URL to fetch data via AJAX
                type: "GET", // AJAX method
                data: function(d) {
                    // If you need to pass any additional parameters like filters:
                    d._token = "{{ csrf_token() }}"; // CSRF token for security
                }
            },
            columns: [
                { data: 'id', name: 'id' },
                { data: 'user_name', name: 'user_name' },
                { data: 'username', name: 'username' },
                { data: 'email', name: 'email' },
                { data: 'parent', name: 'parent' },
                { data: 'mobile', name: 'mobile' },
                { data: 'rambaan_coin', name: 'rambaan_coin' },
                { data: 'brahmastra_coin', name: 'brahmastra_coin' },
                { data: 'created_at', name: 'created_at', render: function(data) {
                    return moment(data).format('DD/MM/YY hh:mm:ss A');
                }},
                { data: 'action', name: 'action', orderable: false, searchable: false }
            ],
            pageLength: 10, // Default rows per page
            lengthMenu: [ [10, 25, 50, 100], [10, 25, 50, 100] ], // Pagination options
            dom: 'Bfrtip', // DataTable controls
            stateSave: true, // Save table state (pagination, search, etc.)
        });
    });
</script>
@endpush