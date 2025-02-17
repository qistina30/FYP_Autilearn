@extends('layouts.app')

@section('content')
    <div class="container">
        <h2>Upload New Learning Module</h2>

        <form action="{{ route('learning.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-3">
                <label for="title" class="form-label">Title:</label>
                <input type="text" name="title" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="type" class="form-label">Type:</label>
                <select name="type" class="form-control" required>
                    <option value="picture">Picture</option>
                    <option value="video">Video</option>
                </select>
            </div>

            <div class="mb-3">
                <label for="file" class="form-label">Upload File (Image or Video):</label>
                <input type="file" name="file" class="form-control" required>
            </div>

            <div class="mb-3">
                <label for="audio" class="form-label">Upload Audio (Optional):</label>
                <input type="file" name="audio" class="form-control">
            </div>

            <button type="submit" class="btn btn-primary">Upload</button>
        </form>
    </div>
@endsection
