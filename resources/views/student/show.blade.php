@extends('layouts.app')

@section('content')
    <div class="container py-4">
        <div class="card shadow-sm p-4 rounded-4 border-0">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4 class="fw-bold text-primary mb-0">
                    🎓 Student Details
                </h4>
                <div>
                    <a href="{{ route('student.edit', $student->id) }}" class="btn btn-warning btn-sm me-2">
                        ✏️ Edit
                    </a>
                    <a href="{{ route('student.index') }}" class="btn btn-outline-secondary btn-sm">
                        🔙 Back to List
                    </a>
                </div>
            </div>

            <div class="row g-3">
                <div class="col-md-6">
                    <div class="p-3 border rounded-3 bg-light">
                        <h6 class="fw-semibold text-muted mb-1">Full Name</h6>
                        <p class="mb-0">{{ $student->full_name }}</p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="p-3 border rounded-3 bg-light">
                        <h6 class="fw-semibold text-muted mb-1">IC Number</h6>
                        <p class="mb-0">{{ $student->ic_number }}</p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="p-3 border rounded-3 bg-light">
                        <h6 class="fw-semibold text-muted mb-1">Guardian Name</h6>
                        <p class="mb-0">{{ $student->guardian_name }}</p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="p-3 border rounded-3 bg-light">
                        <h6 class="fw-semibold text-muted mb-1">Contact Number</h6>
                        <p class="mb-0">{{ $student->contact_number }}</p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="p-3 border rounded-3 bg-light">
                        <h6 class="fw-semibold text-muted mb-1">Email</h6>
                        <p class="mb-0">{{ $student->email ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="p-3 border rounded-3 bg-light">
                        <h6 class="fw-semibold text-muted mb-1">Added By</h6>
                        <p class="mb-0">{{ $student->educator->name ?? 'N/A' }}</p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="p-3 border rounded-3 bg-light">
                        <h6 class="fw-semibold text-muted mb-1">Date Added</h6>
                        <p class="mb-0">{{ $student->created_at->format('d M Y, h:i A') }}</p>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="p-3 border rounded-3 bg-light">
                        <h6 class="fw-semibold text-muted mb-1">Last Updated</h6>
                        <p class="mb-0">{{ $student->updated_at->format('d M Y, h:i A') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
