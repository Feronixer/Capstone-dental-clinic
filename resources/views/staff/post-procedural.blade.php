@extends('layout.staff.app')
@section('content')
<link rel="stylesheet" href="{{ asset('css/post-procedural.css') }}">

<div class="post-procedural-container">
    <div class="layout-wrapper">
        <!-- Sidebar -->
        <div class="sidebar-nav">
            <h6 class="sidebar-title">Form List</h6>
            <ul class="nav-list">
                <li class="nav-item active" data-section="form-list">
                    <span>Form List</span>
                </li>
                <li class="nav-item" data-section="patient-record">
                    <span>Patient Record</span>
                </li>
                <li class="nav-item" data-section="patient-history">
                    <span>Patient History</span>
                </li>
                <li class="nav-item" data-section="progress-notes">
                    <span>Progress Notes</span>
                </li>
            </ul>
        </div>

        <!-- Main Content -->
        <div class="main-content">
            <!-- Header -->
            <div class="content-header">
                <h4 class="page-title">Post-Procedure Form</h4>
            </div>

            <!-- Form List Section (Table) -->
            <div class="content-section" id="form-list-section">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <label class="mb-0">Show</label>
                        <select class="form-select form-select-sm" id="entriesPerPage" style="width: 80px;">
                            <option value="5" selected>5</option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <label class="mb-0">entries</label>
                        </div>

                    <div class="d-flex gap-2">
                        <button class="btn btn-sm btn-outline-primary" id="filterBtn">
                            <i class="bi bi-funnel"></i> Filter By
                        </button>
                        <div class="input-group" style="width: 250px;">
                            <span class="input-group-text bg-white">
                                <i class="bi bi-search"></i>
                            </span>
                            <input type="text" class="form-control form-control-sm" id="searchInput" placeholder="Search">
                        </div>
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th style="width: 5%;" class="text-center">ID</th>
                                <th style="width: 15%;">Patient Name</th>
                                <th style="width: 15%;">Treatment</th>
                                <th style="width: 15%;" class="text-center">Patient Information Record</th>
                                <th style="width: 15%;" class="text-center">Patient History</th>
                                <th style="width: 15%;" class="text-center">Progress Notes</th>
                                <th style="width: 20%;" class="text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="recordsTableBody">
                            @forelse($records as $record)
                            <tr>
                                <td class="text-center">{{ $loop->iteration }}</td>
                                <td>
                                    @if($record->user && $record->user->info)
                                        {{ $record->user->info->first_name }} {{ $record->user->info->last_name }}
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td>
                                    @if($record->appointment && $record->appointment->service)
                                        {{ $record->appointment->service->service_name }}
                                    @elseif($record->user && $record->user->appointments()->exists())
                                        @php
                                            $latestAppointment = $record->user->appointments()->with('service')->latest('start_datetime')->first();
                                        @endphp
                                        @if($latestAppointment && $latestAppointment->service)
                                            {{ $latestAppointment->service->service_name }}
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                    @else
                                        <span class="text-muted">N/A</span>
                                    @endif
                                </td>
                                <td class="text-center">
                                    <!-- Actions removed -->
                                </td>
                                <td class="text-center">
                                    <!-- Actions removed -->
                                </td>
                                <td class="text-center">
                                    <!-- Actions removed -->
                                </td>
                                <td class="text-center">
                                    <button type="button" class="btn btn-dark btn-sm" onclick="clearAllFiles({{ $record->id }})" style="border-radius: 20px; padding: 5px 15px;">
                                        Clear All Files
                                    </button>
                                    <button type="button" class="btn btn-danger btn-sm" onclick="removeRecord({{ $record->id }})" style="border-radius: 20px; padding: 5px 15px;">
                                        Remove
                                    </button>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="7" class="text-center py-5">
                                    <div class="text-muted">
                                        <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                                        <p class="mt-3 mb-0">No patient records found</p>
                                        <small>Click on "Patient Record" tab to create a new record</small>
                                    </div>
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="d-flex justify-content-between align-items-center mt-3">
                    <div class="text-muted">
                        Showing {{ $records->firstItem() ?? 0 }} to {{ $records->lastItem() ?? 0 }} of {{ $records->total() }} entries
                    </div>
                    <nav>
                        {{ $records->links('pagination::bootstrap-5') }}
                    </nav>
                </div>
            </div>

            <!-- Patient Record Section (Form) -->
            <div class="content-section d-none" id="patient-record-section">
                <div class="patient-record-wrapper">
                    <!-- Clinic Header -->
                    <div class="clinic-header text-center mb-4">
                        <h3 class="fw-bold mb-1" style="font-size: 1.5rem; color: #0a4275;">JVALERA DENTAL CLINIC</h3>
                        <p class="mb-0" style="font-size: 0.875rem; color: #495057;">0190 Policapio St. Gen T. Deleon Valenzuela City</p>
                        <p class="mb-0" style="font-size: 0.875rem; color: #495057;">No: +63 15 622 9695 | FB: JValera Dental Clinic | EMAIL: jvaleradentalclinic@gmail.com</p>
                        <hr style="border-top: 2px solid #000; margin-top: 1rem;">
                    </div>

                    <!-- Section Title -->
                    <div class="section-title mb-4">
                        <h5 class="fw-bold" style="color: #0a4275;">PATIENT INFORMATION RECORD</h5>
                        <hr style="border-top: 1px solid #000; margin-top: 0.5rem;">
                    </div>

                    <!-- Patient Search & Selection -->
                    <div class="mb-4 patient-search-section">
                        <div class="search-header-box">
                            <label class="form-label fw-bold mb-2" style="color: #0a4275; font-size: 0.95rem;">
                                <i class="bi bi-search me-2"></i>Search Patient
                            </label>
                            <small class="text-muted" style="font-size: 0.75rem;">
                                <i class="bi bi-info-circle-fill me-1"></i>Type name or username to auto-fill information
                            </small>
                        </div>
                        <div class="position-relative mt-2">
                            <input type="text" class="form-control form-control-lg patient-search-input" id="patientNameSearch"
                                   placeholder="Start typing patient name or username..." autocomplete="off"
                                   style="border: 2px solid #0d6efd; border-radius: 8px; padding-left: 45px;">
                            <i class="bi bi-search position-absolute" style="left: 15px; top: 50%; transform: translateY(-50%); color: #0d6efd; font-size: 1.2rem;"></i>
                            <div id="patientNameSearchResults" class="search-results-dropdown"></div>
                        </div>
                    </div>

                    <!-- Selected Patient Info Alert (Hidden by default) -->
                    <div id="selectedPatientInfoAlert" class="alert alert-info d-none mb-4" style="background: linear-gradient(135deg, #e7f1ff 0%, #cfe2ff 100%); border: 1px solid #9ec5fe; border-radius: 8px;">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill text-success me-2" style="font-size: 1.5rem;"></i>
                            <div>
                                <strong style="color: #0a4275;">Patient Selected:</strong>
                                <span id="selectedPatientInfoText" class="ms-2"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Patient Name -->
                    <div class="row mb-3">
                        <div class="col-12 mb-2">
                            <label class="form-label fw-bold mb-0" style="color: #495057;">Patient's Name</label>
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted" style="font-size: 0.7rem; margin-bottom: 4px;">(LAST NAME)</label>
                            <input type="text" class="form-control readonly-field" id="lastName" readonly
                                   style="background: #e9ecef; border: 1px solid #ced4da; cursor: not-allowed;">
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted" style="font-size: 0.7rem; margin-bottom: 4px;">(GIVEN NAME)</label>
                            <input type="text" class="form-control readonly-field" id="givenName" readonly
                                   style="background: #e9ecef; border: 1px solid #ced4da; cursor: not-allowed;">
                        </div>
                        <div class="col-md-4">
                            <label class="text-muted" style="font-size: 0.7rem; margin-bottom: 4px;">(MIDDLE NAME)</label>
                            <input type="text" class="form-control readonly-field" id="middleName" readonly
                                   style="background: #e9ecef; border: 1px solid #ced4da; cursor: not-allowed;">
                        </div>
                    </div>

                    <hr style="border-top: 1px dashed #dee2e6; margin: 1.5rem 0;">

                    <!-- Home Address -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <label class="form-label fw-bold mb-2" style="color: #495057;">Home Address</label>
                            <input type="text" class="form-control" id="homeAddress"
                                   style="border: 2px solid #dee2e6; border-radius: 6px;">
                        </div>
                    </div>

                    <!-- Date of Birth, Age, Sex, Nickname -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label class="form-label fw-bold mb-2" style="color: #495057; font-size: 0.875rem;">Date of birth</label>
                            <input type="date" class="form-control" id="dateOfBirth" placeholder="MM/DD/YYYY"
                                   style="border: 2px solid #dee2e6; border-radius: 6px;">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold mb-2" style="color: #495057; font-size: 0.875rem;">
                                Age <small class="text-muted" style="font-weight: 400;">(auto-calculated)</small>
                            </label>
                            <input type="number" class="form-control" id="age" readonly
                                   style="border: 2px solid #dee2e6; border-radius: 6px; background: #f8f9fa; cursor: not-allowed;">
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold mb-2" style="color: #495057; font-size: 0.875rem;">Sex</label>
                            <select class="form-select" id="sex"
                                    style="border: 2px solid #dee2e6; border-radius: 6px;">
                                <option value="">Select...</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-bold mb-2" style="color: #495057; font-size: 0.875rem;">Nickname</label>
                            <input type="text" class="form-control" id="nickname"
                                   style="border: 2px solid #dee2e6; border-radius: 6px;">
                        </div>
                    </div>

                    <!-- Religion, Occupation, Contact -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label class="form-label fw-bold mb-2" style="color: #495057; font-size: 0.875rem;">Religion</label>
                            <input type="text" class="form-control" id="religion"
                                   style="border: 2px solid #dee2e6; border-radius: 6px;">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold mb-2" style="color: #495057; font-size: 0.875rem;">Occupation</label>
                            <input type="text" class="form-control" id="occupation"
                                   style="border: 2px solid #dee2e6; border-radius: 6px;">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-bold mb-2" style="color: #495057; font-size: 0.875rem;">Contact</label>
                            <input type="text" class="form-control" id="contact"
                                   style="border: 2px solid #dee2e6; border-radius: 6px;">
                        </div>
                    </div>

                    <hr style="border-top: 1px dashed #dee2e6; margin: 1.5rem 0;">

                    <!-- For Minors Section -->
                    <div class="mb-4">
                        <label class="form-label fw-bold mb-3" style="color: #0a4275; font-size: 1rem;">
                            <i class="bi bi-person-lines-fill me-2"></i>For minors:
                        </label>
                        <div class="row" style="background: #f8f9fa; padding: 15px; border-radius: 8px; border: 1px solid #dee2e6;">
                            <div class="col-12 mb-2">
                                <label class="form-label mb-1" style="font-size: 0.875rem; color: #495057;">Parent/Guardian's Name</label>
                                <input type="text" class="form-control" id="guardianName"
                                       style="border: 2px solid #dee2e6; border-radius: 6px;">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label mb-1" style="font-size: 0.875rem; color: #495057;">Contact No.</label>
                                <input type="text" class="form-control" id="guardianContact"
                                       style="border: 2px solid #dee2e6; border-radius: 6px;">
                            </div>
                            <div class="col-md-6 mb-2">
                                <label class="form-label mb-1" style="font-size: 0.875rem; color: #495057;">Occupation</label>
                                <input type="text" class="form-control" id="guardianOccupation"
                                       style="border: 2px solid #dee2e6; border-radius: 6px;">
                            </div>
                        </div>
                    </div>

                    <hr style="border-top: 1px dashed #dee2e6; margin: 1.5rem 0;">

                    <!-- Other Notes and Sent To Section -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label class="form-label fw-bold mb-2" style="color: #0a4275; font-size: 1rem;">
                                <i class="bi bi-pencil-square me-2"></i>Other Notes:
                            </label>
                            <textarea class="form-control" id="otherNotes" rows="6"
                                      style="resize: none; border: 2px solid #dee2e6; border-radius: 8px;"></textarea>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label fw-bold mb-2" style="color: #0a4275; font-size: 1rem;">
                                <i class="bi bi-send-fill me-2"></i>Send to Patient:
                            </label>
                            <div class="sent-to-box p-3" style="background: linear-gradient(135deg, #e7f1ff 0%, #f8f9fa 100%); border: 2px solid #0d6efd; border-radius: 12px; height: 100%;">
                                <small class="text-muted d-block mb-2" style="font-size: 0.75rem;">
                                    <i class="bi bi-info-circle-fill me-1"></i>Record will be automatically sent to this patient
                                </small>
                                <div class="position-relative mb-3">
                                    <input type="text" class="form-control" id="patientSearchInput"
                                           placeholder="Search patient to send record..." autocomplete="off"
                                           style="border: 2px solid #dee2e6; border-radius: 8px; padding-right: 48px;">
                                    <button type="button" class="btn btn-sm btn-primary search-icon-btn" onclick="triggerPatientSearch()"
                                            style="border-radius: 6px;">
                                        <i class="bi bi-search"></i>
                                    </button>
                                    <div id="patientSearchResults" class="search-results-dropdown"></div>
                                </div>
                                <input type="hidden" id="selectedPatientId">
                                <div id="selectedPatientDisplay" class="mb-3 d-none">
                                    <div class="alert alert-success mb-0 py-2" style="border-radius: 8px;">
                                        <small><i class="bi bi-check-circle-fill me-1"></i><span id="selectedPatientText"></span></small>
                                    </div>
                                </div>
                                <button type="button" class="btn btn-primary w-100" onclick="sendRecordToPatient()"
                                        style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%); border: none; border-radius: 8px; padding: 10px; font-weight: 600;">
                                    <i class="bi bi-send-fill me-2"></i>SEND TO PATIENT
                                </button>
                            </div>
                        </div>
                    </div>

                    <!-- Form Actions -->
                    <div class="mt-4 pt-3" style="border-top: 2px solid #dee2e6;">
                        <div class="d-flex gap-3 justify-content-end">
                            <button type="button" class="btn btn-secondary btn-lg" onclick="clearPatientRecordForm()"
                                    style="border-radius: 8px; padding: 10px 24px; font-weight: 600;">
                                <i class="bi bi-x-circle me-2"></i> CLEAR
                            </button>
                            <button type="button" class="btn btn-primary btn-lg" onclick="savePatientRecordFromTab()"
                                    style="background: linear-gradient(135deg, #198754 0%, #146c43 100%); border: none; border-radius: 8px; padding: 10px 24px; font-weight: 600;">
                                <i class="bi bi-floppy-fill me-2"></i> SAVE RECORD
                            </button>
                            <button type="button" class="btn btn-info btn-lg" onclick="printPatientRecord()"
                                    style="background: linear-gradient(135deg, #0dcaf0 0%, #0aa2c0 100%); border: none; border-radius: 8px; padding: 10px 24px; font-weight: 600;">
                                <i class="bi bi-printer-fill me-2"></i> PRINT
                            </button>
                        </div>
                    </div>
                </div>
            </div>


            <!-- Progress Notes Section -->
            <div class="content-section d-none" id="progress-notes-section">
                <div class="form-header mb-4" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); color: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 15px rgba(23, 162, 184, 0.2);">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-journal-text me-2"></i>Progress Notes
                    </h5>
                    <p class="mb-0 mt-1" style="font-size: 0.9rem; opacity: 0.95;">Document patient treatment progress and observations</p>
                </div>

                <!-- Patient Selection -->
                <div class="card mb-4" style="border: 2px solid #17a2b8; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                    <div class="card-header" style="background: linear-gradient(135deg, #e7f9fc 0%, #d0f4fa 100); border-bottom: 2px solid #17a2b8;">
                        <h6 class="mb-0 fw-bold text-info">
                            <i class="bi bi-person-circle me-2"></i>Select Patient
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="position-relative mt-2">
                            <input type="text" class="form-control form-control-lg patient-search-input" id="progressNotePatientSearch"
                                   placeholder="Start typing patient name or username..." autocomplete="off"
                                   style="border: 2px solid #17a2b8; border-radius: 8px; padding-left: 45px;">
                            <i class="bi bi-search position-absolute" style="left: 15px; top: 50%; transform: translateY(-50%); color: #17a2b8; font-size: 1.2rem;"></i>
                            <div id="progressNotePatientSearchResults" class="search-results-dropdown"></div>
                        </div>
                    </div>
                </div>

                <!-- Selected Patient Info Alert -->
                <div id="selectedProgressNotePatientAlert" class="alert alert-info d-none mb-4" style="background: linear-gradient(135deg, #e7f9fc 0%, #d0f4fa 100%); border: 1px solid #17a2b8; border-radius: 8px;">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-check-circle-fill text-success me-2" style="font-size: 1.5rem;"></i>
                        <div>
                            <strong style="color: #0c5460;">Patient Selected:</strong>
                            <span id="selectedProgressNotePatientText" class="ms-2"></span>
                        </div>
                    </div>
                </div>

                <!-- Progress Notes Table -->
                <div id="progressNotesTableContainer">
                    <div class="card mb-4" style="border: 2px solid #17a2b8; border-radius: 12px;">
                        <div class="card-header" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); color: white;">
                            <div class="d-flex justify-content-between align-items-center">
                                <h6 class="mb-0 fw-bold">
                                    <i class="bi bi-table me-2"></i>Progress Notes History
                                </h6>
                                <button type="button" class="btn btn-sm btn-light" id="addProgressNoteRowBtn">
                                    <i class="bi bi-plus-circle me-1"></i>Add Row
                                </button>
                            </div>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-bordered mb-0" id="progressNotesTable">
                                    <thead class="table-light">
                                        <tr>
                                            <th style="width: 15%;">DATE</th>
                                            <th style="width: 25%;">Progress Note</th>
                                            <th style="width: 20%;">Oral Hygiene</th>
                                            <th style="width: 25%;">Conformed Practices</th>
                                            <th style="width: 15%;" class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="progressNotesTableBody">
                                        <!-- Rows will be added dynamically -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Other Notes Section -->
                    <div class="card mb-4" style="border: 2px solid #17a2b8; border-radius: 12px;">
                        <div class="card-header" style="background: linear-gradient(135deg, #17a2b8 0%, #138496 100%); color: white;">
                            <h6 class="mb-0 fw-bold">
                                <i class="bi bi-chat-left-text me-2"></i>Other Notes
                            </h6>
                        </div>
                        <div class="card-body">
                            <textarea class="form-control" id="progressNoteOtherNotes" rows="5"
                                      placeholder="Enter any additional observations, recommendations, or follow-up instructions..."
                                      style="border: 2px solid #e0e0e0; border-radius: 8px;"></textarea>
                        </div>
                    </div>

                    <!-- Send to Patient Section -->
                    <div class="card mb-4" style="border: 2px solid #17a2b8; border-radius: 12px; background: linear-gradient(135deg, #f8fdfe 0%, #f0fbfc 100%);">
                        <div class="card-body">
                            <div class="row align-items-end">
                                <div class="col-md-8">
                                    <label class="form-label fw-bold text-info">
                                        <i class="bi bi-send me-2"></i>Send to:
                                    </label>
                                    <div class="position-relative">
                                        <input type="text" class="form-control form-control-lg" id="progressNoteSendToPatient" readonly
                                               placeholder="Patient will be auto-selected..."
                                               style="border: 2px solid #17a2b8; border-radius: 8px; background: white;">
                                        <i class="bi bi-person-check position-absolute" style="right: 15px; top: 50%; transform: translateY(-50%); color: #17a2b8; font-size: 1.2rem;"></i>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <button type="button" class="btn btn-info btn-lg w-100" id="sendProgressNoteBtn"
                                            style="border-radius: 10px; font-weight: 600; padding: 0.75rem;">
                                        <i class="bi bi-send-fill me-2"></i>SEND
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- View/Edit Modal with Tabs -->
<div class="modal fade" id="detailsModal" tabindex="-1">
    <div class="modal-dialog modal-xl modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%); color: white;">
                <h5 class="modal-title" id="modalTitle">
                    <i class="bi bi-file-medical me-2"></i>Patient Details
                </h5>
                <div class="ms-auto d-flex gap-2">
                    <button type="button" class="btn btn-sm btn-light" onclick="printModalContent()">
                        <i class="bi bi-printer-fill me-1"></i> Print
                    </button>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                </div>
            </div>

            <!-- Modal Tabs (only shown in view mode) -->
            <div id="modalTabs" class="d-none">
                <ul class="nav nav-tabs nav-fill" id="detailsModalTabs" role="tablist" style="border-bottom: 2px solid #dee2e6; background: #f8f9fa;">
                    <li class="nav-item" role="presentation">
                        <button class="nav-link active" id="patient-record-tab" data-bs-toggle="tab"
                                data-bs-target="#patient-record-content" type="button" role="tab"
                                style="font-weight: 600; color: #495057;">
                            <i class="bi bi-person-vcard me-2"></i>Patient Record
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="patient-history-tab" data-bs-toggle="tab"
                                data-bs-target="#patient-history-content" type="button" role="tab"
                                style="font-weight: 600; color: #495057;">
                            <i class="bi bi-clock-history me-2"></i>Patient History
                        </button>
                    </li>
                    <li class="nav-item" role="presentation">
                        <button class="nav-link" id="progress-notes-tab" data-bs-toggle="tab"
                                data-bs-target="#progress-notes-content" type="button" role="tab"
                                style="font-weight: 600; color: #495057;">
                            <i class="bi bi-journal-text me-2"></i>Progress Notes
                        </button>
                    </li>
                </ul>
            </div>

            <div class="modal-body" id="modalContent">
                <!-- For Edit Mode -->
                <div id="editModeContent"></div>

                <!-- For View Mode with Tabs -->
                <div id="viewModeContent" class="d-none">
                    <div class="tab-content" id="detailsModalTabContent">
                        <div class="tab-pane fade show active" id="patient-record-content" role="tabpanel">
                            <!-- Patient Record Content -->
                        </div>
                        <div class="tab-pane fade" id="patient-history-content" role="tabpanel">
                            <!-- Patient History Content -->
                        </div>
                        <div class="tab-pane fade" id="progress-notes-content" role="tabpanel">
                            <!-- Progress Notes Content -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="modal-footer" style="background: #f8f9fa;">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Close
                </button>
                <button type="button" class="btn btn-primary" id="saveBtn" style="background: linear-gradient(135deg, #198754 0%, #146c43 100%); border: none;">
                    <i class="bi bi-floppy-fill me-1"></i>Save Changes
                </button>
            </div>
        </div>
    </div>
