@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Welcome to Your Dashboard, {{ Auth::user()->name }}</h1>

        <div class="row">
            <div class="col-md-12">

                <a href="{{ route('educator.add-student') }}" class="btn btn-primary">Add New Student</a>
            </div>
        </div>
    </div>
@endsection
