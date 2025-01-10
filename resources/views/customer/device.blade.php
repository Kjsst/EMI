<!-- Main Content -->
@extends('layouts.app')
@section('pageTitle', 'device')

@section('content')
    <div id="main-content">
        <div class="container">
            <div class="setting-page">

                <div id="Tab1" class="tabcontent" style="display: block;">
                    <div class="row">
                        <div class="col-lg-6 col-md-12">
                            <p>Device Detail</p>
                            <div class="select-product">
                                <span><b>INEI1: </b>{{$data['imei_one']?? "-"}} </span>
                            </div>
                            <div class="select-product">
                                <span><b>IMEI2: </b>{{$data['imei_two'] ?? "-"}}</span>
                            </div>
                            <div class="select-product">
                                <span><b>Devcie Status: </b>
                                    @if($data['status'] == 1)
                                        Not Active Yet
                                    @elseif($data['status'] == 2)
                                        Active and App Installed
                                    @elseif($data['status'] == 3)
                                        Lock Stage
                                    @elseif($data['status'] == 4)
                                        Uninstalled
                                    @elseif($data['status'] == 5)
                                        Deactivating
                                    @else
                                        In-Locking Stage
                                    @endif
                                </span>
                            </div>
                            <div class="select-product">
                                <span><b>ZT Status: </b>
                                    @if($data['zt_status'] == 1)
                                       IMEI not register with ZT
                                    @elseif($data['zt_status'] == 2)
                                        IMEI successfully register with ZT
                                    @else
                                        IMEI already register with another ZT
                                    @endif
                                </span>
                            </div>
                            <div class="select-product">
                                <span><b>Type: </b>
                                    @if($data['zt_status'] == 1)
                                        QR Code Enrollment
                                    @else
                                        Old phone enrollment with APK
                                    @endif
                                </span>
                            </div>
                            <div class="select-product">
                                <span><b>Model: </b>{{$data['model'] ?? "-"}}</span>
                            </div>
                            <div class="select-product">
                                <span><b>Manufacturer: </b>{{$data['manufacturer'] ?? "-"}}</span>
                            </div>
                            <div class="select-product">
                                <span><b>Latitude: </b>{{$data['latitude'] ?? "-"}}</span>
                            </div>
                            <div class="select-product">
                                <span><b>Longitude: </b>{{$data['longitude'] ?? "-"}}</span>
                            </div>
                            <div class="select-product">
                                <span><b>Mobile One: </b>{{$data['mobile_one'] ?? "-"}}</span>
                            </div>
                            <div class="select-product">
                                <span><b>Mobile One Network: </b>{{$data['mobile_one_network'] ?? "-"}}</span>
                            </div>
                            <div class="select-product">
                                <span><b>Mobile Two: </b>{{$data['mobile_two'] ?? "-"}}</span>
                            </div>
                            <div class="select-product">
                                <span><b>Mobile Two Network: </b>{{$data['mobile_two_network'] ?? "-"}}</span>
                            </div>
                            <div class="select-product">
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mt-3">
                            <button class="device btn btn-danger" data-name="lock">Lock Device</button>
                        </div>
                        <div class="col mt-3">
                            <button class="device btn btn-secondary" data-name="unlock">Unlock Device</button>
                        </div>
                        <div class="col mt-3">
                            <button class="device btn btn-success" data-name="location-update">Update location</button>
                        </div>
                        <div class="col mt-3">
                            <button class="device btn btn-warning" data-name="reboot">Reboot Device</button>
                        </div>
{{--                        <div class="col mt-3">--}}
{{--                            <button class="btn btn-primary" data-name="change_password">Change Password</button>--}}
{{--                        </div>--}}
                        <div class="col mt-3">
                            <button class="device btn btn-primary" data-name="audio">Play audio</button>
                        </div>
                        <div class="col mt-3">
                            <button class="device btn btn-success" data-name="active">Active Device</button>
                        </div>
                        <div class="col mt-3">
                            <button class="device btn btn-danger" data-name="deactivate">Deactivate Device</button>
                        </div>
                        <div class="col mt-3">
                            <button class="device btn btn-info" data-name="set-wallpaper">Set Wallpaper</button>
                        </div>
                        <div class="col mt-3">
                            <button class="device btn btn-secondary" data-name="remove-wallpaper">Remove Wallpaper</button>
                        </div>
                        <div class="col mt-3">
                            <button class="device btn btn-warning" data-name="remove-wallpaper">Remove Wallpaper</button>
                        </div>
                        <div class="col mt-3">
                            <button class="device btn btn-danger" data-name="uninstall">Uninstall app</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('script')
    <script>
        $('.btn').on('click',function (){
           var name =  $(this).attr('data-name');
            $.ajax({
                url: '{{route('device.status')}}',
                method: 'POST',
                data: {
                    'type': name,
                    'imei1': {{$data['imei_one']}},
                    "_token": "{{ csrf_token() }}",
                },
                success: function(res) {
                    console.log(res);
                    if (res.success){
                        toastr.success(res.success)
                        setTimeout(location.reload.bind(location), 3000);
                    }
                    else {
                        toastr.error(res.message)
                    }
                },
                error: function(xhr, status, error) {

                }
            });
        });
    </script>
@endpush