</div>


<!-- Success Modal -->
<div class="modal fade" id="successModal" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border: none; border-radius: 16px; box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
            <div class="modal-body text-center p-5">
                <div class="mb-4" style="animation: scaleIn 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);">
                    <div style="width: 80px; height: 80px; margin: 0 auto; background: linear-gradient(135deg, #10b981 0%, #059669 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);">
                        <i class="bi bi-check-lg" style="font-size: 3rem; color: white; font-weight: bold;"></i>
                </div>
                </div>
                <h3 class="fw-bold mb-3" style="color: #059669;">Success!</h3>
                <p class="mb-4" id="successModalMessage" style="font-size: 1.1rem; color: #6b7280;">Record saved and sent to patient successfully!</p>
                <button type="button" class="btn btn-success btn-lg px-5" onclick="closeSuccessModal()" style="background: linear-gradient(135deg, #10b981 0%, #059669 100%); border: none; border-radius: 8px; font-weight: 600; box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3);">
                    <i class="bi bi-check-circle me-2"></i>OK
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Confirm Modal (for delete/clear actions) -->
<div class="modal fade" id="confirmModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border: none; border-radius: 16px; box-shadow: 0 10px 40px rgba(0,0,0,0.2);">
            <div class="modal-body text-center p-5">
                <div class="mb-4" style="animation: scaleIn 0.5s cubic-bezier(0.68, -0.55, 0.265, 1.55);">
                    <div id="confirmModalIcon" style="width: 80px; height: 80px; margin: 0 auto; background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 8px 20px rgba(245, 158, 11, 0.4);">
                        <i class="bi bi-question-circle" style="font-size: 3rem; color: white; font-weight: bold;"></i>
                    </div>
                </div>
                <h3 class="fw-bold mb-3" id="confirmModalTitle" style="color: #d97706;">Confirm Action</h3>
                <p class="mb-4" id="confirmModalMessage" style="font-size: 1.1rem; color: #6b7280;">Are you sure you want to proceed?</p>
                <div class="d-flex gap-3 justify-content-center">
                    <button type="button" class="btn btn-secondary btn-lg px-4" onclick="closeConfirmModal(false)" style="border-radius: 8px; font-weight: 600;">
                        <i class="bi bi-x-circle me-2"></i>Cancel
                    </button>
                    <button type="button" class="btn btn-primary btn-lg px-4" id="confirmModalBtn" onclick="closeConfirmModal(true)" style="border-radius: 8px; font-weight: 600;">
                        <i class="bi bi-check-circle me-2"></i>Confirm
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes scaleIn {
    from {
        transform: scale(0);
        opacity: 0;
    }
    to {
        transform: scale(1);
        opacity: 1;
    }
}

