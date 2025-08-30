<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ __('app.welcome') }} - My Clinic Center</title>

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
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                                 <ul class="navbar-nav mx-auto">
                     <li class="nav-item">
                         <a class="nav-link active" href="#">{{ __('app.home') }}</a>
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

        <!-- Hero Section -->
    <div class="container">
        <div class="row align-items-center justify-content-center" style="min-height: 90vh;">
            <div class="col-12">
                <div class="hero-card">
                    <div class="row align-items-center">
                        <div class="col-lg-6 hero-content-left">
                            <div class="ai-badge">
                                <i class="fas fa-robot"></i>
                                {{ __('app.ai_powered') }}
                            </div>
                            
                            <h1 class="hero-heading">{{ __('app.smart_patient_tracking') }}</h1>
                            
                            <p class="hero-description">{!! __('app.description') !!}</p>
                            
                                                         <div class="hero-buttons">
                                 <a href="{{ route('signup') }}" class="btn hero-btn btn-success">
                                     <i class="fas fa-user-plus me-2"></i>
                                     {{ __('app.free_start') }}
                                 </a>
                                 <a href="#" class="btn hero-btn btn-outline-primary">
                                     <i class="fas fa-info-circle me-2"></i>
                                     {{ __('app.how_it_works') }}
                                 </a>
                             </div>
                            
                            <p class="trial-text">{{ __('app.free_trial') }}</p>
                            
                            <div class="stats">
                                <div class="stat-item">
                                    <div class="stat-avatars">
                                        <div class="stat-avatar">
                                            <img src="https://randomuser.me/api/portraits/men/32.jpg" alt="User 1">
                                        </div>
                                        <div class="stat-avatar">
                                            <img src="https://randomuser.me/api/portraits/women/44.jpg" alt="User 2">
                                        </div>
                                        <div class="stat-avatar">
                                            <img src="https://randomuser.me/api/portraits/men/65.jpg" alt="User 3">
                                        </div>
                                    </div>
                                    <div class="stat-text">
                                        <div class="stat-number">10K+</div>
                                        <div class="stat-label">{{ __('app.active_users') }}</div>
                                    </div>
                                </div>
                                
                                <div class="stat-item">
                                    <div class="stat-rating">
                                        <div class="stat-number">4.9/5</div>
                                        <div class="rating-star">
                                            <i class="fas fa-star"></i>
                                        </div>
                                    </div>
                                    <div class="stat-label">{{ __('app.user_rating') }}</div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-lg-6 text-center">
                            <!-- Robot Illustration -->
                            <div class="robot-illustration">
                                <img src="{{ asset('images/maskot.png') }}" alt="MyClinicCenter Robot" class="img-fluid">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
