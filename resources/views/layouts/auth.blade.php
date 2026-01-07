<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title') - Leafé Mart</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #D9E2FF 0%, #f0f4ff 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .auth-container {
            background: white;
            border-radius: 24px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            width: 100%;
            max-width: 420px;
            padding: 40px;
        }
        .auth-logo {
            text-align: center;
            margin-bottom: 30px;
        }
        .auth-logo .logo {
            width: 70px;
            height: 70px;
            border-radius: 16px;
            margin-bottom: 15px;
            object-fit: cover;
        }
        .auth-logo h1 {
            color: #1E3A5F;
            font-size: 24px;
            font-weight: 700;
        }
        .auth-logo p {
            color: #94A3B8;
            font-size: 14px;
            margin-top: 5px;
        }
        .form-group { margin-bottom: 20px; }
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #1E3A5F;
            font-size: 14px;
        }
        .form-control {
            width: 100%;
            padding: 14px 16px;
            border: 2px solid #E2E8F0;
            border-radius: 12px;
            font-size: 14px;
            transition: all 0.3s ease;
            font-family: inherit;
        }
        .form-control:focus {
            outline: none;
            border-color: #4A90D9;
            box-shadow: 0 0 0 3px rgba(74, 144, 217, 0.1);
        }
        .form-check {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 20px;
        }
        .form-check input[type="checkbox"] {
            width: 18px;
            height: 18px;
            accent-color: #4A90D9;
        }
        .form-check label {
            font-size: 14px;
            color: #475569;
        }
        .btn-primary {
            width: 100%;
            padding: 14px;
            background: linear-gradient(135deg, #4A90D9, #1E3A5F);
            color: white;
            border: none;
            border-radius: 12px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s ease;
        }
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 25px rgba(74, 144, 217, 0.4);
        }
        .auth-footer {
            text-align: center;
            margin-top: 25px;
            color: #94A3B8;
            font-size: 14px;
        }
        .auth-footer a {
            color: #4A90D9;
            text-decoration: none;
            font-weight: 500;
        }
        .auth-footer a:hover { text-decoration: underline; }
        .error-message {
            background: #FEE2E2;
            color: #DC2626;
            padding: 12px 16px;
            border-radius: 10px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .forgot-password {
            text-align: right;
            margin-bottom: 20px;
        }
        .forgot-password a {
            color: #4A90D9;
            font-size: 13px;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="auth-container">
        <div class="auth-logo">
            <img src="{{ asset('images/logo.jpg') }}" alt="Leafé Mart" class="logo">
            <h1>Leafé Mart</h1>
            <p>Mahallah Bilal Online Store</p>
        </div>
        @yield('content')
    </div>
</body>
</html>