@keyframes fadeIn {
    from {
        opacity: 0;
        transform: translateY(-20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}
</style>

<script>
// Debounce utility function
function debounce(func, wait) {
    let timeout;
    return function executedFunction(event) {
        const context = this;
        const args = arguments;
        const later = () => {
            clearTimeout(timeout);
            func.apply(context, args);
        };
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
    };
}

// Navigation handling
document.querySelectorAll('.nav-item').forEach(item => {
    item.addEventListener('click', function() {
        document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
        this.classList.add('active');

        const section = this.dataset.section;
        document.querySelectorAll('.content-section').forEach(sec => sec.classList.add('d-none'));
        document.getElementById(section + '-section').classList.remove('d-none');

        // Initialize patient search when Patient Record tab is shown
        if (section === 'patient-record') {
            initializePatientRecordSearch();
        }

        // Initialize progress notes when Progress Notes tab is shown
        if (section === 'progress-notes') {
            initializeProgressNotesSearch();
        }
    });
});

// Reset to Form List when modal closes
const detailsModal = document.getElementById('detailsModal');
if (detailsModal) {
    detailsModal.addEventListener('hidden.bs.modal', function() {
        // Reset to Form List view
        document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
        document.querySelector('.nav-item[data-section="form-list"]').classList.add('active');

        // Show Form List section, hide others
        document.querySelectorAll('.content-section').forEach(sec => sec.classList.add('d-none'));
        document.getElementById('form-list-section').classList.remove('d-none');
    });
}


// Clear all files for a patient record - Staff restricted
function clearAllFiles(recordId) {
    alert('Staff members do not have permission to clear patient files. Please contact an administrator.');
}

// Remove patient record - Staff restricted
function removeRecord(recordId) {
    alert('Staff members do not have permission to remove patient records. Please contact an administrator.');
}

// Staff users are not allowed to delete records
function deleteRecord(id) {
    removeRecord(id);
}

// Archive record (placeholder)
function archiveRecord(id) {
    console.log('Archive record:', id);
}

// Render functions - Show full patient information in view mode
function renderPatientInfo(record) {
    const firstName = record.user?.info?.first_name || '';
    const lastName = record.user?.info?.last_name || '';
    const middleName = record.user?.info?.middle_name || '';
    const fullName = `${firstName} ${lastName}`.trim() || 'N/A';

    // Parse JSON fields
    let healthQuestions = {};
    let allergiesDetail = {};

    try {
        healthQuestions = typeof record.health_questions === 'string' ? JSON.parse(record.health_questions) : (record.health_questions || {});
    } catch (e) {
        healthQuestions = {};
    }

    try {
        allergiesDetail = typeof record.allergies_detail === 'string' ? JSON.parse(record.allergies_detail) : (record.allergies_detail || {});
    } catch (e) {
        allergiesDetail = {};
    }

    return `
        <div class="patient-record-form">
            <!-- Header -->
            <div class="form-header text-center mb-3">
                <h5 class="fw-bold mb-0">JVALERA DENTAL CLINIC</h5>
                <p class="mb-0" style="font-size: 0.75rem;">0190 Policarpio St. Gen T. Deleon Valenzuela City</p>
                <p class="mb-0" style="font-size: 0.75rem;">No: +63 15 622 9695 | FB: JValera Dental Clinic | EMAIL: jvaleradentalclinic@gmail.com</p>
            </div>

            <h6 class="section-title">PATIENT INFORMATION RECORD</h6>

            <!-- Patient's Name -->
            <div class="mb-3">
                <label class="fw-bold" style="color: #2c3e50; font-size: 1rem;">Patient's Name:</label>
                <div class="row mt-2">
                    <div class="col-4">
                        <div class="p-2" style="border-bottom: 1px solid #dee2e6;">${lastName || 'N/A'}</div>
                    </div>
                    <div class="col-4">
                        <div class="p-2" style="border-bottom: 1px solid #dee2e6;">${firstName || 'N/A'}</div>
                    </div>
                    <div class="col-4">
                        <div class="p-2" style="border-bottom: 1px solid #dee2e6;">${middleName || 'N/A'}</div>
                    </div>
                </div>
            </div>

            <!-- Home Address -->
            <div class="mb-3">
                <label class="fw-bold" style="color: #2c3e50;">Home Address:</label>
                <div class="p-2" style="border-bottom: 1px solid #dee2e6;">${record.home_address || 'N/A'}</div>
            </div>

            <!-- Date of birth, Age, Sex, Nickname -->
            <div class="row g-3 mb-3">
                <div class="col-3">
                    <label class="fw-bold" style="color: #2c3e50;">Date of birth:</label>
                    <div class="p-2" style="border-bottom: 1px solid #dee2e6;">${(() => {
                        if (!record.date_of_birth) return 'N/A';
                        try {
                            const date = new Date(record.date_of_birth);
                            return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
                        } catch(e) {
                            return record.date_of_birth;
                        }
                    })()}</div>
                </div>
                <div class="col-3">
                    <label class="fw-bold" style="color: #2c3e50;">Age:</label>
                    <div class="p-2" style="border-bottom: 1px solid #dee2e6;">${record.age || 'N/A'}</div>
                </div>
                <div class="col-3">
                    <label class="fw-bold" style="color: #2c3e50;">Sex:</label>
                    <div class="p-2" style="border-bottom: 1px solid #dee2e6;">${record.sex || 'N/A'}</div>
                </div>
                <div class="col-3">
                    <label class="fw-bold" style="color: #2c3e50;">Nickname:</label>
                    <div class="p-2" style="border-bottom: 1px solid #dee2e6;">${record.nickname || 'N/A'}</div>
                </div>
            </div>

            <!-- Religion, Occupation, Contact -->
            <div class="row g-3 mb-4">
                <div class="col-4">
                    <label class="fw-bold" style="color: #2c3e50;">Religion:</label>
                    <div class="p-2" style="border-bottom: 1px solid #dee2e6;">${record.religion || 'N/A'}</div>
                </div>
                <div class="col-4">
                    <label class="fw-bold" style="color: #2c3e50;">Occupation:</label>
                    <div class="p-2" style="border-bottom: 1px solid #dee2e6;">${record.occupation || 'N/A'}</div>
                </div>
                <div class="col-4">
                    <label class="fw-bold" style="color: #2c3e50;">Contact:</label>
                    <div class="p-2" style="border-bottom: 1px solid #dee2e6;">${record.contact || 'N/A'}</div>
                </div>
            </div>

            <!-- For Minors -->
            <div class="mb-4 p-3" style="background: #f8f9fa; border-left: 4px solid #0d6efd;">
                <h6 class="fw-bold mb-3" style="color: #0d6efd;">
                    <i class="bi bi-person-badge me-2"></i>For minors:
                </h6>
                <div class="mb-3">
                    <label class="fw-bold" style="color: #2c3e50;">Parent/Guardian's Name</label>
                    <div class="p-2" style="border-bottom: 1px solid #dee2e6;">${record.guardian_name || ''}</div>
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="fw-bold" style="color: #2c3e50;">Contact No.</label>
                        <div class="p-2" style="border-bottom: 1px solid #dee2e6;">${record.guardian_contact || ''}</div>
                    </div>
                    <div class="col-md-6">
                        <label class="fw-bold" style="color: #2c3e50;">Occupation</label>
                        <div class="p-2" style="border-bottom: 1px solid #dee2e6;">${record.guardian_occupation || ''}</div>
                    </div>
                </div>
            </div>

            <!-- Other Notes -->
            <div class="mb-3">
                <label class="fw-bold" style="color: #2c3e50;">Other Notes:</label>
                <div class="p-2" style="border-bottom: 1px solid #dee2e6; min-height: 60px; white-space: pre-wrap;">${record.notes || ''}</div>
            </div>
        </div>
    `;
}

function renderPatientInfoForm(record) {
    return `
        <form id="patientInfoForm" class="patient-record-form">
            ${record.id && record.id !== 'null' ? `<input type="hidden" name="id" value="${record.id}">` : ''}
            ${record.patient_number ? `<input type="hidden" name="patient_number" value="${record.patient_number}">` : ''}
            ${record.user_id ? `<input type="hidden" name="user_id" value="${record.user_id}">` : ''}

            <!-- Header -->
            <div class="form-header text-center mb-3">
                <h5 class="fw-bold mb-0">JVALERA DENTAL CLINIC</h5>
                <p class="mb-0" style="font-size: 0.75rem;">0190 Policarpio St. Gen T. Deleon Valenzuela City</p>
                <p class="mb-0" style="font-size: 0.75rem;">No: +63 15 622 9695 | FB: JValera Dental Clinic | EMAIL: jvaleradentalclinic@gmail.com</p>
            </div>

            <h6 class="section-title">PATIENT INFORMATION RECORD</h6>

            <!-- Patient's Name -->
            <div class="mb-3">
                <label class="form-label fw-bold mb-2" style="color: #2c3e50;">Patient's Name</label>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label text-muted" style="font-size: 0.75rem; text-transform: uppercase;">(Last Name)</label>
                        <input type="text" class="form-control" readonly value="${record.user?.info?.last_name || ''}" style="background: #e9ecef; border: 1px solid #ced4da;">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted" style="font-size: 0.75rem; text-transform: uppercase;">(Given Name)</label>
                        <input type="text" class="form-control" readonly value="${record.user?.info?.first_name || ''}" style="background: #e9ecef; border: 1px solid #ced4da;">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label text-muted" style="font-size: 0.75rem; text-transform: uppercase;">(Middle Name)</label>
                        <input type="text" class="form-control" readonly value="${record.user?.info?.middle_name || ''}" style="background: #e9ecef; border: 1px solid #ced4da;">
                    </div>
                </div>
            </div>

            <!-- Home Address -->
            <div class="mb-3">
                <label class="form-label fw-bold" style="color: #2c3e50;">Home Address</label>
                <input type="text" class="form-control" name="home_address" value="${record.home_address || ''}" style="border: 1px solid #ced4da;">
            </div>

            <!-- Date of birth, Age, Sex, Nickname -->
            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <label class="form-label fw-bold" style="color: #2c3e50;">Date of birth</label>
                    <input type="date" class="form-control" name="date_of_birth" value="${record.date_of_birth || ''}" style="border: 1px solid #ced4da;">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold" style="color: #2c3e50;">Age <span class="text-muted fw-normal" style="font-size: 0.75rem;">(auto-calculated)</span></label>
                    <input type="number" class="form-control" name="age" value="${record.age || ''}" readonly style="background: #e9ecef; border: 1px solid #ced4da;">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold" style="color: #2c3e50;">Sex</label>
                    <select class="form-select" name="sex" style="border: 1px solid #ced4da;">
                        <option value="" ${!record.sex ? 'selected' : ''}>Select...</option>
                        <option value="Male" ${record.sex === 'Male' ? 'selected' : ''}>Male</option>
                        <option value="Female" ${record.sex === 'Female' ? 'selected' : ''}>Female</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold" style="color: #2c3e50;">Nickname</label>
                    <input type="text" class="form-control" name="nickname" value="${record.nickname || ''}" style="border: 1px solid #ced4da;">
                </div>
            </div>

            <!-- Religion, Occupation, Contact -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-bold" style="color: #2c3e50;">Religion</label>
                    <input type="text" class="form-control" name="religion" value="${record.religion || ''}" style="border: 1px solid #ced4da;">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold" style="color: #2c3e50;">Occupation</label>
                    <input type="text" class="form-control" name="occupation" value="${record.occupation || ''}" style="border: 1px solid #ced4da;">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold" style="color: #2c3e50;">Contact</label>
                    <input type="text" class="form-control" name="contact" value="${record.contact || ''}" style="border: 1px solid #ced4da;">
                </div>
            </div>

            <!-- For Minors -->
            <div class="mb-4 p-3" style="background: #f8f9fa; border-left: 4px solid #0d6efd;">
                <h6 class="fw-bold mb-3" style="color: #0d6efd;">
                    <i class="bi bi-person-badge me-2"></i>For minors:
                </h6>
                <div class="mb-3">
                    <label class="form-label fw-bold" style="color: #2c3e50;">Parent/Guardian's Name</label>
                    <input type="text" class="form-control" name="guardian_name" value="${record.guardian_name || ''}" style="border: 1px solid #ced4da;">
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold" style="color: #2c3e50;">Contact No.</label>
                        <input type="text" class="form-control" name="guardian_contact" value="${record.guardian_contact || ''}" style="border: 1px solid #ced4da;">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold" style="color: #2c3e50;">Occupation</label>
                        <input type="text" class="form-control" name="guardian_occupation" value="${record.guardian_occupation || ''}" style="border: 1px solid #ced4da;">
                    </div>
                </div>
            </div>

            <!-- Other Notes -->
            <div class="mb-3">
                <label class="form-label fw-bold" style="color: #2c3e50;">Other Notes:</label>
                <textarea class="form-control" name="notes" rows="3" style="border: 1px solid #ced4da;">${record.notes || ''}</textarea>
            </div>

            <!-- Patient Assignment Info (Hidden) -->
            <input type="hidden" name="send_to_user_id" id="sendToUserId" value="${record.user_id}">

            <div class="alert alert-info mt-3 d-flex align-items-center" style="font-size: 0.875rem;">
                <i class="bi bi-info-circle me-2"></i>
                <span>This record will be automatically saved to <strong>${record.user?.name || 'the selected patient'}</strong>'s account</span>
            </div>
        </form>
    `;
}

// Enhanced Patient History View for Modal
function renderPatientHistoryView(history) {
    if (!history || history.length === 0) {
        return `
            <div class="text-center py-5 text-muted">
                <i class="bi bi-clock-history" style="font-size: 3rem; opacity: 0.3;"></i>
                <p class="mt-3 mb-0">No patient history records found</p>
            </div>
        `;
    }

    return `
        <div class="patient-history-list p-3">
            ${history.map((h, index) => `
                <div class="card mb-4 shadow-sm" style="border: none; overflow: hidden;">
                    <div class="card-header d-flex justify-content-between align-items-center" style="background: #0d6efd; color: white; padding: 12px 20px;">
                        <h6 class="mb-0">
                            <i class="bi bi-calendar-check me-2"></i>Visit #${index + 1} - ${(() => {
                                try {
                                    const date = new Date(h.visit_date);
                                    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
                                } catch(e) {
                                    return h.visit_date || 'Date not set';
                                }
                            })()}
                        </h6>
                    </div>
                    <div class="card-body" style="background: #f8f9fa; padding: 20px;">
                        <!-- DENTAL HISTORY -->
                        <div class="mb-3">
                            <h6 class="fw-bold mb-3" style="color: #0d6efd; border-bottom: 2px solid #0d6efd; padding-bottom: 8px;">DENTAL HISTORY</h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="d-block" style="color: #6c757d; font-weight: 600; font-size: 0.9rem;">Previous Dentist:</label>
                                    <span>${h.previous_dentist || 'N/A'}</span>
                                </div>
                                <div class="col-md-4">
                                    <label class="d-block" style="color: #6c757d; font-weight: 600; font-size: 0.9rem;">Last Dental Visit:</label>
                                    <span>${h.last_dental_visit ? new Date(h.last_dental_visit).toLocaleDateString('en-US', {year: 'numeric', month: '2-digit', day: '2-digit'}) : 'N/A'}</span>
                                </div>
                                <div class="col-md-4">
                                    <label class="d-block" style="color: #6c757d; font-weight: 600; font-size: 0.9rem;">Treatment Done:</label>
                                    <span>${h.treatment_done || 'N/A'}</span>
                                </div>
                            </div>
                        </div>

                        <!-- MEDICAL HISTORY -->
                        <div class="mb-3">
                            <h6 class="fw-bold mb-3" style="color: #0d6efd; border-bottom: 2px solid #0d6efd; padding-bottom: 8px;">MEDICAL HISTORY</h6>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="d-block" style="color: #6c757d; font-weight: 600; font-size: 0.9rem;">Physician Name:</label>
                                    <span>${h.physician_name || 'N/A'}</span>
                                </div>
                                <div class="col-md-8">
                                    <label class="d-block" style="color: #6c757d; font-weight: 600; font-size: 0.9rem;">Office Address:</label>
                                    <span>${h.physician_office_address || 'N/A'}</span>
                                </div>
                                <div class="col-md-4">
                                    <label class="d-block" style="color: #6c757d; font-weight: 600; font-size: 0.9rem;">Contact:</label>
                                    <span>${h.physician_contact || 'N/A'}</span>
                                </div>
                            </div>
                        </div>

                        <!-- HEALTH QUESTIONS -->
                        <div class="mb-3">
                            <h6 class="fw-bold mb-3" style="color: #0d6efd; border-bottom: 2px solid #0d6efd; padding-bottom: 8px;">HEALTH QUESTIONS</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="d-block" style="color: #6c757d; font-weight: 600; font-size: 0.9rem;">Are you in good health?</label>
                                    <span class="badge ${h.good_health && h.good_health.toLowerCase() === 'yes' ? 'bg-success' : 'bg-secondary'}">${h.good_health || 'N/A'}</span>
                                </div>
                                <div class="col-md-6">
                                    <label class="d-block" style="color: #6c757d; font-weight: 600; font-size: 0.9rem;">Under medical treatment?</label>
                                    <span>${h.under_treatment || 'N/A'}</span>
                                </div>
                                <div class="col-md-6">
                                    <label class="d-block" style="color: #6c757d; font-weight: 600; font-size: 0.9rem;">Serious illness/operation?</label>
                                    <span>${h.serious_illness || 'no'}</span>
                                </div>
                                <div class="col-md-6">
                                    <label class="d-block" style="color: #6c757d; font-weight: 600; font-size: 0.9rem;">Been hospitalized?</label>
                                    <span>${h.been_hospitalized || 'no'}</span>
                                </div>
                                <div class="col-md-6">
                                    <label class="d-block" style="color: #6c757d; font-weight: 600; font-size: 0.9rem;">Taking prescription drugs?</label>
                                    <span>${h.taking_drugs || 'N/A'}</span>
                                </div>
                                <div class="col-md-6">
                                    <label class="d-block" style="color: #6c757d; font-weight: 600; font-size: 0.9rem;">Tobacco Use:</label>
                                    <span>${h.tobacco_use || 'N/A'}</span>
                                </div>
                                <div class="col-md-6">
                                    <label class="d-block" style="color: #6c757d; font-weight: 600; font-size: 0.9rem;">Alcohol Use:</label>
                                    <span>${h.alcohol_use || 'no'}</span>
                                </div>
                                <div class="col-md-6">
                                    <label class="d-block" style="color: #6c757d; font-weight: 600; font-size: 0.9rem;">Recreational Drugs:</label>
                                    <span>${h.recreational_drugs || 'no'}</span>
                                </div>
                            </div>
                        </div>

                        <!-- FOR WOMEN -->
                        <div class="mb-3">
                            <h6 class="fw-bold mb-3" style="color: #0d6efd; border-bottom: 2px solid #0d6efd; padding-bottom: 8px;">FOR WOMEN</h6>
                            <div class="row g-3">
                                <div class="col-md-4">
                                    <label class="d-block" style="color: #6c757d; font-weight: 600; font-size: 0.9rem;">Pregnant:</label>
                                    <span>${h.is_pregnant || 'no'}</span>
                                </div>
                                <div class="col-md-4">
                                    <label class="d-block" style="color: #6c757d; font-weight: 600; font-size: 0.9rem;">Nursing:</label>
                                    <span>${h.is_nursing || 'no'}</span>
                                </div>
                                <div class="col-md-4">
                                    <label class="d-block" style="color: #6c757d; font-weight: 600; font-size: 0.9rem;">Taking Birth Control Pills:</label>
                                    <span>${h.birth_control || 'no'}</span>
                                </div>
                            </div>
                        </div>

                        <!-- PROCEDURE DETAILS -->
                        <div class="mb-3">
                            <h6 class="fw-bold mb-3" style="color: #0d6efd; border-bottom: 2px solid #0d6efd; padding-bottom: 8px;">PROCEDURE DETAILS</h6>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="d-block" style="color: #6c757d; font-weight: 600; font-size: 0.9rem;">PROCEDURE PERFORMED:</label>
                                    <span>${h.procedure_performed || 'N/A'}</span>
                                </div>
                                <div class="col-md-12">
                                    <label class="d-block" style="color: #6c757d; font-weight: 600; font-size: 0.9rem;">MATERIALS USED:</label>
                                    <span>${h.materials_used || 'N/A'}</span>
                                </div>
                                <div class="col-md-12">
                                    <label class="d-block" style="color: #6c757d; font-weight: 600; font-size: 0.9rem;">ANESTHESIA USED:</label>
                                    <span>${h.anesthesia_used || 'N/A'}</span>
                                </div>
                                <div class="col-md-12">
                                    <label class="d-block" style="color: #6c757d; font-weight: 600; font-size: 0.9rem;">COMPLICATIONS:</label>
                                    <span>${h.complications || 'None reported'}</span>
                                </div>
                                <div class="col-md-12">
                                    <label class="d-block" style="color: #6c757d; font-weight: 600; font-size: 0.9rem;">POST-OPERATIVE INSTRUCTIONS:</label>
                                    <span>${h.post_operative_instructions || 'N/A'}</span>
                                </div>
                                <div class="col-md-12">
                                    <label class="d-block" style="color: #6c757d; font-weight: 600; font-size: 0.9rem;">FOLLOW-UP NOTES:</label>
                                    <span>${h.follow_up_notes || 'N/A'}</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            `).join('')}
        </div>
    `;
}

function renderPatientHistory(history) {
    if (!history || history.length === 0) {
        return '<p class="text-center text-muted">No history records found</p>';
    }
    return history.map(h => `
        <div class="history-item">
            <div><strong>Visit Date:</strong> ${h.visit_date}</div>
            <div><strong>Procedure:</strong> ${h.procedure_performed || 'N/A'}</div>
            <div><strong>Materials:</strong> ${h.materials_used || 'N/A'}</div>
            <div><strong>Anesthesia:</strong> ${h.anesthesia_used || 'N/A'}</div>
        </div>
    `).join('<hr>');
}

// Editable Patient History List
function renderPatientHistoryEditList(history, recordId) {
    return `
        <div class="p-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0"><i class="bi bi-clock-history me-2"></i>Patient History Records</h6>
                <button type="button" class="btn btn-sm btn-primary" onclick="showAddHistoryForm()">
                    <i class="bi bi-plus-circle me-1"></i>Add New Visit
                </button>
            </div>

            <div id="addHistoryFormContainer" class="d-none mb-3">
                ${renderAddPatientHistoryForm(recordId)}
            </div>

            ${history.map((h, index) => `
                <div class="card mb-3" style="border-left: 4px solid #0d6efd;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0" style="color: #0a4275;">Visit #${index + 1}</h6>
                        </div>
                        <form id="historyForm${h.id}">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Visit Date</label>
                                    <input type="date" class="form-control" name="visit_date" value="${h.visit_date || ''}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Anesthesia Used</label>
                                    <input type="text" class="form-control" name="anesthesia_used" value="${h.anesthesia_used || ''}">
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Procedure Performed</label>
                                    <textarea class="form-control" name="procedure_performed" rows="2">${h.procedure_performed || ''}</textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Materials Used</label>
                                    <textarea class="form-control" name="materials_used" rows="2">${h.materials_used || ''}</textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Complications</label>
                                    <textarea class="form-control" name="complications" rows="2">${h.complications || ''}</textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Post-Operative Instructions</label>
                                    <textarea class="form-control" name="post_operative_instructions" rows="2">${h.post_operative_instructions || ''}</textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Follow-up Notes</label>
                                    <textarea class="form-control" name="follow_up_notes" rows="2">${h.follow_up_notes || ''}</textarea>
                                </div>
                                <div class="col-12">
                                    <button type="button" class="btn btn-success btn-sm" onclick="savePatientHistory(${h.id}, ${recordId})">
                                        <i class="bi bi-check-circle me-1"></i>Save Changes
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            `).join('')}
        </div>
    `;
}

// Add Patient History Form
function renderAddPatientHistoryForm(recordId) {
    return `
        <div class="card bg-light">
            <div class="card-body">
                <h6 class="mb-3"><i class="bi bi-plus-circle me-2"></i>Add New Visit Record</h6>
                <form id="newHistoryForm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Visit Date</label>
                            <input type="date" class="form-control" name="visit_date" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Anesthesia Used</label>
                            <input type="text" class="form-control" name="anesthesia_used">
                        </div>
                        <div class="col-12">
                            <label class="form-label">Procedure Performed</label>
                            <textarea class="form-control" name="procedure_performed" rows="2"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Materials Used</label>
                            <textarea class="form-control" name="materials_used" rows="2"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Complications</label>
                            <textarea class="form-control" name="complications" rows="2"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Post-Operative Instructions</label>
                            <textarea class="form-control" name="post_operative_instructions" rows="2"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Follow-up Notes</label>
                            <textarea class="form-control" name="follow_up_notes" rows="2"></textarea>
                        </div>
                        <div class="col-12">
                            <button type="button" class="btn btn-primary" onclick="addNewPatientHistory(${recordId})">
                                <i class="bi bi-plus-circle me-1"></i>Add Visit Record
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="hideAddHistoryForm()">
                                Cancel
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    `;
}

function renderPatientHistoryForm(id) {
    return `
        <form id="patientHistoryForm">
            <input type="hidden" name="patient_record_id" value="${id}">
            <div class="mb-3">
                <label class="form-label">Visit Date</label>
                <input type="date" class="form-control" name="visit_date" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Procedure Performed</label>
                <textarea class="form-control" name="procedure_performed" rows="2"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Materials Used</label>
                <textarea class="form-control" name="materials_used" rows="2"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Anesthesia Used</label>
                <textarea class="form-control" name="anesthesia_used" rows="2"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Post-Operative Instructions</label>
                <textarea class="form-control" name="post_operative_instructions" rows="2"></textarea>
            </div>
        </form>
    `;
}

