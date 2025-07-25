<!DOCTYPE html>
<html>
<head>
    <title>Password Changed Successfully</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .email-header {
            background-color: #4caf50;
            padding: 20px;
            text-align: center;
        }
        .email-header img {
            max-width: 150px;
        }
        .email-body {
            padding: 20px;
            color: #333333;
        }
        .email-footer {
            background-color: #f4f4f4;
            padding: 10px;
            text-align: center;
            font-size: 12px;
            color: #666666;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            color: #ffffff;
            background-color: #4caf50;
            text-decoration: none;
            border-radius: 4px;
            font-weight: bold;
        }
        .button:hover {
            background-color: #45a049;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <img src="{{ asset('storage/logos/family-cloud-logo.png') }}" alt="Family Cloud Logo">
        </div>
        <div class="email-body">
            <h1>Hello {{ $user->name }},</h1>
            <p>Your password has been changed successfully. If you did not make this change, please contact support immediately.</p>
            <p>You can access your account using the link below:</p>
            <p><a href="{{ url('/') }}" class="button">Access Family Cloud</a></p>
            <p>Thank you,</p>
            <p>The Family Cloud Team</p>
        </div>
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} Family Cloud. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
