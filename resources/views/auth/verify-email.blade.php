<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>E-posta Doğrulama - My Clinic Center</title>

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@700;900&family=Nunito:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    
    <!-- Alpine.js -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    
    <style>
        body {
            font-family: 'Nunito', sans-serif;
            background: linear-gradient(135deg, #f6fafd 0%, #e6f3ff 100%);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        .modern-navbar {
            background-color: #fff;
            border-bottom-left-radius: 1.5rem;
            border-bottom-right-radius: 1.5rem;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.05);
        }
        .modern-navbar .navbar-brand {
            color: #2563eb;
            font-family: 'Fredoka', sans-serif;
            font-weight: 900;
            letter-spacing: 0.01em;
            font-size: 1.5rem;
        }
        .modern-navbar .nav-link {
            color: #4b5563;
            font-weight: 600;
            padding: 0.5rem 1rem;
            transition: color 0.3s ease;
        }
        .modern-navbar .nav-link:hover {
            color: #1f2937;
        }
        .modern-btn {
            border-radius: 50px;
            font-size: 1rem;
            padding: 0.4rem 1rem;
            transition: all 0.3s ease;
            font-weight: 600;
        }
        .modern-btn.btn-outline-primary {
            color: #2563eb;
            border: 2px solid #2563eb;
            background-color: transparent;
        }
        .modern-btn.btn-outline-primary:hover {
            background-color: #2563eb;
            color: #fff;
        }
        .modern-btn.btn-success {
            background: linear-gradient(135deg, #10b981 0%, #2563eb 100%);
            border: none;
            color: #fff;
            font-weight: 600;
        }
        .modern-btn.btn-success:hover {
            background: linear-gradient(135deg, #0e9f6e 0%, #1d4ed8 100%);
            color: #fff;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
        }
        .verify-card {
            background: #fff;
            border-radius: 1.5rem;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            padding: 2.5rem;
            margin: 2rem auto;
            max-width: 600px;
            text-align: center;
        }
        .verify-icon {
            width: 80px;
            height: 80px;
            background: linear-gradient(135deg, #10b981 0%, #2563eb 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 2rem;
            color: white;
            font-size: 2rem;
        }
        .btn-submit {
            background: linear-gradient(135deg, #10b981 0%, #2563eb 100%);
            border: none;
            border-radius: 50px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            font-size: 1.1rem;
            transition: all 0.3s ease;
            color: white;
        }
        .btn-submit:hover {
            background: linear-gradient(135deg, #0e9f6e 0%, #1d4ed8 100%);
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);
            color: white;
        }
        .btn-outline {
            background: transparent;
            border: 2px solid #e5e7eb;
            border-radius: 50px;
            padding: 0.75rem 2rem;
            font-weight: 600;
            font-size: 1rem;
            transition: all 0.3s ease;
            color: #6b7280;
            text-decoration: none;
        }
        .btn-outline:hover {
            border-color: #d1d5db;
            background-color: #f9fafb;
            color: #374151;
            text-decoration: none;
        }
        .alert {
            border-radius: 12px;
            border: none;
        }
        .alert-success {
            background-color: #f0fdf4;
            color: #16a34a;
            border: 1px solid #bbf7d0;
        }
        .language-selector {
            position: relative;
        }
        .language-dropdown {
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 0.5rem 0;
            min-width: 150px;
            z-index: 1000;
        }
        .language-option {
            display: block;
            padding: 0.5rem 1rem;
            text-decoration: none;
            transition: background-color 0.2s;
        }
        .language-option:hover {
            background-color: #f3f4f6;
            text-decoration: none;
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

    <!-- Verify Email Form -->
    <div class="container">
        <div class="verify-card">
            <div class="verify-icon">
                <i class="fas fa-envelope"></i>
            </div>

            <h1 class="h3 fw-bold text-dark mb-3">E-posta Adresinizi Doğrulayın</h1>
            
            <p class="text-muted mb-4">
                Kayıt olduğunuz için teşekkürler! Başlamadan önce, size gönderdiğimiz e-postadaki bağlantıya tıklayarak e-posta adresinizi doğrulayabilir misiniz? E-posta almadıysanız, size başka bir tane göndermekten memnuniyet duyarız.
            </p>

            @if (session('status') == 'verification-link-sent')
                <div class="alert alert-success mb-4">
                    <i class="fas fa-check-circle me-2"></i>
                    Kayıt sırasında verdiğiniz e-posta adresine yeni bir doğrulama bağlantısı gönderildi.
                </div>
            @endif

            <div class="d-flex flex-column flex-md-row gap-3 justify-content-center">
                <form method="POST" action="{{ route('verification.send') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-submit">
                        <i class="fas fa-paper-plane me-2"></i>
                        Doğrulama E-postasını Tekrar Gönder
                    </button>
                </form>

                <form method="POST" action="{{ route('logout') }}" class="d-inline">
                    @csrf
                    <button type="submit" class="btn btn-outline">
                        <i class="fas fa-sign-out-alt me-2"></i>
                        Çıkış Yap
                    </button>
                </form>
            </div>

            <div class="mt-4 pt-4 border-top">
                <p class="text-muted small mb-0">
                    <i class="fas fa-info-circle me-1"></i>
                    E-posta doğrulaması tamamlandıktan sonra 14 günlük ücretsiz deneme süreniz başlayacaktır.
                </p>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
