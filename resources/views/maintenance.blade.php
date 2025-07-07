<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Site Under Maintenance - {{ $siteName ?? 'Family Cloud' }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        .maintenance-card {
            background: rgba(255, 255, 255, 0.95);
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            padding: 3rem;
            text-align: center;
            max-width: 500px;
            width: 90%;
        }
        .maintenance-icon {
            font-size: 4rem;
            color: #667eea;
            margin-bottom: 1rem;
        }
        .maintenance-title {
            color: #2c3e50;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        .maintenance-message {
            color: #7f8c8d;
            line-height: 1.6;
            margin-bottom: 2rem;
        }
        .pulse {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
    </style>
</head>
<body>
    <div class="maintenance-card">
        <div class="maintenance-icon pulse">
            🔧
        </div>
        <h1 class="maintenance-title">Site Under Maintenance</h1>
        <p class="maintenance-message">
            We're currently performing scheduled maintenance to improve your experience.
            {{ $siteName ?? 'Our site' }} will be back online shortly.
        </p>
        <p class="maintenance-message">
            <strong>Thank you for your patience!</strong>
        </p>
        <div class="mt-4">
            <small class="text-muted">
                If you're an administrator, please
                <a href="{{ route('login') }}" class="text-decoration-none">sign in</a>
                to access the site.
            </small>
        </div>
    </div>
</body>
</html>
