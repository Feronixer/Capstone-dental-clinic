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
            background: #2196F3;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            font-size: 13px;
        }

        .btn-print:hover {
            background: #1976D2;
        }

        .page-title {
            font-size: 22px;
            font-weight: 700;
            color: #2C3E50;
            margin-bottom: 10px;
        }

        .title-underline {
            height: 3px;
            background: #2196F3;
            margin-bottom: 5px;
        }

        .title-underline-thin {
            height: 2px;
            background: #2196F3;
            margin-bottom: 25px;
        }

        .section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }

        .section-header {
            background: #f5f5f5;
            padding: 10px 12px;
            border-left: 4px solid #2196F3;
            margin-bottom: 15px;
        }

        .section-title {
            font-size: 14px;
            font-weight: 600;
            color: #2C3E50;
        }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px 30px;
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
        }

        .info-field.full-width {
            grid-column: 1 / -1;
        }

        .notes-box {
            background: #E3F2FD;
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
    <div class="page-title">Patient Medical Record</div>
    <div class="title-underline"></div>
    <div class="title-underline-thin"></div>

    <!-- Patient Information -->
    <div class="section">
        <div class="section-header">
            <div class="section-title">Patient Information</div>
        </div>
        <div class="info-grid">
            <div class="info-field">
                <div class="field-label">Patient Name:</div>
                <div class="field-value">{{ $record->user && $record->user->info ? $record->user->info->first_name . ' ' . $record->user->info->last_name : 'N/A' }}</div>
            </div>
            <div class="info-field">
                <div class="field-label">Sex:</div>
                <div class="field-value">{{ $record->sex ?? 'N/A' }}</div>
            </div>
            <div class="info-field">
                <div class="field-label">Date of Birth:</div>
                <div class="field-value">{{ $record->date_of_birth ? \Carbon\Carbon::parse($record->date_of_birth)->format('F d, Y') : 'N/A' }}</div>
            </div>
            <div class="info-field">
                <div class="field-label">Age:</div>
                <div class="field-value">{{ $record->age ?? 'N/A' }}</div>
            </div>
            <div class="info-field">
                <div class="field-label">Contact:</div>
                <div class="field-value">{{ $record->contact ?? 'N/A' }}</div>
            </div>
            <div class="info-field">
                <div class="field-label">Nickname:</div>
                <div class="field-value">{{ $record->nickname ?? 'N/A' }}</div>
            </div>
            <div class="info-field full-width">
                <div class="field-label">Home Address:</div>
                <div class="field-value">{{ $record->home_address ?? 'N/A' }}</div>
            </div>
            <div class="info-field">
                <div class="field-label">Religion:</div>
                <div class="field-value">{{ $record->religion ?? 'N/A' }}</div>
            </div>
            <div class="info-field">
                <div class="field-label">Occupation:</div>
                <div class="field-value">{{ $record->occupation ?? 'N/A' }}</div>
            </div>
        </div>
    </div>

    @if($record->guardian_name)
    <!-- Guardian Information -->
    <div class="section">
        <div class="section-header">
            <div class="section-title">Guardian Information</div>
        </div>
        <div class="info-grid">
            <div class="info-field">
                <div class="field-label">Guardian Name:</div>
                <div class="field-value">{{ $record->guardian_name }}</div>
            </div>
            <div class="info-field">
                <div class="field-label">Guardian Contact:</div>
                <div class="field-value">{{ $record->guardian_contact ?? 'N/A' }}</div>
            </div>
            <div class="info-field">
                <div class="field-label">Guardian Occupation:</div>
                <div class="field-value">{{ $record->guardian_occupation ?? 'N/A' }}</div>
            </div>
        </div>
    </div>
    @endif

    @if($record->physician_name)
    <!-- Physician Information -->
    <div class="section">
        <div class="section-header">
            <div class="section-title">Physician Information</div>
        </div>
        <div class="info-grid">
            <div class="info-field">
                <div class="field-label">Physician Name:</div>
                <div class="field-value">{{ $record->physician_name }}</div>
            </div>
            <div class="info-field">
                <div class="field-label">Specialty:</div>
                <div class="field-value">{{ $record->physician_specialty ?? 'N/A' }}</div>
            </div>
            <div class="info-field">
                <div class="field-label">Contact:</div>
                <div class="field-value">{{ $record->physician_contact ?? 'N/A' }}</div>
            </div>
            <div class="info-field full-width">
                <div class="field-label">Office Address:</div>
                <div class="field-value">{{ $record->physician_office_address ?? 'N/A' }}</div>
            </div>
        </div>
    </div>
    @endif

    @if($record->previous_dentist || $record->last_dental_visit || $record->treatment_done)
    <!-- Dental History -->
    <div class="section">
        <div class="section-header">
            <div class="section-title">Dental History</div>
        </div>
        <div class="info-grid">
            @if($record->previous_dentist)
            <div class="info-field">
                <div class="field-label">Previous Dentist:</div>
                <div class="field-value">{{ $record->previous_dentist }}</div>
            </div>
            <div class="info-field">
                <div class="field-label">Last Dental Visit:</div>
                <div class="field-value">{{ $record->last_dental_visit ? $record->last_dental_visit->format('m/d/Y') : 'N/A' }}</div>
            </div>
            @endif
            @if($record->treatment_done)
            <div class="info-field full-width">
                <div class="field-label">Treatment Done:</div>
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
            <div class="section-title">Medical Information</div>
        </div>
        @if($record->medical_history)
        <div class="notes-box" style="margin-bottom: 10px;">
            <strong style="display: block; margin-bottom: 5px;">Medical History:</strong>
            {{ $record->medical_history }}
        </div>
        @endif
        @if($record->allergies)
        <div class="notes-box" style="margin-bottom: 10px;">
            <strong style="display: block; margin-bottom: 5px;">Allergies:</strong>
            {{ $record->allergies }}
        </div>
        @endif
        @if($record->current_medications)
        <div class="notes-box">
            <strong style="display: block; margin-bottom: 5px;">Current Medications:</strong>
            {{ $record->current_medications }}
        </div>
        @endif
    </div>
    @endif

    @if($record->chief_complaint || $record->diagnosis || $record->treatment_plan)
    <!-- Treatment Information -->
    <div class="section">
        <div class="section-header">
            <div class="section-title">Treatment Information</div>
        </div>
        @if($record->chief_complaint)
        <div class="notes-box" style="margin-bottom: 10px;">
            <strong style="display: block; margin-bottom: 5px;">Chief Complaint:</strong>
            {{ $record->chief_complaint }}
        </div>
        @endif
        @if($record->diagnosis)
        <div class="notes-box" style="margin-bottom: 10px;">
            <strong style="display: block; margin-bottom: 5px;">Diagnosis:</strong>
            {{ $record->diagnosis }}
        </div>
        @endif
        @if($record->treatment_plan)
        <div class="notes-box">
            <strong style="display: block; margin-bottom: 5px;">Treatment Plan:</strong>
            {{ $record->treatment_plan }}
        </div>
        @endif
    </div>
    @endif

    @if($record->other_notes)
    <!-- Additional Notes -->
    <div class="section">
        <div class="section-header">
            <div class="section-title">Additional Notes</div>
        </div>
        <div class="notes-box">
            {{ $record->other_notes }}
        </div>
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p><strong>JValera Dental Clinic</strong></p>
        <p>This is a computer-generated document. No signature is required.</p>
        <p>Document generated on {{ date('F d, Y \a\t h:i A') }}</p>
        <p>Record ID: {{ $record->id }} | Patient ID: {{ $record->user_id }}</p>
    </div>
</body>
</html>

