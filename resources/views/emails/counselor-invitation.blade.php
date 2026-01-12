<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Paghupay - Counselor Account</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #3d9f9b, #235675);
            color: white;
            padding: 30px 20px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .header h1 {
            margin: 0;
            font-size: 28px;
        }
        .header p {
            margin: 5px 0 0;
            opacity: 0.9;
        }
        .content {
            background: #f8f9fa;
            padding: 30px;
            border: 1px solid #dee2e6;
            border-top: none;
            border-radius: 0 0 10px 10px;
        }
        .welcome-box {
            background: linear-gradient(135deg, #e8f5e9, #c8e6c9);
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
            text-align: center;
        }
        .welcome-box h2 {
            color: #2e7d32;
            margin: 0 0 10px;
        }
        .credentials-box {
            background: white;
            border: 2px dashed #3d9f9b;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .credentials-box h3 {
            color: #3d9f9b;
            margin-top: 0;
        }
        .credential-item {
            margin: 10px 0;
            padding: 10px;
            background: #e0f2f1;
            border-radius: 5px;
        }
        .credential-item strong {
            color: #3d9f9b;
        }
        .credential-item .value {
            font-family: 'Courier New', monospace;
            font-size: 16px;
            font-weight: bold;
            color: #212529;
        }
        .btn {
            display: inline-block;
            background: #3d9f9b;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 20px 0;
        }
        .btn:hover {
            background: #358a87;
        }
        .important {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 15px;
            margin: 20px 0;
        }
        .important strong {
            color: #856404;
        }
        .security-notice {
            background: #e3f2fd;
            border-left: 4px solid #2196f3;
            padding: 15px;
            margin: 20px 0;
        }
        .security-notice strong {
            color: #1565c0;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #6c757d;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🎓 Paghupay</h1>
        <p>TUP-V Guidance & Counseling System</p>
    </div>
    
    <div class="content">
        <div class="welcome-box">
            <h2>Welcome, {{ $name }}!</h2>
            @if($position)
                <p style="margin: 0; color: #388e3c;">{{ $position }}</p>
            @endif
        </div>
        
        <p>You have been added as a <strong>Counselor</strong> in the Paghupay Guidance & Counseling System by the TUP-V Administrator.</p>
        
        <div class="credentials-box">
            <h3>🔑 Your Login Credentials</h3>
            <div class="credential-item">
                <strong>Email:</strong><br>
                <span class="value">{{ $email }}</span>
            </div>
            <div class="credential-item">
                <strong>Temporary Password:</strong><br>
                <span class="value">{{ $tempPassword }}</span>
            </div>
        </div>
        
        <p style="text-align: center;">
            <a href="{{ $loginUrl }}" class="btn">Login to Your Account</a>
        </p>
        
        <div class="security-notice">
            <strong>🔒 DEVICE BINDING NOTICE:</strong>
            <p style="margin-bottom: 0;">For security purposes, your account will be <strong>bound to the first device</strong> you use to log in. This means:</p>
            <ul style="margin-bottom: 0;">
                <li>Please log in from your <strong>primary work computer</strong></li>
                <li>You will only be able to access your account from this device</li>
                <li>If you need to change devices, contact the Administrator</li>
            </ul>
        </div>
        
        <div class="important">
            <strong>⚠️ IMPORTANT:</strong>
            <ul style="margin-bottom: 0;">
                <li>Log in using your <strong>email address</strong> and temporary password</li>
                <li>You will be prompted to change your password on first login</li>
                <li>Keep your credentials secure and do not share them</li>
                <li>This temporary password is only for your first login</li>
            </ul>
        </div>
        
        <p>If you did not expect this email or have any questions, please contact the TUP-V Administrator immediately.</p>
        
        <p>Best regards,<br>
        <strong>TUP-V Guidance Office</strong></p>
    </div>
    
    <div class="footer">
        <p>This is an automated message from the Paghupay System.<br>
        Technological University of the Philippines - Visayas</p>
    </div>
</body>
</html>
