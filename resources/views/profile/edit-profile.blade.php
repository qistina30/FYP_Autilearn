@extends('layouts.app')

@section('content')
    <div class="container">
        <h2 class="mb-4">Edit Profile</h2>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <form action="{{ route('profile.update') }}" method="POST">
            @csrf
            @method('PUT')

            <!-- Name -->
            <div class="mb-3">
                <label for="name" class="form-label">Name</label>
                <input type="text" id="name" name="name" class="form-control"
                       value="{{ old('name', auth()->user()->name) }}" required>
            </div>

            <!-- Email -->
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" id="email" name="email" class="form-control"
                       value="{{ old('email', auth()->user()->email) }}" required>
            </div>

            <!-- Password (Optional) -->
            <div class="mb-3">
                <label for="password" class="form-label">New Password (Leave blank to keep current)</label>
                <input type="password" id="password" name="password" class="form-control">
            </div>

            <!-- Guardian Section (Editable Children Names) -->
            @if(auth()->user()->role == 'guardian')
                <div class="mb-3">
                    <label class="form-label">Children</label>
                    <ul class="list-group">
                        @foreach(auth()->user()->children as $index => $child)
                            <li class="list-group-item">
                                <input type="text" name="children[{{ $child->id }}]" class="form-control"
                                       value="{{ old("children.$child->id", $child->full_name) }}">
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <button type="submit" class="btn btn-success">Update Profile</button>
            <a href="{{ url('/') }}" class="btn btn-secondary">Cancel</a>
        </form>
    </div>
@endsection
