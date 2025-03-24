@extends('layouts.app')

@section('content')


    <h1>Choose Subject </h1>

    <div class="level-container">
        <a href="{{ route('activity.basic') }}">Science</a>
{{--        <a href="{{ route('activity.intermediate') }}" class="level-button">Unscrambled</a>--}}
        <a href="{{ route('activity.math') }}" class="level-button">Math</a>
        <a href="{{ route('activity.spellingBee') }}" class="level-button">Spelling Bee</a>
    </div>



@endsection
