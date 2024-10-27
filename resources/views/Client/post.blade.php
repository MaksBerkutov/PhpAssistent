@extends('layouts.main')
@section('title','post')

@section('content')
    @if (session('message'))
        <div class="alert alert-info">
            {{ session('message') }}
        </div>
    @endif
   <form method="post">
       @CSRF
       <input type="text" name="message" required>
       <input type="submit">
   </form>
@endsection
