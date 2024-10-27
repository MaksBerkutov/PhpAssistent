@extends('layouts.main')
@section('title','Authentication')
@section('styles')
    <link rel="stylesheet" href="{{asset('css/login.css')}}">
    <script src="{{asset('js/login.js')}}"></script>
@endsection
@section('content')
<div class="d-flex justify-content-center align-items-center vh-100">
    <div class="card p-4">
        <ul class="nav nav-pills mb-3 d-flex justify-content-center" id="pills-tab" role="tablist">
            <li class="nav-item">
                <a class="nav-link active mx-2" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Login</a>
            </li>
            <li class="nav-item">
                <a class="nav-link mx-2" id="pills-profile-tab" data-toggle="pill" href="#pills-profile" role="tab" aria-controls="pills-profile" aria-selected="false">Signup</a>
            </li>
        </ul>

        <div class="tab-content" id="pills-tabContent">
            <div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
                <form method="post" action="{{route('authentication')}}">
                    @csrf
                    <div class="form px-4 pt-3">
                        <x-default-form-input type="text" name="email"/>
                        <x-default-form-input type="password" name="password"/>
                        <button class="btn btn-dark btn-block">Login</button>
                    </div>
                </form>

            </div>

            <div class="tab-pane fade" id="pills-profile" role="tabpanel" aria-labelledby="pills-profile-tab">
                <form  method="post" action="{{route('register')}}">
                    @csrf
                    <div class="form px-4 pt-3">
                        <x-default-form-input type="text" name="name"/>
                        <x-default-form-input type="text" name="email"/>
                        <x-default-form-input type="password" name="password"/>
                        <x-default-form-input type="password" name="password_confirmation" placeholder="Password confirmation"/>
                        <button class="btn btn-dark btn-block">Signup</button>
                    </div>
                </form>

            </div>
        </div>
    </div>
</div>
@endsection
