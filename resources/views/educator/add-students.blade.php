@extends('layouts.app')

@section('content')
    <div class="container">
        <h1>Add New Student</h1>

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">
                {{ session('error') }}
            </div>
        @endif

        <form method="POST" action="{{ route('educator.store-student') }}">
            @csrf

            <!-- Student Full Name -->
            <div class="form-group">
                <label for="full_name">Student Name</label>
                <input type="text" class="form-control" id="full_name" name="full_name" required>
            </div>

            <!-- IC Number -->
            <div class="form-group">
                <label for="ic_number">IC Number</label>
                <input type="text" class="form-control" id="ic_number" name="ic_number" required>
            </div>

            <!-- Age -->
            <div class="form-group">
                <label for="age">Age</label>
                <input type="number" class="form-control" id="age" name="age" required>
            </div>

            <!-- Address -->
            <div class="form-group">
                <label for="address">Address</label>
                <textarea class="form-control" id="address" name="address" required></textarea>
            </div>

            <!-- Parent Name -->
            <div class="form-group">
                <label for="parent_name">Guardian Name</label>
                <input type="text" class="form-control" id="parent_name" name="guardian_name" required>
            </div>

            <!-- Parent Contact Number -->
            <div class="form-group">
                <label for="contact_number">Guardian Contact Number</label>
                <input type="text" class="form-control" id="contact_number" name="contact_number" required>
            </div>

            <!-- Parent Email -->
            <div class="form-group">
                <label for="email">Guardian Email </label>
                <input type="email" class="form-control" id="email" name="email">
            </div>


            <button type="submit" class="btn btn-primary">Save Student</button>
        </form>
    </div>
@endsection
