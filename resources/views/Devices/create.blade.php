@extends('layouts.menu')
@section('title','Authentication')

@section('content')
    <div class="container">
        <h1 class="text-center ion-fade-in">Create a New Device</h1>

        @if(session('success'))
            <div class="alert alert-success ion-fade-in">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger ion-fade-in">
                {{ session('error') }}
            </div>
        @endif

        <form action="{{ route('devices.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label for="name">Device Name</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required>
                @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="url">Device URL</label>
                <input type="text" class="form-control @error('url') is-invalid @enderror" id="url" name="url" value="{{ old('url') }}" required>
                @error('url')
                <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary ion-fade-in">Add Device</button>
        </form>
    </div>
@endsection
