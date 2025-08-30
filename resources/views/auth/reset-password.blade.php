<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('app.reset_password_title') }} - {{ __('app.my_clinic_center') }}</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@700;900&family=Nunito:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg modern-navbar">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-heartbeat me-2"></i>
                {{ __('app.my_clinic_center') }}
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav mx-auto">
                    <li class="nav-item">
                        <a class="nav-link" href="/">{{ __('app.home') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">{{ __('app.how_it_works') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">{{ __('app.features') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">{{ __('app.about') }}</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">{{ __('app.contact') }}</a>
                    </li>
                </ul>
                
                <div class="d-flex align-items-center gap-3">
                    <a href="{{ route('login') }}" class="btn modern-btn btn-outline-primary">
                        <i class="fas fa-sign-in-alt me-2"></i>
                        {{ __('app.login') }}
                    </a>
                    <a href="{{ route('signup') }}" class="btn modern-btn btn-success">
                        <i class="fas fa-user-plus me-2"></i>
                        {{ __('app.free_start') }}
                    </a>
                    
                    <!-- Language Selector -->
                    <div class="language-selector" x-data="{ open: false }">
                        <button @click="open = !open" class="btn btn-outline-secondary btn-sm d-flex align-items-center gap-1" style="border-color: #d1d5db;border-radius: 20px;padding: 7px 18px">
                            {{ strtoupper(app()->getLocale()) }} <i class="fas fa-chevron-down"></i>
                        </button>
                        <div x-show="open" @click.away="open = false" class="language-dropdown">
                            <a href="{{ route('language.switch', 'tr') }}" class="language-option" style="color: #4b5563;">
                                🇹🇷 Türkçe
                            </a>
                            <a href="{{ route('language.switch', 'en') }}" class="language-option" style="color: #4b5563;">
                                🇺🇸 English
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="main-content">
        <div class="reset-container">
            <!-- Reset Password Form Section -->
            <div class="reset-form-section">
                <!-- Session Status -->
                @if (session('status'))
                    <div class="alert alert-success">
                        {{ session('status') }}
                    </div>
                @endif

                <!-- Validation Errors -->
                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul style="margin: 0; padding-left: 20px;">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="app-title">{{ __('app.my_clinic_center') }}</div>
                <div class="app-subtitle">{{ __('app.reset_password_title') }}</div>
                <div class="reset-description">
                    {{ __('app.reset_password_description') }}
                </div>

                <form method="POST" action="{{ route('password.update') }}">
                    @csrf

                    <!-- Password Reset Token -->
                    <input type="hidden" name="token" value="{{ $request->route('token') }}">

                    <div class="form-group">
                        <label for="email" class="form-label">{{ __('app.email') }}</label>
                        <input type="email" class="form-input" id="email" name="email" value="{{ old('email', $request->email) }}" required autofocus>
                    </div>

                    <div class="form-group">
                        <label for="password" class="form-label">{{ __('app.new_password') }}</label>
                        <div class="password-input-wrapper">
                            <input type="password" class="form-input" id="password" name="password" required>
                            <button type="button" class="password-toggle" onclick="togglePassword('password')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="password_confirmation" class="form-label">{{ __('app.confirm_password') }}</label>
                        <div class="password-input-wrapper">
                            <input type="password" class="form-input" id="password_confirmation" name="password_confirmation" required>
                            <button type="button" class="password-toggle" onclick="togglePassword('password_confirmation')">
                                <i class="fas fa-eye"></i>
                            </button>
                        </div>
                    </div>

                    <button type="submit" class="reset-button">
                        <i class="fas fa-key me-2"></i>
                        {{ __('app.reset_password') }}
                    </button>

                    <div class="back-to-login">
                        <a href="{{ route('login') }}">
                            <i class="fas fa-arrow-left me-1"></i>
                            {{ __('app.back_to_login') }}
                        </a>
                    </div>
                </form>

                <div class="footer-links">
                    <span>{{ __('app.copyright') }}</span>
                    <span>|</span>
                    <a href="#">{{ __('app.help') }}</a>
                    <span>|</span>
                    <a href="#">{{ __('app.privacy') }}</a>
                    <span>|</span>
                    <a href="#">{{ __('app.terms') }}</a>
                </div>
            </div>

            <!-- Illustration Section -->
            <div class="illustration-section">
                <div class="lock-illustration">
                    <img src="{{ asset('images/maskot.png') }}" alt="My Clinic Center Maskot">
                </div>
            </div>
        </div>
    </div>

    <script>
        function togglePassword(fieldId) {
            const passwordInput = document.getElementById(fieldId);
            const toggleButton = passwordInput.parentElement.querySelector('.password-toggle i');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                toggleButton.className = 'fas fa-eye-slash';
            } else {
                passwordInput.type = 'password';
                toggleButton.className = 'fas fa-eye';
            }
        }
        
        // Form validation
        document.addEventListener('DOMContentLoaded', function() {
            const form = document.querySelector('form');
            const passwordInput = document.getElementById('password');
            const confirmPasswordInput = document.getElementById('password_confirmation');
            
            form.addEventListener('submit', function(e) {
                if (passwordInput.value !== confirmPasswordInput.value) {
                    e.preventDefault();
                    alert('{{ __("app.js_passwords_not_match") }}');
                    return false;
                }
                
                if (passwordInput.value.length < 8) {
                    e.preventDefault();
                    alert('{{ __("app.js_password_min_length") }}');
                    return false;
                }
            });
        });
    </script>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
