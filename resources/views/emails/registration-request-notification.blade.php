<!DOCTYPE html>
<html>
<head>
    <title>New User Registration Request - Family Cloud</title>
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
            background-color: #f8f9fa;
            padding: 10px;
            text-align: center;
            font-size: 0.9em;
        }
        .button {
            display: inline-block;
            padding: 10px 20px;
            margin: 10px 0;
            color: #fff;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Family Cloud</h1>
    </div>
    <div class="content">
        <p>Hello Admin!</p>
        <p>A new user has requested to join Family Cloud.</p>
        <p><strong>User details:</strong></p>
        <ul>
            <li>Name: {{ $user->name }}</li>
            <li>Email: {{ $user->email }}</li>
            <li>Registration Date: {{ $user->created_at->format('M d, Y H:i') }}</li>
        </ul>
        <p>Please review this registration request and take appropriate action.</p>
        <p><a href="{{ $userManagementUrl }}" class="button">View User Management</a></p>
        <p>You can also use the direct action links below:</p>
        <p><a href="{{ $approveUrl }}" class="button">Approve User</a></p>
        <p>Or click here to reject: <a href="{{ $rejectUrl }}">Reject User</a></p>
    </div>
    <div class="footer">
        <p>&copy; {{ date('Y') }} Family Cloud. All rights reserved.</p>
    </div>
</body>
</html>
