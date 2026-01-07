<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Verify Your Email</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8fafc;
            margin: 0;
            padding: 20px;
        }
        .container {
            max-width: 500px;
            margin: 0 auto;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #10B981, #059669);
            color: white;
            padding: 30px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px;
        }
        .code-box {
            background: #ecfdf5;
            border: 2px dashed #10B981;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }
        .code {
            font-size: 36px;
            font-weight: bold;
            letter-spacing: 8px;
            color: #059669;
        }
        .note {
            color: #64748b;
            font-size: 14px;
            text-align: center;
        }
        .footer {
            background: #f8fafc;
            padding: 20px;
            text-align: center;
            color: #94a3b8;
            font-size: 12px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🍃 Leafé Mart</h1>
        </div>
        <div class="content">
            <h2 style="color: #059669; margin-top: 0;">Verify Your Email</h2>
            <p style="color: #475569;">Thank you for registering! Please use the code below to verify your email address:</p>
            
            <div class="code-box">
                <div class="code">{{ $code }}</div>
            </div>
            
            <p class="note">This code will expire in <strong>15 minutes</strong>.</p>
            <p class="note">Once verified, you'll be able to place orders on Leafé Mart.</p>
        </div>
        <div class="footer">
            &copy; {{ date('Y') }} Leafé Mart. All rights reserved.
        </div>
    </div>
</body>
</html>
