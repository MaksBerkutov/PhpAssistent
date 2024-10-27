@extends('layouts.main')
@section('title','Verified Email')

@section('content')
    <div class="alert alert-info" role="alert">
        Thank you for registering! A link to confirm your registration has been sent to your email.
    </div>
    <div>
        Didn't receive the link?
        <form method="post" action="{{route('verification.send')}}">
            @csrf
            <button type="submit" class="btn btn-link ps-0">Send link</button>
        </form>
    </div>
@endsection
