@extends('layouts.app')
@section('body-class', 'login-page')
@section('content')
<div class="container1 d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="login-card">
        <h2 class="text-center mb-4">{{ __('en_labels.login') }}</h2>

        <form method="POST" action="{{ route('login') }}">
            @csrf
            <!-- Email field -->
            <div class="form-group mb-3">
                <label for="email" class="form-label">{{ __('en_labels.email_address') }}</label>
                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email"
                    value="{{ old('email') }}" required autocomplete="email" autofocus>
                @error('email')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <!-- Password field -->
            <div class="form-group mb-3">
                <label for="password" class="form-label">{{ __('en_labels.password') }}</label>
                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                    name="password" required autocomplete="current-password">
                @error('password')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                @enderror
            </div>

            <!-- Remember me checkbox -->
            <div class="form-group form-check mb-3">
                <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                <label class="form-check-label" for="remember">{{ __('en_labels.remember_me') }}</label>
            </div>

            <!-- Submit button -->
            <div class="d-grid">
                <button type="submit" class="btn btn-gradient">
                    {{ __('en_labels.login') }}
                </button>
            </div>

            @if (Route::has('password.request'))
                <div class="text-center mt-3">
                    <a class="small-link" href="{{ route('password.request') }}">{{ __('en_labels.forgot_password') }}</a>
                </div>
            @endif
        </form>
    </div>
</div>


<style>
    body {
        background: linear-gradient(135deg, #6a11cb, #2575fc);
        font-family: Arial, sans-serif;
    }
</style>
@endsection