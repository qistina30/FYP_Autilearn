@extends('layouts.app')

@section('content')
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card shadow-lg">
                    <div class="card-header bg-primary text-white text-center">
                        <h3 class="mb-0"><i class="fas fa-user-plus"></i> Add New Student</h3>
                    </div>
                    <div class="card-body">

                        <!-- Success & Error Messages -->
                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- Student Form -->
                        <form method="POST" action="{{ route('educator.store-student') }}">
                            @csrf

                            <div class="row">
                                <!-- Student Full Name -->
                                <div class="col-md-6">
                                    <label for="full_name" class="form-label"><i class="fas fa-user"></i> Student Name</label>
                                    <input type="text" class="form-control" id="full_name" name="full_name" required placeholder="Enter student name">
                                </div>

                                <!-- IC Number -->
                                <div class="col-md-6">
                                    <label for="ic_number" class="form-label"><i class="fas fa-id-card"></i> MyKid/IC Number</label>
                                    <input type="text" class="form-control" id="ic_number" name="ic_number" required placeholder="Enter MyKid/IC number">
                                </div>

                            </div>


                            <div class="row mt-3">
                                <!-- Guardian Name -->
                                <div class="col-md-6">
                                    <label for="parent_name" class="form-label"><i class="fas fa-user-shield"></i> Guardian Name</label>
                                    <input type="text" class="form-control" id="parent_name" name="guardian_name" required placeholder="Enter guardian name">
                                </div>

                                <!-- Guardian Contact -->
                                <div class="col-md-6">
                                    <label for="contact_number" class="form-label"><i class="fas fa-phone"></i> Guardian Contact Number</label>
                                    <input type="text" class="form-control" id="contact_number" name="contact_number" required placeholder="Enter contact number">
                                </div>
                            </div>

                            <div class="row mt-3">
                                <!-- Guardian Email -->
                                <div class="col-md-12">
                                    <label for="email" class="form-label"><i class="fas fa-envelope"></i> Guardian Email</label>
                                    <input type="email" class="form-control" id="email" name="email" required placeholder="Enter email ">
                                </div>
                            </div>

                            <!-- Submit & Reset Buttons -->
                            <div class="text-center mt-4">
                                <button type="submit" class="btn btn-success px-5">
                                    <i class="fas fa-save"></i> Save Student
                                </button>
                                <button type="reset" class="btn btn-warning px-4">
                                    <i class="fas fa-redo"></i> Reset
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
                    // Remove all non-digit characters
                    let value = e.target.value.replace(/\D/g, '');

                    // Limit to 12 digits max
                    value = value.substring(0, 12);

                    // Apply the format: XXXXXX-XX-XXXX
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

