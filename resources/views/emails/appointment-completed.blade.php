<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Completed</title>
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
        .session-info {
            background-color: #d1e7dd;
            border: 1px solid #198754;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }
        .session-info h3 {
            margin-top: 0;
            color: #0f5132;
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
        .next-steps {
            background-color: #e7f3ff;
            border: 1px solid #0d6efd;
            border-radius: 8px;
            padding: 15px;
            margin: 20px 0;
        }
        .next-steps h3 {
            margin-top: 0;
            color: #0d6efd;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>✅ Session Completed</h1>
    </div>
    
    <div class="content">
        <p>Dear {{ $appointment->client->name }},</p>
        
        <p>Thank you for attending your counseling session. Your appointment has been <strong>completed</strong>.</p>
        
        <div class="appointment-details">
            <div class="detail-row">
                <span class="detail-label">Counselor</span>
                <span class="detail-value">{{ $appointment->counselor->name }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Date</span>
                <span class="detail-value">{{ $appointment->scheduled_at->format('l, F j, Y') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Case Log ID</span>
                <span class="detail-value">{{ $caseLog->case_log_id }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Session Duration</span>
                <span class="detail-value">{{ $caseLog->session_duration }} minutes</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Status</span>
                <span class="status-badge">Completed</span>
            </div>
        </div>

        <div class="session-info">
            <h3>📝 Session Summary</h3>
            <p>A case log has been created for your session. Your counselor has documented the session details which are kept confidential in accordance with the Data Privacy Act (RA 10173).</p>
        </div>

        <div class="next-steps">
            <h3>📅 What's Next?</h3>
            <ul>
                <li>If follow-up sessions are needed, you can book another appointment through the Paghupay system.</li>
                <li>Remember to follow any recommendations discussed during your session.</li>
                <li>Feel free to reach out to the Guidance Office if you have any concerns.</li>
            </ul>
        </div>
        
        <p>We hope your session was helpful. Remember, seeking guidance is a sign of strength, and we're here to support you.</p>
        
        <p>Best regards,<br>
        <strong>TUP-V Guidance & Counseling Office</strong></p>
    </div>
    
    <div class="footer">
        <p>This is an automated message from the Paghupay - TUP-V Guidance & Counseling System.</p>
        <p>Please do not reply to this email.</p>
    </div>
</body>
</html>
