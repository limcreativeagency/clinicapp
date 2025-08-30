<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Super Admin Dashboard - My Clinic Center</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-12">
                <h1 class="text-center mb-4">Super Admin Dashboard</h1>
                <div class="alert alert-info">
                    <h4>Hoş Geldiniz, {{ auth()->user()->name }}!</h4>
                    <p>Bu Super Admin dashboard'udur. Sistem yönetimi buradan yapılacak.</p>
                </div>
                <div class="text-center">
                    <a href="{{ route('dashboard') }}" class="btn btn-primary">Ana Dashboard</a>
                    <form method="POST" action="{{ route('logout') }}" class="d-inline">
                        @csrf
                        <button type="submit" class="btn btn-outline-danger">Çıkış Yap</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>

