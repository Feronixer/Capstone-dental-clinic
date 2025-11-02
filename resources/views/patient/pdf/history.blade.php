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
            padding: 0.75rem 1rem;
            color: #1e293b;
            line-height: 1.7;
            font-size: 10pt;
            background: #ffffff;
        }

        @page {
            size: legal;
            margin: 0.75in;
        }

        .header {
            text-align: center;
            margin-bottom: 1.5rem;
            padding-bottom: 1rem;
            border-bottom: 4px solid #0d6efd;
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            padding: 1.25rem 1rem;
            border-radius: 8px 8px 0 0;
            margin: -0.75in -0.75in 1.5rem -0.75in;
        }

        .header h1 {
            color: #ffffff;
            font-size: 1.75rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }

        .header p {
            color: #e0f2fe;
            font-size: 0.95rem;
            margin: 0.25rem 0;
        }

        .section {
            margin-bottom: 1.25rem;
            page-break-inside: avoid;
            background: #ffffff;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            overflow: hidden;
        }

        .section-title {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            color: white;
            padding: 0.625rem 1rem;
            font-size: 0.95rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .field-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 0.875rem;
            padding: 1rem;
        }

        .field {
            padding: 0.75rem;
            background: #f8fafc;
            border-left: 3px solid #0d6efd;
            border-radius: 4px;
            transition: all 0.2s ease;
        }

        .field:hover {
            background: #f1f5f9;
            box-shadow: 0 2px 4px rgba(13, 110, 253, 0.1);
        }

        .field-full {
            grid-column: span 2;
        }

        .field-label {
            display: block;
            font-weight: 700;
            color: #475569;
            font-size: 0.8rem;
            margin-bottom: 0.375rem;
            text-transform: uppercase;
            letter-spacing: 0.3px;
        }

        .field-value {
            display: block;
            color: #1e293b;
            font-size: 0.9rem;
            line-height: 1.5;
            word-wrap: break-word;
        }

        .conditional-field {
            padding-left: 1.5rem;
            border-left: 3px solid #10b981;
            background: #f0fdf4;
            margin-top: 0.5rem;
        }

        .conditional-field .field-label {
            font-style: italic;
            color: #64748b;
            font-size: 0.75rem;
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

        .footer .clinic-name {
            font-weight: 700;
            color: #0d6efd;
            font-size: 1rem;
            margin-bottom: 0.5rem;
        }

        .footer p {
            margin: 0.25rem 0;
        }

        @media print {
            body {
                padding: 0;
            }

            .header {
                margin: 0;
                border-radius: 0;
            }

            .footer {
                margin: 2rem 0 0 0;
                border-radius: 0;
            }

            .no-print {
                display: none !important;
            }

            .section {
                page-break-inside: avoid;
                break-inside: avoid;
            }
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>📋 Patient Medical History</h1>
        <p><strong>Visit Date:</strong> {{ $history->visit_date ? \Carbon\Carbon::parse($history->visit_date)->format('F d, Y') : 'Not Specified' }}</p>
        @if($history->patientRecord && $history->patientRecord->user)
            <p><strong>Patient:</strong> {{ $history->patientRecord->user->name ?? 'N/A' }}</p>
        @endif
    </div>

    @if($history->previous_dentist || $history->last_dental_visit || $history->treatment_done)
    <div class="section">
        <div class="section-title">🦷 Dental History</div>
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
                <span class="field-value">{{ \Carbon\Carbon::parse($history->last_dental_visit)->format('F d, Y') }}</span>
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
        <div class="section-title">👨‍⚕️ Physician Information</div>
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
        <div class="section-title">💊 Health Questions</div>
        <div class="field-grid">
            @if($history->good_health)
            <div class="field">
                <span class="field-label">Good Health</span>
                <span class="field-value"><strong style="color: {{ strtolower($history->good_health) === 'yes' ? '#10b981' : '#ef4444' }};">{{ ucfirst($history->good_health) }}</strong></span>
            </div>
            @endif
            @if($history->under_treatment)
            <div class="field">
                <span class="field-label">Under Treatment</span>
                <span class="field-value"><strong style="color: {{ strtolower($history->under_treatment) === 'yes' ? '#f59e0b' : '#10b981' }};">{{ ucfirst($history->under_treatment) }}</strong></span>
            </div>
            @endif
            @if($history->treatment_condition && strtolower($history->under_treatment ?? '') === 'yes')
            <div class="field field-full conditional-field">
                <span class="field-label">If Under Treatment (Yes), Condition:</span>
                <span class="field-value">{{ $history->treatment_condition }}</span>
            </div>
            @endif
            @if($history->serious_illness)
            <div class="field">
                <span class="field-label">Serious Illness</span>
                <span class="field-value"><strong style="color: {{ strtolower($history->serious_illness) === 'yes' ? '#ef4444' : '#10b981' }};">{{ ucfirst($history->serious_illness) }}</strong></span>
            </div>
            @endif
            @if($history->illness_details && strtolower($history->serious_illness ?? '') === 'yes')
            <div class="field field-full conditional-field">
                <span class="field-label">If Serious Illness (Yes), Illness Details:</span>
                <span class="field-value">{{ $history->illness_details }}</span>
            </div>
            @endif
            @if($history->been_hospitalized)
            <div class="field">
                <span class="field-label">Been Hospitalized</span>
                <span class="field-value"><strong style="color: {{ strtolower($history->been_hospitalized) === 'yes' ? '#ef4444' : '#10b981' }};">{{ ucfirst($history->been_hospitalized) }}</strong></span>
            </div>
            @endif
            @if($history->hospitalization_reason && strtolower($history->been_hospitalized ?? '') === 'yes')
            <div class="field field-full conditional-field">
                <span class="field-label">If Been Hospitalized (Yes), Reason:</span>
                <span class="field-value">{{ $history->hospitalization_reason }}</span>
            </div>
            @endif
            @if($history->taking_drugs)
            <div class="field">
                <span class="field-label">Taking Medications</span>
                <span class="field-value"><strong style="color: {{ strtolower($history->taking_drugs) === 'yes' ? '#f59e0b' : '#10b981' }};">{{ ucfirst($history->taking_drugs) }}</strong></span>
            </div>
            @endif
            @if($history->medications && strtolower($history->taking_drugs ?? '') === 'yes')
            <div class="field field-full conditional-field">
                <span class="field-label">If Taking Medications (Yes), Medications:</span>
                <span class="field-value">{{ $history->medications }}</span>
            </div>
            @endif
            @if($history->tobacco_use)
            <div class="field">
                <span class="field-label">Tobacco Use</span>
                <span class="field-value"><strong style="color: {{ strtolower($history->tobacco_use) === 'yes' ? '#ef4444' : '#10b981' }};">{{ ucfirst($history->tobacco_use) }}</strong></span>
            </div>
            @endif
            @if($history->alcohol_use)
            <div class="field">
                <span class="field-label">Alcohol Use</span>
                <span class="field-value"><strong style="color: {{ strtolower($history->alcohol_use) === 'yes' ? '#f59e0b' : '#10b981' }};">{{ ucfirst($history->alcohol_use) }}</strong></span>
            </div>
            @endif
            @if($history->recreational_drugs)
            <div class="field">
                <span class="field-label">Recreational Drugs</span>
                <span class="field-value"><strong style="color: {{ strtolower($history->recreational_drugs) === 'yes' ? '#ef4444' : '#10b981' }};">{{ ucfirst($history->recreational_drugs) }}</strong></span>
            </div>
            @endif
        </div>
    </div>
    @endif

    @if($history->allergy_anesthesia || $history->allergy_sulfa || $history->allergy_antibiotics || $history->allergy_aspirin || $history->allergy_analgesics || $history->allergy_latex || $history->food_allergy_details || $history->other_allergy_details)
    <div class="section">
        <div class="section-title">⚠️ Allergies</div>
        <div class="field-grid">
            @if($history->allergy_anesthesia)
            <div class="field">
                <span class="field-label">Local Anesthesia</span>
                <span class="field-value"><strong style="color: #ef4444;">Yes ⚠️</strong></span>
            </div>
            @endif
            @if($history->allergy_sulfa)
            <div class="field">
                <span class="field-label">Sulfa Drugs</span>
                <span class="field-value"><strong style="color: #ef4444;">Yes ⚠️</strong></span>
            </div>
            @endif
            @if($history->allergy_antibiotics)
            <div class="field">
                <span class="field-label">Antibiotics</span>
                <span class="field-value"><strong style="color: #ef4444;">Yes ⚠️</strong></span>
            </div>
            @endif
            @if($history->allergy_aspirin)
            <div class="field">
                <span class="field-label">Aspirin</span>
                <span class="field-value"><strong style="color: #ef4444;">Yes ⚠️</strong></span>
            </div>
            @endif
            @if($history->allergy_analgesics)
            <div class="field">
                <span class="field-label">Analgesics</span>
                <span class="field-value"><strong style="color: #ef4444;">Yes ⚠️</strong></span>
            </div>
            @endif
            @if($history->allergy_latex)
            <div class="field">
                <span class="field-label">Latex</span>
                <span class="field-value"><strong style="color: #ef4444;">Yes ⚠️</strong></span>
            </div>
            @endif
            @if($history->food_allergy_details)
            <div class="field field-full">
                <span class="field-label">Food Allergies</span>
                <span class="field-value"><strong style="color: #ef4444;">{{ $history->food_allergy_details }}</strong></span>
            </div>
            @endif
            @if($history->other_allergy_details)
            <div class="field field-full">
                <span class="field-label">Other Allergies</span>
                <span class="field-value"><strong style="color: #ef4444;">{{ $history->other_allergy_details }}</strong></span>
            </div>
            @endif
        </div>
    </div>
    @endif

    @if($history->is_pregnant || $history->is_nursing || $history->birth_control)
    <div class="section">
        <div class="section-title">👩 For Women</div>
        <div class="field-grid">
            @if($history->is_pregnant)
            <div class="field">
                <span class="field-label">Pregnant</span>
                <span class="field-value"><strong style="color: {{ strtolower($history->is_pregnant) === 'yes' ? '#ef4444' : '#10b981' }};">{{ ucfirst($history->is_pregnant) }}</strong></span>
            </div>
            @endif
            @if($history->is_nursing)
            <div class="field">
                <span class="field-label">Nursing</span>
                <span class="field-value"><strong style="color: {{ strtolower($history->is_nursing) === 'yes' ? '#f59e0b' : '#10b981' }};">{{ ucfirst($history->is_nursing) }}</strong></span>
            </div>
            @endif
            @if($history->birth_control)
            <div class="field">
                <span class="field-label">Taking Birth Control</span>
                <span class="field-value"><strong style="color: {{ strtolower($history->birth_control) === 'yes' ? '#f59e0b' : '#10b981' }};">{{ ucfirst($history->birth_control) }}</strong></span>
            </div>
            @endif
        </div>
    </div>
    @endif

    @if($history->procedure_performed || $history->anesthesia_used || $history->materials_used || $history->complications || $history->post_operative_instructions || $history->follow_up_notes)
    <div class="section">
        <div class="section-title">⚕️ Procedure Details</div>
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
                <span class="field-value" style="color: #ef4444;"><strong>{{ $history->complications }}</strong></span>
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
        <p>History ID: {{ $history->id }}</p>
    </div>

    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>
