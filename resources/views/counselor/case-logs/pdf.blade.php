<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Case Log - {{ $caseLog->case_log_id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Arial, sans-serif;
            font-size: 12pt;
            line-height: 1.6;
            color: #333;
            padding: 20px;
            max-width: 800px;
            margin: 0 auto;
        }

        .header {
            text-align: center;
            border-bottom: 2px solid #198754;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .header h1 {
            color: #198754;
            font-size: 24pt;
            margin-bottom: 5px;
        }

        .header p {
            color: #666;
            font-size: 11pt;
        }

        .case-id {
            background: #f8f9fa;
            padding: 10px;
            border-radius: 5px;
            text-align: center;
            margin-bottom: 20px;
        }

        .case-id code {
            font-size: 14pt;
            color: #198754;
            font-weight: bold;
        }

        .section {
            margin-bottom: 25px;
        }

        .section-title {
            background: #198754;
            color: white;
            padding: 8px 15px;
            font-size: 12pt;
            font-weight: bold;
            margin-bottom: 15px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
        }

        .info-item {
            margin-bottom: 10px;
        }

        .info-item label {
            display: block;
            font-size: 9pt;
            color: #666;
            text-transform: uppercase;
            margin-bottom: 3px;
        }

        .info-item strong {
            font-size: 11pt;
        }

        .notes-box {
            background: #f8f9fa;
            padding: 15px;
            border-left: 4px solid #198754;
            margin-bottom: 15px;
        }

        .notes-box h4 {
            font-size: 11pt;
            color: #198754;
            margin-bottom: 8px;
        }

        .notes-box p {
            font-size: 10pt;
            white-space: pre-wrap;
        }

        .goal-card {
            border: 1px solid #ddd;
            margin-bottom: 15px;
            page-break-inside: avoid;
        }

        .goal-header {
            background: #ffc107;
            padding: 8px 15px;
            font-weight: bold;
        }

        .goal-body {
            padding: 15px;
        }

        .goal-description {
            margin-bottom: 10px;
        }

        .activity-list {
            margin-left: 20px;
        }

        .activity-item {
            margin-bottom: 8px;
            padding: 8px;
            background: #f8f9fa;
            border-radius: 4px;
        }

        .activity-date {
            font-size: 9pt;
            color: #666;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            font-size: 9pt;
            color: #666;
        }

        .signature-line {
            margin-top: 50px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
        }

        .signature-box {
            text-align: center;
        }

        .signature-box .line {
            border-top: 1px solid #333;
            margin-top: 40px;
            padding-top: 5px;
        }

        @media print {
            body {
                padding: 0;
            }

            .no-print {
                display: none !important;
            }
        }
    </style>
</head>
<body>
    <div class="no-print" style="text-align: center; margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; background: #198754; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14px;">
            <i class="bi bi-printer"></i> Print / Save as PDF
        </button>
        <button onclick="window.close()" style="padding: 10px 20px; background: #6c757d; color: white; border: none; border-radius: 5px; cursor: pointer; font-size: 14px; margin-left: 10px;">
            Close
        </button>
    </div>

    <div class="header">
        <h1>TUP-V Guidance & Counseling</h1>
        <p>Confidential Case Log Report</p>
    </div>

    <div class="case-id">
        <code>{{ $caseLog->case_log_id }}</code>
    </div>

    <div class="section">
        <div class="section-title">Session Information</div>
        <div class="info-grid">
            <div class="info-item">
                <label>Student Name</label>
                <strong>{{ $caseLog->client->name }}</strong>
            </div>
            <div class="info-item">
                <label>Course/Year/Section</label>
                <strong>{{ $caseLog->client->course_year_section ?? 'N/A' }}</strong>
            </div>
            <div class="info-item">
                <label>Session Date</label>
                <strong>{{ $caseLog->start_time ? $caseLog->start_time->format('F j, Y') : 'N/A' }}</strong>
            </div>
            <div class="info-item">
                <label>Session Time</label>
                <strong>
                    {{ $caseLog->start_time ? $caseLog->start_time->format('g:i A') : 'N/A' }} - 
                    {{ $caseLog->end_time ? $caseLog->end_time->format('g:i A') : 'N/A' }}
                </strong>
            </div>
            <div class="info-item">
                <label>Duration</label>
                <strong>{{ $caseLog->formatted_duration }}</strong>
            </div>
            <div class="info-item">
                <label>Counselor</label>
                <strong>{{ $caseLog->counselor->name }}</strong>
            </div>
        </div>
    </div>

    @if($caseLog->appointment)
    <div class="section">
        <div class="section-title">Reason for Visit</div>
        <p style="padding: 15px;">{{ $caseLog->appointment->reason }}</p>
    </div>
    @endif

    <div class="section">
        <div class="section-title">Session Notes</div>
        
        <div class="notes-box">
            <h4>Progress Report</h4>
            <p>{{ $caseLog->progress_report ?: 'No progress report recorded.' }}</p>
        </div>

        <div class="notes-box">
            <h4>Additional Notes & Recommendations</h4>
            <p>{{ $caseLog->additional_notes ?: 'No additional notes recorded.' }}</p>
        </div>
    </div>

    @if($caseLog->treatmentGoals->isNotEmpty())
    <div class="section">
        <div class="section-title">Treatment Plan</div>
        
        @foreach($caseLog->treatmentGoals as $index => $goal)
        <div class="goal-card">
            <div class="goal-header">Goal #{{ $index + 1 }}</div>
            <div class="goal-body">
                <div class="goal-description">{{ $goal->description }}</div>
                
                @if($goal->activities->isNotEmpty())
                <div class="activity-list">
                    <strong style="font-size: 10pt;">Activities:</strong>
                    @foreach($goal->activities as $activity)
                    <div class="activity-item">
                        <div>{{ $activity->description }}</div>
                        <div class="activity-date">
                            Scheduled: {{ $activity->activity_date ? \Carbon\Carbon::parse($activity->activity_date)->format('M j, Y') : 'N/A' }}
                        </div>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
        </div>
        @endforeach
    </div>
    @endif

    <div class="signature-line">
        <div class="signature-box">
            <div class="line">Student Signature</div>
        </div>
        <div class="signature-box">
            <div class="line">Counselor Signature</div>
        </div>
    </div>

    <div class="footer">
        <p>This document is confidential and protected under the Data Privacy Act of 2012 (RA 10173).</p>
        <p>Generated on {{ now()->format('F j, Y \a\t g:i A') }}</p>
    </div>
</body>
</html>
