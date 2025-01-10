<!-- Main Content -->
@extends('layouts.app')
@section('pageTitle', 'contact')

@section('content')
    <div id="main-content">
        <div class="container">
            <div class="setting-page">

                <div id="Tab1" class="tabcontent" style="display: block;">
                    <div class="profile-setting">
                        <p>Contact Page Information</p>
                        <form action="{{route('contact.update')}}" method="post" enctype="multipart/form-data">
                            @csrf
                            @foreach($contacts as $contact)
                                <div class="form-control-s">
                                    <label for="name">{{ucfirst(trans($contact->name))}}</label>
                                    <input name="{{$contact->name}}" type="text" value="{{$contact->value}}" placeholder="Name">
                                    @error($contact->name)
                                    <span class="invalid-feedback" style="justify-content: end;" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>
                            @endforeach
                            <div class="setting-page-button">
                                <button type="submit" class="btn-dark">save</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
