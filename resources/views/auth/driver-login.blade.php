<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Driver Login - QuickDines</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        body { background: #0f172a; font-family: 'Inter', sans-serif; display: flex; align-items: center; justify-content: center; height: 100vh; margin: 0; color: white; }
        .login-box { background: #1e293b; padding: 40px; border-radius: 16px; width: 100%; max-width: 400px; box-shadow: 0 20px 40px rgba(0,0,0,0.4); text-align: center; }
        .logo { font-size: 48px; color: #2563eb; margin-bottom: 20px; }
        h2 { font-weight: 800; margin-bottom: 8px; }
        p { color: #94a3b8; font-size: 14px; margin-bottom: 30px; }
        input { width: 100%; padding: 12px 16px; border-radius: 8px; background: #0f172a; border: 1px solid #334155; color: white; margin-bottom: 16px; font-size: 15px; box-sizing: border-box;}
        input:focus { outline: none; border-color: #2563eb; }
        .btn { width: 100%; padding: 14px; background: #2563eb; color: white; border: none; border-radius: 8px; font-weight: 700; font-size: 16px; cursor: pointer; transition: 0.3s; }
        .btn:hover { background: #1d4ed8; }
        .error { color: #ef4444; font-size: 13px; text-align: left; margin-bottom: 10px; }
    </style>
</head>
<body>
    <div class="login-box">
        <div class="logo"><i class="fa-solid fa-bus"></i></div>
        <h2>Driver Login</h2>
        <p>Login with your Bus Number and Mobile (OTP simulated)</p>

        @if($errors->any())
            <div style="background: rgba(239, 68, 68, 0.1); padding: 10px; border-radius: 8px; margin-bottom: 20px;">
                @foreach($errors->all() as $error)
                    <div class="error"><i class="fa-solid fa-circle-exclamation"></i> {{ $error }}</div>
                @endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('driver.login') }}">
            @csrf
            <input type="text" name="bus_number" placeholder="Bus Number (e.g., MH 12 AB 1234)" required value="{{ old('bus_number') }}">
            <input type="text" name="mobile" placeholder="Mobile Number" required value="{{ old('mobile') }}">
            
            <button type="submit" class="btn">Login & Start Trip</button>
        </form>
    </div>
</body>
</html>
