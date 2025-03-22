@extends('layouts.app')

@section('content')


    <h1>Choose Your Activity </h1>

    <div class="level-container">
        <a href="{{ route('activity.basic') }}">Basic</a>
        <a href="{{ route('activity.intermediate') }}" class="level-button">Intermediate</a>
        <a href="{{ route('activity.math') }}" class="level-button">Math</a>
    </div>



@endsection
