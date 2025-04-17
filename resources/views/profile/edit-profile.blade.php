@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="row justify-content-center">
            <div class="col-md-8">

                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                        <h4 class="mb-0"><i class="bi bi-person-lines-fill me-2"></i>Edit Profile</h4>
                    </div>

                    <div class="card-body">
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <i class="bi bi-check-circle me-1"></i> {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <form action="{{ route('profile.update') }}" method="POST">
                            @csrf
                            @method('PUT')

                            <!-- Name -->
                            <div class="mb-3">
                                <label for="name" class="form-label fw-semibold">👤 Name</label>
                                <input type="text" id="name" name="name" class="form-control"
                                       value="{{ old('name', auth()->user()->name) }}" required>
                            </div>

                            <!-- Email -->
                            <div class="mb-3">
                                <label for="email" class="form-label fw-semibold">📧 Email</label>
                                <input type="email" id="email" name="email" class="form-control"
                                       value="{{ old('email', auth()->user()->email) }}" required>
                            </div>

                            <!-- Password -->
                            <div class="mb-3">
                                <label for="password" class="form-label fw-semibold">🔒 New Password
                                    <small class="text-muted">(Leave blank to keep current)</small>
                                </label>
                                <input type="password" id="password" name="password" class="form-control">
                            </div>

                            {{--<!-- Guardian Section -->
                            @if(auth()->user()->role == 'guardian')
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">👨‍👩‍👧 Children Name</label>

                                        @foreach(auth()->user()->children as $index => $child)
                                            <li class="list-group-item">
                                                <input type="text" name="children[{{ $child->id }}]" class="form-control"
                                                       value="{{ old("children.$child->id", $child->full_name) }}">
                                            </li>
                                        @endforeach

                                </div>
                            @endif--}}
                            <div class="d-flex justify-content-end mt-4 gap-2">
                                <button type="reset" class="btn btn-secondary">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-save2-fill me-1"></i> Update Profile
                                </button>
                            </div>
                        </form>
                    </div>

                </div>

            </div>
        </div>
    </div>
@endsection
