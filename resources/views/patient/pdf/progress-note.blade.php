<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Progress Note - {{ $note->note_date ? \Carbon\Carbon::parse($note->note_date)->format('m/d/Y') : 'N/A' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Roboto', Arial, sans-serif;
            font-size: 13px;
            color: #2C3E50;
            line-height: 1.6;
            padding: 30px;
            background: white;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            body {
                padding: 15px;
            }
        }

        .print-button-container {
            position: fixed;
            top: 10px;
            right: 10px;
            z-index: 1000;
        }

        .btn-print {
            background: #10b981;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            font-size: 13px;
        }

        .btn-print:hover {
            background: #059669;
        }

        .page-title {
            font-size: 22px;
            font-weight: 700;
            color: #2C3E50;
            margin-bottom: 10px;
        }

        .title-underline {
            height: 3px;
            background: #10b981;
            margin-bottom: 5px;
        }

        .title-underline-thin {
            height: 2px;
            background: #10b981;
            margin-bottom: 25px;
        }

        .section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }

        .section-header {
            background: #f5f5f5;
            padding: 10px 12px;
            border-left: 4px solid #10b981;
            margin-bottom: 15px;
        }

        .section-title {
            font-size: 14px;
            font-weight: 600;
            color: #2C3E50;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 15px;
        }

        .info-field {
            padding: 8px 0;
            border-bottom: 1px solid #e0e0e0;
        }

        .field-label {
            font-size: 12px;
            color: #546E7A;
            font-weight: 600;
            margin-bottom: 4px;
        }

        .field-value {
            font-size: 13px;
            color: #2C3E50;
            white-space: pre-wrap;
        }

        .notes-box {
            background: #E8F5E9;
            padding: 15px;
            border-radius: 4px;
            margin-top: 10px;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #e0e0e0;
            text-align: center;
            color: #757575;
            font-size: 11px;
        }
    </style>
</head>
<body>
    <!-- Print Button -->
    <div class="print-button-container no-print">
        <button class="btn-print" onclick="window.print()">
            🖨️ Print / Save as PDF
        </button>
    </div>

    <!-- Page Title -->
    <div class="page-title">Progress Note</div>
    <div class="title-underline"></div>
    <div class="title-underline-thin"></div>

    <!-- Progress Note Information -->
    <div class="section">
        <div class="section-header">
            <div class="section-title">Note Information</div>
        </div>
        <div class="info-grid">
            <div class="info-field">
                <div class="field-label">Date:</div>
                <div class="field-value">{{ $note->note_date ? \Carbon\Carbon::parse($note->note_date)->format('F d, Y') : 'N/A' }}</div>
            </div>
        </div>
    </div>

    @if($note->progress_description)
    <!-- Progress Description -->
    <div class="section">
        <div class="section-header">
            <div class="section-title">Progress Description</div>
        </div>
        <div class="notes-box">
            {{ $note->progress_description }}
        </div>
    </div>
    @endif

    @if($note->treatment_response)
    <!-- Treatment Response -->
    <div class="section">
        <div class="section-header">
            <div class="section-title">Treatment Response</div>
        </div>
        <div class="notes-box">
            {{ $note->treatment_response }}
        </div>
    </div>
    @endif

    @if($note->next_steps)
    <!-- Next Steps -->
    <div class="section">
        <div class="section-header">
            <div class="section-title">Next Steps</div>
        </div>
        <div class="notes-box">
            {{ $note->next_steps }}
        </div>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p><strong>JValera Dental Clinic</strong></p>
        <p>This is a computer-generated document. No signature is required.</p>
        <p>Document generated on {{ date('F d, Y \a\t h:i A') }}</p>
        <p>Note ID: {{ $note->id }}</p>
    </div>
</body>
</html>

