<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Appointment Confirmation</title>
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
            background-color: #0d6efd;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 8px 8px 0 0;
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
            display: flex;
            padding: 10px 0;
            border-bottom: 1px solid #eee;
        }
        .detail-row:last-child {
            border-bottom: none;
        }
        .detail-label {
            font-weight: bold;
            width: 120px;
            color: #666;
        }
        .detail-value {
            flex: 1;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            background-color: #ffc107;
            color: #212529;
            border-radius: 20px;
            font-size: 14px;
            font-weight: bold;
        }
        .footer {
            background-color: #343a40;
            color: #adb5bd;
            padding: 20px;
            text-align: center;
            border-radius: 0 0 8px 8px;
            font-size: 12px;
        }
        .notice {
            background-color: #e7f3ff;
            border-left: 4px solid #0d6efd;
            padding: 15px;
            margin: 20px 0;
        }
        h1 {
            margin: 0;
            font-size: 24px;
        }
        h2 {
            color: #0d6efd;
            margin-top: 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📅 Appointment Confirmation</h1>
        <p style="margin: 5px 0 0 0;">TUP-V Guidance & Counseling System</p>
    </div>

    <div class="content">
        <p>Dear <strong>{{ $appointment->client->name }}</strong>,</p>

        <p>Thank you for booking an appointment with the TUP-V Guidance and Counseling Office. Your appointment request has been received and is currently pending approval.</p>

        <h2>📋 Appointment Details</h2>

        <div class="appointment-details">
            <div class="detail-row">
                <span class="detail-label">Status:</span>
                <span class="detail-value">
                    <span class="status-badge">⏳ Pending</span>
                </span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Counselor:</span>
                <span class="detail-value">{{ $appointment->counselor->name }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Date:</span>
                <span class="detail-value">{{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('l, F d, Y') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Time:</span>
                <span class="detail-value">{{ \Carbon\Carbon::parse($appointment->scheduled_at)->format('g:i A') }}</span>
            </div>
            <div class="detail-row">
                <span class="detail-label">Reason:</span>
                <span class="detail-value">{{ Str::limit($appointment->reason, 100) }}</span>
            </div>
        </div>

        <div class="notice">
            <strong>📌 What's Next?</strong>
            <ul style="margin: 10px 0 0 0; padding-left: 20px;">
                <li>Your appointment is being reviewed by the counselor</li>
                <li>You will receive another email once your appointment is confirmed</li>
                <li>Please arrive 10 minutes before your scheduled time</li>
                <li>Bring any relevant documents or information for your session</li>
            </ul>
        </div>

        <p>If you need to reschedule or cancel your appointment, please contact the Guidance Office directly or log in to your Paghupay account.</p>

        <p>
            Best regards,<br>
            <strong>TUP-V Guidance and Counseling Office</strong>
        </p>
    </div>

    <div class="footer">
        <p><strong>Paghupay</strong> - TUP-V Guidance & Counseling System</p>
        <p>This is an automated message. Please do not reply to this email.</p>
        <p>© {{ date('Y') }} Technological University of the Philippines - Visayas</p>
    </div>
</body>
</html>
