<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Progress Notes - {{ $record->user->info ? $record->user->info->first_name . ' ' . $record->user->info->last_name : $record->user->username }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 9pt;
            color: #1e293b;
            line-height: 1.6;
            padding: 0.75rem 1rem;
            background: #ffffff;
        }

        @page {
            size: legal landscape;
            margin: 0.5in;
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
            padding: 1.5rem 0.5in 1.25rem 0.5in;
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            color: white;
            border-radius: 0;
            margin: 0 -0.5in 1.5rem -0.5in;
            width: 100%;
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

        .patient-info {
            margin-bottom: 1.5rem;
            padding: 1rem;
            background: #f8f9fa;
            border-left: 4px solid #0d6efd;
            border-radius: 4px;
        }

        .patient-info p {
            margin: 0.5rem 0;
            font-size: 0.85rem;
        }

        .patient-info strong {
            color: #0d6efd;
            font-weight: 700;
        }

        .table-container {
            margin-bottom: 1.5rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 8pt;
            page-break-inside: auto;
        }

        thead {
            background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
            color: white;
        }

        thead th {
            padding: 0.5rem 0.4rem;
            text-align: center;
            font-weight: 700;
            border: 1px solid #084298;
            font-size: 8pt;
        }

        thead th.text-center {
            text-align: center;
        }

        thead th.text-right {
            text-align: center;
        
        }

        tbody tr {
            page-break-inside: avoid;
            page-break-after: auto;
        }

        tbody tr:nth-child(even) {
            background: #f8f9fa;
        }

        tbody td {
            padding: 0.5rem 0.4rem;
            border: 1px solid #dee2e6;
            vertical-align: top;
        }

        tbody td.text-center {
            text-align: center;
        }

        tbody td.text-right {
            text-align: center;
        }

        .footer {
            margin-top: 1.5rem;
            padding: 1.5rem 1rem;
            border-top: 3px solid #e5e7eb;
            text-align: center;
            color: #64748b;
            font-size: 0.75rem;
            line-height: 1.8;
            background: #f8fafc;
            border-radius: 6px;
            margin: 1.5rem -0.5in 0 -0.5in;
        }

        .footer strong {
            font-weight: 700;
            color: #0d6efd;
            font-size: 0.9rem;
            display: block;
            margin-bottom: 0.5rem;
        }

        .footer p {
            margin: 0.5rem 0;
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

    <!-- Patient Information -->
    <div class="patient-info">
        <p><strong>Patient Name:</strong> {{ $record->user->info ? $record->user->info->first_name . ' ' . $record->user->info->last_name : $record->user->username }}</p>
        <p><strong>Total Entries:</strong> {{ $record->progressNotes->count() }}</p>
    </div>

    <!-- Progress Notes Table -->
    <div class="table-container">
        <table>
            <thead>
                <tr>
                    <th class="text-center" style="width: 3%;">#</th>
                    <th class="text-right" style="width: 20%;">DATE</th>
                    <th class="text-right"style="width: 20%;">PROGRESS NOTES</th>
                    <th class="text-right" style="width: 20%;">AMOUNT PAID</th>
                    <th class="text-right" style="width: 20%;">BALANCE</th>
                    <th style="width: 20%;">CONFORME</th>
                </tr>
            </thead>
            <tbody>
                @forelse($record->progressNotes as $index => $note)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td class="text-right">{{ $note->note_date ? \Carbon\Carbon::parse($note->note_date)->format('m/d/Y') : 'N/A' }}</td>
                    <td class="text-right">{{ $note->progress_description ?? '-' }}</td>
                    <td class="text-right">{{ $note->amount_paid ? '₱' . number_format($note->amount_paid, 2) : '-' }}</td>
                    <td class="text-right">{{ $note->balance ? '₱' . number_format($note->balance, 2) : '-' }}</td>
                    <td class="text-right">{{ $note->conforme ?? '-' }}</td>
                </tr>
                @if($note->createdBy)
                <tr style="background: #f8f9fa; font-size: 7pt; color:rgb(0, 0, 0);">
                    <td colspan="6" style="padding: 0.4rem 0.4rem; font-style: italic; poppins-italic;">
                        Created by: {{ $note->createdBy->info ? $note->createdBy->info->first_name . ' ' . $note->createdBy->info->last_name : $note->createdBy->username }} ({{ ucfirst($note->created_by_role ?? 'staff') }}) on {{ $note->created_at ? \Carbon\Carbon::parse($note->created_at)->format('m/d/Y h:i A') : 'N/A' }}
                    </td>
                </tr>
                @endif
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 1rem; color: #64748b;">
                        No progress notes found.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- Footer -->
    <div class="footer">
        <strong>JValera Dental Clinic</strong>
        <p>This is a computer-generated document. No signature is required.</p>
        <p>Document generated on {{ date('F d, Y \a\t h:i A') }}</p>
        <p>Record ID: {{ $record->id }}</p>
    </div>
</body>
</html>

