<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset - JValera Dental Clinic</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 2px solid #e9ecef;
        }
        .logo {
            font-size: 24px;
            font-weight: bold;
            color: #2c5aa0;
            margin-bottom: 10px;
        }
        .verification-code {
            background-color: #f8f9fa;
            border: 2px dashed #2c5aa0;
            border-radius: 8px;
            padding: 20px;
            text-align: center;
            margin: 20px 0;
        }
        .code {
            font-size: 32px;
            font-weight: bold;
            color: #2c5aa0;
            letter-spacing: 5px;
            margin: 10px 0;
        }
        .warning {
            background-color: #fff3cd;
            border: 1px solid #ffeaa7;
            border-radius: 5px;
            padding: 15px;
            margin: 20px 0;
            color: #856404;
        }
        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #e9ecef;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background-color: #2c5aa0;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">🦷 JValera Dental Clinic</div>
            <p>Patient Portal - Password Reset</p>
        </div>

        <h2>Hello {{ $user->name }},</h2>

        <p>We received a request to reset your password for your JValera Dental Clinic account. Use the verification code below to reset your password:</p>

        <div class="verification-code">
            <h3>Your Verification Code</h3>
            <div class="code">{{ $token }}</div>
            <p><strong>This code will expire in 15 minutes.</strong></p>
        </div>

        <div class="warning">
            <strong>⚠️ Security Notice:</strong>
            <ul>
                <li>This code is valid for 15 minutes only</li>
                <li>Do not share this code with anyone</li>
                <li>If you didn't request this reset, please ignore this email</li>
                <li>For security reasons, this code can only be used once</li>
            </ul>
        </div>

        <p>If you're having trouble with the verification code, you can request a new one by visiting our password reset page.</p>

        <div class="footer">
            <p><strong>JValera Dental Clinic</strong></p>
            <p>Patient Portal System</p>
            <p>This is an automated message. Please do not reply to this email.</p>
            <p>&copy; {{ date('Y') }} JValera Dental Clinic. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
