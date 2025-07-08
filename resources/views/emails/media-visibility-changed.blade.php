<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Media Visibility Changed</title>
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
        .visibility-changed {
            display: flex;
            justify-content: center;
            align-items: center;
            margin: 20px 0;
        }
        .visibility-box {
            padding: 10px 15px;
            border-radius: 5px;
            margin: 0 10px;
            text-align: center;
        }
        .visibility-private {
            background-color: #fee2e2;
            border: 1px solid #f87171;
            color: #b91c1c;
        }
        .visibility-public {
            background-color: #dcfce7;
            border: 1px solid #34d399;
            color: #047857;
        }
        .arrow {
            font-size: 24px;
            color: #9ca3af;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Media Visibility Changed</h1>
        </div>

        <div class="content">
            <p>Hello {{ $owner->name }},</p>

            <p>The visibility of your {{ $mediaType }} <strong>{{ $mediaName }}</strong> has been changed.</p>

            <div class="visibility-changed">
                <div class="visibility-box {{ $oldVisibility == 'private' ? 'visibility-private' : 'visibility-public' }}">
                    <strong>{{ ucfirst($oldVisibility) }}</strong>
                </div>
                <div class="arrow">→</div>
                <div class="visibility-box {{ $newVisibility == 'private' ? 'visibility-private' : 'visibility-public' }}">
                    <strong>{{ ucfirst($newVisibility) }}</strong>
                </div>
            </div>

            <div class="info">
                <p><strong>Media Type:</strong> {{ ucfirst($mediaType) }}</p>
                <p><strong>Name:</strong> {{ $mediaName }}</p>
                <p><strong>New Visibility:</strong> {{ ucfirst($newVisibility) }}</p>

                @if($newVisibility == 'public' && $shareLink)
                <p><strong>Important:</strong> This content is now publicly accessible to anyone with the link.</p>
                @elseif($newVisibility == 'private')
                <p><strong>Important:</strong> This content is now private and only accessible to you and users you specifically share it with.</p>
                @endif
            </div>

            @if($shareLink)
            <p>Access your content using the link below:</p>

            <div style="text-align: center;">
                <a href="{{ $shareLink }}" class="button">Access Content</a>
            </div>

            <p>Or copy and paste this link in your browser:</p>
            <p style="word-break: break-all; background: #eee; padding: 10px; border-radius: 5px;">{{ $shareLink }}</p>
            @endif

            <p>If you did not make this change, please review your account security and contact support immediately.</p>
        </div>

        <div class="footer">
            <p>This is an automated message. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} Family Cloud. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
