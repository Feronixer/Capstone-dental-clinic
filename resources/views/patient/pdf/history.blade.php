<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Patient Medical History - {{ $history->visit_date ? \Carbon\Carbon::parse($history->visit_date)->format('m/d/Y') : 'N/A' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            padding: 2rem;
            color: #2C3E50;
            line-height: 1.6;
        }

        .header {
            text-align: center;
            margin-bottom: 2rem;
            padding-bottom: 1rem;
            border-bottom: 3px solid #2196F3;
        }

        .header h1 {
            color: #2196F3;
            font-size: 2rem;
            margin-bottom: 0.5rem;
        }

        .header p {
            color: #64748b;
            font-size: 1rem;
        }

        .section {
            margin-bottom: 2rem;
            page-break-inside: avoid;
        }

        .section-title {
            background: #2196F3;
            color: white;
            padding: 0.75rem 1rem;
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 1rem;
            text-transform: uppercase;
        }

        .field-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
        }

        .field {
            padding: 0.75rem;
            background: #f8f9fa;
            border-left: 4px solid #2196F3;
        }

        .field-full {
            grid-column: span 2;
        }

        .field-label {
            display: block;
            font-weight: 700;
            color: #475569;
            font-size: 0.85rem;
            margin-bottom: 0.25rem;
            text-transform: uppercase;
        }

        .field-value {
            display: block;
            color: #2C3E50;
            font-size: 1rem;
        }

        @media print {
            body {
                padding: 1rem;
            }

            .no-print {
                display: none !important;
            }
        }

        @page {
            margin: 1cm;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Patient Medical History</h1>
        <p>Visit Date: {{ $history->visit_date ? \Carbon\Carbon::parse($history->visit_date)->format('F d, Y') : 'Not Specified' }}</p>
        @if($history->patientRecord && $history->patientRecord->user)
            <p>Patient: {{ $history->patientRecord->user->name ?? 'N/A' }}</p>
        @endif
    </div>

    @if($history->previous_dentist || $history->last_dental_visit || $history->treatment_done)
    <div class="section">
        <div class="section-title">Dental History</div>
        <div class="field-grid">
            @if($history->previous_dentist)
            <div class="field">
                <span class="field-label">Previous Dentist</span>
                <span class="field-value">{{ $history->previous_dentist }}</span>
            </div>
            @endif
            @if($history->last_dental_visit)
            <div class="field">
                <span class="field-label">Last Dental Visit</span>
                <span class="field-value">{{ \Carbon\Carbon::parse($history->last_dental_visit)->format('m/d/Y') }}</span>
            </div>
            @endif
            @if($history->treatment_done)
            <div class="field field-full">
                <span class="field-label">Treatment Done</span>
                <span class="field-value">{{ $history->treatment_done }}</span>
            </div>
            @endif
        </div>
    </div>
    @endif

    @if($history->physician_name || $history->physician_specialty || $history->physician_office_address || $history->physician_contact)
    <div class="section">
        <div class="section-title">Medical History</div>
        <div class="field-grid">
            @if($history->physician_name)
            <div class="field">
                <span class="field-label">Physician Name</span>
                <span class="field-value">{{ $history->physician_name }}</span>
            </div>
            @endif
            @if($history->physician_specialty)
            <div class="field">
                <span class="field-label">Specialty</span>
                <span class="field-value">{{ $history->physician_specialty }}</span>
            </div>
            @endif
            @if($history->physician_office_address)
            <div class="field field-full">
                <span class="field-label">Office Address</span>
                <span class="field-value">{{ $history->physician_office_address }}</span>
            </div>
            @endif
            @if($history->physician_contact)
            <div class="field">
                <span class="field-label">Contact Number</span>
                <span class="field-value">{{ $history->physician_contact }}</span>
            </div>
            @endif
        </div>
    </div>
    @endif

    @if($history->procedure_performed || $history->anesthesia_used || $history->materials_used || $history->complications || $history->post_operative_instructions || $history->follow_up_notes)
    <div class="section">
        <div class="section-title">Procedure Details</div>
        <div class="field-grid">
            @if($history->anesthesia_used)
            <div class="field">
                <span class="field-label">Anesthesia Used</span>
                <span class="field-value">{{ $history->anesthesia_used }}</span>
            </div>
            @endif
            @if($history->procedure_performed)
            <div class="field field-full">
                <span class="field-label">Procedure Performed</span>
                <span class="field-value">{{ $history->procedure_performed }}</span>
            </div>
            @endif
            @if($history->materials_used)
            <div class="field field-full">
                <span class="field-label">Materials Used</span>
                <span class="field-value">{{ $history->materials_used }}</span>
            </div>
            @endif
            @if($history->complications)
            <div class="field field-full">
                <span class="field-label">Complications</span>
                <span class="field-value">{{ $history->complications }}</span>
            </div>
            @endif
            @if($history->post_operative_instructions)
            <div class="field field-full">
                <span class="field-label">Post-Operative Instructions</span>
                <span class="field-value">{{ $history->post_operative_instructions }}</span>
            </div>
            @endif
            @if($history->follow_up_notes)
            <div class="field field-full">
                <span class="field-label">Follow-up Notes</span>
                <span class="field-value">{{ $history->follow_up_notes }}</span>
            </div>
            @endif
        </div>
    </div>
    @endif

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>

