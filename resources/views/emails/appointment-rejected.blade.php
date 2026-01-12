<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Request Declined</title>
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
            background-color: #6c757d;
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
            background-color: #6c757d;
            color: white;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
        }
        .reason-box {
            background-color: #fff3cd;
            border: 1px solid #ffc107;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }
        .reason-box h3 {
            margin-top: 0;
            color: #856404;
        }
        .action-box {
            background-color: #e7f3ff;
            border: 1px solid #0d6efd;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }
        .action-box h3 {
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
        <h1>📋 Appointment Request Declined</h1>
    </div>
    
    <div class="content">
        <p>Dear {{ $appointment->client->name }},</p>
        
        <p>We regret to inform you that your appointment request has been <strong>declined</strong>.</p>
        
        <div class="appointment-details">
            <div class="detail-row">
                <span class="detail-label">Counselor</span>
                <span class="detail-value">{{ $appointment->counselor->name }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Requested Date & Time</span>
                <span class="detail-value">{{ $appointment->scheduled_at->format('l, F j, Y \a\t g:i A') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Status</span>
                <span class="status-badge">Declined</span>
            </div>
        </div>

        <div class="reason-box">
            <h3>📝 Reason</h3>
            <p>{{ $reason }}</p>
        </div>

        <div class="action-box">
            <h3>📅 What's Next?</h3>
            <p>You are welcome to submit a new appointment request for a different date or time through the Paghupay system. If you have any questions or concerns, please don't hesitate to contact the Guidance Office directly.</p>
        </div>
        
        <p>We apologize for any inconvenience this may have caused.</p>
        
        <p>Best regards,<br>
        <strong>TUP-V Guidance & Counseling Office</strong></p>
    </div>
    
    <div class="footer">
        <p>This is an automated message from the Paghupay - TUP-V Guidance & Counseling System.</p>
        <p>Please do not reply to this email.</p>
    </div>
</body>
</html>
