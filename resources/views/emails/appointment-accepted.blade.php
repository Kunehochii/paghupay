<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Accepted</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #198754;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            background-color: #f8f9fa;
            padding: 30px;
            border: 1px solid #dee2e6;
            border-top: none;
        }
        .appointment-details {
            background-color: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            border: 1px solid #dee2e6;
        }
        .detail-row {
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: bold;
            color: #666;
            display: block;
            margin-bottom: 5px;
        }
        .detail-value {
            color: #333;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            background-color: #198754;
            color: white;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
        }
        .notice-box {
            background-color: #d1e7dd;
            border: 1px solid #198754;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }
        .notice-box h3 {
            margin-top: 0;
            color: #0f5132;
        }
        .reminder-box {
            background-color: #e7f3ff;
            border: 1px solid #0d6efd;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }
        .reminder-box h3 {
            margin-top: 0;
            color: #0d6efd;
        }
        .footer {
            text-align: center;
            padding: 20px;
            color: #666;
            font-size: 12px;
            border: 1px solid #dee2e6;
            border-top: none;
            border-radius: 0 0 8px 8px;
            background-color: #f8f9fa;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>✅ Appointment Accepted</h1>
    </div>
    
    <div class="content">
        <p>Dear {{ $appointment->client->name }},</p>
        
        <p>Great news! Your appointment request has been <strong>accepted</strong> by your counselor.</p>
        
        <div class="notice-box">
            <h3>🎉 Your Appointment is Confirmed!</h3>
            <p>Please make sure to be available at the scheduled date and time.</p>
        </div>
        
        <div class="appointment-details">
            <div class="detail-row">
                <span class="detail-label">Counselor</span>
                <span class="detail-value">{{ $appointment->counselor->name }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Date & Time</span>
                <span class="detail-value">{{ $appointment->scheduled_at->format('l, F j, Y \a\t g:i A') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Status</span>
                <span class="status-badge">Accepted</span>
            </div>
        </div>

        <div class="reminder-box">
            <h3>📋 Reminders</h3>
            <ul style="margin: 10px 0 0 0; padding-left: 20px;">
                <li>Please arrive 10 minutes before your scheduled time</li>
                <li>Bring any relevant documents or information for your session</li>
                <li>The session will take place at the Guidance Office</li>
                <li>If you need to cancel, please do so at least 24 hours in advance</li>
            </ul>
        </div>
        
        <p>We look forward to seeing you!</p>
        
        <p>Best regards,<br>
        <strong>TUP-V Guidance & Counseling Office</strong></p>
    </div>
    
    <div class="footer">
        <p>This is an automated message from the Paghupay - TUP-V Guidance & Counseling System.</p>
        <p>Please do not reply to this email.</p>
    </div>
</body>
</html>
