@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white text-center">
                        <h3 class="mb-0"><i class="fas fa-user-edit"></i> Edit Student Information</h3>
                    </div>
                    <div class="card-body">

                        <!-- Validation Errors -->
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

                        <!-- Edit Student Form -->
                        <form method="POST" action="{{ route('student.update', $student->id) }}">
                            @csrf
                            @method('PUT')

                            <div class="row">
                                <!-- Student Full Name -->
                                <div class="col-md-6">
                                    <label for="full_name" class="form-label"><i class="fas fa-user"></i> Student Name</label>
                                    <input type="text" class="form-control" id="full_name" name="full_name"
                                           value="{{ old('full_name', $student->full_name) }}" required>
                                </div>

                                <!-- IC Number -->
                                <div class="col-md-6">
                                    <label for="ic_number" class="form-label"><i class="fas fa-id-card"></i> MyKid/IC Number</label>
                                    <input type="text" class="form-control" id="ic_number" name="ic_number"
                                           value="{{ old('ic_number', $student->ic_number) }}" required>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <!-- Guardian Name -->
                                <div class="col-md-6">
                                    <label for="guardian_name" class="form-label"><i class="fas fa-user-shield"></i> Guardian Name</label>
                                    <input type="text" class="form-control" id="guardian_name" name="guardian_name"
                                           value="{{ old('guardian_name', $student->guardian_name) }}" required>
                                </div>

                                <!-- Contact Number -->
                                <div class="col-md-6">
                                    <label for="contact_number" class="form-label"><i class="fas fa-phone"></i> Contact Number</label>
                                    <input type="text" class="form-control" id="contact_number" name="contact_number"
                                           value="{{ old('contact_number', $student->contact_number) }}" required>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <!-- Email -->
                                <div class="col-md-12">
                                    <label for="email" class="form-label"><i class="fas fa-envelope"></i> Guardian Email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                           value="{{ old('email', $student->email) }}" placeholder="Enter email">
                                </div>
                            </div>

                            <!-- Submit Buttons -->
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-success px-5">
                                    <i class="fas fa-save"></i> Update
                                </button>
                                <a href="{{ route('student.index') }}" class="btn btn-secondary px-4">
                                    <i class="fas fa-arrow-left"></i> Back
                                </a>
                            </div>
                        </form>

                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const icInput = document.getElementById('ic_number');

            icInput.addEventListener('input', function (e) {
                let value = e.target.value.replace(/\D/g, '');
                value = value.substring(0, 12);

                let formatted = value;
                if (value.length > 6 && value.length <= 8) {
                    formatted = value.substring(0, 6) + '-' + value.substring(6);
                } else if (value.length > 8) {
                    formatted = value.substring(0, 6) + '-' + value.substring(6, 8) + '-' + value.substring(8);
                }

                e.target.value = formatted;
            });
        });
    </script>
@endsection
