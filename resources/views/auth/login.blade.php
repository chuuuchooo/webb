@extends('layouts.app')

@section('content')
<div class="container-fluid h-100">
    <div class="row h-100">
        <div class="col-md-6 d-none d-md-flex align-items-center justify-content-center" style="background-color: #008080;">
            <div class="text-center text-white px-5">
                <h1 class="display-4 fw-bold mb-4">Health Information System</h1>
                <p class="lead">Manage and track health records efficiently</p>
            </div>
        </div>
        <div class="col-md-6 d-flex align-items-center justify-content-center" style="background-color: #F5F5F5;">
            <div class="w-100 px-4" style="max-width: 400px;">
                <div class="text-center mb-4">
                    <h2 class="fw-bold" style="color: #008080;">Welcome Back!</h2>
                    <p class="text-muted">Please login to your account</p>
                </div>
                <div class="card shadow-lg border-0">
                    <div class="card-body p-4">
                        <form method="POST" action="{{ route('login') }}">
                            @csrf

                            <div class="mb-3">
                                <label for="email" class="form-label text-muted">{{ __('Email Address') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text border-end-0" style="background-color: #F5F5F5;">
                                        <i class="fas fa-envelope" style="color: #008080;"></i>
                                    </span>
                                    <input id="email" type="email" class="form-control border-start-0 @error('email') is-invalid @enderror" 
                                        name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                                        placeholder="Enter your email">
                                </div>
                                @error('email')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="mb-3">
                                <label for="password" class="form-label text-muted">{{ __('Password') }}</label>
                                <div class="input-group">
                                    <span class="input-group-text border-end-0" style="background-color: #F5F5F5;">
                                        <i class="fas fa-lock" style="color: #008080;"></i>
                                    </span>
                                    <input id="password" type="password" class="form-control border-start-0 @error('password') is-invalid @enderror" 
                                        name="password" required autocomplete="current-password"
                                        placeholder="Enter your password">
                                </div>
                                @error('password')
                                    <span class="invalid-feedback d-block" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                            </div>

                            <div class="d-flex justify-content-between align-items-center mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                                    <label class="form-check-label text-muted" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                                @if (Route::has('password.request'))
                                    <a class="text-decoration-none" href="{{ route('password.request') }}" style="color: #008080;">
                                        {{ __('Forgot Password?') }}
                                    </a>
                                @endif
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn" style="background-color: #008080; color: white;">
                                    {{ __('Login') }}
                                </button>
                            </div>

                            <div class="text-center mt-3">
                                <p class="text-muted mb-0">Don't have an account? 
                                    <a href="{{ route('register') }}" class="text-decoration-none" style="color: #008080;">Register here</a>
                                </p>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
body {
    overflow: hidden;
}
.btn:hover {
    background-color: #006666 !important;
    color: white !important;
}
</style>
@endsection
