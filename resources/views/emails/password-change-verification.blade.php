<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Change Verification</title>
</head>
<body style="font-family: Arial, sans-serif; line-height: 1.6; color: #333; max-width: 600px; margin: 0 auto; padding: 20px;">
    <div style="background: linear-gradient(135deg, #1976D2 0%, #1565C0 100%); padding: 30px; text-align: center; border-radius: 10px 10px 0 0;">
        <h1 style="color: white; margin: 0;">JValera Dental Clinic</h1>
    </div>
    
    <div style="background: #f8f9fa; padding: 30px; border-radius: 0 0 10px 10px; border: 1px solid #e0e0e0;">
        <h2 style="color: #1976D2; margin-top: 0;">Password Change Verification</h2>
        
        <p>Hello {{ $user->name }},</p>
        
        <p>You have requested to change your password. Please use the verification code below to confirm your identity:</p>
        
        <div style="background: white; border: 2px solid #1976D2; border-radius: 8px; padding: 20px; text-align: center; margin: 30px 0;">
            <p style="margin: 0; font-size: 14px; color: #666; text-transform: uppercase; letter-spacing: 1px;">Verification Code</p>
            <p style="margin: 10px 0 0 0; font-size: 36px; font-weight: bold; color: #1976D2; letter-spacing: 5px;">{{ $token }}</p>
        </div>
        
        <p style="color: #666; font-size: 14px;">This code will expire in <strong>30 minutes</strong>.</p>
        
        <p style="color: #d32f2f; font-size: 14px; margin-top: 30px;">
            <strong>Security Notice:</strong> If you did not request this password change, please ignore this email or contact our support team immediately.
        </p>
        
        <hr style="border: none; border-top: 1px solid #e0e0e0; margin: 30px 0;">
        
        <p style="color: #666; font-size: 12px; margin: 0;">
            This is an automated message from JValera Dental Clinic. Please do not reply to this email.
        </p>
    </div>
</body>
</html>

