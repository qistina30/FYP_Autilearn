@extends('layouts.app')

@section('content')
    <div class="container d-flex justify-content-center">
        <div class="card shadow-lg p-4 w-50">
            <h2 class="text-center mb-4"><i class="fas fa-sign-in-alt"></i> Login </h2>

            @if (session('status'))
                <div class="alert alert-success text-center">
                    {{ session('status') }}
                </div>
            @endif

            <div class="card-body">
                <form method="POST" action="{{ route('login') }}">
                    @csrf

                    <!-- User ID -->
                    <div class="form-group mb-3">
                        <label for="user_id" class="form-label"><i class="fas fa-user"></i> User ID</label>
                        <input id="user_id" type="text" class="form-control @error('user_id') is-invalid @enderror"
                               name="user_id" value="{{ old('user_id') }}" required autofocus placeholder="Enter your User ID">
                        @error('user_id')
                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <!-- Password -->
                    <div class="form-group mb-3">
                        <label for="password" class="form-label"><i class="fas fa-lock"></i> Password</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                               name="password" required autocomplete="current-password" placeholder="Enter your password">
                        @error('password')
                        <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                        @enderror
                    </div>

                    <!-- Remember Me & Forgot Password -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">{{ __('Remember Me') }}</label>
                        </div>
                        @if (Route::has('password.request'))
                            <a class="text-decoration-none" href="{{ route('password.request') }}">
                                {{ __('Forgot Password?') }}
                            </a>
                        @endif
                    </div>

                    <!-- Submit Button -->
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary px-4">
                            <i class="fas fa-sign-in-alt"></i> Login
                        </button>
                    </div>
                </form>

                <!-- Don't have an account? -->
                {{--<div class="text-center mt-3">
                    <p>Don't have an account?
                        <a href="{{ route('register') }}" class="text-primary fw-bold">Sign up here</a>
                    </p>
                </div>--}}
            </div>
        </div>
    </div>
@endsection
