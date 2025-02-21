@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>All Available Learning Modules</h2>

        <!-- Choose Level Button -->
        <a href="{{ route('choose.level') }}" class="btn btn-primary mb-3">Choose Level</a>

        <!-- Add New Learning Material Button -->
        @if(Auth::user()->role == 'admin' || Auth::user()->role == 'educator')
            <a href="{{ route('learning.create') }}" class="btn btn-success mb-3">Add New Material</a>
        @endif

        <div class="row">
            @foreach ($materials as $material)
                <div class="col-md-4">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="card-title">{{ $material->title }}</h5>
                            @if ($material->type == 'picture')
                                <img src="{{ asset('storage/' . $material->file_path) }}" class="img-fluid">
                                @if ($material->audio_path)
                                    <audio controls>
                                        <source src="{{ asset('storage/' . $material->audio_path) }}" type="audio/mp3">
                                    </audio>
                                @endif
                            @else
                                <video controls class="w-100">
                                    <source src="{{ asset('storage/' . $material->file_path) }}" type="video/mp4">
                                </video>
                            @endif

                            <!-- Delete Button Form (Only for Admin) -->
                            @if(Auth::user()->role == 'admin')
                                <form action="{{ route('learning.destroy', $material->id) }}" method="POST" class="mt-2">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                                </form>
                            @endif
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
