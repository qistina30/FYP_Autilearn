@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Welcome to Your Dashboard, {{ Auth::user()->name }}</h1>


    </div>
@endsection
