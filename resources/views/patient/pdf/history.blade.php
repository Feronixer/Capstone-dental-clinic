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
            font-size: 10pt;
            color: #1e293b;
            line-height: 1.6;
            padding: 50px;
            background: #ffffff;
        }

        @page {
            size: legal;
            margin: 0;
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
        }

        .btn-print:hover {
            background: linear-gradient(135deg, #0a58ca 0%, #084298 100%);
            box-shadow: 0 6px 12px rgba(13, 110, 253, 0.4);
        }

        /* Header Section */
        .header {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            color: white;
            padding: 1.5rem 0.5in 1.25rem 0.5in;
            margin-bottom: 1.5rem;
            text-align: center;
            width: 100%;
            margin-top: -0.25in;
        }

        .header .clinic-name {
            font-size: 1.1rem;
            font-weight: 700;
            margin-bottom: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header .clinic-address {
            font-size: 0.75rem;
            color: #cfe2ff;
            margin-bottom: 0.5rem;
            line-height: 1.4;
        }

        .header .clinic-contact {
            font-size: 0.7rem;
            color: #cfe2ff;
            opacity: 0.95;
            line-height: 1.4;
            margin-bottom: 0;
        }

        /* Top Section with Patient Name, Service, and Created By */
        .top-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.5rem;
            gap: 1.5rem;
        }

        .patient-info-left {
            flex: 1;
        }

        .patient-info-left .field {
            margin-bottom: 0.75rem;
        }

        .patient-info-left label {
            display: block;
            font-weight: 700;
            font-size: 0.9rem;
            color: #000000;
            margin-bottom: 0.25rem;
        }

        .patient-info-left input {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #007bff;
            border-bottom: 1px solid #007bff;
            font-size: 0.9rem;
            background: transparent;
        }

        .created-by-box {
            width: 250px;
            border: 2px solid #007bff;
            border-radius: 4px;
            padding: 0.75rem;
            margin-top: 0.23in;
            background: #ffffff;
        }

        .created-by-box .label {
            font-weight: 700;
            font-size: 0.85rem;
            color: #000000;
            margin-bottom: 0.25rem;
        }

        .created-by-box .name {
            font-size: 0.9rem;
            color: #000000;
            margin-bottom: 0.15rem;
        }

        .created-by-box .role {
            font-size: 0.8rem;
            color: #000000;
            margin-bottom: 0.15rem;
        }

        .created-by-box .date {
            font-size: 0.75rem;
            color: #000000;
        }

        /* Section Styles */
        .section {
            margin-bottom: 1.5rem;
            page-break-inside: avoid;
        }

        .section-title {
            font-weight: 700;
            font-size: 0.95rem;
            color: #000000;
            margin-bottom: 0.5rem;
            padding-bottom: 0.5rem;
            border-bottom: 1px solid #007bff;
            text-transform: uppercase;
        }

        .section-content {
            padding: 0.5rem 0;
        }

        /* Form Fields */
        .form-field {
            margin-bottom: 1.5rem;
        }

        .form-field label {
            display: block;
            font-weight: 700;
            font-size: 0.9rem;
            color: #000000;
            margin-bottom: 0.25rem;
        }

        .form-field input,
        .form-field textarea {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #007bff;
            border-bottom: 1px solid #007bff;
            font-size: 0.9rem;
            background: transparent;
            font-family: inherit;
        }

        /* Dental History Grid - 3 columns */
        .dental-history-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1rem;
            margin-top: 0.5rem;
        }

        .dental-history-field {
            display: flex;
            flex-direction: column;
        }

        .dental-history-field label {
            display: block;
            font-weight: 700;
            font-size: 0.9rem;
            color: #000000;
            margin-bottom: 0.25rem;
        }

        .dental-history-field input {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #007bff;
            border-bottom: 1px solid #007bff;
            font-size: 0.9rem;
            background: transparent;
        }

        /* Medical History Grid - 2 columns */
        .medical-history-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-top: 0.5rem;
        }

        .medical-history-field {
            display: flex;
            flex-direction: column;
        }

        .medical-history-field label {
            display: block;
            font-weight: 700;
            font-size: 0.9rem;
            color: #000000;
            margin-bottom: 0.25rem;
        }

        .medical-history-field input {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #007bff;
            border-bottom: 1px solid #007bff;
            font-size: 0.9rem;
            background: transparent;
        }

        .form-field textarea {
            min-height: 60px;
            resize: vertical;
        }

        /* Health Questions */
        .health-question {
            margin-bottom: 1.5rem;
            padding-bottom: 0.75rem;
            border-bottom: 1px solid #007bff;
        }

        .health-question:last-child {
            border-bottom: none;
        }

        .question-row {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 0.5rem;
        }

        .question-text {
            font-weight: 700;
            font-size: 0.9rem;
            color: #000000;
        }

        .answer-buttons {
            display: flex;
            gap: 1rem;
        }

        .answer-button {
            display: flex;
            align-items: center;
            gap: 0.25rem;
        }

        .answer-button input[type="radio"] {
            width: auto;
            margin: 0;
        }

        .answer-button label {
            font-weight: 700;
            font-size: 0.9rem;
            color: #000000;
            margin: 0;
            cursor: pointer;
        }

        .conditional-detail {
            margin-top: 0.5rem;
            padding-left: 1rem;
            font-style: italic;
            font-size: 0.85rem;
            color: #000000;
        }

        .conditional-detail input {
            width: 100%;
            padding: 0.5rem;
            border: 1px solid #007bff;
            border-bottom: 1px solid #007bff;
            font-size: 0.85rem;
            background: transparent;
            margin-top: 0.25rem;
        }

        /* Allergies Section */
        .allergies-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 1rem;
            margin-top: 0.5rem;
        }

        .allergy-item {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-bottom: 0.5rem;
        }

        .allergy-item input[type="checkbox"] {
            width: auto;
            margin: 0;
        }

        .allergy-item label {
            font-weight: 700;
            font-size: 0.9rem;
            color: #000000;
            margin: 0;
            cursor: pointer;
        }

        .allergy-item input[type="text"] {
            flex: 1;
            padding: 0.25rem;
            border: 1px solid #007bff;
            border-bottom: 1px solid #007bff;
            font-size: 0.85rem;
            background: transparent;
        }

        /* For Women Section */
        .women-section {
            margin-top: 1rem;
        }

        .women-question {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 0.5rem 0;
            border-bottom: 1px solid #007bff;
        }

        .women-question:last-child {
            border-bottom: none;
        }

        .women-question-text {
            font-weight: 700;
            font-size: 0.9rem;
            color: #000000;
        }

        .women-answer-buttons {
            display: flex;
            gap: 1rem;
        }

         /* Footer/Signature Section */
         .footer {
            margin-top: 1.5rem;
            text-align: right;
           
        }

        .signature-line {
            border-top: 1px solid #000000;
            width: 200px;
            margin-left: auto;
            margin-bottom: 0.5rem;
        }

        .footer .name {
            font-size: 0.9rem;
            color: #000000;
            margin-bottom: 0.5rem;
        }

        .footer .role {
            font-weight: 700;
            font-size: 0.9rem;
            color: #000000;
            margin-bottom: 0.5rem;
        }
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                padding: 0.5in;
            }
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
        <div class="clinic-name">JVALERA DENTAL CLINIC</div>
        <div class="clinic-address">0190 Policapio St. Gen T. Deleon Valenzuela City</div>
        <div class="clinic-contact">No: +63 15 622 9695 | FB: JValera Dental Clinic | EMAIL: jvaleradentalclinic@gmail.com</div>
    </div>

    <!-- Top Section: Patient Name, Service, Created By -->
    <div class="top-section">
        <div class="patient-info-left">
            <div class="field">
                <label>Patient Name:</label>
                <input type="text" value="{{ $history->patientRecord && $history->patientRecord->user && $history->patientRecord->user->info ? $history->patientRecord->user->info->first_name . ' ' . $history->patientRecord->user->info->last_name : ($history->patientRecord && $history->patientRecord->user ? $history->patientRecord->user->username : 'N/A') }}" readonly>
            </div>
            <div class="field">
                <label>Service:</label>
                <input type="text" value="{{ $history->patientRecord && $history->patientRecord->appointment && $history->patientRecord->appointment->service ? $history->patientRecord->appointment->service->service_name : 'N/A' }}" readonly>
            </div>
        </div>
        <div class="created-by-box">
            <div class="label">Created by:</div>
            @if($creator)
            <div class="name">{{ $creator->info ? $creator->info->first_name . ' ' . $creator->info->last_name : $creator->username }}</div>
            <div class="role">{{ ucfirst($creatorRole ?? 'Staff') }}</div>
            @else
            <div class="name">N/A</div>
            <div class="role">Staff</div>
            @endif
            <div class="date">{{ $history->created_at ? \Carbon\Carbon::parse($history->created_at)->format('F d, Y, h:i A') : 'N/A' }}</div>
        </div>
    </div>

    <!-- DENTAL HISTORY Section -->
    <div class="section">
        <div class="section-title">DENTAL HISTORY</div>
        <div class="section-content">
            <div class="dental-history-grid">
                <div class="dental-history-field">
                    <label>Previous Dentist</label>
                    <input type="text" value="{{ $history->previous_dentist ?? '' }}" readonly>
                </div>
                <div class="dental-history-field">
                    <label>Last dental visit</label>
                    <input type="text" value="{{ $history->last_dental_visit ? \Carbon\Carbon::parse($history->last_dental_visit)->format('m/d/Y') : '' }}" readonly>
                </div>
                <div class="dental-history-field">
                    <label>Treatment done</label>
                    <input type="text" value="{{ $history->treatment_done ?? '' }}" readonly>
                </div>
            </div>
        </div>
    </div>

    <!-- MEDICAL HISTORY Section -->
    <div class="section">
        <div class="section-title">MEDICAL HISTORY</div>
        <div class="section-content">
            <div class="medical-history-grid">
                <div class="medical-history-field">
                    <label>Name of Physician</label>
                    <input type="text" value="{{ $history->physician_name ?? '' }}" readonly>
                </div>
                <div class="medical-history-field">
                    <label>Specialty</label>
                    <input type="text" value="{{ $history->physician_specialty ?? '' }}" readonly>
                </div>
                <div class="medical-history-field">
                    <label>Office Address</label>
                    <input type="text" value="{{ $history->physician_office_address ?? '' }}" readonly>
                </div>
                <div class="medical-history-field">
                    <label>Contact No.</label>
                    <input type="text" value="{{ $history->physician_contact ?? '' }}" readonly>
                </div>
            </div>
        </div>
    </div>

    <!-- Health Questions Section -->
    <div class="section">
        <div class="health-question">
            <div class="question-row">
                <span class="question-text">1. Are you in good health?</span>
                <div class="answer-buttons">
                    <div class="answer-button">
                        <input type="radio" {{ strtolower($history->good_health ?? '') === 'yes' ? 'checked' : '' }} disabled>
                        <label>YES</label>
                    </div>
                    <div class="answer-button">
                        <input type="radio" {{ strtolower($history->good_health ?? '') === 'no' ? 'checked' : '' }} disabled>
                        <label>NO</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="health-question">
            <div class="question-row">
                <span class="question-text">2. Are you under any medical treatment now?</span>
                <div class="answer-buttons">
                    <div class="answer-button">
                        <input type="radio" {{ strtolower($history->under_treatment ?? '') === 'yes' ? 'checked' : '' }} disabled>
                        <label>YES</label>
                    </div>
                    <div class="answer-button">
                        <input type="radio" {{ strtolower($history->under_treatment ?? '') === 'no' ? 'checked' : '' }} disabled>
                        <label>NO</label>
                    </div>
                </div>
            </div>
            @if(strtolower($history->under_treatment ?? '') === 'yes' && $history->treatment_condition)
            <div class="conditional-detail">
                <label>If yes, what condition is being treated?</label>
                <input type="text" value="{{ $history->treatment_condition }}" readonly>
            </div>
            @endif
        </div>

        <div class="health-question">
            <div class="question-row">
                <span class="question-text">3. Have you ever had any serious illness or surgery?</span>
                <div class="answer-buttons">
                    <div class="answer-button">
                        <input type="radio" {{ strtolower($history->serious_illness ?? '') === 'yes' ? 'checked' : '' }} disabled>
                        <label>YES</label>
                    </div>
                    <div class="answer-button">
                        <input type="radio" {{ strtolower($history->serious_illness ?? '') === 'no' ? 'checked' : '' }} disabled>
                        <label>NO</label>
                    </div>
                </div>
            </div>
            @if(strtolower($history->serious_illness ?? '') === 'yes' && $history->illness_details)
            <div class="conditional-detail">
                <label>If yes, what illness or surgery?</label>
                <input type="text" value="{{ $history->illness_details }}" readonly>
            </div>
            @endif
        </div>

        <div class="health-question">
            <div class="question-row">
                <span class="question-text">4. Have you ever been hospitalized?</span>
                <div class="answer-buttons">
                    <div class="answer-button">
                        <input type="radio" {{ strtolower($history->been_hospitalized ?? '') === 'yes' ? 'checked' : '' }} disabled>
                        <label>YES</label>
                    </div>
                    <div class="answer-button">
                        <input type="radio" {{ strtolower($history->been_hospitalized ?? '') === 'no' ? 'checked' : '' }} disabled>
                        <label>NO</label>
                    </div>
                </div>
            </div>
            @if(strtolower($history->been_hospitalized ?? '') === 'yes' && $history->hospitalization_reason)
            <div class="conditional-detail">
                <label>If yes, when and why?</label>
                <input type="text" value="{{ $history->hospitalization_reason }}" readonly>
            </div>
            @endif
        </div>

        <div class="health-question">
            <div class="question-row">
                <span class="question-text">5. Are you taking any prescription or non prescription drugs?</span>
                <div class="answer-buttons">
                    <div class="answer-button">
                        <input type="radio" {{ strtolower($history->taking_drugs ?? '') === 'yes' ? 'checked' : '' }} disabled>
                        <label>YES</label>
                    </div>
                    <div class="answer-button">
                        <input type="radio" {{ strtolower($history->taking_drugs ?? '') === 'no' ? 'checked' : '' }} disabled>
                        <label>NO</label>
                    </div>
                </div>
            </div>
            @if(strtolower($history->taking_drugs ?? '') === 'yes' && $history->medications)
            <div class="conditional-detail">
                <label>If yes, what medication?</label>
                <input type="text" value="{{ $history->medications }}" readonly>
            </div>
            @endif
        </div>

        <div class="health-question">
            <div class="question-row">
                <span class="question-text">6. Do you use any tobacco products?</span>
                <div class="answer-buttons">
                    <div class="answer-button">
                        <input type="radio" {{ strtolower($history->tobacco_use ?? '') === 'yes' ? 'checked' : '' }} disabled>
                        <label>YES</label>
                    </div>
                    <div class="answer-button">
                        <input type="radio" {{ strtolower($history->tobacco_use ?? '') === 'no' ? 'checked' : '' }} disabled>
                        <label>NO</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="health-question">
            <div class="question-row">
                <span class="question-text">7. Do you drink alcoholic beverages?</span>
                <div class="answer-buttons">
                    <div class="answer-button">
                        <input type="radio" {{ strtolower($history->alcohol_use ?? '') === 'yes' ? 'checked' : '' }} disabled>
                        <label>YES</label>
                    </div>
                    <div class="answer-button">
                        <input type="radio" {{ strtolower($history->alcohol_use ?? '') === 'no' ? 'checked' : '' }} disabled>
                        <label>NO</label>
                    </div>
                </div>
            </div>
        </div>

        <div class="health-question">
            <div class="question-row">
                <span class="question-text">8. Do you take any recreational drugs?</span>
                <div class="answer-buttons">
                    <div class="answer-button">
                        <input type="radio" {{ strtolower($history->recreational_drugs ?? '') === 'yes' ? 'checked' : '' }} disabled>
                        <label>YES</label>
                    </div>
                    <div class="answer-button">
                        <input type="radio" {{ strtolower($history->recreational_drugs ?? '') === 'no' ? 'checked' : '' }} disabled>
                        <label>NO</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Allergies Section -->
    <div class="section">
        <div class="section-title">9. Are you allergic to the following:</div>
        <div class="section-content">
            <div class="allergies-grid">
                <div class="allergy-item">
                    <input type="checkbox" {{ $history->allergy_anesthesia ? 'checked' : '' }} disabled>
                    <label>Local Anesthesia (e.g., Lidocaine)</label>
                </div>
                <div class="allergy-item">
                    <input type="checkbox" {{ $history->allergy_sulfa ? 'checked' : '' }} disabled>
                    <label>Sulfa drugs</label>
                </div>
                <div class="allergy-item">
                    <input type="checkbox" {{ $history->allergy_antibiotics ? 'checked' : '' }} disabled>
                    <label>Antibiotics (e.g., Amoxicillin)</label>
                </div>
                <div class="allergy-item">
                    <input type="checkbox" {{ $history->allergy_aspirin ? 'checked' : '' }} disabled>
                    <label>Aspirin</label>
                </div>
                <div class="allergy-item">
                    <input type="checkbox" {{ $history->allergy_analgesics ? 'checked' : '' }} disabled>
                    <label>Analgesics (e.g., Mefenamic Acid)</label>
                </div>
                <div class="allergy-item">
                    <input type="checkbox" {{ $history->allergy_latex ? 'checked' : '' }} disabled>
                    <label>Latex (e.g., Gloves)</label>
                </div>
                <div class="allergy-item">
                    <input type="checkbox" {{ $history->food_allergy_details ? 'checked' : '' }} disabled>
                    <label>Food (Please specify:</label>
                    <input type="text" value="{{ $history->food_allergy_details ?? '' }}" readonly>
                    <span>)</span>
                </div>
                <div class="allergy-item">
                    <input type="checkbox" {{ $history->other_allergy_details ? 'checked' : '' }} disabled>
                    <label>Others (Please specify:</label>
                    <input type="text" value="{{ $history->other_allergy_details ?? '' }}" readonly>
                    <span>)</span>
                </div>
            </div>
        </div>
    </div>

    <!-- FOR WOMEN Section -->
    <div class="section women-section">
        <div class="section-title">FOR WOMEN</div>
        <div class="section-content">
            <div class="women-question">
                <span class="women-question-text">1. Are you pregnant?</span>
                <div class="women-answer-buttons">
                    <div class="answer-button">
                        <input type="radio" {{ strtolower($history->is_pregnant ?? '') === 'yes' ? 'checked' : '' }} disabled>
                        <label>YES</label>
                    </div>
                    <div class="answer-button">
                        <input type="radio" {{ strtolower($history->is_pregnant ?? '') === 'no' ? 'checked' : '' }} disabled>
                        <label>NO</label>
                    </div>
                </div>
            </div>
            <div class="women-question">
                <span class="women-question-text">2. Are you currently nursing?</span>
                <div class="women-answer-buttons">
                    <div class="answer-button">
                        <input type="radio" {{ strtolower($history->is_nursing ?? '') === 'yes' ? 'checked' : '' }} disabled>
                        <label>YES</label>
                    </div>
                    <div class="answer-button">
                        <input type="radio" {{ strtolower($history->is_nursing ?? '') === 'no' ? 'checked' : '' }} disabled>
                        <label>NO</label>
                    </div>
                </div>
            </div>
            <div class="women-question">
                <span class="women-question-text">3. Are you currently taking birth control pills?</span>
                <div class="women-answer-buttons">
                    <div class="answer-button">
                        <input type="radio" {{ strtolower($history->birth_control ?? '') === 'yes' ? 'checked' : '' }} disabled>
                        <label>YES</label>
                    </div>
                    <div class="answer-button">
                        <input type="radio" {{ strtolower($history->birth_control ?? '') === 'no' ? 'checked' : '' }} disabled>
                        <label>NO</label>
                    </div>
                </div>
            </div>
        </div>
    </div>

     <!-- Footer/Signature -->
     <div class="footer">
        <div class="signature-line"></div>
        <div class="name">Doc. Justine Valera</div>
        <div class="role">Lead Dentist</div>
    </div>
</body>
</html>

