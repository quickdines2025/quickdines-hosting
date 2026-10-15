<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - QuickDines</title>

    <style>
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: Arial, sans-serif;
            background: #f8fafc;
        }

        .login-box {
            width: 400px;
            max-width: 92%;
            background: white;
            padding: 35px;
            border-radius: 16px;
            box-shadow: 0 10px 35px rgba(0,0,0,0.12);
        }

        h1 {
            text-align: center;
            margin-bottom: 8px;
            color: #111827;
        }

        .subtitle {
            text-align: center;
            color: #64748b;
            margin-bottom: 28px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: 600;
            color: #334155;
        }

        input {
            width: 100%;
            padding: 13px;
            margin-bottom: 18px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 15px;
        }

        input:focus {
            outline: none;
            border-color: #f97316;
        }

        button {
            width: 100%;
            padding: 14px;
            border: none;
            border-radius: 8px;
            background: #f97316;
            color: white;
            font-size: 16px;
            font-weight: 700;
            cursor: pointer;
        }

        button:hover {
            background: #ea580c;
        }

        .error {
            background: #fee2e2;
            color: #b91c1c;
            padding: 10px;
            border-radius: 8px;
            margin-bottom: 18px;
            font-size: 14px;
        }

        .brand {
            text-align: center;
            color: #f97316;
            font-size: 14px;
            font-weight: 700;
            margin-bottom: 20px;
        }
    </style>
</head>

<body>

<div class="login-box">

    <div class="brand">QUICKDINES ADMIN</div>

    <h1>Admin Login</h1>
    <p class="subtitle">Sign in to manage QuickDines</p>

    @if ($errors->any())
        <div class="error">
            {{ $errors->first() }}
        </div>
    @endif

    <form method="POST" action="{{ url('/admin/login') }}">
        @csrf

        <label>Email</label>
        <input
            type="email"
            name="email"
            value="{{ old('email') }}"
            placeholder="Admin email"
            required
        >

        <label>Password</label>
        <input
            type="password"
            name="password"
            placeholder="Admin password"
            required
        >

        <button type="submit">Login to Admin</button>
    </form>

</div>

</body>
</html>
