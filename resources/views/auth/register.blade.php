@extends('layouts.app')

@section('content')
<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="login-card">
    <h2 class="text-center mb-4">{{ __('Register') }}</h2>
                    <form method="POST" action="{{ route('register') }}">
                        @csrf

                        <div class="form-group mb-3">
                            <label for="name" class="form-label">{{ __('Name') }}</label>
                                <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>

                                @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="email" class="form-label">{{ __('Email Address') }}</label>
                                <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">

                                @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="password" class="form-label">{{ __('Password') }}</label>

                                <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">

                                @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                @enderror
                        </div>

                        <div class="form-group mb-3">
                            <label for="password-confirm" class="form-label">{{ __('Confirm Password') }}</label>
                                <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password">
                        </div>

                        <div class="d-grid">
                                <button type="submit" class="btn btn-gradient">
                                    {{ __('Register') }}
                                </button>
                        </div>
                    </form>
            
    </div>
</div>
<!-- Custom CSS for the login page -->
<style>
    body {
        background: linear-gradient(135deg, #6a11cb, #2575fc);
        font-family: Arial, sans-serif;
    }
    .container {
            display: flex;
            justify-content: center;
            align-items: center;
            
        }
    .login-card {
        background: #fff;
        border-radius: 12px;
        padding: 2rem;
        max-width: 400px;
        width: 100%;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
    }

    .login-card h2 {
        font-size: 1.5rem;
        color: #333;
    }

    .form-label {
        font-weight: bold;
        color: #555;
    }

    .form-control {
        border: 1px solid #ddd;
        padding: 0.75rem;
        border-radius: 8px;
    }

    .form-control:focus {
        border-color: #6a11cb;
        box-shadow: 0 0 0 0.2rem rgba(106, 17, 203, 0.25);
    }

    .btn-gradient {
        background: linear-gradient(90deg, #6a11cb, #2575fc);
        color: #fff;
        border: none;
        padding: 0.75rem;
        font-size: 1rem;
        border-radius: 8px;
        cursor: pointer;
        transition: background 0.3s ease;
    }

    .btn-gradient:hover {
        background: linear-gradient(90deg, #5c0daf, #1f63d1);
    }

    .small-link {
        color: #6a11cb;
        text-decoration: none;
        font-size: 0.9rem;
    }

    .small-link:hover {
        text-decoration: underline;
    }
</style>
@endsection