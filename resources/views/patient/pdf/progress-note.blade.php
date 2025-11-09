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
            font-family: Poppins;
            font-size: 10pt;
            color: #1e293b;
            line-height: 1.7;
            padding: 0.75rem 1rem;
            background: #ffffff;
        }

        @page {
            size: legal;
            margin: 0.75in;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            body {
                padding: 0;
            }
        }

        .print-button-container {
            position: fixed;
            top: 10px;
            right: 10px;
            z-index: 1000;
        }

        .btn-print {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            font-size: 14px;
            box-shadow: 0 4px 6px rgba(16, 185, 129, 0.3);
            transition: all 0.3s ease;
        }

        .btn-print:hover {
            background: linear-gradient(135deg, #059669 0%, #047857 100%);
            box-shadow: 0 6px 12px rgba(16, 185, 129, 0.4);
            transform: translateY(-2px);
        }

        .header {
            text-align: center;
            margin-bottom: 1.5rem;
            padding: 1.25rem 1rem;
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            color: white;
            border-radius: 8px 8px 0 0;
            margin: -0.75in -0.75in 1.5rem -0.75in;
        }

        .header h1 {
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .header .subtitle {
            font-size: 0.95rem;
            color: #d1fae5;
            opacity: 0.95;
        }

        .section {
            margin-bottom: 1.25rem;
            page-break-inside: avoid;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            overflow: hidden;
        }

        .section-header {
            background: linear-gradient(135deg, #10b981 0%, #059669 100%);
            padding: 0.75rem 1rem;
            margin-bottom: 0;
        }

        .section-title {
            font-size: 0.95rem;
            font-weight: 700;
            color: white;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 1rem;
            padding: 1rem;
        }

        .info-field {
            padding: 0.75rem;
            background: #f8fafc;
            border-left: 3px solid #10b981;
            border-radius: 4px;
        }

        .field-label {
            font-size: 0.8rem;
            color: #475569;
            font-weight: 700;
            margin-bottom: 0.375rem;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .field-value {
            font-size: 0.9rem;
            color: #1e293b;
            font-weight: 500;
            line-height: 1.7;
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        .notes-box {
            background: linear-gradient(135deg, #d1fae5 0%, #a7f3d0 100%);
            padding: 1rem;
            border-radius: 6px;
            margin: 0.75rem 1rem;
            border-left: 4px solid #10b981;
            box-shadow: 0 2px 4px rgba(16, 185, 129, 0.1);
            line-height: 1.8;
            white-space: pre-wrap;
            word-wrap: break-word;
        }

        .notes-box strong {
            display: block;
            margin-bottom: 0.5rem;
            color: #047857;
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .footer {
            margin-top: 2rem;
            padding-top: 1rem;
            border-top: 3px solid #e5e7eb;
            text-align: center;
            color: #64748b;
            font-size: 0.8rem;
            line-height: 1.8;
            background: #f8fafc;
            padding: 1rem;
            border-radius: 6px;
            margin: 2rem -0.75in -0.75in -0.75in;
        }

        .footer strong {
            font-weight: 700;
            color: #10b981;
            font-size: 1rem;
            display: block;
            margin-bottom: 0.5rem;
        }

        .footer p {
            margin: 0.25rem 0;
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

    <!-- Header -->
    <div class="header">
        <h1>📋 Progress Note</h1>
        <div class="subtitle">JValera Dental Clinic</div>
    </div>

    <!-- Progress Note Information -->
    <div class="section">
        <div class="section-header">
            <div class="section-title">📅 Note Information</div>
        </div>
        <div class="info-grid">
            <div class="info-field">
                <div class="field-label">Date</div>
                <div class="field-value">{{ $note->note_date ? \Carbon\Carbon::parse($note->note_date)->format('F d, Y') : 'N/A' }}</div>
            </div>
        </div>
    </div>

    @if($note->progress_description)
    <!-- Progress Description -->
    <div class="section">
        <div class="section-header">
            <div class="section-title">📝 Progress Description</div>
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
            <div class="section-title">💊 Treatment Response</div>
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
            <div class="section-title">➡️ Next Steps</div>
        </div>
        <div class="notes-box">
            {{ $note->next_steps }}
        </div>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <strong>JValera Dental Clinic</strong>
        <p>This is a computer-generated document. No signature is required.</p>
        <p>Document generated on {{ date('F d, Y \a\t h:i A') }}</p>
        <p>Note ID: {{ $note->id }}</p>
    </div>
</body>
</html>
