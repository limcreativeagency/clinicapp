<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('app.signup') }} - {{ __('app.my_clinic_center') }}</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@700;900&family=Nunito:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="{{ asset('css/auth.css') }}">
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        body {
            background: linear-gradient(135deg, #f6fafd 0%, #e6f3ff 100%);
        }
        .signup-card {
            background: #fff;
            border-radius: 1.5rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            padding: 2.5rem;
            margin: 2rem auto;
            max-width: 800px;
        }
        .form-control {
            border-radius: 12px;
            border: 2px solid #e5e7eb;
            padding: 0.75rem 1rem;
            font-size: 1rem;
            transition: all 0.3s ease;
        }
        .form-control:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 0.2rem rgba(37, 99, 235, 0.25);
        }
        .section-title {
            color: #1f2937;
            font-weight: 700;
            font-size: 1.25rem;
            margin-bottom: 1.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 2px solid #e5e7eb;
        }
        .btn-submit {
            background: linear-gradient(135deg, #10b981 0%, #2563eb 100%);
            border: none;
            border-radius: 50px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
        }
        .btn-submit:hover {
            background: linear-gradient(135deg, #0e9f6e 0%, #1d4ed8 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }
    </style>
</head>
<body>
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg modern-navbar">
        <div class="container">
            <a class="navbar-brand" href="{{ url('/') }}">
                <i class="fas fa-heartbeat me-2"></i>
                {{ __('app.my_clinic_center') }}
            </a>
            
            <div class="d-flex align-items-center gap-3">
                <a href="{{ route('login') }}" class="btn modern-btn btn-outline-primary">
                    <i class="fas fa-sign-in-alt me-2"></i>
                    {{ __('app.login') }}
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
    </nav>

    <!-- Signup Form -->
    <div class="container">
        <div class="signup-card">
            <!-- Session Status -->
            @if (session('status'))
                <div class="alert alert-success mb-4">
                    {{ session('status') }}
                </div>
            @endif

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="alert alert-danger mb-4">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('signup.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="text-center mb-5">
                    <h1 class="h2 fw-bold text-dark mb-2">{{ __('app.hospital_manager_signup') }}</h1>
                    <p class="text-muted">{{ __('app.hospital_manager_signup_desc') }}</p>
                </div>

                <!-- Admin Bilgileri -->
                <div class="mb-5">
                    <h3 class="section-title">
                        <i class="fas fa-user-shield me-2"></i>
                        {{ __('app.admin_info') }}
                    </h3>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="admin_name" class="form-label">{{ __('app.full_name') }}</label>
                            <input type="text" class="form-control" id="admin_name" name="admin_name" value="{{ old('admin_name') }}" required autofocus>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="admin_email" class="form-label">{{ __('app.email') }}</label>
                            <input type="email" class="form-control" id="admin_email" name="admin_email" value="{{ old('admin_email') }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="password" class="form-label">{{ __('app.password') }}</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="password_confirmation" class="form-label">{{ __('app.confirm_password') }}</label>
                            <input type="password" class="form-control" id="password_confirmation" name="password_confirmation" required>
                        </div>
                    </div>
                </div>

                <!-- Hastane Bilgileri -->
                <div class="mb-5">
                    <h3 class="section-title">
                        <i class="fas fa-hospital me-2"></i>
                        {{ __('app.hospital_info') }}
                    </h3>
                    
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="hospital_name" class="form-label">{{ __('app.hospital_name') }}</label>
                            <input type="text" class="form-control" id="hospital_name" name="hospital_name" value="{{ old('hospital_name') }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="hospital_email" class="form-label">{{ __('app.hospital_email') }}</label>
                            <input type="email" class="form-control" id="hospital_email" name="hospital_email" value="{{ old('hospital_email') }}" required>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label for="phone_country_code" class="form-label">{{ __('app.country_code') }}</label>
                            <input type="text" class="form-control" id="phone_country_code" name="phone_country_code" value="{{ old('phone_country_code', '+90') }}" placeholder="+90">
                        </div>

                        <div class="col-md-9 mb-3">
                            <label for="phone" class="form-label">{{ __('app.phone') }}</label>
                            <input type="text" class="form-control" id="phone" name="phone" value="{{ old('phone') }}" placeholder="5XX XXX XX XX">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="city" class="form-label">{{ __('app.city') }}</label>
                            <input type="text" class="form-control" id="city" name="city" value="{{ old('city') }}" required>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="country" class="form-label">{{ __('app.country') }}</label>
                            <input type="text" class="form-control" id="country" name="country" value="{{ old('country', 'Türkiye') }}" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">{{ __('app.address') }}</label>
                        <textarea class="form-control" id="address" name="address" rows="3" required>{{ old('address') }}</textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="website" class="form-label">{{ __('app.website') }}</label>
                            <input type="url" class="form-control" id="website" name="website" value="{{ old('website') }}" placeholder="https://www.example.com">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="logo" class="form-label">{{ __('app.logo') }}</label>
                            <input type="file" class="form-control" id="logo" name="logo" accept="image/*">
                            <small class="text-muted">{{ __('app.logo_help') }}</small>
                        </div>
                    </div>

                                            <div class="mb-3">
                            <label for="description" class="form-label">{{ __('app.hospital_description') }}</label>
                            <textarea class="form-control" id="description" name="description" rows="3">{{ old('description') }}</textarea>
                        </div>
                </div>

                <div class="d-flex justify-content-between align-items-center">
                    <a href="{{ route('login') }}" class="text-decoration-none">
                        <i class="fas fa-arrow-left me-2"></i>
                        {{ __('app.already_have_account') }}
                    </a>

                    <button type="submit" class="btn btn-submit text-white">
                        <i class="fas fa-hospital-user me-2"></i>
                        {{ __('app.create_hospital_account') }}
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
