<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Media Shared With You</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f9f9f9;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 5px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 1px solid #eee;
        }
        .content {
            padding: 20px 0;
        }
        .footer {
            padding-top: 20px;
            border-top: 1px solid #eee;
            text-align: center;
            font-size: 12px;
            color: #888;
        }
        .button {
            display: inline-block;
            background-color: #4f46e5;
            color: #ffffff !important;
            text-decoration: none;
            padding: 10px 20px;
            border-radius: 5px;
            font-weight: bold;
            margin: 20px 0;
        }
        .info {
            background-color: #f0f7ff;
            border-left: 4px solid #3b82f6;
            padding: 10px 15px;
            margin: 15px 0;
            border-radius: 0 5px 5px 0;
        }
        .expire-info {
            font-style: italic;
            color: #666;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Media Shared With You</h1>
        </div>

        <div class="content">
            <p>Hello,</p>

            <p><strong>{{ $sharedBy->name }}</strong> has shared a {{ $mediaType }} with you: <strong>{{ $mediaName }}</strong></p>

            <div class="info">
                <p><strong>Media Type:</strong> {{ ucfirst($mediaType) }}</p>
                <p><strong>Shared By:</strong> {{ $sharedBy->name }} ({{ $sharedBy->email }})</p>
                <p><strong>Access Type:</strong> {{ $isPublic ? 'Public' : 'Private' }}</p>

                @if(!empty($permissions))
                <p><strong>Your Permissions:</strong> {{ implode(', ', array_map('ucfirst', $permissions)) }}</p>
                @endif

                @if($expiresAt)
                <p class="expire-info">This share will expire on {{ $expiresAt->format('F j, Y \a\t g:i A') }}</p>
                @endif
            </div>

            <p>Click the button below to access the shared content:</p>

            <div style="text-align: center;">
                <a href="{{ $shareLink }}" class="button">View Shared Content</a>
            </div>

            <p>Or copy and paste this link in your browser:</p>
            <p style="word-break: break-all; background: #eee; padding: 10px; border-radius: 5px;">{{ $shareLink }}</p>

        </div>

        <div class="footer">
            <p>This is an automated message. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} Family Cloud. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