// Enhanced Progress Notes View for Modal
function renderProgressNotesView(notes) {
    if (!notes || notes.length === 0) {
        return `
            <div class="text-center py-5 text-muted">
                <i class="bi bi-journal-text" style="font-size: 3rem; opacity: 0.3;"></i>
                <p class="mt-3 mb-0">No progress notes found</p>
            </div>
        `;
    }

    return `
        <div class="progress-notes-list p-3">
            ${notes.map((n, index) => `
                <div class="card mb-4 shadow-sm" style="border: none; overflow: hidden;">
                    <div class="card-header d-flex justify-content-between align-items-center" style="background: #6f42c1; color: white; padding: 12px 20px;">
                        <h6 class="mb-0">
                            <i class="bi bi-journal-medical me-2"></i>Progress Note #${index + 1} - ${(() => {
                                try {
                                    const date = new Date(n.note_date);
                                    return date.toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
                                } catch(e) {
                                    return n.note_date || 'Date not set';
                                }
                            })()}
                        </h6>
                    </div>
                    <div class="card-body" style="background: #f8f9fa; padding: 20px;">
                        <!-- PROGRESS NOTE -->
                        <div class="mb-3">
                            <h6 class="fw-bold mb-3" style="color: #6f42c1; border-bottom: 2px solid #6f42c1; padding-bottom: 8px;">PROGRESS NOTE</h6>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="d-block" style="color: #6c757d; font-weight: 600; font-size: 0.9rem;">Progress Note:</label>
                                    <div style="background: white; padding: 12px; border-radius: 4px; border-left: 3px solid #6f42c1;">
                                        ${n.progress_note || 'N/A'}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ORAL HYGIENE -->
                        <div class="mb-3">
                            <h6 class="fw-bold mb-3" style="color: #6f42c1; border-bottom: 2px solid #6f42c1; padding-bottom: 8px;">ORAL HYGIENE</h6>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="d-block" style="color: #6c757d; font-weight: 600; font-size: 0.9rem;">Oral Hygiene Status:</label>
                                    <div style="background: white; padding: 12px; border-radius: 4px; border-left: 3px solid #17a2b8;">
                                        ${n.oral_hygiene || 'N/A'}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- CONFORMED PRACTICES -->
                        <div class="mb-3">
                            <h6 class="fw-bold mb-3" style="color: #6f42c1; border-bottom: 2px solid #6f42c1; padding-bottom: 8px;">CONFORMED PRACTICES</h6>
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="d-block" style="color: #6c757d; font-weight: 600; font-size: 0.9rem;">Practices Conformed:</label>
                                    <div style="background: white; padding: 12px; border-radius: 4px; border-left: 3px solid #28a745;">
                                        ${n.conformed_practices || 'N/A'}
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- ADDITIONAL INFO -->
                        ${n.created_at ? `
                        <div class="mt-4 pt-3" style="border-top: 1px solid #dee2e6;">
                            <small class="text-muted">
                                <i class="bi bi-clock me-1"></i>Created: ${new Date(n.created_at).toLocaleString('en-US', {
                                    year: 'numeric',
                                    month: 'long',
                                    day: 'numeric',
                                    hour: '2-digit',
                                    minute: '2-digit'
                                })}
                            </small>
                        </div>
                        ` : ''}
                    </div>
                </div>
            `).join('')}
        </div>
    `;
}

function renderProgressNotes(notes) {
    if (!notes || notes.length === 0) {
        return '<p class="text-center text-muted">No progress notes found</p>';
    }
    return notes.map(n => `
        <div class="note-item">
            <div><strong>Date:</strong> ${n.note_date}</div>
            <div><strong>Description:</strong> ${n.progress_description}</div>
            <div><strong>Status:</strong> <span class="badge bg-primary">${n.status}</span></div>
        </div>
    `).join('<hr>');
}

// Editable Progress Notes List
function renderProgressNotesEditList(notes, recordId) {
    const statusColors = {
        'ongoing': 'warning',
        'completed': 'success',
        'followup_needed': 'info'
    };

    return `
        <div class="p-3">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0"><i class="bi bi-journal-text me-2"></i>Progress Notes</h6>
                <button type="button" class="btn btn-sm btn-primary" onclick="showAddNoteForm()">
                    <i class="bi bi-plus-circle me-1"></i>Add New Note
                </button>
            </div>

            <div id="addNoteFormContainer" class="d-none mb-3">
                ${renderAddProgressNoteForm(recordId)}
            </div>

            ${notes.map((n, index) => `
                <div class="card mb-3" style="border-left: 4px solid #${statusColors[n.status] === 'warning' ? 'ffc107' : statusColors[n.status] === 'success' ? '198754' : '0dcaf0'};">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0" style="color: #0a4275;">Note #${index + 1}</h6>
                        </div>
                        <form id="noteForm${n.id}">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">Note Date</label>
                                    <input type="date" class="form-control" name="note_date" value="${n.note_date || ''}" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Status</label>
                                    <select class="form-select" name="status" required>
                                        <option value="ongoing" ${n.status === 'ongoing' ? 'selected' : ''}>Ongoing</option>
                                        <option value="completed" ${n.status === 'completed' ? 'selected' : ''}>Completed</option>
                                        <option value="followup_needed" ${n.status === 'followup_needed' ? 'selected' : ''}>Follow-up Needed</option>
                                    </select>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Progress Description</label>
                                    <textarea class="form-control" name="progress_description" rows="3" required>${n.progress_description || ''}</textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Treatment Response</label>
                                    <textarea class="form-control" name="treatment_response" rows="2">${n.treatment_response || ''}</textarea>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Next Steps</label>
                                    <textarea class="form-control" name="next_steps" rows="2">${n.next_steps || ''}</textarea>
                                </div>
                                <div class="col-12">
                                    <button type="button" class="btn btn-success btn-sm" onclick="saveProgressNote(${n.id}, ${recordId})">
                                        <i class="bi bi-check-circle me-1"></i>Save Changes
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            `).join('')}
        </div>
    `;
}

// Add Progress Note Form
function renderAddProgressNoteForm(recordId) {
    return `
        <div class="card bg-light">
            <div class="card-body">
                <h6 class="mb-3"><i class="bi bi-plus-circle me-2"></i>Add New Progress Note</h6>
                <form id="newNoteForm">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Note Date</label>
                            <input type="date" class="form-control" name="note_date" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select class="form-select" name="status" required>
                                <option value="ongoing">Ongoing</option>
                                <option value="completed">Completed</option>
                                <option value="followup_needed">Follow-up Needed</option>
                            </select>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Progress Description</label>
                            <textarea class="form-control" name="progress_description" rows="3" required></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Treatment Response</label>
                            <textarea class="form-control" name="treatment_response" rows="2"></textarea>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Next Steps</label>
                            <textarea class="form-control" name="next_steps" rows="2"></textarea>
                        </div>
                        <div class="col-12">
                            <button type="button" class="btn btn-primary" onclick="addNewProgressNote(${recordId})">
                                <i class="bi bi-plus-circle me-1"></i>Add Note
                            </button>
                            <button type="button" class="btn btn-secondary" onclick="hideAddNoteForm()">
                                Cancel
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    `;
}

function renderProgressNotesForm(id) {
    return `
        <form id="progressNotesForm">
            <input type="hidden" name="patient_record_id" value="${id}">
            <div class="mb-3">
                <label class="form-label">Note Date</label>
                <input type="date" class="form-control" name="note_date" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Progress Description</label>
                <textarea class="form-control" name="progress_description" rows="3" required></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Treatment Response</label>
                <textarea class="form-control" name="treatment_response" rows="2"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Next Steps</label>
                <textarea class="form-control" name="next_steps" rows="2"></textarea>
            </div>
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select class="form-select" name="status" required>
                    <option value="ongoing">Ongoing</option>
                    <option value="completed">Completed</option>
                    <option value="followup_needed">Follow-up Needed</option>
                </select>
            </div>
        </form>
    `;
}

// Print modal content
function printModalContent() {
    // Get the active tab content
    const activeTab = document.querySelector('#detailsModalTabContent .tab-pane.active');
    const printContent = activeTab ? activeTab.innerHTML : document.getElementById('editModeContent').innerHTML;

    const printWindow = window.open('', '', 'height=600,width=800');
    printWindow.document.write('<html><head><title>Patient Information</title>');
    printWindow.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">');
    printWindow.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">');
    printWindow.document.write('<style>');
    printWindow.document.write('@media print { .no-print { display: none; } }');
    printWindow.document.write('.patient-record-form { font-size: 12px; }');
    printWindow.document.write('.section-title { font-size: 14px; font-weight: 700; margin: 10px 0 6px 0; padding: 4px 0; border-bottom: 1px solid #dee2e6; }');
    printWindow.document.write('.card { page-break-inside: avoid; }');
    printWindow.document.write('</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write('<div class="container mt-4">');
    printWindow.document.write(printContent);
    printWindow.document.write('</div>');
    printWindow.document.write('</body></html>');
    printWindow.document.close();

    setTimeout(() => {
        printWindow.print();
    }, 250);
}

// Search functionality
document.getElementById('searchInput').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    document.querySelectorAll('#recordsTableBody tr').forEach(row => {
        const text = row.textContent.toLowerCase();
        row.style.display = text.includes(searchTerm) ? '' : 'none';
    });
});

// Print form function
function printForm() {
    const printContent = document.getElementById('modalContent').innerHTML;
    const printWindow = window.open('', '', 'height=600,width=800');
    printWindow.document.write('<html><head><title>Patient Record</title>');
    printWindow.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">');
    printWindow.document.write('<style>');
    printWindow.document.write('@media print { .no-print, .send-to-patient-section { display: none; } }');
    printWindow.document.write('.patient-record-form { font-size: 12px; }');
    printWindow.document.write('.section-title { font-size: 14px; font-weight: 700; margin: 10px 0 6px 0; padding: 4px 0; border-bottom: 1px solid #dee2e6; }');
    printWindow.document.write('.question-row { display: flex; align-items: center; padding: 3px 0; font-size: 11px; gap: 8px; }');
    printWindow.document.write('.q-number { min-width: 20px; font-weight: 600; }');
    printWindow.document.write('.q-text { flex: 1; }');
    printWindow.document.write('.q-options { display: flex; gap: 15px; min-width: 100px; justify-content: flex-end; }');
    printWindow.document.write('.form-label-sm { font-size: 11px; font-weight: 500; margin-bottom: 2px; }');
    printWindow.document.write('.form-control-sm { font-size: 11px; padding: 4px 8px; }');
    printWindow.document.write('.form-check-sm { font-size: 11px; }');
    printWindow.document.write('</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write(printContent);
    printWindow.document.write('</body></html>');
    printWindow.document.close();

    setTimeout(() => {
        printWindow.print();
    }, 250);
}

// Patient search functionality
let patientSearchTimeout;
let currentPatientRecord = null;
let patientRecordSearchInitialized = false;

// Initialize patient record search
function initializePatientRecordSearch() {
    if (patientRecordSearchInitialized) return;

    const patientRecordSearch = document.getElementById('patientRecordSearch');
    if (patientRecordSearch) {
        patientRecordSearch.addEventListener('input', function() {
            clearTimeout(patientSearchTimeout);
            const searchTerm = this.value.trim();

            console.log('Searching for:', searchTerm);

            if (searchTerm.length < 2) {
                document.getElementById('patientRecordSearchResults').innerHTML = '';
                return;
            }

            patientSearchTimeout = setTimeout(() => {
                searchPatientsForRecord(searchTerm);
            }, 300);
        });
        patientRecordSearchInitialized = true;
        console.log('Patient record search initialized');
    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Auto-calculate age when date of birth changes
    const dateOfBirthInput = document.getElementById('dateOfBirth');
    const ageInput = document.getElementById('age');

    if (dateOfBirthInput && ageInput) {
        dateOfBirthInput.addEventListener('change', function() {
            const birthDate = new Date(this.value);
            if (!isNaN(birthDate.getTime())) {
                const today = new Date();
                let age = today.getFullYear() - birthDate.getFullYear();
                const monthDiff = today.getMonth() - birthDate.getMonth();

                // Adjust age if birthday hasn't occurred yet this year
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }

                // Only set age if it's a valid positive number
                if (age >= 0 && age < 150) {
                    ageInput.value = age;

                    // Visual feedback
                    ageInput.style.backgroundColor = '#d1e7dd';
                    ageInput.style.borderColor = '#198754';
                    setTimeout(() => {
                        ageInput.style.backgroundColor = '';
                        ageInput.style.borderColor = '';
                    }, 1000);
                } else {
                    ageInput.value = '';
                }
            } else {
                ageInput.value = '';
            }
        });

        // Also trigger on input for immediate feedback
        dateOfBirthInput.addEventListener('input', function() {
            const birthDate = new Date(this.value);
            if (!isNaN(birthDate.getTime()) && this.value.length === 10) {
                dateOfBirthInput.dispatchEvent(new Event('change'));
            }
        });
    }

    // Patient name search in Patient Record form
    const patientNameSearch = document.getElementById('patientNameSearch');
    if (patientNameSearch) {
        patientNameSearch.addEventListener('input', function() {
            clearTimeout(patientSearchTimeout);
            const searchTerm = this.value.trim();

            if (searchTerm.length < 2) {
                document.getElementById('patientNameSearchResults').innerHTML = '';
                return;
            }

            patientSearchTimeout = setTimeout(() => {
                searchPatientsForPatientName(searchTerm);
            }, 300);
        });

        // Clear results when clicking outside
        document.addEventListener('click', function(e) {
            if (!patientNameSearch.contains(e.target) &&
                !document.getElementById('patientNameSearchResults').contains(e.target)) {
                document.getElementById('patientNameSearchResults').innerHTML = '';
            }
        });
    }

    // Patient search in "Sent to" section
    const patientSearchInput = document.getElementById('patientSearchInput');
    if (patientSearchInput) {
        patientSearchInput.addEventListener('input', function() {
            clearTimeout(patientSearchTimeout);
            const searchTerm = this.value.trim();

            if (searchTerm.length < 2) {
                document.getElementById('patientSearchResults').innerHTML = '';
                return;
            }

            patientSearchTimeout = setTimeout(() => {
                searchPatientsForSendTo(searchTerm);
            }, 300);
        });

        // Clear results when clicking outside
        document.addEventListener('click', function(e) {
            if (!patientSearchInput.contains(e.target) &&
                !document.getElementById('patientSearchResults').contains(e.target)) {
                document.getElementById('patientSearchResults').innerHTML = '';
            }
        });
    }

    // Initialize patient record search if on that tab
    const patientRecordSection = document.getElementById('patient-record-section');
    if (patientRecordSection && !patientRecordSection.classList.contains('d-none')) {
        initializePatientRecordSearch();
    }
});

// Legacy functions kept for modal compatibility (auto-assign now)
function searchPatients(searchTerm) {
    // Not used anymore - patient is automatically assigned
    console.log('Patient search not needed - automatically assigned');
}

function displayPatientSearchResults(patients) {
    // Not used anymore - patient is automatically assigned
    console.log('Patient results not needed - automatically assigned');
}

function selectPatient(userId, username, fullName) {
    // Not used anymore - patient is automatically assigned
    console.log('Patient selection not needed - automatically assigned');
}

// Send record to patient (automatically handled in save)
function sendRecordToPatient() {
    // This function is now handled automatically in savePatientRecordForm
    savePatientRecordFromTab();
}

// Save patient record form
function savePatientRecordForm(callback) {
    const form = document.getElementById('patientInfoForm');
    if (!form) {
        console.error('Form not found');
        if (callback) callback();
        return;
    }

    const formData = new FormData(form);

    // Collect all form data including checkboxes and radio buttons
    const data = {};
    formData.forEach((value, key) => {
        data[key] = value;
    });

    // Ensure user_id is set (from currentPatientRecord if available)
    if (!data.user_id && window.currentPatientRecord && window.currentPatientRecord.user_id) {
        data.user_id = window.currentPatientRecord.user_id;
    }

    console.log('Saving record for user_id:', data.user_id);
    console.log('Full data being saved:', data);

    // Validate user_id before sending
    if (!data.user_id) {
        showNotification('warning', 'Please select a patient before saving the record.');
        return;
    }

    // Handle health questions
    const healthQuestions = {};
    ['good_health', 'under_treatment', 'serious_illness', 'hospitalized', 'taking_drugs', 'tobacco', 'alcohol', 'recreational_drugs'].forEach(q => {
        const checked = form.querySelector(`input[name="${q}"]:checked`);
        if (checked) healthQuestions[q] = checked.value;
    });
    data.health_questions = JSON.stringify(healthQuestions);

    // Handle allergies
    const allergies = {};
    ['allergy_anesthesia', 'allergy_antibiotics', 'allergy_analgesics', 'allergy_sulfa', 'allergy_aspirin', 'allergy_latex'].forEach(a => {
        allergies[a] = form.querySelector(`input[name="${a}"]`)?.checked || false;
    });
    if (form.querySelector('input[name="allergy_food"]')?.value) {
        allergies.food = form.querySelector('input[name="allergy_food"]').value;
    }
    if (form.querySelector('input[name="allergy_others"]')?.value) {
        allergies.others = form.querySelector('input[name="allergy_others"]').value;
    }
    data.allergies_detail = JSON.stringify(allergies);

    // Handle women's questions
    const pregnant = form.querySelector(`input[name="pregnant"]:checked`);
    if (pregnant) data.is_pregnant = pregnant.value === 'yes';

    const nursing = form.querySelector(`input[name="nursing"]:checked`);
    if (nursing) data.is_nursing = nursing.value === 'yes';

    const birthControl = form.querySelector(`input[name="birth_control"]:checked`);
    if (birthControl) data.takes_birth_control = birthControl.value === 'yes';

    // Automatically mark as sent to patient
    data.sent_to_patient = true;

    console.log('Saving patient record with data:', data);

    fetch('/staff/post-procedural/patient-record/store', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        // Check if response is ok
        if (!response.ok) {
            return response.json().then(err => {
                throw new Error(err.message || 'Server error');
            });
        }
        return response.json();
    })
    .then(data => {
        console.log('Save response:', data);
        if (data.success) {
            // Store the record ID that was saved
            const savedRecordId = data.data?.id || window.currentEditingRecordId;

            // Close the current modal properly
            const detailsModal = bootstrap.Modal.getInstance(document.getElementById('detailsModal'));
            if (detailsModal) {
                detailsModal.hide();
            }

            // Show success message
            showNotification('success', '✅ Record saved successfully!');

            // Reload the page after a short delay to show updated data
                setTimeout(() => {
                window.location.reload();
            }, 1000);

            if (callback) callback();
        } else {
            console.error('Save failed:', data);
            let errorMessage = 'Failed to save record';
            if (data.message) {
                errorMessage = data.message;
            } else if (data.errors) {
                const firstError = Object.values(data.errors)[0];
                errorMessage = Array.isArray(firstError) ? firstError[0] : firstError;
            }
            showNotification('error', '❌ ' + errorMessage);
        }
    })
    .catch(error => {
        console.error('Error saving record:', error);
        showNotification('error', '❌ An error occurred while saving the record: ' + error.message);
    });
}

