@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white text-center d-flex justify-content-between align-items-center">
                        <h3 class="mb-0"><i class="fas fa-user-edit"></i> Edit User Information</h3>
                    </div>
                    <div class="card-body">

                        {{-- Validation Errors --}}
                        @if ($errors->any())
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <ul class="mb-0">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        {{-- Edit User Form --}}
                        <form method="POST" action="{{ route('admin.update', $user->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Full Name -->
                                <div class="col-md-6">
                                    <label for="name" class="form-label"><i class="fas fa-user" style="color: #28a745;"></i> Full Name</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                           value="{{ old('name', $user->name) }}" required>
                                </div>

                                <!-- Email -->
                                <div class="col-md-6">
                                    <label for="email" class="form-label"><i class="fas fa-envelope" style="color: #007bff;"></i> Email Address</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                           value="{{ old('email', $user->email) }}" required>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <!-- Role (Read-Only with Tooltip) -->
                                <div class="col-md-6">
                                    <label for="role" class="form-label">
                                        <i class="fas fa-user-tag" style="color: #6f42c1;"></i> Role
                                        <i class="fas fa-info-circle text-info ms-1" data-bs-toggle="tooltip" title="This role is fixed and cannot be changed."></i>
                                    </label>
                                    <input type="text" class="form-control bg-light" id="role" value="{{ ucfirst($user->role) }}" readonly>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4 gap-2">
                                <button type="reset" class="btn btn-secondary">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save"></i> Update
                                </button>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <!-- Enable Bootstrap Tooltips -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" crossorigin="anonymous"></script>
    <script>
        document.addEventListener("DOMContentLoaded", function () {
            var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        });
    </script>
@endsection
