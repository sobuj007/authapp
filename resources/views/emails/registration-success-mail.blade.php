<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Changed Successfully</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
            background-color: #f4f4f7;
        }
        .container {
            max-width: 600px;
            margin: 20px auto;
            padding: 0;
            background-color: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #10B981 0%, #059669 100%);
            color: white;
            padding: 40px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 32px;
            font-weight: 600;
        }
        .success-icon {
            width: 80px;
            height: 80px;
            background-color: rgba(255, 255, 255, 0.2);
            border-radius: 50%;
            margin: 0 auto 20px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
        }
        .content {
            padding: 40px 30px;
        }
        .greeting {
            font-size: 20px;
            color: #111827;
            margin-bottom: 20px;
        }
        .greeting strong {
            color: #059669;
        }
        .message-box {
            background-color: #f3f4f6;
            border-left: 4px solid #10B981;
            padding: 20px;
            margin: 25px 0;
            border-radius: 0 8px 8px 0;
        }
        .info-grid {
            background-color: #f9fafb;
            border-radius: 12px;
            padding: 25px;
            margin: 25px 0;
            border: 1px solid #e5e7eb;
        }
        .info-row {
            display: flex;
            padding: 12px 0;
            border-bottom: 1px solid #e5e7eb;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            width: 120px;
            font-weight: 600;
            color: #4b5563;
        }
        .info-value {
            flex: 1;
            color: #111827;
        }
        .alert-box {
            background-color: #fef3c7;
            border-radius: 8px;
            padding: 20px;
            margin: 25px 0;
        }
        .alert-title {
            color: #92400e;
            font-weight: 600;
            margin-bottom: 10px;
            font-size: 16px;
        }
        .button {
            display: inline-block;
            background-color: #10B981;
            color: white;
            text-decoration: none;
            padding: 14px 30px;
            border-radius: 8px;
            font-weight: 500;
            margin: 20px 0;
            transition: background-color 0.3s;
        }
        .button:hover {
            background-color: #059669;
        }
        .footer {
            background-color: #f9fafb;
            padding: 30px;
            text-align: center;
            font-size: 14px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
        }
        .help-links {
            margin: 20px 0;
        }
        .help-links a {
            color: #10B981;
            text-decoration: none;
            margin: 0 10px;
        }
        .help-links a:hover {
            text-decoration: underline;
        }
        @media only screen and (max-width: 600px) {
            .container {
                margin: 10px;
                border-radius: 8px;
            }
            .content {
                padding: 25px 20px;
            }
            .info-row {
                flex-direction: column;
                gap: 5px;
            }
            .info-label {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <div class="success-icon">✓</div>
            <h1>Password Changed Successfully</h1>
        </div>
        
        <div class="content">
            <div class="greeting">
                Hello <strong>{{ $user->name }}</strong>,
            </div>
            
            <div class="message-box">
                <p style="margin: 0; font-size: 16px;">
                    Your password has been successfully changed. This confirmation is sent to ensure 
                    the security of your account.
                </p>
            </div>
            
            <h3 style="color: #111827; margin-bottom: 15px;">📋 Change Details</h3>
            
            {{-- <div class="info-grid">
                <div class="info-row">
                    <span class="info-label">Changed on:</span>
                    <span class="info-value">{{ $changeTime->format('F j, Y \a\t g:i A') }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">IP Address:</span>
                    <span class="info-value">{{ $ipAddress }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Device:</span>
                    <span class="info-value">{{ $device }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Browser:</span>
                    <span class="info-value">{{ $browser }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Location:</span>
                    <span class="info-value">{{ $location }}</span>
                </div>
            </div> --}}
            
            <div class="alert-box">
                <div class="alert-title">⚠️ Didn't change your password?</div>
                <p style="margin: 0; color: #854d0e;">
                    If you did not make this change, please secure your account immediately:
                </p>
                <ul style="color: #854d0e; margin-top: 10px; padding-left: 20px;">
                    <li>Reset your password right away</li>
                    <li>Enable two-factor authentication</li>
                    <li>Contact our support team immediately</li>
                </ul>
            
            </div>
            
       
        </div>
        
        <div class="footer">
            <p><strong>Need help?</strong></p>
       
            <p style="margin-top: 20px;">
                This is an automated security notification from {{ config('app.name') }}. 
                Please do not reply to this email.
            </p>
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>