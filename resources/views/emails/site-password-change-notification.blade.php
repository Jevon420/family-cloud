<!DOCTYPE html>
<html>
<head>
    <title>User Password Change Notification</title>
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
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <img src="{{ asset('storage/logos/family-cloud-logo.png') }}" alt="Family Cloud Logo">
        </div>
        <div class="email-body">
            <h1>Notification: User Password Changed</h1>
            <p>A user has successfully changed their password on Family Cloud.</p>
            <p><strong>User Details:</strong></p>
            <ul>
                <li>Name: {{ $user->name }}</li>
                <li>Email: {{ $user->email }}</li>
            </ul>
            <p>If you suspect any unauthorized activity, please investigate immediately.</p>
            <p>Thank you,</p>
            <p>The Family Cloud Team</p>
        </div>
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} Family Cloud. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
