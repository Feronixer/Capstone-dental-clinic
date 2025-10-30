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

        .footer {
            margin-top: 3rem;
            padding-top: 1.5rem;
            border-top: 2px solid #e2e8f0;
            text-align: center;
            color: #64748b;
            font-size: 0.85rem;
            line-height: 1.6;
        }

        .footer .clinic-name {
            font-weight: 700;
            color: #2196F3;
            margin-bottom: 0.5rem;
        }

        .footer p {
            margin: 0.25rem 0;
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
        <div class="section-title">Physician Information</div>
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

    @if($history->good_health || $history->under_treatment || $history->serious_illness || $history->been_hospitalized || $history->taking_drugs || $history->tobacco_use || $history->alcohol_use || $history->recreational_drugs)
    <div class="section">
        <div class="section-title">Health Questions</div>
        <div class="field-grid">
            @if($history->good_health)
            <div class="field">
                <span class="field-label">Good Health</span>
                <span class="field-value">{{ ucfirst($history->good_health) }}</span>
            </div>
            @endif
            @if($history->under_treatment)
            <div class="field">
                <span class="field-label">Under Treatment</span>
                <span class="field-value">{{ ucfirst($history->under_treatment) }}</span>
            </div>
            @endif
            @if($history->treatment_condition)
            <div class="field field-full">
                <span class="field-label">Condition</span>
                <span class="field-value">{{ $history->treatment_condition }}</span>
            </div>
            @endif
            @if($history->serious_illness)
            <div class="field">
                <span class="field-label">Serious Illness</span>
                <span class="field-value">{{ ucfirst($history->serious_illness) }}</span>
            </div>
            @endif
            @if($history->illness_details)
            <div class="field field-full">
                <span class="field-label">Illness Details</span>
                <span class="field-value">{{ $history->illness_details }}</span>
            </div>
            @endif
            @if($history->been_hospitalized)
            <div class="field">
                <span class="field-label">Been Hospitalized</span>
                <span class="field-value">{{ ucfirst($history->been_hospitalized) }}</span>
            </div>
            @endif
            @if($history->hospitalization_reason)
            <div class="field field-full">
                <span class="field-label">Hospitalization Reason</span>
                <span class="field-value">{{ $history->hospitalization_reason }}</span>
            </div>
            @endif
            @if($history->taking_drugs)
            <div class="field">
                <span class="field-label">Taking Medications</span>
                <span class="field-value">{{ ucfirst($history->taking_drugs) }}</span>
            </div>
            @endif
            @if($history->medications)
            <div class="field field-full">
                <span class="field-label">Medications</span>
                <span class="field-value">{{ $history->medications }}</span>
            </div>
            @endif
            @if($history->tobacco_use)
            <div class="field">
                <span class="field-label">Tobacco Use</span>
                <span class="field-value">{{ ucfirst($history->tobacco_use) }}</span>
            </div>
            @endif
            @if($history->alcohol_use)
            <div class="field">
                <span class="field-label">Alcohol Use</span>
                <span class="field-value">{{ ucfirst($history->alcohol_use) }}</span>
            </div>
            @endif
            @if($history->recreational_drugs)
            <div class="field">
                <span class="field-label">Recreational Drugs</span>
                <span class="field-value">{{ ucfirst($history->recreational_drugs) }}</span>
            </div>
            @endif
        </div>
    </div>
    @endif

    @if($history->allergy_anesthesia || $history->allergy_sulfa || $history->allergy_antibiotics || $history->allergy_aspirin || $history->allergy_analgesics || $history->allergy_latex || $history->food_allergy_details || $history->other_allergy_details)
    <div class="section">
        <div class="section-title">Allergies</div>
        <div class="field-grid">
            @if($history->allergy_anesthesia)
            <div class="field">
                <span class="field-label">Local Anesthesia</span>
                <span class="field-value">Yes</span>
            </div>
            @endif
            @if($history->allergy_sulfa)
            <div class="field">
                <span class="field-label">Sulfa Drugs</span>
                <span class="field-value">Yes</span>
            </div>
            @endif
            @if($history->allergy_antibiotics)
            <div class="field">
                <span class="field-label">Antibiotics</span>
                <span class="field-value">Yes</span>
            </div>
            @endif
            @if($history->allergy_aspirin)
            <div class="field">
                <span class="field-label">Aspirin</span>
                <span class="field-value">Yes</span>
            </div>
            @endif
            @if($history->allergy_analgesics)
            <div class="field">
                <span class="field-label">Analgesics</span>
                <span class="field-value">Yes</span>
            </div>
            @endif
            @if($history->allergy_latex)
            <div class="field">
                <span class="field-label">Latex</span>
                <span class="field-value">Yes</span>
            </div>
            @endif
            @if($history->food_allergy_details)
            <div class="field field-full">
                <span class="field-label">Food Allergies</span>
                <span class="field-value">{{ $history->food_allergy_details }}</span>
            </div>
            @endif
            @if($history->other_allergy_details)
            <div class="field field-full">
                <span class="field-label">Other Allergies</span>
                <span class="field-value">{{ $history->other_allergy_details }}</span>
            </div>
            @endif
        </div>
    </div>
    @endif

    @if($history->is_pregnant || $history->is_nursing || $history->birth_control)
    <div class="section">
        <div class="section-title">For Women</div>
        <div class="field-grid">
            @if($history->is_pregnant)
            <div class="field">
                <span class="field-label">Pregnant</span>
                <span class="field-value">{{ ucfirst($history->is_pregnant) }}</span>
            </div>
            @endif
            @if($history->is_nursing)
            <div class="field">
                <span class="field-label">Nursing</span>
                <span class="field-value">{{ ucfirst($history->is_nursing) }}</span>
            </div>
            @endif
            @if($history->birth_control)
            <div class="field">
                <span class="field-label">Taking Birth Control</span>
                <span class="field-value">{{ ucfirst($history->birth_control) }}</span>
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

    <div class="footer">
        <div class="clinic-name">JValera Dental Clinic</div>
        <p>This is a computer-generated document. No signature is required.</p>
        <p>Document generated on {{ \Carbon\Carbon::now()->format('F d, Y') }} at {{ \Carbon\Carbon::now()->format('h:i A') }}</p>
        <p>Note ID: {{ $history->id }}</p>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>

