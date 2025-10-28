<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Medical Record - {{ $record->patient_name ?? 'Patient' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            font-size: 12px;
            color: #2C3E50;
            line-height: 1.6;
            padding: 20px;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            body {
                padding: 10px;
            }
        }

        .print-button-container {
            position: fixed;
            top: 10px;
            right: 10px;
            z-index: 1000;
        }

        .btn-print {
            background: linear-gradient(135deg, #2196F3 0%, #1976D2 100%);
            color: white;
            border: none;
            padding: 12px 24px;
            border-radius: 6px;
            font-weight: 600;
            cursor: pointer;
            box-shadow: 0 4px 12px rgba(33, 150, 243, 0.3);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .btn-print:hover {
            background: linear-gradient(135deg, #1976D2 0%, #1565C0 100%);
            box-shadow: 0 6px 16px rgba(33, 150, 243, 0.4);
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 3px solid #2196F3;
        }

        .header h1 {
            color: #2196F3;
            font-size: 24px;
            margin-bottom: 5px;
        }

        .header p {
            color: #64748b;
            font-size: 11px;
        }

        .section {
            margin-bottom: 25px;
            page-break-inside: avoid;
        }

        .section-title {
            background: #eceff1;
            padding: 8px 12px;
            font-weight: bold;
            color: #2C3E50;
            font-size: 14px;
            margin-bottom: 15px;
            border-left: 4px solid #2196F3;
        }

        .info-grid {
            display: table;
            width: 100%;
            margin-bottom: 15px;
        }

        .info-row {
            display: table-row;
        }

        .info-cell {
            display: table-cell;
            padding: 8px 10px;
            width: 50%;
            vertical-align: top;
        }

        .info-label {
            font-weight: bold;
            color: #64748b;
            font-size: 10px;
            display: block;
            margin-bottom: 3px;
        }

        .info-value {
            color: #2C3E50;
            font-size: 12px;
        }

        .full-width {
            width: 100%;
        }

        .notes-box {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 4px;
            padding: 15px;
            margin-top: 10px;
        }

        .footer {
            margin-top: 50px;
            padding-top: 20px;
            border-top: 2px solid #e2e8f0;
            text-align: center;
            color: #64748b;
            font-size: 10px;
        }

        .clinic-info {
            background: #f8f9fa;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
        }

        .clinic-info h3 {
            color: #2196F3;
            font-size: 14px;
            margin-bottom: 5px;
        }

        .clinic-info p {
            font-size: 11px;
            color: #64748b;
            margin: 2px 0;
        }
    </style>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.13.1/font/bootstrap-icons.min.css">
</head>
<body>
    <!-- Print Button -->
    <div class="print-button-container no-print">
        <button class="btn-print" onclick="window.print()">
            <i class="bi bi-printer-fill"></i>
            <span>Print / Save as PDF</span>
        </button>
    </div>

    <!-- Header -->
    <div class="header">
        <h1>JValera Dental Clinic</h1>
        <p>0190 Policapio St. Gen T. Deleon Valenzuela City</p>
        <p>Phone: +63 15 622 9695 | Email: jvalera@dentalclinic.com</p>
    </div>

    <div style="text-align: center; margin-bottom: 30px;">
        <h2 style="color: #2C3E50; font-size: 18px; margin-bottom: 5px;">PATIENT MEDICAL RECORD</h2>
        <p style="color: #64748b; font-size: 11px;">Generated on {{ date('F d, Y') }}</p>
    </div>

    <!-- Patient Information -->
    <div class="section">
        <div class="section-title">PATIENT INFORMATION</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-cell">
                    <span class="info-label">Patient Number:</span>
                    <span class="info-value">{{ $record->patient_number ?? 'N/A' }}</span>
                </div>
                <div class="info-cell">
                    <span class="info-label">Sex:</span>
                    <span class="info-value">{{ $record->sex ?? 'N/A' }}</span>
                </div>
            </div>
            <div class="info-row">
                <div class="info-cell">
                    <span class="info-label">Date of Birth:</span>
                    <span class="info-value">{{ $record->date_of_birth ? $record->date_of_birth->format('m/d/Y') : 'N/A' }}</span>
                </div>
                <div class="info-cell">
                    <span class="info-label">Age:</span>
                    <span class="info-value">{{ $record->age ?? 'N/A' }}</span>
                </div>
            </div>
            <div class="info-row">
                <div class="info-cell">
                    <span class="info-label">Contact Number:</span>
                    <span class="info-value">{{ $record->contact ?? 'N/A' }}</span>
                </div>
                <div class="info-cell">
                    <span class="info-label">Nickname:</span>
                    <span class="info-value">{{ $record->nickname ?? 'N/A' }}</span>
                </div>
            </div>
            <div class="info-row">
                <div class="info-cell full-width" style="width: 100%;">
                    <span class="info-label">Home Address:</span>
                    <span class="info-value">{{ $record->home_address ?? 'N/A' }}</span>
                </div>
            </div>
            <div class="info-row">
                <div class="info-cell">
                    <span class="info-label">Religion:</span>
                    <span class="info-value">{{ $record->religion ?? 'N/A' }}</span>
                </div>
                <div class="info-cell">
                    <span class="info-label">Occupation:</span>
                    <span class="info-value">{{ $record->occupation ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>

    @if($record->guardian_name)
    <!-- Guardian Information -->
    <div class="section">
        <div class="section-title">GUARDIAN INFORMATION</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-cell">
                    <span class="info-label">Guardian Name:</span>
                    <span class="info-value">{{ $record->guardian_name }}</span>
                </div>
                <div class="info-cell">
                    <span class="info-label">Guardian Contact:</span>
                    <span class="info-value">{{ $record->guardian_contact ?? 'N/A' }}</span>
                </div>
            </div>
            <div class="info-row">
                <div class="info-cell">
                    <span class="info-label">Guardian Occupation:</span>
                    <span class="info-value">{{ $record->guardian_occupation ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($record->physician_name)
    <!-- Physician Information -->
    <div class="section">
        <div class="section-title">PHYSICIAN INFORMATION</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-cell">
                    <span class="info-label">Physician Name:</span>
                    <span class="info-value">{{ $record->physician_name }}</span>
                </div>
                <div class="info-cell">
                    <span class="info-label">Specialty:</span>
                    <span class="info-value">{{ $record->physician_specialty ?? 'N/A' }}</span>
                </div>
            </div>
            <div class="info-row">
                <div class="info-cell">
                    <span class="info-label">Contact:</span>
                    <span class="info-value">{{ $record->physician_contact ?? 'N/A' }}</span>
                </div>
            </div>
            <div class="info-row">
                <div class="info-cell full-width" style="width: 100%;">
                    <span class="info-label">Office Address:</span>
                    <span class="info-value">{{ $record->physician_office_address ?? 'N/A' }}</span>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if($record->previous_dentist || $record->last_dental_visit || $record->treatment_done)
    <!-- Dental History -->
    <div class="section">
        <div class="section-title">DENTAL HISTORY</div>
        <div class="info-grid">
            @if($record->previous_dentist)
            <div class="info-row">
                <div class="info-cell">
                    <span class="info-label">Previous Dentist:</span>
                    <span class="info-value">{{ $record->previous_dentist }}</span>
                </div>
                <div class="info-cell">
                    <span class="info-label">Last Dental Visit:</span>
                    <span class="info-value">{{ $record->last_dental_visit ? $record->last_dental_visit->format('m/d/Y') : 'N/A' }}</span>
                </div>
            </div>
            @endif
            @if($record->treatment_done)
            <div class="info-row">
                <div class="info-cell full-width" style="width: 100%;">
                    <span class="info-label">Treatment Done:</span>
                    <span class="info-value">{{ $record->treatment_done }}</span>
                </div>
            </div>
            @endif
        </div>
    </div>
    @endif

    @if($record->medical_history || $record->allergies || $record->current_medications)
    <!-- Medical Information -->
    <div class="section">
        <div class="section-title">MEDICAL INFORMATION</div>
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
        <div class="section-title">TREATMENT INFORMATION</div>
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
        <div class="section-title">ADDITIONAL NOTES</div>
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

