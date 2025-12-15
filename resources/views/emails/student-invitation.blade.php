<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome to Paghupay</title>
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
            background: linear-gradient(135deg, #0d6efd, #0dcaf0);
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
        .credentials-box {
            background: white;
            border: 2px dashed #0d6efd;
            border-radius: 8px;
            padding: 20px;
            margin: 20px 0;
        }
        .credentials-box h3 {
            color: #0d6efd;
            margin-top: 0;
        }
        .credential-item {
            margin: 10px 0;
            padding: 10px;
            background: #e7f1ff;
            border-radius: 5px;
        }
        .credential-item strong {
            color: #0d6efd;
        }
        .credential-item .value {
            font-family: 'Courier New', monospace;
            font-size: 16px;
            font-weight: bold;
            color: #212529;
        }
        .btn {
            display: inline-block;
            background: #0d6efd;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 5px;
            font-weight: bold;
            margin: 20px 0;
        }
        .btn:hover {
            background: #0b5ed7;
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
        <p>Dear Student,</p>
        
        <p>You have been registered in the <strong>Paghupay Guidance & Counseling System</strong> by the TUP-V Guidance Office.</p>
        
        <div class="credentials-box">
            <h3>📧 Your Login Credentials</h3>
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
            <a href="{{ $loginUrl }}" class="btn">Login to Complete Registration</a>
        </p>
        
        <div class="important">
            <strong>⚠️ IMPORTANT:</strong>
            <ul style="margin-bottom: 0;">
                <li>Log in using the email and temporary password above</li>
                <li>You will be asked to create a <strong>new password</strong></li>
                <li>You must complete your <strong>profile information</strong></li>
                <li>This temporary password is only for your first login</li>
            </ul>
        </div>
        
        <p>If you did not expect this email or have any questions, please contact the TUP-V Guidance Office.</p>
        
        <p>Best regards,<br>
        <strong>TUP-V Guidance Office</strong></p>
    </div>
    
    <div class="footer">
        <p>This is an automated message from the Paghupay System.<br>
        Technological University of the Philippines - Visayas</p>
    </div>
</body>
</html>
