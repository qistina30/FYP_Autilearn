@extends('layouts.app')

@section('content')

    <head>
        <title>Choose Learning Level</title>
{{--        <link rel="stylesheet" type="text/css" href="{{ asset('css/style.css') }}">--}}
    </head>
    <body>
    <h1>Choose Your Learning Level</h1>
    <div class="level-container">
        <a href="{{ route('activity.basic') }}" class="level-button">Basic</a>
        <a href="{{ route('activity.intermediate') }}" class="level-button">Intermediate</a>
        <a href="{{ route('activity.hard') }}" class="level-button">Hard</a>
    </div>
    </body>

@endsection
