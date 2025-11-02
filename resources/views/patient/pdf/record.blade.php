<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Medical Record - {{ $record->user && $record->user->info ? $record->user->info->first_name . ' ' . $record->user->info->last_name : 'Patient' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
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
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 8px;
            font-weight: 700;
            cursor: pointer;
            font-size: 14px;
            box-shadow: 0 4px 6px rgba(13, 110, 253, 0.3);
            transition: all 0.3s ease;
        }

        .btn-print:hover {
            background: linear-gradient(135deg, #0a58ca 0%, #084298 100%);
            box-shadow: 0 6px 12px rgba(13, 110, 253, 0.4);
            transform: translateY(-2px);
        }

        .header {
            text-align: center;
            margin-bottom: 1.5rem;
            padding: 1.25rem 1rem;
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
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
            color: #e0f2fe;
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
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            padding: 0.75rem 1rem;
            border-left: none;
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
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            padding: 1rem;
        }

        .info-field {
            padding: 0.75rem;
            background: #f8fafc;
            border-left: 3px solid #0d6efd;
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
            line-height: 1.5;
        }

        .info-field.full-width {
            grid-column: 1 / -1;
        }

        .notes-box {
            background: linear-gradient(135deg, #e0f2fe 0%, #bae6fd 100%);
            padding: 1rem;
            border-radius: 6px;
            margin: 0.75rem 1rem;
            border-left: 4px solid #0d6efd;
            box-shadow: 0 2px 4px rgba(13, 110, 253, 0.1);
        }

        .notes-box strong {
            display: block;
            margin-bottom: 0.5rem;
            color: #0a58ca;
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
            color: #0d6efd;
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
        <h1>📄 Patient Medical Record</h1>
        <div class="subtitle">JValera Dental Clinic</div>
    </div>

    <!-- Patient Information -->
    <div class="section">
        <div class="section-header">
            <div class="section-title">👤 Patient Information</div>
        </div>
        <div class="info-grid">
            <div class="info-field">
                <div class="field-label">Patient Name</div>
                <div class="field-value">{{ $record->user && $record->user->info ? $record->user->info->first_name . ' ' . $record->user->info->last_name : 'N/A' }}</div>
            </div>
            <div class="info-field">
                <div class="field-label">Sex</div>
                <div class="field-value">{{ $record->sex ?? 'N/A' }}</div>
            </div>
            <div class="info-field">
                <div class="field-label">Date of Birth</div>
                <div class="field-value">{{ $record->date_of_birth ? \Carbon\Carbon::parse($record->date_of_birth)->format('F d, Y') : 'N/A' }}</div>
            </div>
            <div class="info-field">
                <div class="field-label">Age</div>
                <div class="field-value">{{ $record->age ?? 'N/A' }}</div>
            </div>
            <div class="info-field">
                <div class="field-label">Contact</div>
                <div class="field-value">{{ $record->contact ?? 'N/A' }}</div>
            </div>
            <div class="info-field">
                <div class="field-label">Nickname</div>
                <div class="field-value">{{ $record->nickname ?? 'N/A' }}</div>
            </div>
            <div class="info-field full-width">
                <div class="field-label">Home Address</div>
                <div class="field-value">{{ $record->home_address ?? 'N/A' }}</div>
            </div>
            <div class="info-field">
                <div class="field-label">Religion</div>
                <div class="field-value">{{ $record->religion ?? 'N/A' }}</div>
            </div>
            <div class="info-field">
                <div class="field-label">Occupation</div>
                <div class="field-value">{{ $record->occupation ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    @if($record->guardian_name)
    <!-- Guardian Information -->
    <div class="section">
        <div class="section-header">
            <div class="section-title">👨‍👩‍👧‍👦 Guardian Information</div>
        </div>
        <div class="info-grid">
            <div class="info-field">
                <div class="field-label">Guardian Name</div>
                <div class="field-value">{{ $record->guardian_name }}</div>
            </div>
            <div class="info-field">
                <div class="field-label">Guardian Contact</div>
                <div class="field-value">{{ $record->guardian_contact ?? 'N/A' }}</div>
            </div>
            <div class="info-field">
                <div class="field-label">Guardian Occupation</div>
                <div class="field-value">{{ $record->guardian_occupation ?? 'N/A' }}</div>
            </div>
        </div>
    </div>
    @endif

    @if($record->physician_name)
    <!-- Physician Information -->
    <div class="section">
        <div class="section-header">
            <div class="section-title">👨‍⚕️ Physician Information</div>
        </div>
        <div class="info-grid">
            <div class="info-field">
                <div class="field-label">Physician Name</div>
                <div class="field-value">{{ $record->physician_name }}</div>
            </div>
            <div class="info-field">
                <div class="field-label">Specialty</div>
                <div class="field-value">{{ $record->physician_specialty ?? 'N/A' }}</div>
            </div>
            <div class="info-field">
                <div class="field-label">Contact</div>
                <div class="field-value">{{ $record->physician_contact ?? 'N/A' }}</div>
            </div>
            <div class="info-field full-width">
                <div class="field-label">Office Address</div>
                <div class="field-value">{{ $record->physician_office_address ?? 'N/A' }}</div>
            </div>
        </div>
    </div>
    @endif

    @if($record->previous_dentist || $record->last_dental_visit || $record->treatment_done)
    <!-- Dental History -->
    <div class="section">
        <div class="section-header">
            <div class="section-title">🦷 Dental History</div>
        </div>
        <div class="info-grid">
            @if($record->previous_dentist)
            <div class="info-field">
                <div class="field-label">Previous Dentist</div>
                <div class="field-value">{{ $record->previous_dentist }}</div>
            </div>
            <div class="info-field">
                <div class="field-label">Last Dental Visit</div>
                <div class="field-value">{{ $record->last_dental_visit ? \Carbon\Carbon::parse($record->last_dental_visit)->format('F d, Y') : 'N/A' }}</div>
            </div>
            @endif
            @if($record->treatment_done)
            <div class="info-field full-width">
                <div class="field-label">Treatment Done</div>
                <div class="field-value">{{ $record->treatment_done }}</div>
            </div>
            @endif
        </div>
    </div>
    @endif

    @if($record->medical_history || $record->allergies || $record->current_medications)
    <!-- Medical Information -->
    <div class="section">
        <div class="section-header">
            <div class="section-title">💊 Medical Information</div>
        </div>
        @if($record->medical_history)
        <div class="notes-box">
            <strong>Medical History</strong>
            {{ $record->medical_history }}
        </div>
        @endif
        @if($record->allergies)
        <div class="notes-box">
            <strong>Allergies</strong>
            {{ $record->allergies }}
        </div>
        @endif
        @if($record->current_medications)
        <div class="notes-box">
            <strong>Current Medications</strong>
            {{ $record->current_medications }}
        </div>
        @endif
    </div>
    @endif

    @if($record->chief_complaint || $record->diagnosis || $record->treatment_plan)
    <!-- Treatment Information -->
    <div class="section">
        <div class="section-header">
            <div class="section-title">⚕️ Treatment Information</div>
        </div>
        @if($record->chief_complaint)
        <div class="notes-box">
            <strong>Chief Complaint</strong>
            {{ $record->chief_complaint }}
        </div>
        @endif
        @if($record->diagnosis)
        <div class="notes-box">
            <strong>Diagnosis</strong>
            {{ $record->diagnosis }}
        </div>
        @endif
        @if($record->treatment_plan)
        <div class="notes-box">
            <strong>Treatment Plan</strong>
            {{ $record->treatment_plan }}
        </div>
        @endif
    </div>
    @endif

    @if($record->other_notes)
    <!-- Additional Notes -->
    <div class="section">
        <div class="section-header">
            <div class="section-title">📝 Additional Notes</div>
        </div>
        <div class="notes-box">
            {{ $record->other_notes }}
        </div>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <strong>JValera Dental Clinic</strong>
        <p>This is a computer-generated document. No signature is required.</p>
        <p>Document generated on {{ date('F d, Y \a\t h:i A') }}</p>
        <p>Record ID: {{ $record->id }} | Patient ID: {{ $record->user_id }}</p>
    </div>
</body>
</html>
