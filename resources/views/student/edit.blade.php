@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white text-center d-flex justify-content-between align-items-center">
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
                                    <label for="full_name" class="form-label"><i class="fas fa-user" style="color: #28a745;"></i> Student Name</label>
                                    <input type="text" class="form-control" id="full_name" name="full_name"
                                           value="{{ old('full_name', $student->full_name) }}" required>
                                </div>

                                <!-- IC Number -->
                                <div class="col-md-6">
                                    <label for="ic_number" class="form-label"><i class="fas fa-id-card" style="color: #17a2b8;"></i> MyKid/IC Number</label>
                                    <input type="text" class="form-control" id="ic_number" name="ic_number"
                                           value="{{ old('ic_number', $student->ic_number) }}" required>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <!-- Guardian Name -->
                                <div class="col-md-6">
                                    <label for="guardian_name" class="form-label"><i class="fas fa-user-shield" style="color: #ffc107;"></i> Guardian Name</label>
                                    <input type="text" class="form-control" id="guardian_name" name="guardian_name"
                                           value="{{ old('guardian_name', $student->guardian_name) }}" required>
                                </div>

                                <!-- Contact Number -->
                                <div class="col-md-6">
                                    <label for="contact_number" class="form-label"><i class="fas fa-phone" style="color: #fd7e14;"></i> Guardian Contact Number</label>
                                    <input type="text" class="form-control" id="contact_number" name="contact_number"
                                           value="{{ old('contact_number', $student->contact_number) }}" required>
                                </div>
                            </div>

                            <div class="row mt-3">
                                <!-- Email -->
                                <div class="col-md-12">
                                    <label for="email" class="form-label"><i class="fas fa-envelope" style="color: #007bff;"></i> Guardian Email</label>
                                    <input type="email" class="form-control" id="email" name="email"
                                           value="{{ old('email', $student->email) }}" placeholder="Enter email">
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
