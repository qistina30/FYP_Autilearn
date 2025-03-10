@extends('layouts.app')

@section('content')
    <div class="container d-flex justify-content-center">
        <div class="card shadow-lg p-4 w-50">
            <h2 class="text-center mb-4"><i class="fas fa-user-plus"></i> Register as Educator</h2>

            <form method="POST" action="{{ route('register') }}">
                @csrf

                <!-- Name -->
                <div class="form-group mb-3">
                    <label for="name" class="form-label"><i class="fas fa-user"></i> Full Name</label>
                    <input id="name" type="text" class="form-control @error('name') is-invalid @enderror"
                           name="name" value="{{ old('name') }}" required autofocus placeholder="Enter your full name">
                    @error('name')
                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <!-- Email -->
                <div class="form-group mb-3">
                    <label for="email" class="form-label"><i class="fas fa-envelope"></i> Email Address</label>
                    <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                           name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Enter your email">
                    @error('email')
                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <!-- Password -->
                <div class="form-group mb-3">
                    <label for="password" class="form-label"><i class="fas fa-lock"></i> Password</label>
                    <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                           name="password" required autocomplete="new-password" placeholder="Enter a strong password">
                    @error('password')
                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                    @enderror
                </div>

                <!-- Confirm Password -->
                <div class="form-group mb-3">
                    <label for="password-confirm" class="form-label"><i class="fas fa-lock"></i> Confirm Password</label>
                    <input id="password-confirm" type="password" class="form-control"
                           name="password_confirmation" required autocomplete="new-password"
                           placeholder="Re-enter your password">
                </div>

                <!-- Submit & Reset Buttons -->
                <div class="text-center mt-4">
                    <button type="submit" class="btn btn-primary px-4">
                        <i class="fas fa-user-check"></i> Register
                    </button>
                    <button type="reset" class="btn btn-warning px-4">
                        <i class="fas fa-redo"></i> Reset
                    </button>
                </div>
            </form>

            <!-- Already have an account? -->
            <div class="text-center mt-3">
                <p>Already have an account?
                    <a href="{{ route('login') }}" class="text-primary fw-bold">Login here</a>
                </p>
            </div>
        </div>
    </div>
@endsection
