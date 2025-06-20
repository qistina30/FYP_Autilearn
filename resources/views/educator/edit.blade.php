@extends('layouts.app')


@section('content')

    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white text-center d-flex justify-content-between align-items-center">
                        <h3 class="mb-0"><i class="fas fa-user-edit"></i> Edit Educator Information</h3>
                    </div>
                    <div class="card-body">

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

                        <form method="POST" action="{{ route('educator.update', $educator->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- User ID (Disabled with Tooltip) -->
                                <div class="col-md-6">
                                    <label for="user_id" class="form-label">
                                        <i class="fas fa-id-badge" style="color: #007bff;"></i> User ID
                                        <i class="fas fa-info-circle text-info ms-1" data-bs-toggle="tooltip"
                                           data-bs-placement="top"
                                           title="This User ID is fixed and provided by the school/admin."></i>

                                    </label>
                                    <input type="text" class="form-control" id="user_id" name="user_id"
                                           value="{{ old('user_id', $educator->user_id) }}" disabled>
                                </div>

                                <!-- Name -->
                                <div class="col-md-6">
                                    <label for="name" class="form-label"><i class="fas fa-user" style="color: #28a745;"></i> Name</label>
                                    <input type="text" class="form-control" id="name" name="name"
                                           value="{{ old('name', $educator->name) }}" required>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <!-- Email -->
                                <div class="col-md-12">
                                    <label for="email" class="form-label"><i class="fas fa-envelope" style="color: #ffc107;"></i> Email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                           value="{{ old('email', $educator->email) }}" required>
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4 gap-2">
                                <button type="reset" class="btn btn-secondary">
                                    <i class="bi bi-arrow-counterclockwise me-1"></i> Reset
                                </button>
                                <button type="submit" class="btn btn-success">
                                    <i class="bi bi-save2-fill me-1"></i> Update
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