// Update the save button click handler
document.addEventListener('DOMContentLoaded', function() {
    const saveBtn = document.getElementById('saveBtn');
    if (saveBtn) {
        saveBtn.addEventListener('click', function() {
            if (document.getElementById('patientInfoForm')) {
                savePatientRecordForm(() => {
                    // Modal content will auto-refresh after save
                    // No need to close or reload
                });
            }
        });
    }
});

// Trigger patient search manually
function triggerPatientSearch() {
    const searchInput = document.getElementById('patientSearchInput');
    const searchTerm = searchInput ? searchInput.value.trim() : '';

    console.log('Manual search triggered for:', searchTerm);

    if (searchTerm.length < 2) {
        showNotification('warning', 'Please enter at least 2 characters to search');
        return;
    }

    searchPatientsForSendTo(searchTerm);
}

// Search patients for patient name autocomplete in Patient Record form
function searchPatientsForPatientName(searchTerm) {
    console.log('Fetching patients for patient name:', searchTerm);

    fetch(`/staff/post-procedural/search-patients?q=${encodeURIComponent(searchTerm)}`)
        .then(response => response.json())
        .then(data => {
            console.log('Search results:', data);
            if (data.success) {
                displayPatientNameResults(data.data);
            } else {
                console.error('Search failed:', data);
                document.getElementById('patientNameSearchResults').innerHTML =
                    '<div class="search-result-item text-danger">Error loading patients</div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('patientNameSearchResults').innerHTML =
                '<div class="search-result-item text-danger">Error: ' + error.message + '</div>';
        });
}

function displayPatientNameResults(patients) {
    const resultsDiv = document.getElementById('patientNameSearchResults');

    console.log('Displaying', patients.length, 'patients for name search');

    if (!patients || patients.length === 0) {
        resultsDiv.innerHTML = '<div class="search-result-item text-muted"><i class="bi bi-info-circle me-2"></i>No patients with appointments found</div>';
        return;
    }

    resultsDiv.innerHTML = patients.map(patient => {
        const firstName = patient.first_name || '';
        const lastName = patient.last_name || '';
        const fullName = `${firstName} ${lastName}`.trim();

        return `
            <div class="search-result-item" onclick='selectPatientForRecord(${JSON.stringify(patient).replace(/'/g, "&#39;")})'>
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong><i class="bi bi-person-fill me-1"></i>${escapeHtml(patient.username || patient.name)}</strong>
                        <small class="text-muted d-block">${escapeHtml(fullName)}</small>
                    </div>
                </div>
            </div>
        `;
    }).join('');
}

function selectPatientForRecord(patientData) {
    console.log('Selected patient for record:', patientData);

    // Clear search results and input
    document.getElementById('patientNameSearchResults').innerHTML = '';
    document.getElementById('patientNameSearch').value = '';

    const firstName = patientData.first_name || '';
    const lastName = patientData.last_name || '';
    const username = patientData.username || patientData.name || '';

    // Show selected patient alert
    const alertBox = document.getElementById('selectedPatientInfoAlert');
    const alertText = document.getElementById('selectedPatientInfoText');
    alertText.textContent = `${username} - ${firstName} ${lastName}`;
    alertBox.classList.remove('d-none');

    // Auto-populate the patient name fields
    document.getElementById('lastName').value = lastName;
    document.getElementById('givenName').value = firstName;
    document.getElementById('middleName').value = ''; // Not provided by API

    // Auto-populate home address
    if (patientData.home_address) {
        document.getElementById('homeAddress').value = patientData.home_address;
    }

    // Auto-populate birthdate and age
    if (patientData.birthdate) {
        document.getElementById('dateOfBirth').value = patientData.birthdate;

        // Calculate age
        const today = new Date();
        const birthDate = new Date(patientData.birthdate);
        let age = today.getFullYear() - birthDate.getFullYear();
        const monthDiff = today.getMonth() - birthDate.getMonth();
        if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
            age--;
        }
        document.getElementById('age').value = age;
    }

    // Auto-populate sex/gender
    if (patientData.sex) {
        // Capitalize first letter
        const sex = patientData.sex.charAt(0).toUpperCase() + patientData.sex.slice(1).toLowerCase();
        document.getElementById('sex').value = sex;
    }

    // Auto-populate religion
    if (patientData.religion) {
        document.getElementById('religion').value = patientData.religion;
    }

    // Auto-populate occupation
    if (patientData.occupation) {
        document.getElementById('occupation').value = patientData.occupation;
    }

    // Auto-populate contact number
    if (patientData.contact_number) {
        document.getElementById('contact').value = patientData.contact_number;
    }

    // Auto-populate "Sent to" section
    document.getElementById('patientSearchInput').value = `${username} - ${firstName} ${lastName}`;
    document.getElementById('selectedPatientId').value = patientData.id;
    document.getElementById('selectedPatientDisplay').classList.remove('d-none');
    document.getElementById('selectedPatientText').textContent = `${username} - ${firstName} ${lastName}`;

    // Show success message
    showNotification('success', 'Patient information auto-filled successfully!');

    // Scroll to form fields smoothly
    setTimeout(() => {
        document.getElementById('homeAddress').scrollIntoView({ behavior: 'smooth', block: 'center' });
    }, 300);
}

