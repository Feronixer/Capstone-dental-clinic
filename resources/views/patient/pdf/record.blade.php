<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Patient Record - {{ $record->user && $record->user->info ? $record->user->info->first_name . ' ' . $record->user->info->last_name : 'Patient' }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: POPPINS;
            font-size: 10pt;
            color: #000000;
            line-height: 1.5;
            padding: 50px;
            background: #ffffff;
        }

        @page {
            size: A4;
            margin: 0.5inch;
        }

        @media print {
            .no-print {
                display: none !important;
            }
            body {
                padding: 50px;
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

        .header h1 {
            font-size: 1.1rem;
            font-weight: 700;
            color: white;
            margin-bottom: 0.75rem;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .header .clinic-info {
            text-align: center;
            font-size: 0.75rem;
            color: #cfe2ff;
            line-height: 1.4;
        }

        .header .clinic-info p {
            margin: 0.5rem 0;
        }

        .header .clinic-info p:last-child {
            margin-bottom: 0;
        }

        /* Top Section with Patient Name, Service, and Created By */
        .top-section {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            margin-bottom: 1.5rem;
            gap: 1rem;
        }

        .patient-info-left {
            flex: 1;
        }

        .created-by-box {
            width: 250px;
            border: 2px solid #007bff;
            border-radius: 4px;
            padding: 0.75rem;
            background: #ffffff;
            margin-top: 0.23in;
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
            border: 1px solid #007bff;
            border-radius: 2px;
            padding: 0.4rem;
            font-size: 0.9rem;
            color: #000000;
            background: #ffffff;
            font-family: sans-serif;
        }

        .form-field textarea {
            min-height: 60px;
            resize: vertical;
        }

        /* Horizontal Layout Fields */
        .form-row {
            display: flex;
            gap: 1rem;
            margin-bottom: 1.5rem;
        }

        .form-row .form-field {
            flex: 1;
            margin-bottom: 0;
        }

        .form-row .form-field.col-2 {
            flex: 2;
        }

        .form-row .form-field.col-3 {
            flex: 3;
        }

        .form-row .form-field.col-4 {
            flex: 4;
        }

        /* FOR MINORS Section */
        .minors-section {
            margin-top: 1.5rem;
            margin-bottom: 1.5rem;
            padding: 1rem;
            border: 2px solid #0d6efd;
            border-radius: 4px;
            background: #f8f9fa;
        }

        .minors-section .section-title {
            font-weight: 700;
            font-size: 1rem;
            color: #000000;
            margin-bottom: 2rem;
            text-transform: uppercase;
            letter-spacing: 0.5px;
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
        <h1>JVALERA DENTAL CLINIC</h1>
        <div class="clinic-info">
            <p>0190 Policapio St. Gen T. Deleon Valenzuela City</p>
            <p>No: +63 15 622 9695 | FB: JValera Dental Clinic | EMAIL: jvaleradentalclinic@gmail.com</p>
        </div>
    </div>

    <!-- Top Section: Patient Name, Service, Created By -->
    <div class="top-section">
        <div class="patient-info-left">
            <div class="form-field">
                <label>Patient Name:</label>
                <input type="text" value="{{ $record->user && $record->user->info ? $record->user->info->first_name . ' ' . $record->user->info->last_name : 'N/A' }}" readonly>
            </div>
            <div class="form-field">
                <label>Service:</label>
                <input type="text" value="{{ $record->appointment && $record->appointment->service ? $record->appointment->service->service_name : 'N/A' }}" readonly>
            </div>
        </div>
        <div class="created-by-box">
            <div class="label">Created by:</div>
            @if($creator && $creator->info)
                <div class="name">{{ $creator->info->first_name . ' ' . $creator->info->last_name }}</div>
                <div class="role">{{ ucfirst($creatorRole ?? 'Staff') }}</div>
            @else
                <div class="name">N/A</div>
                <div class="role">Staff</div>
            @endif
            <div class="date">{{ $record->created_at ? \Carbon\Carbon::parse($record->created_at)->format('F d, Y, h:i A') : 'N/A' }}</div>
        </div>
    </div>

    <!-- Home Address -->
    <div class="form-field">
        <label>Home Address</label>
        <input type="text" value="{{ $record->home_address ?? '' }}" readonly>
    </div>

    <!-- Date Of Birth, Age, Sex, Nickname -->
    <div class="form-row">
        <div class="form-field col-3">
            <label>Date Of Birth</label>
            <input type="text" value="{{ $record->date_of_birth ? \Carbon\Carbon::parse($record->date_of_birth)->format('m/d/Y') : '' }}" readonly>
        </div>
        <div class="form-field col-2">
            <label>Age</label>
            <input type="text" value="{{ $record->age ?? '' }}" readonly>
        </div>
        <div class="form-field col-2">
            <label>Sex</label>
            <input type="text" value="{{ $record->sex ?? '' }}" readonly>
        </div>
        <div class="form-field col-3">
            <label>Nickname</label>
            <input type="text" value="{{ $record->nickname ?? '' }}" readonly>
        </div>
    </div>

    <!-- Religion, Occupation, Contact -->
    <div class="form-row">
        <div class="form-field col-4">
            <label>Religion</label>
            <input type="text" value="{{ $record->religion ?? '' }}" readonly>
        </div>
        <div class="form-field col-4">
            <label>Occupation</label>
            <input type="text" value="{{ $record->occupation ?? '' }}" readonly>
        </div>
        <div class="form-field col-4">
            <label>Contact</label>
            <input type="text" value="{{ $record->contact ?? '' }}" readonly>
        </div>
    </div>

    <!-- FOR MINORS Section -->
    @if($record->guardian_name || $record->guardian_contact || $record->guardian_occupation)
    <div class="minors-section">
        <div class="section-title">FOR MINORS</div>
        <div class="form-row">
            <div class="form-field col-4">
                <label>Parent/Guardian's Name</label>
                <input type="text" value="{{ $record->guardian_name ?? '' }}" readonly>
            </div>
            <div class="form-field col-4">
                <label>Contact No.</label>
                <input type="text" value="{{ $record->guardian_contact ?? '' }}" readonly>
            </div>
            <div class="form-field col-4">
                <label>Occupation</label>
                <input type="text" value="{{ $record->guardian_occupation ?? '' }}" readonly>
            </div>
        </div>
    </div>
    @endif

    <!-- Other Notes -->
    <div class="form-field">
        <label>Other Notes</label>
        <textarea readonly>{{ $record->other_notes ?? '' }}</textarea>
    </div>

    <!-- Footer/Signature -->
    <div class="footer">
        <div class="signature-line"></div>
        <div class="name">Doc. Justine Valera</div>
        <div class="role">Lead Dentist</div>
    </div>
</body>
</html>
