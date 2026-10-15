<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - QuickDines</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }
        body {
            background-image: url('{{ asset("images/formbg.png") }}');
            background-size: cover;
            background-position: center;
            background-attachment: fixed;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .overlay {
            position: absolute;
            inset: 0;
            background: rgba(0, 0, 0, 0.4);
            z-index: 1;
        }
        .auth-card {
            background: #fff;
            width: 100%;
            max-width: 450px;
            border-radius: 16px;
            overflow: hidden;
            position: relative;
            z-index: 2;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
        }
        .auth-header {
            background: #2563eb;
            padding: 20px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .auth-brand {
            color: #fff;
            font-size: 20px;
            font-weight: 800;
            display: flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
        }
        .auth-type {
            color: rgba(255, 255, 255, 0.9);
            font-size: 14px;
            font-weight: 500;
        }
        .auth-body {
            padding: 40px 30px;
        }
        .auth-title {
            text-align: center;
            font-size: 24px;
            font-weight: 700;
            color: #1e293b;
            margin-bottom: 8px;
        }
        .auth-subtitle {
            text-align: center;
            font-size: 14px;
            color: #64748b;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 16px;
        }
        .form-input {
            width: 100%;
            padding: 14px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            color: #1e293b;
            outline: none;
            transition: border-color 0.3s;
        }
        .form-input:focus {
            border-color: #2563eb;
        }
        .checkbox-group {
            display: flex;
            align-items: center;
            gap: 8px;
            margin-bottom: 20px;
        }
        .checkbox-group input[type="checkbox"] {
            width: 16px;
            height: 16px;
            accent-color: #2563eb;
        }
        .checkbox-group label {
            font-size: 14px;
            color: #475569;
            cursor: pointer;
        }
        .btn-submit {
            width: 100%;
            padding: 14px;
            background: #2563eb;
            color: #fff;
            border: none;
            border-radius: 8px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: background 0.3s;
            margin-top: 10px;
        }
        .btn-submit:hover {
            background: #1d4ed8;
        }
        .auth-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #64748b;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }
        .auth-footer a {
            color: #2563eb;
            font-weight: 600;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="overlay"></div>

    <div class="auth-card">
        <div class="auth-header">
            <a href="{{ route('home') }}" class="auth-brand">
                <i class="fa-solid fa-bell-concierge"></i> QuickDines
            </a>
            <span class="auth-type">Login</span>
        </div>

        <div class="auth-body">
            <h1 class="auth-title">Welcome Back</h1>
            <p class="auth-subtitle">Sign in to your account</p>

            @if(session('status'))
                <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #16a34a; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 13px;">
                    {{ session('status') }}
                </div>
            @endif

            @if(session('success'))
                <div style="background: #f0fdf4; border: 1px solid #bbf7d0; color: #16a34a; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 13px;">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div style="background: #fef2f2; border: 1px solid #fecaca; color: #ef4444; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 13px;">
                    {{ session('error') }}
                </div>
            @endif

            @if($errors->any())
                <div style="background: #fef2f2; border: 1px solid #fecaca; color: #ef4444; padding: 15px; border-radius: 8px; margin-bottom: 20px; font-size: 13px;">
                    <ul style="margin: 0; padding-left: 20px;">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('login') }}">
                @csrf
                
                <div class="form-group">
                    <input type="text" name="mobile" class="form-input" placeholder="Mobile Number" value="{{ old('mobile') }}" required autofocus>
                </div>
                <div class="form-group" style="position: relative;">
                    <input type="password" name="password" class="form-input" placeholder="Password" required style="padding-right: 40px;">
                    <i class="fa-regular fa-eye" style="position: absolute; right: 15px; top: 50%; transform: translateY(-50%); cursor: pointer; color: #64748b;" onclick="togglePassword(this)"></i>
                </div>
                
                <div class="checkbox-group">
                    <input type="checkbox" name="remember" id="remember">
                    <label for="remember">Remember me</label>
                </div>

                <button type="submit" class="btn-submit">Sign In</button>

                <div class="auth-footer">
                    @if (Route::has('password.request'))
                        <a href="{{ route('password.request') }}" style="color: #64748b; font-weight: 500;">Forgot your password?</a>
                    @endif
                    <div>Don't have an account? <a href="{{ route('register') }}">Register here</a></div>
                </div>
            </form>
        </div>
    </div>

    <script>
        function togglePassword(icon) {
            var input = icon.previousElementSibling;
            if (input.type === "password") {
                input.type = "text";
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = "password";
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }
    </script>
</body>
</html>
