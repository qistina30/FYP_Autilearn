@extends('layouts.app')

@section('content')


    <body>
    <h1>Choose Your Activity Level</h1>

    <div class="level-container">
        <a href="#" id="basicBtn" class="level-button">Basic</a>
        <a href="{{ route('activity.intermediate') }}" class="level-button">Intermediate</a>
        <a href="{{ route('activity.hard') }}" class="level-button">Hard</a>
    </div>

    <!-- Show Activities for Basic Level -->
    <div id="basicActivities" style="display: none;">
        <h3>Basic Level Activities</h3>
        <ul>
            <li><a href="{{ route('activity.basic') }}">Find the Missing Letter</a></li>
            <li><a href="{{ route('activity.basicLetterTracing') }}">Match the Letter</a></li>
        </ul>
    </div>

    <script>
        document.getElementById("basicBtn").addEventListener("click", function(event) {
            event.preventDefault(); // Prevent page reload
            document.getElementById("basicActivities").style.display = "block";
        });
    </script>
    </body>

@endsection
