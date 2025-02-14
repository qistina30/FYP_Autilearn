@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Animal Sound Recognition</h2>

        <p>Listen to the sound and choose the correct animal.</p>

        <!-- Play the Animal Sound -->
        <audio controls>
            <source src="{{ asset('sounds/cat.mp3') }}" type="audio/mpeg">
            Your browser does not support the audio element.
        </audio>

        <!-- Form for Answer Submission -->
        <form action="{{ route('submit.answer') }}" method="POST">
            @csrf

            <label>
                <input type="radio" name="animal" value="cat"> Cat
            </label><br>

            <label>
                <input type="radio" name="animal" value="dog"> Dog
            </label><br>

            <label>
                <input type="radio" name="animal" value="elephant"> Elephant
            </label><br>

            <label>
                <input type="radio" name="animal" value="bird"> Bird
            </label><br>

            <button type="submit" class="btn btn-primary mt-3">Submit Answer</button>
        </form>

        @if(session('feedback'))
            <div class="alert alert-info mt-3">
                {{ session('feedback') }}
            </div>
        @endif
    </div>
@endsection
