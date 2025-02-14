@extends('layouts.app')

@section('content')
    <h1>Learning Modules</h1>

    <!-- Video Section -->
    <div class="video-container">
        <video id="learning-video" width="700" controls>
            <source src="{{ asset('videos/animals_intro.mp4') }}" type="video/mp4">
            Your browser does not support the video tag.
        </video>
    </div>

    <!-- Button to go to the Animal Recognition Module -->
    <a href="{{ route('student.learning-module.animal-recognition') }}" class="btn btn-primary mt-3">
        Go to Animal Recognition Module
    </a>
@endsection

@push('styles')
    <style>
        .video-container {
            text-align: center;
            margin-bottom: 20px;
        }

        video {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }
    </style>
@endpush
