<!DOCTYPE html>
<html>
<head>
    <title>New User Registration - Family Cloud</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }
        .header {
            background-color: #f8f9fa;
            padding: 10px;
            text-align: center;
        }
        .content {
            margin: 20px;
        }
        .footer {
            margin-top: 20px;
            font-size: 0.9em;
            color: #666;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Family Cloud</h1>
    </div>
    <div class="content">
        <p>Hello,</p>
        <p>A new user registration has been submitted on Family Cloud.</p>
        <p><strong>User Details:</strong></p>
        <ul>
            <li><strong>Name:</strong> {{ $user->name }}</li>
            <li><strong>Email:</strong> {{ $user->email }}</li>
            <li><strong>Registration Date:</strong> {{ $user->created_at->format('Y-m-d H:i:s') }}</li>
            <li><strong>Status:</strong> Pending Approval</li>
        </ul>
        <p>The request has been sent to administrators for review.</p>
        <p>This is an automated notification from the Family Cloud system.</p>
    </div>
    <div class="footer">
        <p>&copy; {{ date('Y') }} Family Cloud. All rights reserved.</p>
    </div>
</body>
</html>
