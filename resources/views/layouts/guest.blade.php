<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'QuickDines') }}</title>

    <!-- Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">

    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
        }
        .auth-card {
            border: none;
            border-radius: 1rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        .brand-logo {
            font-weight: 700;
            color: #ff4757;
            font-size: 2rem;
            text-decoration: none;
        }
        .btn-primary {
            background-color: #ff4757;
            border-color: #ff4757;
        }
        .btn-primary:hover {
            background-color: #ff6b81;
            border-color: #ff6b81;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center align-items-center min-vh-100">
            <div class="col-md-6 col-lg-5">
                <div class="text-center mb-4">
                    <a href="/" class="brand-logo"><i class="fa-solid fa-utensils me-2"></i>QuickDines</a>
                </div>
                <div class="card auth-card">
                    <div class="card-body p-4 p-md-5">
                        {{ $slot }}
                    </div>
                </div>
            </div>
        </div>
        
        <footer style="padding: 20px; display: flex; justify-content: space-between; align-items: center; background: white; border-top: 1px solid #e2e8f0; color: #64748b; font-size: 0.9rem; margin-top: auto;">
            <div>
                <div>&copy; 2026 <strong>QuickDines</strong>. All rights reserved.</div>
                <div style="margin-top: 4px;">Designed & Developed by <a href="https://quickdines.com" target="_blank" style="color: #ff4757; text-decoration: none; font-weight: 600;">QuickDines Pvt Ltd</a></div>
            </div>
            <div>Version 1.0.0</div>
        </footer>
    </div>

    <!-- Bootstrap 5 JS Bundle -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