// Search patients for "Send To" functionality
function searchPatientsForSendTo(searchTerm) {
    console.log('Fetching patients for send to:', searchTerm);

    fetch(`/staff/post-procedural/search-patients?q=${encodeURIComponent(searchTerm)}`)
        .then(response => response.json())
        .then(data => {
            console.log('Search results:', data);
            if (data.success) {
                displayPatientSendToResults(data.data);
            } else {
                console.error('Search failed:', data);
                document.getElementById('patientSearchResults').innerHTML =
                    '<div class="search-result-item text-danger">Error loading patients</div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('patientSearchResults').innerHTML =
                '<div class="search-result-item text-danger">Error: ' + error.message + '</div>';
        });
}

function displayPatientSendToResults(patients) {
    const resultsDiv = document.getElementById('patientSearchResults');

    console.log('Displaying', patients.length, 'patients');

    if (!patients || patients.length === 0) {
        resultsDiv.innerHTML = '<div class="search-result-item text-muted"><i class="bi bi-info-circle me-2"></i>No patients with appointments found</div>';
        return;
    }

    resultsDiv.innerHTML = patients.map(patient => {
        const firstName = patient.info?.first_name || '';
        const lastName = patient.info?.last_name || '';
        const fullName = `${firstName} ${lastName}`.trim();

        return `
            <div class="search-result-item" onclick="selectPatientForSendTo(${patient.id}, '${escapeHtml(patient.name)}', '${escapeHtml(fullName)}')">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong>${escapeHtml(patient.name)}</strong>
                        <small class="text-muted d-block">${escapeHtml(fullName)}</small>
                    </div>
                </div>
            </div>
        `;
    }).join('');
}

function selectPatientForSendTo(userId, username, fullName) {
    document.getElementById('patientSearchResults').innerHTML = '';
    document.getElementById('patientSearchInput').value = `${username} - ${fullName}`;
    document.getElementById('selectedPatientId').value = userId;

    // Show selected patient
    document.getElementById('selectedPatientDisplay').classList.remove('d-none');
    document.getElementById('selectedPatientText').textContent = `${username} - ${fullName}`;

    console.log('Selected patient for send to:', userId, username);
}

// Patient Record Tab Functions
function searchPatientsForRecord(searchTerm) {
    console.log('Fetching patients with term:', searchTerm);

    fetch(`/staff/post-procedural/search-patients?q=${encodeURIComponent(searchTerm)}`)
        .then(response => {
            console.log('Response status:', response.status);
            return response.json();
        })
        .then(data => {
            console.log('Search results:', data);
            if (data.success) {
                displayPatientRecordSearchResults(data.data);
            } else {
                console.error('Search failed:', data);
                document.getElementById('patientRecordSearchResults').innerHTML =
                    '<div class="search-result-item text-danger">Error loading patients</div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('patientRecordSearchResults').innerHTML =
                '<div class="search-result-item text-danger">Error: ' + error.message + '</div>';
        });
}

function displayPatientRecordSearchResults(patients) {
    const resultsDiv = document.getElementById('patientRecordSearchResults');

    console.log('Displaying', patients.length, 'patients');

    if (!patients || patients.length === 0) {
        resultsDiv.innerHTML = '<div class="search-result-item text-muted"><i class="bi bi-info-circle me-2"></i>No patients with appointments found</div>';
        return;
    }

    resultsDiv.innerHTML = patients.map(patient => {
        const firstName = patient.first_name || '';
        const lastName = patient.last_name || '';
        const fullName = `${firstName} ${lastName}`.trim();
        const username = patient.username || patient.name || '';

        return `
            <div class="search-result-item" onclick="loadPatientRecordIntoForm(${patient.id}, '${escapeHtml(username)}', '${escapeHtml(fullName)}')">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <strong>${escapeHtml(username)}</strong>
                        <small class="text-muted d-block">${escapeHtml(fullName)}</small>
                    </div>
                </div>
            </div>
        `;
    }).join('');
}

// Helper function to escape HTML
function escapeHtml(text) {
    if (!text) return '';
    const map = {
        '&': '&amp;',
        '<': '&lt;',
        '>': '&gt;',
        '"': '&quot;',
        "'": '&#039;'
    };
    return text.replace(/[&<>"']/g, m => map[m]);
}

function loadPatientRecordIntoForm(userId, username, fullName) {
    document.getElementById('patientRecordSearchResults').innerHTML = '';
    document.getElementById('patientRecordSearch').value = `${username} - ${fullName}`;

    // Show selected patient info
    document.getElementById('selectedPatientInfo').classList.remove('d-none');
    document.getElementById('selectedPatientName').textContent = `${username} - ${fullName}`;

    // Check if record exists for this user
    fetch(`/staff/post-procedural/patient-record-by-user/${userId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data) {
                // Load existing record
                currentPatientRecord = data.data;
            } else {
                // Create new record for this user
                currentPatientRecord = {
                    id: null,
                    user_id: userId,
                    user: {
                        id: userId,
                        name: username,
                        info: data.userInfo || {}
                    }
                };
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('error', 'Failed to load patient record');
        });
}

function createNewPatientRecord() {
    const searchValue = document.getElementById('patientRecordSearch').value;
    if (!searchValue || !currentPatientRecord) {
        showNotification('warning', 'Please search and select a patient first using the search box above');
        return;
    }

    // Show the form container
    document.getElementById('formContainerWrapper').classList.remove('d-none');

    // Populate the form with patient data from the system
    if (currentPatientRecord.user && currentPatientRecord.user.info) {
        const info = currentPatientRecord.user.info;
        document.getElementById('lastName').value = info.last_name || '';
        document.getElementById('givenName').value = info.first_name || '';
        document.getElementById('middleName').value = info.middle_name || '';
        document.getElementById('contact').value = info.phone || '';

        // Calculate age if birthdate exists
        if (info.birthdate) {
            document.getElementById('dateOfBirth').value = info.birthdate;
            const today = new Date();
            const birthDate = new Date(info.birthdate);
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            }
            document.getElementById('age').value = age;
        }
    }

    // Scroll to form
    document.getElementById('formContainerWrapper').scrollIntoView({ behavior: 'smooth', block: 'start' });
}

function renderPatientRecordForm(record) {
    const container = document.getElementById('patientRecordFormContainer');
    container.innerHTML = renderPatientInfoForm(record);

    // Populate form with existing data
    setTimeout(() => {
        populateFormWithData(record);
    }, 100);
}

// Populate form fields with existing data
function populateFormWithData(record) {
    const form = document.getElementById('patientInfoForm');
    if (!form) return;

    // Parse health questions
    let healthQuestions = {};
    try {
        healthQuestions = typeof record.health_questions === 'string' ? JSON.parse(record.health_questions) : (record.health_questions || {});
    } catch(e) {
        healthQuestions = {};
    }

    // Set health question radio buttons
    Object.entries(healthQuestions).forEach(([key, value]) => {
        const radio = form.querySelector(`input[name="${key}"][value="${value}"]`);
        if (radio) radio.checked = true;
    });

    // Parse and set allergies
    let allergies = {};
    try {
        allergies = typeof record.allergies_detail === 'string' ? JSON.parse(record.allergies_detail) : (record.allergies_detail || {});
    } catch(e) {
        allergies = {};
    }

    Object.entries(allergies).forEach(([key, value]) => {
        if (key === 'food') {
            const input = form.querySelector('input[name="allergy_food"]');
            if (input) input.value = value || '';
        } else if (key === 'others') {
            const input = form.querySelector('input[name="allergy_others"]');
            if (input) input.value = value || '';
        } else {
            const checkbox = form.querySelector(`input[name="${key}"]`);
            if (checkbox && value === true) checkbox.checked = true;
        }
    });

    // Set women's questions
    if (record.is_pregnant !== undefined && record.is_pregnant !== null) {
        const value = record.is_pregnant ? 'yes' : 'no';
        const radio = form.querySelector(`input[name="pregnant"][value="${value}"]`);
        if (radio) radio.checked = true;
    }

    if (record.is_nursing !== undefined && record.is_nursing !== null) {
        const value = record.is_nursing ? 'yes' : 'no';
        const radio = form.querySelector(`input[name="nursing"][value="${value}"]`);
        if (radio) radio.checked = true;
    }

    if (record.takes_birth_control !== undefined && record.takes_birth_control !== null) {
        const value = record.takes_birth_control ? 'yes' : 'no';
        const radio = form.querySelector(`input[name="birth_control"][value="${value}"]`);
        if (radio) radio.checked = true;
    }
}

function savePatientRecordFromTab() {
    // Collect form data
    const data = {
        home_address: document.getElementById('homeAddress').value,
        date_of_birth: document.getElementById('dateOfBirth').value,
        age: document.getElementById('age').value,
        sex: document.getElementById('sex').value,
        nickname: document.getElementById('nickname').value,
        religion: document.getElementById('religion').value,
        occupation: document.getElementById('occupation').value,
        contact: document.getElementById('contact').value,
        guardian_name: document.getElementById('guardianName').value,
        guardian_contact: document.getElementById('guardianContact').value,
        guardian_occupation: document.getElementById('guardianOccupation').value,
        notes: document.getElementById('otherNotes').value
    };

    // Check if patient is selected in "Sent to"
    const selectedPatientId = document.getElementById('selectedPatientId').value;
    if (selectedPatientId) {
        data.user_id = selectedPatientId;
        data.sent_to_patient = true;
    }

    console.log('Saving patient record with data:', data);

    fetch('/staff/post-procedural/patient-record/store', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        console.log('Save response:', result);
        if (result.success) {
            if (selectedPatientId) {
                showNotification('success', 'Record saved and sent to patient successfully!');
            } else {
                showNotification('success', 'Record saved successfully!');
            }
            // Reload the patient records table
            loadPatientRecords();
        } else {
            let errorMessage = 'Failed to save record';
            if (result.message) {
                errorMessage = result.message;
            } else if (result.errors) {
                const firstError = Object.values(result.errors)[0];
                errorMessage = Array.isArray(firstError) ? firstError[0] : firstError;
            }
            showNotification('error', errorMessage);
        }
    })
    .catch(error => {
        console.error('Error saving record:', error);
        showNotification('error', 'An error occurred while saving the record: ' + error.message);
    });
}

// Send record to patient
function sendRecordToPatient() {
    const selectedPatientId = document.getElementById('selectedPatientId').value;

    if (!selectedPatientId) {
        showNotification('warning', 'Please search and select a patient first');
        return;
    }

    // Collect form data and save with patient assignment
    const data = {
        user_id: selectedPatientId,
        home_address: document.getElementById('homeAddress').value,
        date_of_birth: document.getElementById('dateOfBirth').value,
        age: document.getElementById('age').value,
        sex: document.getElementById('sex').value,
        nickname: document.getElementById('nickname').value,
        religion: document.getElementById('religion').value,
        occupation: document.getElementById('occupation').value,
        contact: document.getElementById('contact').value,
        guardian_name: document.getElementById('guardianName').value,
        guardian_contact: document.getElementById('guardianContact').value,
        guardian_occupation: document.getElementById('guardianOccupation').value,
        notes: document.getElementById('otherNotes').value,
        sent_to_patient: true
    };

    console.log('Sending record to patient:', data);

    fetch('/staff/post-procedural/patient-record/store', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        console.log('Send response:', result);
        if (result.success) {
            const patientText = document.getElementById('selectedPatientText').textContent;
            showNotification('success', `Record successfully sent to ${patientText}!`);
            // Reload the patient records table
            loadPatientRecords();
            clearPatientRecordForm();
        } else {
            let errorMessage = 'Failed to send record';
            if (result.message) {
                errorMessage = result.message;
            } else if (result.errors) {
                const firstError = Object.values(result.errors)[0];
                errorMessage = Array.isArray(firstError) ? firstError[0] : firstError;
            }
            showNotification('error', errorMessage);
        }
    })
    .catch(error => {
        console.error('Error sending record:', error);
        showNotification('error', 'An error occurred while sending the record: ' + error.message);
    });
}

function clearPatientRecordForm() {
    showConfirmModal(
        'Clear Form',
        'Are you sure you want to clear the form? All unsaved data will be lost.',
        () => {
        // Hide selected patient alert
        document.getElementById('selectedPatientInfoAlert').classList.add('d-none');

        // Clear patient name search
        document.getElementById('patientNameSearch').value = '';
        document.getElementById('patientNameSearchResults').innerHTML = '';

        // Clear all form fields
        document.getElementById('lastName').value = '';
        document.getElementById('givenName').value = '';
        document.getElementById('middleName').value = '';
        document.getElementById('homeAddress').value = '';
        document.getElementById('dateOfBirth').value = '';
        document.getElementById('age').value = '';
        document.getElementById('sex').value = '';
        document.getElementById('nickname').value = '';
        document.getElementById('religion').value = '';
        document.getElementById('occupation').value = '';
        document.getElementById('contact').value = '';
        document.getElementById('guardianName').value = '';
        document.getElementById('guardianContact').value = '';
        document.getElementById('guardianOccupation').value = '';
        document.getElementById('otherNotes').value = '';

        // Reset "Sent to" section
        document.getElementById('patientSearchInput').value = '';
        document.getElementById('selectedPatientId').value = '';
        document.getElementById('selectedPatientDisplay').classList.add('d-none');
        document.getElementById('patientSearchResults').innerHTML = '';

        currentPatientRecord = null;

        // Scroll back to top
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
    );
}

function printPatientRecord() {
    const formContent = document.getElementById('patientRecordFormContainer').innerHTML;
    const printWindow = window.open('', '', 'height=600,width=800');
    printWindow.document.write('<html><head><title>Patient Record</title>');
    printWindow.document.write('<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css">');
    printWindow.document.write('<style>');
    printWindow.document.write('@media print { .no-print, .send-to-patient-section { display: none; } }');
    printWindow.document.write('.patient-record-form { font-size: 12px; }');
    printWindow.document.write('.section-title { font-size: 14px; font-weight: 700; margin: 10px 0 6px 0; padding: 4px 0; border-bottom: 1px solid #dee2e6; }');
    printWindow.document.write('.question-row { display: flex; align-items: center; padding: 3px 0; font-size: 11px; gap: 8px; }');
    printWindow.document.write('.q-number { min-width: 20px; font-weight: 600; }');
    printWindow.document.write('.q-text { flex: 1; }');
    printWindow.document.write('.q-options { display: flex; gap: 15px; min-width: 100px; justify-content: flex-end; }');
    printWindow.document.write('.form-label-sm { font-size: 11px; font-weight: 500; margin-bottom: 2px; }');
    printWindow.document.write('.form-control-sm { font-size: 11px; padding: 4px 8px; }');
    printWindow.document.write('.form-check-sm { font-size: 11px; }');
    printWindow.document.write('</style>');
    printWindow.document.write('</head><body>');
    printWindow.document.write(formContent);
    printWindow.document.write('</body></html>');
    printWindow.document.close();

    setTimeout(() => {
        printWindow.print();
    }, 250);
}

// Patient History Functions
function showAddHistoryForm() {
    document.getElementById('addHistoryFormContainer').classList.remove('d-none');
}

function hideAddHistoryForm() {
    document.getElementById('addHistoryFormContainer').classList.add('d-none');
}

function addNewPatientHistory(recordId) {
    const form = document.getElementById('newHistoryForm');
    const formData = new FormData(form);
    const data = {
        patient_record_id: recordId,
        visit_date: formData.get('visit_date'),
        procedure_performed: formData.get('procedure_performed'),
        materials_used: formData.get('materials_used'),
        anesthesia_used: formData.get('anesthesia_used'),
        complications: formData.get('complications'),
        post_operative_instructions: formData.get('post_operative_instructions'),
        follow_up_notes: formData.get('follow_up_notes')
    };

    fetch('/staff/post-procedural/patient-history/store', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            showNotification('success', 'Patient history added successfully!');
            // Delay reload to let user see the notification
            setTimeout(() => {
            editPatientInfo(window.currentEditingRecordId);
            }, 800);
        } else {
            showNotification('error', 'Failed to add patient history');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Error adding patient history');
    });
}

function savePatientHistory(historyId, recordId) {
    const form = document.getElementById(`historyForm${historyId}`);
    const formData = new FormData(form);
    const data = {
        id: historyId,
        patient_record_id: recordId,
        visit_date: formData.get('visit_date'),
        procedure_performed: formData.get('procedure_performed'),
        materials_used: formData.get('materials_used'),
        anesthesia_used: formData.get('anesthesia_used'),
        complications: formData.get('complications'),
        post_operative_instructions: formData.get('post_operative_instructions'),
        follow_up_notes: formData.get('follow_up_notes')
    };

    fetch('/staff/post-procedural/patient-history/store', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            showNotification('success', 'Patient history updated successfully!');
        } else {
            showNotification('error', 'Failed to update patient history');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Error updating patient history');
    });
}

// Staff users are not allowed to delete patient history
function deletePatientHistory(historyId) {
    showNotification('warning', 'Staff members do not have permission to delete patient history. Please contact an administrator.');
}

// Progress Notes Functions
function showAddNoteForm() {
    document.getElementById('addNoteFormContainer').classList.remove('d-none');
}

function hideAddNoteForm() {
    document.getElementById('addNoteFormContainer').classList.add('d-none');
}

function addNewProgressNote(recordId) {
    const form = document.getElementById('newNoteForm');
    const formData = new FormData(form);
    const data = {
        patient_record_id: recordId,
        note_date: formData.get('note_date'),
        progress_description: formData.get('progress_description'),
        treatment_response: formData.get('treatment_response'),
        next_steps: formData.get('next_steps'),
        status: formData.get('status')
    };

    fetch('/staff/post-procedural/progress-note/store', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            showNotification('success', 'Progress note added successfully!');
            // Delay reload to let user see the notification
            setTimeout(() => {
            editPatientInfo(window.currentEditingRecordId);
            }, 800);
        } else {
            showNotification('error', 'Failed to add progress note');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Error adding progress note');
    });
}

function saveProgressNote(noteId, recordId) {
    const form = document.getElementById(`noteForm${noteId}`);
    const formData = new FormData(form);
    const data = {
        id: noteId,
        patient_record_id: recordId,
        note_date: formData.get('note_date'),
        progress_description: formData.get('progress_description'),
        treatment_response: formData.get('treatment_response'),
        next_steps: formData.get('next_steps'),
        status: formData.get('status')
    };

    fetch('/staff/post-procedural/progress-note/store', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            showNotification('success', 'Progress note updated successfully!');
        } else {
            showNotification('error', 'Failed to update progress note');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('error', 'Error updating progress note');
    });
}

// Staff users are not allowed to delete progress notes
function deleteProgressNote(noteId) {
    showNotification('warning', 'Staff members do not have permission to delete progress notes. Please contact an administrator.');
}

// Success Modal Functions
function showSuccessModal(message, callback) {
    console.log('🎉 showSuccessModal called with message:', message);
    console.log('🔍 Looking for successModal element...');

    const messageEl = document.getElementById('successModalMessage');
    const modalEl = document.getElementById('successModal');

    console.log('📝 Message element:', messageEl);
    console.log('🎭 Modal element:', modalEl);
    console.log('📦 Bootstrap:', typeof bootstrap);

    if (!modalEl) {
        console.error('❌ SUCCESS MODAL ELEMENT NOT FOUND!');
        alert('Record saved and sent to patient successfully!');
        if (callback) callback();
        return;
    }

    messageEl.textContent = message;
    const modal = new bootstrap.Modal(modalEl);
    console.log('✅ Modal created, showing...');
    modal.show();

    // Store callback for later use
    window.successModalCallback = callback;
    console.log('💾 Callback stored');
}

function closeSuccessModal() {
    const modal = bootstrap.Modal.getInstance(document.getElementById('successModal'));
    if (modal) {
        modal.hide();
    }

    // Execute callback if exists
    if (window.successModalCallback) {
        window.successModalCallback();
        window.successModalCallback = null;
    }
}

// Confirm Modal Functions
function showConfirmModal(title, message, onConfirm, type = 'warning') {
    document.getElementById('confirmModalTitle').textContent = title;
    document.getElementById('confirmModalMessage').textContent = message;

    // Update icon and colors based on type
    const iconDiv = document.getElementById('confirmModalIcon');
    const btn = document.getElementById('confirmModalBtn');

    if (type === 'danger') {
        iconDiv.style.background = 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)';
        iconDiv.style.boxShadow = '0 8px 20px rgba(239, 68, 68, 0.4)';
        iconDiv.innerHTML = '<i class="bi bi-trash" style="font-size: 3rem; color: white; font-weight: bold;"></i>';
        btn.className = 'btn btn-danger btn-lg px-4';
        btn.style.background = 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)';
        btn.style.boxShadow = '0 4px 12px rgba(239, 68, 68, 0.3)';
        btn.innerHTML = '<i class="bi bi-trash me-2"></i>Delete';
        document.getElementById('confirmModalTitle').style.color = '#dc2626';
        } else {
        iconDiv.style.background = 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)';
        iconDiv.style.boxShadow = '0 8px 20px rgba(245, 158, 11, 0.4)';
        iconDiv.innerHTML = '<i class="bi bi-question-circle" style="font-size: 3rem; color: white; font-weight: bold;"></i>';
        btn.className = 'btn btn-primary btn-lg px-4';
        btn.style.background = '';
        btn.style.boxShadow = '';
        btn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Confirm';
        document.getElementById('confirmModalTitle').style.color = '#d97706';
    }

    const modal = new bootstrap.Modal(document.getElementById('confirmModal'));
    modal.show();

    // Store callback for later use
    window.confirmModalCallback = onConfirm;
}

function closeConfirmModal(confirmed) {
    const modal = bootstrap.Modal.getInstance(document.getElementById('confirmModal'));
    if (modal) {
        modal.hide();
    }

    // Execute callback if confirmed and exists
    if (confirmed && window.confirmModalCallback) {
        window.confirmModalCallback();
    }
    window.confirmModalCallback = null;
}

// Custom Notification System
function showNotification(type, message, duration = 3000) {
    // Remove any existing notification
    const existing = document.getElementById('customNotification');
    if (existing) {
        existing.remove();
    }

    // Define notification styles based on type
    const styles = {
        success: {
            bg: 'linear-gradient(135deg, #10b981 0%, #059669 100%)',
            icon: 'bi-check-circle-fill',
            title: 'Success!'
        },
        error: {
            bg: 'linear-gradient(135deg, #ef4444 0%, #dc2626 100%)',
            icon: 'bi-x-circle-fill',
            title: 'Error!'
        },
        info: {
            bg: 'linear-gradient(135deg, #3b82f6 0%, #2563eb 100%)',
            icon: 'bi-info-circle-fill',
            title: 'Information'
        },
        warning: {
            bg: 'linear-gradient(135deg, #f59e0b 0%, #d97706 100%)',
            icon: 'bi-exclamation-triangle-fill',
            title: 'Warning!'
        }
    };

    const style = styles[type] || styles.info;

    // Create notification element
    const notification = document.createElement('div');
    notification.id = 'customNotification';
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 99999;
        min-width: 350px;
        max-width: 500px;
        background: ${style.bg};
        color: white;
        padding: 20px 24px;
        border-radius: 12px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3), 0 0 20px rgba(255, 255, 255, 0.1);
        animation: slideInRight 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55);
        font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
    `;

    notification.innerHTML = `
        <div style="display: flex; align-items: center; gap: 16px;">
            <div style="flex-shrink: 0;">
                <i class="bi ${style.icon}" style="font-size: 32px; filter: drop-shadow(0 2px 4px rgba(0,0,0,0.2));"></i>
            </div>
            <div style="flex-grow: 1;">
                <div style="font-weight: 700; font-size: 16px; margin-bottom: 4px; text-shadow: 0 1px 2px rgba(0,0,0,0.1);">
                    ${style.title}
                </div>
                <div style="font-size: 14px; line-height: 1.5; opacity: 0.95;">
                    ${message}
                </div>
            </div>
            <button onclick="this.parentElement.parentElement.remove()"
                    style="flex-shrink: 0; background: rgba(255,255,255,0.2); border: none; color: white; width: 28px; height: 28px; border-radius: 50%; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 18px; transition: all 0.2s; backdrop-filter: blur(10px);"
                    onmouseover="this.style.background='rgba(255,255,255,0.3)'; this.style.transform='scale(1.1)';"
                    onmouseout="this.style.background='rgba(255,255,255,0.2)'; this.style.transform='scale(1)';">
                <i class="bi bi-x"></i>
            </button>
        </div>
        <div style="position: absolute; bottom: 0; left: 0; height: 4px; background: rgba(255,255,255,0.3); width: 100%; border-radius: 0 0 12px 12px; overflow: hidden;">
            <div style="height: 100%; background: rgba(255,255,255,0.6); width: 100%; animation: progressBar ${duration}ms linear;"></div>
        </div>
    `;

    // Add animations
    const styleSheet = document.createElement('style');
    styleSheet.textContent = `
        @keyframes slideInRight {
            from {
                transform: translateX(400px);
                opacity: 0;
            }
            to {
                transform: translateX(0);
                opacity: 1;
            }
        }
        @keyframes slideOutRight {
            from {
                transform: translateX(0);
                opacity: 1;
            }
            to {
                transform: translateX(400px);
                opacity: 0;
            }
        }
        @keyframes progressBar {
            from {
                width: 100%;
            }
            to {
                width: 0%;
            }
        }
    `;
    document.head.appendChild(styleSheet);

    document.body.appendChild(notification);

    // Auto remove after duration
    setTimeout(() => {
        notification.style.animation = 'slideOutRight 0.4s cubic-bezier(0.68, -0.55, 0.265, 1.55)';
        setTimeout(() => notification.remove(), 400);
    }, duration);
}

// ==================== PATIENT HISTORY SEARCH FUNCTIONS ====================
let selectedPatientForHistory = null;

// Search patients for medical history
function searchPatientsForHistory() {
    const searchTerm = document.getElementById('historyPatientSearch').value.trim();

    if (!searchTerm) {
        showNotification('Please enter a search term', 'warning');
        return;
    }

    fetch(`/staff/post-procedural/search-patients?search=${encodeURIComponent(searchTerm)}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data.length > 0) {
                displayHistoryPatientList(data.data);
                document.getElementById('historyResultCount').textContent = data.data.length;
            } else {
                document.getElementById('historyPatientListContainer').innerHTML = `
                    <div class="text-center py-5">
                        <i class="bi bi-inbox text-muted" style="font-size: 3rem;"></i>
                        <p class="text-muted mt-3">No patients found</p>
                    </div>
                `;
                document.getElementById('historyResultCount').textContent = '0';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error searching for patients', 'danger');
        });
}

// Display patient list for history search
function displayHistoryPatientList(patients) {
    const html = patients.map(patient => `
        <div class="list-group-item list-group-item-action"
             style="cursor: pointer; border-left: 4px solid #2196F3;"
             onclick="selectPatientForHistory(${patient.id}, '${patient.name}')">
            <div class="d-flex w-100 justify-content-between">
                <h6 class="mb-1">${patient.name}</h6>
                <small class="text-muted">ID: ${patient.id}</small>
            </div>
            <p class="mb-1 text-muted small">
                <i class="bi bi-envelope me-1"></i>${patient.email || 'No email'}
            </p>
            ${patient.phone ? `<small class="text-muted"><i class="bi bi-telephone me-1"></i>${patient.phone}</small>` : ''}
        </div>
    `).join('');

    document.getElementById('historyPatientListContainer').innerHTML = html;
}

// Select patient and show fillable form
function selectPatientForHistory(patientId, patientName) {
    selectedPatientForHistory = { id: patientId, name: patientName };

    // Show toggle button
    document.getElementById('toggleHistoryView').style.display = 'block';

    // Display the fillable medical history form
    displayMedicalHistoryForm(patientId, patientName);
}

// Display fillable medical history form
function displayMedicalHistoryForm(patientId, patientName) {
    const html = `
        <div class="patient-info-header mb-4 p-3 bg-light rounded">
            <h5 class="mb-0"><i class="bi bi-person-circle me-2"></i>${patientName}</h5>
            <p class="mb-0 text-muted"><small>Fill out the medical history form below</small></p>
        </div>

        <form id="medicalHistoryForm">
            <input type="hidden" name="patient_id" value="${patientId}">

            <!-- DENTAL HISTORY -->
            <div class="mb-4">
                <h6 class="fw-bold mb-3" style="color: #0a4275; border-bottom: 2px solid #0a4275; padding-bottom: 0.5rem;">DENTAL HISTORY</h6>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Previous Dentist</label>
                        <input type="text" class="form-control" name="previous_dentist" style="border: 1px solid #000;">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Last dental visit</label>
                        <input type="date" class="form-control" name="last_dental_visit" style="border: 1px solid #000;">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Treatment done</label>
                        <input type="text" class="form-control" name="treatment_done" style="border: 1px solid #000;">
                    </div>
                </div>
            </div>

            <!-- MEDICAL HISTORY -->
            <div class="mb-4">
                <h6 class="fw-bold mb-3" style="color: #0a4275; border-bottom: 2px solid #0a4275; padding-bottom: 0.5rem;">MEDICAL HISTORY</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Name of Physician</label>
                        <input type="text" class="form-control" name="physician_name" style="border: 1px solid #000;">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Specialty</label>
                        <input type="text" class="form-control" name="physician_specialty" style="border: 1px solid #000;">
                    </div>
                    <div class="col-md-8">
                        <label class="form-label fw-semibold">Office address</label>
                        <input type="text" class="form-control" name="physician_office_address" style="border: 1px solid #000;">
                    </div>
                    <div class="col-md-4">
                        <label class="form-label fw-semibold">Contact No.</label>
                        <input type="text" class="form-control" name="physician_contact" style="border: 1px solid #000;">
                    </div>
                </div>
            </div>

            <!-- HEALTH QUESTIONS -->
            <div class="mb-4">
                <div class="bg-white p-3 rounded" style="border: 1px solid #dee2e6;">
                    <div class="row g-2">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom: 1px solid #000;">
                                <span class="fw-semibold">1. Are you in good health?</span>
                                <div class="d-flex gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="good_health" value="yes" id="goodHealthYes">
                                        <label class="form-check-label fw-semibold" for="goodHealthYes">YES</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="good_health" value="no" id="goodHealthNo">
                                        <label class="form-check-label fw-semibold" for="goodHealthNo">NO</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom: 1px solid #000;">
                                <span class="fw-semibold">2. Are you under any medical treatment now?</span>
                                <div class="d-flex gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="under_treatment" value="yes" id="treatmentYes">
                                        <label class="form-check-label fw-semibold" for="treatmentYes">YES</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="under_treatment" value="no" id="treatmentNo">
                                        <label class="form-check-label fw-semibold" for="treatmentNo">NO</label>
                                    </div>
                                </div>
                            </div>
                            <div class="ps-4 mt-2">
                                <input type="text" class="form-control form-control-sm" name="treatment_condition" placeholder="If yes, what condition is being treated?" style="border: 1px solid #000;">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom: 1px solid #000;">
                                <span class="fw-semibold">3. Have you ever had any serious illness or surgery?</span>
                                <div class="d-flex gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="serious_illness" value="yes" id="illnessYes">
                                        <label class="form-check-label fw-semibold" for="illnessYes">YES</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="serious_illness" value="no" id="illnessNo">
                                        <label class="form-check-label fw-semibold" for="illnessNo">NO</label>
                                    </div>
                                </div>
                            </div>
                            <div class="ps-4 mt-2">
                                <input type="text" class="form-control form-control-sm" name="illness_details" placeholder="If yes, what illness or surgery?" style="border: 1px solid #000;">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom: 1px solid #000;">
                                <span class="fw-semibold">4. Have you ever been hospitalized?</span>
                                <div class="d-flex gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="been_hospitalized" value="yes" id="hospitalizedYes">
                                        <label class="form-check-label fw-semibold" for="hospitalizedYes">YES</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="been_hospitalized" value="no" id="hospitalizedNo">
                                        <label class="form-check-label fw-semibold" for="hospitalizedNo">NO</label>
                                    </div>
                                </div>
                            </div>
                            <div class="ps-4 mt-2">
                                <input type="text" class="form-control form-control-sm" name="hospitalization_reason" placeholder="If yes, when and why?" style="border: 1px solid #000;">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom: 1px solid #000;">
                                <span class="fw-semibold">5. Are you taking any prescription or non prescription drugs?</span>
                                <div class="d-flex gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="taking_drugs" value="yes" id="drugsYes">
                                        <label class="form-check-label fw-semibold" for="drugsYes">YES</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="taking_drugs" value="no" id="drugsNo">
                                        <label class="form-check-label fw-semibold" for="drugsNo">NO</label>
                                    </div>
                                </div>
                            </div>
                            <div class="ps-4 mt-2">
                                <input type="text" class="form-control form-control-sm" name="medications" placeholder="If yes, what medications?" style="border: 1px solid #000;">
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom: 1px solid #000;">
                                <span class="fw-semibold">6. Do you use any tobacco products?</span>
                                <div class="d-flex gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="tobacco_use" value="yes" id="tobaccoYes">
                                        <label class="form-check-label fw-semibold" for="tobaccoYes">YES</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="tobacco_use" value="no" id="tobaccoNo">
                                        <label class="form-check-label fw-semibold" for="tobaccoNo">NO</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom: 1px solid #000;">
                                <span class="fw-semibold">7. Do you drink alcoholic beverages?</span>
                                <div class="d-flex gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="alcohol_use" value="yes" id="alcoholYes">
                                        <label class="form-check-label fw-semibold" for="alcoholYes">YES</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="alcohol_use" value="no" id="alcoholNo">
                                        <label class="form-check-label fw-semibold" for="alcoholNo">NO</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom: 1px solid #000;">
                                <span class="fw-semibold">8. Do you take any recreational drugs?</span>
                                <div class="d-flex gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="recreational_drugs" value="yes" id="recreationalYes">
                                        <label class="form-check-label fw-semibold" for="recreationalYes">YES</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="recreational_drugs" value="no" id="recreationalNo">
                                        <label class="form-check-label fw-semibold" for="recreationalNo">NO</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="py-2">
                                <strong class="fw-semibold">9. Are you allergic to the following:</strong>
                            </div>
                            <div class="row g-2 ps-4">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center gap-2">
                                        <input class="form-check-input" type="checkbox" name="allergy_anesthesia" value="1" id="allergyAnesthesia" style="margin-top: 0;">
                                        <label class="form-check-label" for="allergyAnesthesia">____Local Anesthesia (e.g., Lidocaine)</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center gap-2">
                                        <input class="form-check-input" type="checkbox" name="allergy_sulfa" value="1" id="allergySulfa" style="margin-top: 0;">
                                        <label class="form-check-label" for="allergySulfa">____Sulfa drugs</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center gap-2">
                                        <input class="form-check-input" type="checkbox" name="allergy_antibiotics" value="1" id="allergyAntibiotics" style="margin-top: 0;">
                                        <label class="form-check-label" for="allergyAntibiotics">____Antibiotics (e.g., Amoxicillin)</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center gap-2">
                                        <input class="form-check-input" type="checkbox" name="allergy_aspirin" value="1" id="allergyAspirin" style="margin-top: 0;">
                                        <label class="form-check-label" for="allergyAspirin">____Aspirin</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center gap-2">
                                        <input class="form-check-input" type="checkbox" name="allergy_analgesics" value="1" id="allergyAnalgesics" style="margin-top: 0;">
                                        <label class="form-check-label" for="allergyAnalgesics">____Analgesics (e.g., Mefenamic Acid)</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center gap-2">
                                        <input class="form-check-input" type="checkbox" name="allergy_latex" value="1" id="allergyLatex" style="margin-top: 0;">
                                        <label class="form-check-label" for="allergyLatex">____Latex (e.g., Gloves)</label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center gap-2">
                                        <input class="form-check-input" type="checkbox" name="allergy_food" value="1" id="allergyFood" style="margin-top: 0;">
                                        <label class="form-check-label" for="allergyFood">____Food (Please specify:</label>
                                        <input type="text" class="form-control form-control-sm d-inline-block" name="food_allergy_details" placeholder="" style="width: 200px; border: 1px solid #000; border-bottom: 2px solid #000;">
                                        <span>)</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center gap-2">
                                        <input class="form-check-input" type="checkbox" name="allergy_others" value="1" id="allergyOthers" style="margin-top: 0;">
                                        <label class="form-check-label" for="allergyOthers">____Others (Please specify:</label>
                                        <input type="text" class="form-control form-control-sm d-inline-block" name="other_allergy_details" placeholder="" style="width: 200px; border: 1px solid #000; border-bottom: 2px solid #000;">
                                        <span>)</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- FOR WOMEN -->
            <div class="mb-4">
                <h6 class="fw-bold mb-3" style="color: #0a4275; border-bottom: 2px solid #0a4275; padding-bottom: 0.5rem;">For women:</h6>
                <div class="bg-white p-3 rounded" style="border: 1px solid #dee2e6;">
                    <div class="row g-2">
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom: 1px solid #000;">
                                <span class="fw-semibold">1. Are you pregnant?</span>
                                <div class="d-flex gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="is_pregnant" value="yes" id="pregnantYes">
                                        <label class="form-check-label fw-semibold" for="pregnantYes">YES</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="is_pregnant" value="no" id="pregnantNo">
                                        <label class="form-check-label fw-semibold" for="pregnantNo">NO</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom: 1px solid #000;">
                                <span class="fw-semibold">2. Are you currently nursing?</span>
                                <div class="d-flex gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="is_nursing" value="yes" id="nursingYes">
                                        <label class="form-check-label fw-semibold" for="nursingYes">YES</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="is_nursing" value="no" id="nursingNo">
                                        <label class="form-check-label fw-semibold" for="nursingNo">NO</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-12">
                            <div class="d-flex justify-content-between align-items-center py-2" style="border-bottom: 1px solid #000;">
                                <span class="fw-semibold">3. Are you currently taking birth control pills?</span>
                                <div class="d-flex gap-4">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="birth_control" value="yes" id="birthControlYes">
                                        <label class="form-check-label fw-semibold" for="birthControlYes">YES</label>
                                    </div>
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="birth_control" value="no" id="birthControlNo">
                                        <label class="form-check-label fw-semibold" for="birthControlNo">NO</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- VISIT & PROCEDURE DETAILS -->
            <div class="mb-4">
                <h6 class="fw-bold mb-3" style="color: #0a4275; border-bottom: 2px solid #0a4275; padding-bottom: 0.5rem;">VISIT & PROCEDURE DETAILS</h6>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Visit Date <span class="text-danger">*</span></label>
                        <input type="date" class="form-control" name="visit_date" required style="border: 1px solid #000;">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold">Anesthesia Used</label>
                        <input type="text" class="form-control" name="anesthesia_used" style="border: 1px solid #000;">
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Procedure Performed</label>
                        <textarea class="form-control" name="procedure_performed" rows="2" style="border: 1px solid #000;"></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Materials Used</label>
                        <textarea class="form-control" name="materials_used" rows="2" style="border: 1px solid #000;"></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Complications</label>
                        <textarea class="form-control" name="complications" rows="2" style="border: 1px solid #000;"></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Post-Operative Instructions</label>
                        <textarea class="form-control" name="post_operative_instructions" rows="2" style="border: 1px solid #000;"></textarea>
                    </div>
                    <div class="col-12">
                        <label class="form-label fw-semibold">Follow-up Notes</label>
                        <textarea class="form-control" name="follow_up_notes" rows="2" style="border: 1px solid #000;"></textarea>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="col-12 mt-4 mb-3">
                <button type="button" class="btn btn-primary btn-lg" onclick="saveMedicalHistoryForm()" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%); border: none; padding: 10px 24px; font-weight: 600;">
                    <i class="bi bi-check-circle me-1"></i>Save Medical History
                </button>
                <button type="button" class="btn btn-secondary btn-lg" onclick="clearHistorySearch()" style="padding: 10px 24px; font-weight: 600;">
                    <i class="bi bi-x-circle me-1"></i>Cancel
                </button>
            </div>
        </form>
    `;

    document.getElementById('patientHistoryDetailsContainer').innerHTML = html;
}

// Display patient medical history
function displayPatientMedicalHistory(history, patientName) {
    if (!history || history.length === 0) {
        document.getElementById('patientHistoryDetailsContainer').innerHTML = `
            <div class="text-center py-5">
                <i class="bi bi-info-circle text-muted" style="font-size: 3rem;"></i>
                <p class="mt-3 text-muted">No medical history records found</p>
            </div>
        `;
        return;
    }

    const html = `
        <div class="patient-info-header mb-4 p-3 bg-light rounded">
            <h5 class="mb-0"><i class="bi bi-person-circle me-2"></i>${patientName}</h5>
            <p class="mb-0 text-muted"><small>Medical History Records: ${history.length}</small></p>
        </div>

        ${history.map((record, index) => `
            <div class="card mb-3 shadow-sm" style="border-left: 4px solid #2196F3;">
                <div class="card-header bg-light">
                    <div class="d-flex justify-content-between align-items-center">
                        <h6 class="mb-0">
                            <i class="bi bi-calendar-event me-2"></i>
                            Visit: ${record.visit_date ? new Date(record.visit_date).toLocaleDateString() : 'N/A'}
                        </h6>
                        <span class="badge bg-primary">Record #${index + 1}</span>
                    </div>
                </div>
                <div class="card-body">
                    <!-- Dental History -->
                    ${record.previous_dentist || record.last_dental_visit || record.treatment_done ? `
                    <div class="mb-3">
                        <h6 class="text-primary"><i class="bi bi-tooth me-2"></i>DENTAL HISTORY</h6>
                        <div class="row">
                            ${record.previous_dentist ? `<div class="col-md-4"><strong>Previous Dentist:</strong> ${record.previous_dentist}</div>` : ''}
                            ${record.last_dental_visit ? `<div class="col-md-4"><strong>Last Visit:</strong> ${new Date(record.last_dental_visit).toLocaleDateString()}</div>` : ''}
                            ${record.treatment_done ? `<div class="col-md-4"><strong>Treatment Done:</strong> ${record.treatment_done}</div>` : ''}
                        </div>
                    </div>
                    <hr>
                    ` : ''}

                    <!-- Medical History -->
                    ${record.physician_name || record.physician_specialty ? `
                    <div class="mb-3">
                        <h6 class="text-primary"><i class="bi bi-hospital me-2"></i>MEDICAL HISTORY</h6>
                        <div class="row">
                            ${record.physician_name ? `<div class="col-md-6"><strong>Physician:</strong> ${record.physician_name}</div>` : ''}
                            ${record.physician_specialty ? `<div class="col-md-6"><strong>Specialty:</strong> ${record.physician_specialty}</div>` : ''}
                            ${record.physician_office_address ? `<div class="col-md-6"><strong>Office:</strong> ${record.physician_office_address}</div>` : ''}
                            ${record.physician_contact ? `<div class="col-md-6"><strong>Contact:</strong> ${record.physician_contact}</div>` : ''}
                        </div>
                    </div>
                    <hr>
                    ` : ''}

                    <!-- Health Questions -->
                    <div class="mb-3">
                        <h6 class="text-primary"><i class="bi bi-clipboard-pulse me-2"></i>HEALTH ASSESSMENT</h6>
                        <div class="row g-2">
                            ${record.good_health ? `<div class="col-md-4"><span class="badge ${record.good_health === 'yes' ? 'bg-success' : 'bg-warning'}">Good Health: ${record.good_health.toUpperCase()}</span></div>` : ''}
                            ${record.under_treatment ? `<div class="col-md-4"><span class="badge ${record.under_treatment === 'yes' ? 'bg-warning' : 'bg-success'}">Under Treatment: ${record.under_treatment.toUpperCase()}</span></div>` : ''}
                            ${record.tobacco_use ? `<div class="col-md-4"><span class="badge ${record.tobacco_use === 'yes' ? 'bg-danger' : 'bg-success'}">Tobacco: ${record.tobacco_use.toUpperCase()}</span></div>` : ''}
                            ${record.treatment_condition ? `<div class="col-12"><small class="text-muted">Condition being treated: ${record.treatment_condition}</small></div>` : ''}
                        </div>
                    </div>

                    <!-- Allergies -->
                    ${(record.allergy_anesthesia || record.allergy_antibiotics || record.allergy_sulfa || record.allergy_aspirin) ? `
                    <div class="mb-3">
                        <h6 class="text-danger"><i class="bi bi-exclamation-triangle me-2"></i>ALLERGIES</h6>
                        <div class="d-flex flex-wrap gap-2">
                            ${record.allergy_anesthesia ? '<span class="badge bg-danger">Local Anesthesia</span>' : ''}
                            ${record.allergy_antibiotics ? '<span class="badge bg-danger">Antibiotics</span>' : ''}
                            ${record.allergy_sulfa ? '<span class="badge bg-danger">Sulfa Drugs</span>' : ''}
                            ${record.allergy_aspirin ? '<span class="badge bg-danger">Aspirin</span>' : ''}
                            ${record.allergy_analgesics ? '<span class="badge bg-danger">Analgesics</span>' : ''}
                            ${record.allergy_latex ? '<span class="badge bg-danger">Latex</span>' : ''}
                            ${record.food_allergy_details ? `<span class="badge bg-danger">Food: ${record.food_allergy_details}</span>` : ''}
                            ${record.other_allergy_details ? `<span class="badge bg-danger">Other: ${record.other_allergy_details}</span>` : ''}
                        </div>
                    </div>
                    <hr>
                    ` : ''}

                    <!-- Procedure Details -->
                    ${record.procedure_performed || record.anesthesia_used ? `
                    <div class="mb-3">
                        <h6 class="text-primary"><i class="bi bi-file-medical me-2"></i>PROCEDURE DETAILS</h6>
                        ${record.procedure_performed ? `<p><strong>Procedure:</strong> ${record.procedure_performed}</p>` : ''}
                        ${record.anesthesia_used ? `<p><strong>Anesthesia:</strong> ${record.anesthesia_used}</p>` : ''}
                        ${record.materials_used ? `<p><strong>Materials:</strong> ${record.materials_used}</p>` : ''}
                        ${record.complications ? `<p><strong>Complications:</strong> ${record.complications}</p>` : ''}
                        ${record.post_operative_instructions ? `<p><strong>Post-Op Instructions:</strong> ${record.post_operative_instructions}</p>` : ''}
                        ${record.follow_up_notes ? `<p><strong>Follow-up:</strong> ${record.follow_up_notes}</p>` : ''}
                    </div>
                    ` : ''}
                </div>
                <div class="card-footer bg-light text-muted small">
                    <i class="bi bi-clock me-1"></i>Recorded: ${record.created_at ? new Date(record.created_at).toLocaleDateString() : 'N/A'}
                </div>
            </div>
        `).join('')}
    `;

    document.getElementById('patientHistoryDetailsContainer').innerHTML = html;
}

// Clear history search
function clearHistorySearch() {
    document.getElementById('historyPatientSearch').value = '';
    document.getElementById('historyPatientListContainer').innerHTML = `
        <div class="text-center py-5">
            <i class="bi bi-search text-muted" style="font-size: 3rem;"></i>
            <p class="text-muted mt-3">Search for a patient to view their medical history</p>
        </div>
    `;
    document.getElementById('patientHistoryDetailsContainer').innerHTML = `
        <div class="text-center py-5">
            <i class="bi bi-file-earmark-medical text-muted" style="font-size: 4rem;"></i>
            <h5 class="text-muted mt-3">No Patient Selected</h5>
            <p class="text-muted">Select a patient from the list to view their medical history</p>
        </div>
    `;
    document.getElementById('historyResultCount').textContent = '0';
    selectedPatientForHistory = null;
}

// Save medical history form
function saveMedicalHistoryForm() {
    const form = document.getElementById('medicalHistoryForm');
    const formData = new FormData(form);

    // Validate required fields
    if (!formData.get('visit_date')) {
        showNotification('Please enter a visit date', 'warning');
        return;
    }

    // Build the data object
    const data = {
        patient_id: formData.get('patient_id'),
        previous_dentist: formData.get('previous_dentist'),
        last_dental_visit: formData.get('last_dental_visit'),
        treatment_done: formData.get('treatment_done'),
        physician_name: formData.get('physician_name'),
        physician_specialty: formData.get('physician_specialty'),
        physician_office_address: formData.get('physician_office_address'),
        physician_contact: formData.get('physician_contact'),
        good_health: formData.get('good_health'),
        under_treatment: formData.get('under_treatment'),
        treatment_condition: formData.get('treatment_condition'),
        serious_illness: formData.get('serious_illness'),
        illness_details: formData.get('illness_details'),
        been_hospitalized: formData.get('been_hospitalized'),
        hospitalization_reason: formData.get('hospitalization_reason'),
        taking_drugs: formData.get('taking_drugs'),
        medications: formData.get('medications'),
        tobacco_use: formData.get('tobacco_use'),
        alcohol_use: formData.get('alcohol_use'),
        recreational_drugs: formData.get('recreational_drugs'),
        allergy_anesthesia: formData.get('allergy_anesthesia') ? 1 : 0,
        allergy_sulfa: formData.get('allergy_sulfa') ? 1 : 0,
        allergy_antibiotics: formData.get('allergy_antibiotics') ? 1 : 0,
        allergy_aspirin: formData.get('allergy_aspirin') ? 1 : 0,
        allergy_analgesics: formData.get('allergy_analgesics') ? 1 : 0,
        allergy_latex: formData.get('allergy_latex') ? 1 : 0,
        food_allergy_details: formData.get('food_allergy_details'),
        other_allergy_details: formData.get('other_allergy_details'),
        is_pregnant: formData.get('is_pregnant'),
        is_nursing: formData.get('is_nursing'),
        birth_control: formData.get('birth_control'),
        visit_date: formData.get('visit_date'),
        anesthesia_used: formData.get('anesthesia_used'),
        procedure_performed: formData.get('procedure_performed'),
        materials_used: formData.get('materials_used'),
        complications: formData.get('complications'),
        post_operative_instructions: formData.get('post_operative_instructions'),
        follow_up_notes: formData.get('follow_up_notes')
    };

    // Show loading notification
    showNotification('Saving medical history...', 'info');

    // Send to server
    fetch('/staff/post-procedural/patient-history', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(data)
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            showNotification('✅ Medical history saved successfully!', 'success');
            // Clear the form and show success message
            setTimeout(() => {
                clearHistorySearch();
            }, 1500);
        } else {
            showNotification('❌ Failed to save medical history: ' + (result.message || 'Unknown error'), 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('❌ Error saving medical history', 'danger');
    });
}

// Toggle between form view and history view
function toggleHistoryViewMode() {
    if (!selectedPatientForHistory) return;

    const currentView = document.getElementById('patientHistoryDetailsContainer').querySelector('form') ? 'form' : 'history';

    if (currentView === 'form') {
        // Load and show history
        fetch(`/staff/post-procedural/patient-history/${selectedPatientForHistory.id}`)
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    displayPatientMedicalHistory(data.data, selectedPatientForHistory.name);
                    document.getElementById('toggleHistoryView').innerHTML = '<i class="bi bi-pencil me-1"></i>Fill Form';
                } else {
                    showNotification('No medical history found for this patient', 'info');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showNotification('Error loading medical history', 'danger');
            });
    } else {
        // Show form
        displayMedicalHistoryForm(selectedPatientForHistory.id, selectedPatientForHistory.name);
        document.getElementById('toggleHistoryView').innerHTML = '<i class="bi bi-eye me-1"></i>View History';
    }
}

// Create new medical history for patient
function createNewMedicalHistory(patientId) {
    showNotification('This feature will open a form to create medical history', 'info');
    // TODO: Implement create new medical history form
}

// ========================
// PROGRESS NOTES FUNCTIONALITY
// ========================

let selectedProgressNotePatient = null;
let progressNoteRows = [];
let progressNoteRowCounter = 0;

// Display progress note search results
function displayProgressNoteSearchResults(patients) {
    const resultsDiv = document.getElementById('progressNotePatientSearchResults');
    let html = '';

    patients.forEach(patient => {
        const fullName = patient.info ?
            `${patient.info.first_name} ${patient.info.last_name}` :
            patient.name;

        html += `
            <div class="search-result-item" onclick="selectProgressNotePatient(${patient.id}, '${fullName}', '${patient.username}')">
                <div>
                    <strong>${fullName}</strong>
                    <br>
                    <small class="text-muted">@${patient.username}</small>
                </div>
            </div>
        `;
    });

    resultsDiv.innerHTML = html;
}

// Select patient for progress notes
function selectProgressNotePatient(patientId, patientName, username) {
    selectedProgressNotePatient = {
        id: patientId,
        name: patientName,
        username: username
    };

    // Clear search
    document.getElementById('progressNotePatientSearch').value = '';
    document.getElementById('progressNotePatientSearchResults').innerHTML = '';

    // Show selected patient alert
    document.getElementById('selectedProgressNotePatientAlert').classList.remove('d-none');
    document.getElementById('selectedProgressNotePatientText').textContent = `${patientName} (@${username})`;

    // Set send to patient field
    document.getElementById('progressNoteSendToPatient').value = patientName;

    // Load existing progress notes for this patient
    loadProgressNotes(patientId);

    showNotification('success', `Patient ${patientName} selected`);
}

// Load existing progress notes for patient
function loadProgressNotes(patientId) {
    // First, we need to get or create the patient record by user ID
    fetch(`/staff/post-procedural/patient-record-by-user/${patientId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data) {
                const recordId = data.data.id;
                // Now load progress notes for this record
                fetch(`/staff/post-procedural/progress-notes/${recordId}`)
                    .then(response => response.json())
                    .then(notesData => {
                        if (notesData.success && notesData.data.length > 0) {
                            showNotification('success', `✅ Found ${notesData.data.length} existing progress note${notesData.data.length > 1 ? 's' : ''} for this patient`);
                            progressNoteRows = notesData.data.map((note, index) => ({
                                id: note.id,
                                date: note.note_date,
                                progressNote: note.progress_description || '',
                                oralHygiene: note.treatment_response || '',
                                conformedPractices: note.next_steps || '',
                                rowId: progressNoteRowCounter++
                            }));
                            renderProgressNotesTable();
                        } else {
                            // No existing notes, start fresh
                            showNotification('info', 'ℹ️ No existing progress notes found. You can create new ones.');
                            progressNoteRows = [];
                            addProgressNoteRow();
                        }
                    })
                    .catch(error => {
                        console.error('Error loading progress notes:', error);
                        showNotification('warning', '⚠️ Could not load progress notes. Starting fresh.');
                        progressNoteRows = [];
                        addProgressNoteRow();
                    });
            } else {
                // No patient record yet, start fresh
                showNotification('info', 'ℹ️ No patient record found. Creating notes will auto-create a patient record.');
                progressNoteRows = [];
                addProgressNoteRow();
            }
        })
        .catch(error => {
            console.error('Error loading patient record:', error);
            showNotification('danger', '❌ Error loading patient record');
            progressNoteRows = [];
            addProgressNoteRow();
        });
}

// Add new progress note row
function addProgressNoteRow() {
    const rowId = progressNoteRowCounter++;
    const today = new Date().toISOString().split('T')[0];

    progressNoteRows.push({
        id: null,
        date: today,
        progressNote: '',
        oralHygiene: '',
        conformedPractices: '',
        rowId: rowId
    });

    renderProgressNotesTable();
}

// Render progress notes table
function renderProgressNotesTable() {
    const tbody = document.getElementById('progressNotesTableBody');
    let html = '';

    progressNoteRows.forEach((row, index) => {
        html += `
            <tr data-row-id="${row.rowId}">
                <td>
                    <input type="date" class="form-control" value="${row.date}"
                           onchange="updateProgressNoteRow(${row.rowId}, 'date', this.value)">
                </td>
                <td>
                    <textarea class="form-control" rows="2"
                              onchange="updateProgressNoteRow(${row.rowId}, 'progressNote', this.value)"
                              placeholder="Treatment progress...">${row.progressNote}</textarea>
                </td>
                <td>
                    <textarea class="form-control" rows="2"
                              onchange="updateProgressNoteRow(${row.rowId}, 'oralHygiene', this.value)"
                              placeholder="Hygiene assessment...">${row.oralHygiene}</textarea>
                </td>
                <td>
                    <textarea class="form-control" rows="2"
                              onchange="updateProgressNoteRow(${row.rowId}, 'conformedPractices', this.value)"
                              placeholder="Recommended practices...">${row.conformedPractices}</textarea>
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-danger btn-sm"
                            onclick="deleteProgressNoteRow(${row.rowId})">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    });

    tbody.innerHTML = html;
}

// Update progress note row
function updateProgressNoteRow(rowId, field, value) {
    const row = progressNoteRows.find(r => r.rowId === rowId);
    if (row) {
        row[field] = value;
    }
}

// Delete progress note row
function deleteProgressNoteRow(rowId) {
    if (confirm('Are you sure you want to delete this row?')) {
        progressNoteRows = progressNoteRows.filter(r => r.rowId !== rowId);
        renderProgressNotesTable();
        showNotification('Row deleted', 'info');
    }
}

// Clear progress notes form
function clearProgressNotesForm() {
    selectedProgressNotePatient = null;
    progressNoteRows = [];
    progressNoteRowCounter = 0;

    document.getElementById('progressNotePatientSearch').value = '';
    document.getElementById('selectedProgressNotePatientAlert').classList.add('d-none');
    document.getElementById('progressNoteSendToPatient').value = '';
    document.getElementById('progressNoteOtherNotes').value = '';

    // Add one empty row to start fresh
    addProgressNoteRow();
}

// Initialize Progress Notes Search (called when Progress Notes tab is clicked)
let progressNotesSearchInitialized = false;
function initializeProgressNotesSearch() {
    // Only initialize once
    if (progressNotesSearchInitialized) {
        return;
    }
    progressNotesSearchInitialized = true;

    console.log('Initializing Progress Notes search...');

    // Add initial row if none exist
    if (progressNoteRows.length === 0) {
        addProgressNoteRow();
    }

    // Initialize patient search
    const progressNoteSearchInput = document.getElementById('progressNotePatientSearch');
    if (progressNoteSearchInput) {
        progressNoteSearchInput.addEventListener('input', debounce(function(e) {
            const searchTerm = e.target.value.trim();
            console.log('Search term:', searchTerm);

            if (searchTerm.length < 2) {
                document.getElementById('progressNotePatientSearchResults').innerHTML = '';
                return;
            }

            console.log('Fetching patients...');
            fetch(`/staff/post-procedural/search-patients?search=${encodeURIComponent(searchTerm)}`)
                .then(response => response.json())
                .then(data => {
                    console.log('Search response:', data);
                    if (data.success && data.patients.length > 0) {
                        displayProgressNoteSearchResults(data.patients);
                    } else {
                        document.getElementById('progressNotePatientSearchResults').innerHTML =
                            '<div class="search-result-item text-muted">No patients with appointments found</div>';
                    }
                })
                .catch(error => {
                    console.error('Error searching patients:', error);
                    document.getElementById('progressNotePatientSearchResults').innerHTML =
                        '<div class="search-result-item text-danger">Error loading patients</div>';
                });
        }, 300));
        console.log('Search event listener attached');
    } else {
        console.error('Search input not found');
    }

    // Add Row button event listener
    const addProgressNoteRowBtn = document.getElementById('addProgressNoteRowBtn');
    if (addProgressNoteRowBtn) {
        addProgressNoteRowBtn.addEventListener('click', addProgressNoteRow);
    }

    // Send button event listener
    const sendProgressNoteBtn = document.getElementById('sendProgressNoteBtn');
    if (sendProgressNoteBtn) {
        sendProgressNoteBtn.addEventListener('click', function() {
            if (!selectedProgressNotePatient) {
                showNotification('Please select a patient first', 'warning');
                return;
            }

            // Validate that at least one row has data
            const hasData = progressNoteRows.some(row =>
                row.progressNote || row.oralHygiene || row.conformedPractices
            );

            if (!hasData) {
                showNotification('Please add at least one progress note entry', 'warning');
                return;
            }

            const otherNotes = document.getElementById('progressNoteOtherNotes').value;

            // Prepare the data to send
            const progressNotesData = {
                patient_id: selectedProgressNotePatient.id,
                notes: progressNoteRows,
                other_notes: otherNotes,
                send_to_patient: true
            };

            // Show loading state
            const btn = this;
            const originalContent = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Sending...';

            // Send to backend
            fetch('/staff/post-procedural/store-progress-notes', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(progressNotesData)
            })
            .then(response => response.json())
            .then(data => {
                btn.disabled = false;
                btn.innerHTML = originalContent;

                if (data.success) {
                    showNotification('✅ Progress notes saved and sent to patient successfully!', 'success');

                    // Reset form after a delay
                    setTimeout(() => {
                        clearProgressNotesForm();
                    }, 2000);
                } else {
                    showNotification('❌ Failed to save progress notes: ' + (data.message || 'Unknown error'), 'danger');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                btn.disabled = false;
                btn.innerHTML = originalContent;
                showNotification('❌ Error sending progress notes', 'danger');
            });
        });
    }
}

</script>
@endsection
