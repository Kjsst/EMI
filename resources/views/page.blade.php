<!-- Main Content -->
@extends('layouts.app')
@section('pageTitle', ucfirst($type))

@section('content')
    <div id="main-content">
        <div class="container">
            <div class="setting-page">
                <div id="Tab1" class="tabcontent" style="display: block;">
                    <div class="profile-setting">

                        <p>{{ucfirst($type)}} page</p>
                        <form action="{{route('page.update')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            <textarea class="form-control" id="content" name="{{$type}}">{{$content->content ?? ""}}</textarea>
                            <div class="setting-page-button mt-5">
                                <button type="submit" class="btn-dark">save</button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

