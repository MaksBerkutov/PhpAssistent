@extends('layouts.menu')
@section('title','Authentication')
@section('styles')
    <link rel="stylesheet" href="{{asset('css/profile.css')}}">
@endsection
@section('content')
    <div class="container">
        <div class="page-content page-container" id="page-content">
            <div class="padding">
                <div class="row container d-flex justify-content-center">
                    <div class="col-xl-6 col-md-12">
                        <div class="card user-card-full">
                            <div class="row m-l-0 m-r-0">
                                <div class="col-sm-4 bg-c-lite-green user-profile">
                                    <div class="card-block text-center text-white">
                                        <div class="m-b-25">
                                            <img src="data:image/png;base64,{{Auth::user()->image}}" class="img-radius" alt="User-Profile-Image">
                                        </div>
                                        <h6 class="f-w-600">{{Auth::user()->name}}</h6>
                                        <p>{{Auth::user()->role}}</p>
                                        <i class=" mdi mdi-square-edit-outline feather icon-edit m-t-10 f-16"></i>
                                    </div>
                                </div>
                                <div class="col-sm-8">
                                    <div class="card-block">
                                        <h6 class="m-b-20 p-b-5 b-b-default f-w-600">Information</h6>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <p class="m-b-10 f-w-600">Email</p>
                                                <h6 class="text-muted f-w-400">{{Auth::user()->email}}</h6>
                                            </div>
                                            <div class="col-sm-6">
                                                <p class="m-b-10 f-w-600">Phone</p>
                                                <h6 class="text-muted f-w-400">98979989898</h6>
                                            </div>
                                        </div>
                                        <h6 class="m-b-20 m-t-40 p-b-5 b-b-default f-w-600">Projects</h6>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <p class="m-b-10 f-w-600">Recent</p>
                                                <h6 class="text-muted f-w-400">Sam Disuja</h6>
                                            </div>
                                            <div class="col-sm-6">
                                                <p class="m-b-10 f-w-600">Most Viewed</p>
                                                <h6 class="text-muted f-w-400">Dinoter husainm</h6>
                                            </div>
                                        </div>
                                        <ul class="social-link list-unstyled m-t-40 m-b-10">
                                            <li><a href="#" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="facebook" data-abc="true"><i class="mdi mdi-facebook feather icon-facebook facebook" aria-hidden="true"></i></a></li>
                                            <li><a href="#" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="twitter" data-abc="true"><i class="mdi mdi-twitter feather icon-twitter twitter" aria-hidden="true"></i></a></li>
                                            <li><a href="#" data-toggle="tooltip" data-placement="bottom" title="" data-original-title="instagram" data-abc="true"><i class="mdi mdi-instagram feather icon-instagram instagram" aria-hidden="true"></i></a></li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
