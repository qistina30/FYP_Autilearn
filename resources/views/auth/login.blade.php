@extends('layouts.app')

@section('content')
  {{--  <style>
        html, body {
            height: 100%;
            overflow: hidden;
        }
    </style>--}}
    <div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
        <div class="card shadow-lg p-4" style="width: 100%; max-width: 500px; border-radius: 15px;">

            <!-- Logo -->
            <div class="text-center mb-4">
                <img src="{{ asset('images/logo.png') }}" alt="AutiLearn Logo" style="height: 80px;">
            </div>

            <!-- Status Message -->
            @if (session('status'))
                <div class="alert alert-success text-center">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Form -->
            <div class="card-body">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- User ID -->
                    <div class="mb-3">
                        <label for="user_id" class="form-label fw-semibold">User ID</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-id-badge"></i></span>
                            <input id="user_id" type="text" class="form-control @error('user_id') is-invalid @enderror"
                                   name="user_id" value="{{ old('user_id') }}" required autofocus
                                   placeholder="Enter your User ID">
                        </div>
                        @error('user_id')
                        <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="mb-3">
                        <label for="password" class="form-label fw-semibold">Password</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                            <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                                   name="password" required placeholder="Enter your password">
                        </div>
                        @error('password')
                        <span class="invalid-feedback d-block"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <!-- Remember & Forgot -->
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember"
                                {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                Remember Me
                            </label>
                        </div>
                        @if (Route::has('password.request'))
                            <a href="{{ route('password.request') }}" class="text-decoration-none text-primary small">
                                Forgot Password?
                            </a>
                        @endif
                    </div>

                    <!-- Submit -->
                    <div class="d-grid">
                        <button type="submit" class="btn btn-primary fw-semibold">
                            <i class="fas fa-sign-in-alt me-1"></i> Login
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
