@extends('layouts.app')

@section('content')
<div class="container-fluid h-100">
    <div class="row h-100">
        <div class="col-md-6 d-none d-md-flex align-items-center justify-content-center" style="background-color: #008080;">
            <div class="text-center text-white px-5">
                <h1 class="display-4 fw-bold mb-4">Health Information System</h1>
                <p class="lead">Join us to manage health records efficiently</p>
            </div>
        </div>
        <div class="col-md-6 d-flex align-items-center justify-content-center" style="background-color: #F5F5F5;">
            <div class="w-100 px-4" style="max-width: 500px;">
                <div class="text-center mb-3">
                    <h2 class="fw-bold" style="color: #008080;">Create Account</h2>
                    <p class="text-muted small">Please fill in your details to register</p>
                </div>

                @if ($errors->any())
                <div class="alert alert-danger py-2 mb-3">
                    <ul class="mb-0 small">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
                @endif

                <div class="card shadow-sm border-0">
                    <div class="card-body p-3">
                        <form method="POST" action="{{ route('register') }}" id="registerForm">
                            @csrf
                            <div class="row g-2">
                                <!-- Name Fields -->
                                <div class="col-md-6">
                                    <label for="first_name" class="form-label small text-muted">{{ __('First Name') }}</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text border-end-0" style="background-color: #F5F5F5;">
                                            <i class="fas fa-user" style="color: #008080;"></i>
                                        </span>
                                        <input id="first_name" type="text" class="form-control border-start-0 @error('first_name') is-invalid @enderror" 
                                            name="first_name" value="{{ old('first_name') }}" required autocomplete="first_name" autofocus
                                            placeholder="Enter first name">
                                    </div>
                                    @error('first_name')
                                        <span class="invalid-feedback d-block small" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="last_name" class="form-label small text-muted">{{ __('Last Name') }}</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text border-end-0" style="background-color: #F5F5F5;">
                                            <i class="fas fa-user" style="color: #008080;"></i>
                                        </span>
                                        <input id="last_name" type="text" class="form-control border-start-0 @error('last_name') is-invalid @enderror" 
                                            name="last_name" value="{{ old('last_name') }}" required autocomplete="last_name"
                                            placeholder="Enter last name">
                                    </div>
                                    @error('last_name')
                                        <span class="invalid-feedback d-block small" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- Sex and Birthdate -->
                                <div class="col-md-6">
                                    <label for="sex" class="form-label small text-muted">{{ __('Sex') }}</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text border-end-0" style="background-color: #F5F5F5;">
                                            <i class="fas fa-venus-mars" style="color: #008080;"></i>
                                        </span>
                                        <select id="sex" class="form-control border-start-0 @error('sex') is-invalid @enderror" 
                                            name="sex" required>
                                            <option value="">Select sex</option>
                                            <option value="Male" {{ old('sex') == 'Male' ? 'selected' : '' }}>Male</option>
                                            <option value="Female" {{ old('sex') == 'Female' ? 'selected' : '' }}>Female</option>
                                        </select>
                                    </div>
                                    @error('sex')
                                        <span class="invalid-feedback d-block small" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="birthdate" class="form-label small text-muted">{{ __('Birthdate') }}</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text border-end-0" style="background-color: #F5F5F5;">
                                            <i class="fas fa-calendar" style="color: #008080;"></i>
                                        </span>
                                        <input id="birthdate" type="date" class="form-control border-start-0 @error('birthdate') is-invalid @enderror" 
                                            name="birthdate" value="{{ old('birthdate') }}" required>
                                    </div>
                                    @error('birthdate')
                                        <span class="invalid-feedback d-block small" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- Contact Info -->
                                <div class="col-md-6">
                                    <label for="contact_number" class="form-label small text-muted">{{ __('Contact Number') }}</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text border-end-0" style="background-color: #F5F5F5;">
                                            <i class="fas fa-phone" style="color: #008080;"></i>
                                        </span>
                                        <input id="contact_number" type="text" class="form-control border-start-0 @error('contact_number') is-invalid @enderror" 
                                            name="contact_number" value="{{ old('contact_number') }}" required
                                            placeholder="Enter contact number">
                                    </div>
                                    @error('contact_number')
                                        <span class="invalid-feedback d-block small" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="email" class="form-label small text-muted">{{ __('Email Address') }}</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text border-end-0" style="background-color: #F5F5F5;">
                                            <i class="fas fa-envelope" style="color: #008080;"></i>
                                        </span>
                                        <input id="email" type="email" class="form-control border-start-0 @error('email') is-invalid @enderror" 
                                            name="email" value="{{ old('email') }}" required autocomplete="email"
                                            placeholder="Enter your email">
                                    </div>
                                    @error('email')
                                        <span class="invalid-feedback d-block small" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- Address -->
                                <div class="col-12">
                                    <label for="address" class="form-label small text-muted">{{ __('Address') }}</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text border-end-0" style="background-color: #F5F5F5;">
                                            <i class="fas fa-home" style="color: #008080;"></i>
                                        </span>
                                        <input id="address" type="text" class="form-control border-start-0 @error('address') is-invalid @enderror" 
                                            name="address" value="{{ old('address') }}" required
                                            placeholder="Enter your address">
                                    </div>
                                    @error('address')
                                        <span class="invalid-feedback d-block small" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <!-- Password Fields -->
                                <div class="col-md-6">
                                    <label for="password" class="form-label small text-muted">{{ __('Password') }}</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text border-end-0" style="background-color: #F5F5F5;">
                                            <i class="fas fa-lock" style="color: #008080;"></i>
                                        </span>
                                        <input id="password" type="password" class="form-control border-start-0 @error('password') is-invalid @enderror" 
                                            name="password" required autocomplete="new-password"
                                            placeholder="Enter password">
                                    </div>
                                    @error('password')
                                        <span class="invalid-feedback d-block small" role="alert">
                                            <strong>{{ $message }}</strong>
                                        </span>
                                    @enderror
                                </div>

                                <div class="col-md-6">
                                    <label for="password-confirm" class="form-label small text-muted">{{ __('Confirm Password') }}</label>
                                    <div class="input-group input-group-sm">
                                        <span class="input-group-text border-end-0" style="background-color: #F5F5F5;">
                                            <i class="fas fa-lock" style="color: #008080;"></i>
                                        </span>
                                        <input id="password-confirm" type="password" class="form-control border-start-0" 
                                            name="password_confirmation" required autocomplete="new-password"
                                            placeholder="Confirm password">
                                    </div>
                                </div>

                                <!-- Submit Button -->
                                <div class="col-12 mt-2">
                                    <button type="submit" class="btn btn-sm w-100" style="background-color: #008080; color: white;">
                                        {{ __('Register') }}
                                    </button>
                                </div>

                                <!-- Login Link -->
                                <div class="col-12 text-center mt-2">
                                    <p class="text-muted small mb-0">Already have an account? 
                                        <a href="{{ route('login') }}" style="color: #008080;">Login Here</a>
                                    </p>
                                </div>
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
.form-control:focus, .form-select:focus {
    border-color: #008080;
    box-shadow: 0 0 0 0.2rem rgba(0, 128, 128, 0.25);
}
</style>

<script>
document.getElementById('registerForm').addEventListener('submit', function(e) {
    console.log('Form submitted');
    const formData = new FormData(this);
    for (let pair of formData.entries()) {
        console.log(pair[0] + ': ' + pair[1]);
    }
});
</script>
@endsection
