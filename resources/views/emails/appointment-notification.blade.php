<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Notification</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .email-header {
            background: linear-gradient(135deg, #0D47A1 0%, #1976D2 100%);
            color: #ffffff;
            padding: 30px 20px;
            text-align: center;
        }
        .email-header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }
        .email-header .logo {
            font-size: 32px;
            margin-bottom: 10px;
        }
        .email-body {
            padding: 30px 20px;
        }
        .email-body p {
            margin: 0 0 15px;
            font-size: 16px;
            line-height: 1.8;
        }
        .highlight {
            color: #1976D2;
            font-weight: bold;
        }
        .email-footer {
            background-color: #f8f9fa;
            padding: 20px;
            text-align: center;
            border-top: 1px solid #e9ecef;
        }
        .email-footer p {
            margin: 5px 0;
            font-size: 14px;
            color: #6c757d;
        }
        .divider {
            height: 1px;
            background-color: #e9ecef;
            margin: 20px 0;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="email-header">
            <div class="logo">🦷</div>
            <h1>JValera Dental Clinic</h1>
        </div>
        <div class="email-body">
            <p>{!! nl2br(e($messageContent)) !!}</p>
            <div class="divider"></div>
            <p style="font-size: 14px; color: #6c757d;">
                If you have any questions or need to make changes to your appointment, please contact us.
            </p>
        </div>
        <div class="email-footer">
            <p><strong>JValera Dental Clinic</strong></p>
            <p>Contact: (123) 456-7890 | Email: info@jvaleradental.com</p>
            <p style="font-size: 12px; margin-top: 15px;">
                This is an automated message. Please do not reply to this email.
            </p>
        </div>
    </div>
</body>
</html>

