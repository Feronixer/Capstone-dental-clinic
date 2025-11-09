@extends('layout.admin.app')
@section('content')
<link rel="stylesheet" href="{{ asset('css/post-procedural.css') }}">

<div class="post-procedural-container">
    <div class="layout-wrapper">
        <!-- Sidebar -->
        <div class="sidebar-nav">
            <h6 class="sidebar-title">Medical Documents</h6>
            <ul class="nav-list">
                <li class="nav-item active" data-section="form-list">
                    <span>Form List</span>
                </li>
                <li class="nav-item" data-section="patient-record">
                    <span>Patient Record</span>
                </li>
                <li class="nav-item" data-section="patient-history">
                    <span>Patient History Form</span>
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
                <h4 class="page-title" style="font-size: 28px; font-weight: bold; color: #3498db;">Post-Procedure Form</h4>
            </div>

            <!-- Medical Documents Section (Table) -->
            <div class="content-section" id="form-list-section">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="d-flex align-items-center gap-2">
                        <label for="entriesPerPage" class="mb-0">Show</label>
                        <select class="form-select form-select-sm" id="entriesPerPage" name="entries_per_page" style="width: 80px;">
                            <option value="5" selected>5</option>
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        <span class="mb-0">entries</span>
                    </div>

                    <div class="input-group" style="width: 250px;">
                        <label for="searchInput" class="input-group-text bg-white">
                            <i class="bi bi-search"></i>
                        </label>
                        <input type="text" class="form-control form-control-sm" id="searchInput" name="search_input" placeholder="Search">
                    </div>
                </div>

                <div class="table-responsive">
                    <table class="table table-hover align-middle post-procedural-table">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 4%; min-width: 45px;">No.</th>
                                <th style="width: 20%; min-width: 180px;">Patient Name</th>
                                <th class="text-center" style="width: 12%; min-width: 100px;">Treatment</th>
                                <th class="text-center" style="width: 16%; min-width: 110px; font-size: 0.8rem;">Patient Info</th>
                                <th class="text-center" style="width: 15%; min-width: 100px; font-size: 0.8rem;">History</th>
                                <th class="text-center" style="width: 15%; min-width: 100px; font-size: 0.8rem;">Notes</th>
                                <th class="text-center" style="width: 18%; min-width: 90px;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="recordsTableBody">
                            <!-- Records will be loaded via JavaScript -->
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
                            <label for="patientNameSearch" class="visually-hidden">Search Patient Name</label>
                            <input type="text" class="form-control form-control-lg patient-search-input" id="patientNameSearch" name="patient_name_search"
                                   placeholder="Start typing patient name or username..." autocomplete="off"
                                   style="border: 2px solid #0d6efd; border-radius: 8px; padding-left: 45px;">
                            <i class="bi bi-search position-absolute" style="left: 15px; top: 50%; transform: translateY(-50%); color: #0d6efd; font-size: 1.2rem;"></i>
                            <div id="patientNameSearchResults" class="search-results-dropdown"></div>
                        </div>
                        <input type="hidden" id="selectedPatientId" name="selected_patient_id">
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
                            <label for="lastName" class="text-muted" style="font-size: 0.7rem; margin-bottom: 4px;">(LAST NAME)</label>
                            <input type="text" class="form-control readonly-field" id="lastName" readonly
                                   style="background: #e9ecef; border: 1px solid #ced4da; cursor: not-allowed;">
                        </div>
                        <div class="col-md-4">
                            <label for="givenName" class="text-muted" style="font-size: 0.7rem; margin-bottom: 4px;">(GIVEN NAME)</label>
                            <input type="text" class="form-control readonly-field" id="givenName" readonly
                                   style="background: #e9ecef; border: 1px solid #ced4da; cursor: not-allowed;">
                        </div>
                        <div class="col-md-4">
                            <label for="middleName" class="text-muted" style="font-size: 0.7rem; margin-bottom: 4px;">(MIDDLE NAME)</label>
                            <input type="text" class="form-control readonly-field" id="middleName" readonly
                                   style="background: #e9ecef; border: 1px solid #ced4da; cursor: not-allowed;">
                        </div>
                    </div>

                    <hr style="border-top: 1px dashed #dee2e6; margin: 1.5rem 0;">

                    <!-- Home Address -->
                    <div class="row mb-3">
                        <div class="col-12">
                            <label for="homeAddress" class="form-label fw-bold mb-2" style="color: #495057;">Home Address</label>
                            <input type="text" class="form-control" id="homeAddress"
                                   style="border: 2px solid #dee2e6; border-radius: 6px;">
                        </div>
                    </div>

                    <!-- Date of Birth, Age, Sex, Nickname -->
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <label for="dateOfBirth" class="form-label fw-bold mb-2" style="color: #495057; font-size: 0.875rem;">Date of birth</label>
                            <input type="date" class="form-control" id="dateOfBirth" placeholder="MM/DD/YYYY" readonly
                                   style="border: 2px solid #dee2e6; border-radius: 6px; background: #f8f9fa; cursor: not-allowed;">
                        </div>
                        <div class="col-md-3">
                            <label for="age" class="form-label fw-bold mb-2" style="color: #495057; font-size: 0.875rem;">
                                Age <small class="text-muted" style="font-weight: 400;">(auto-calculated)</small>
                            </label>
                            <input type="number" class="form-control" id="age" readonly
                                   style="border: 2px solid #dee2e6; border-radius: 6px; background: #f8f9fa; cursor: not-allowed;">
                        </div>
                        <div class="col-md-3">
                            <label for="sex" class="form-label fw-bold mb-2" style="color: #495057; font-size: 0.875rem;">Sex</label>
                            <select class="form-select" id="sex" disabled
                                    style="border: 2px solid #dee2e6; border-radius: 6px; background: #f8f9fa; cursor: not-allowed;">
                                <option value="">Select...</option>
                                <option value="Male">Male</option>
                                <option value="Female">Female</option>
                            </select>
                            <input type="hidden" id="sex_hidden" name="sex" value="">
                        </div>
                        <div class="col-md-3">
                            <label for="nickname" class="form-label fw-bold mb-2" style="color: #495057; font-size: 0.875rem;">Nickname</label>
                            <input type="text" class="form-control" id="nickname"
                                   style="border: 2px solid #dee2e6; border-radius: 6px;">
                        </div>
                    </div>

                    <!-- Religion, Occupation, Contact -->
                    <div class="row mb-3">
                        <div class="col-md-4">
                            <label for="religion" class="form-label fw-bold mb-2" style="color: #495057; font-size: 0.875rem;">Religion</label>
                            <input type="text" class="form-control" id="religion"
                                   style="border: 2px solid #dee2e6; border-radius: 6px;">
                        </div>
                        <div class="col-md-4">
                            <label for="occupation" class="form-label fw-bold mb-2" style="color: #495057; font-size: 0.875rem;">Occupation</label>
                            <input type="text" class="form-control" id="occupation"
                                   style="border: 2px solid #dee2e6; border-radius: 6px;">
                        </div>
                        <div class="col-md-4">
                            <label for="contact" class="form-label fw-bold mb-2" style="color: #495057; font-size: 0.875rem;">Contact</label>
                            <input type="text" class="form-control" id="contact" readonly
                                   style="border: 2px solid #dee2e6; border-radius: 6px; background: #f8f9fa; cursor: not-allowed;">
                        </div>
                    </div>

                    <hr style="border-top: 1px dashed #dee2e6; margin: 1.5rem 0;">

                    <!-- For Minors Section -->
                    <div class="mb-4">
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" id="isMinorCheckbox" style="cursor: pointer;">
                            <label class="form-check-label fw-bold" for="isMinorCheckbox" style="color: #0a4275; font-size: 1rem; cursor: pointer;">
                            <i class="bi bi-person-lines-fill me-2"></i>For minors:
                        </label>
                        </div>
                        <div id="minorFormContainer" class="row" style="background: #f8f9fa; padding: 15px; border-radius: 8px; border: 1px solid #dee2e6; display: none;">
                            <div class="col-md-4 mb-2">
                                <label for="guardianName" class="form-label mb-1" style="font-size: 0.875rem; color: #495057;">Parent/Guardian's Name</label>
                                <input type="text" class="form-control" id="guardianName"
                                       style="border: 2px solid #dee2e6; border-radius: 6px;">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label for="guardianContact" class="form-label mb-1" style="font-size: 0.875rem; color: #495057;">Contact No.</label>
                                <input type="text" class="form-control" id="guardianContact"
                                       style="border: 2px solid #dee2e6; border-radius: 6px;">
                            </div>
                            <div class="col-md-4 mb-2">
                                <label for="guardianOccupation" class="form-label mb-1" style="font-size: 0.875rem; color: #495057;">Occupation</label>
                                <input type="text" class="form-control" id="guardianOccupation"
                                       style="border: 2px solid #dee2e6; border-radius: 6px;">
                            </div>
                        </div>
                    </div>

                    <hr style="border-top: 1px dashed #dee2e6; margin: 1.5rem 0;">

                    <!-- Other Notes and Sent To Section -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <label for="otherNotes" class="form-label fw-bold mb-2" style="color: #0a4275; font-size: 1rem;">
                                <i class="bi bi-pencil-square me-2"></i>Other Notes:
                            </label>
                            <textarea class="form-control" id="otherNotes" rows="6"
                                      style="resize: none; border: 2px solid #dee2e6; border-radius: 8px;"></textarea>
                        </div>
                        <!-- Removed Send to Patient UI: auto-send on save -->
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

            <!-- Patient History Section -->
            <div class="content-section d-none" id="patient-history-section">
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
                        <h5 class="fw-bold" style="color: #0a4275;">PATIENT MEDICAL HISTORY FORM</h5>
                        <hr style="border-top: 1px solid #000; margin-top: 0.5rem;">
                            </div>

                    <!-- Patient Search & Selection -->
                    <div class="mb-4 patient-search-section">
                        <div class="search-header-box">
                            <label class="form-label fw-bold mb-2" style="color: #0a4275; font-size: 0.95rem;">
                                <i class="bi bi-search me-2"></i>Search Patient
                            </label>
                            <small class="text-muted" style="font-size: 0.75rem;">
                                <i class="bi bi-info-circle-fill me-1"></i>Type name or username to auto-fill patient information
                            </small>
                            </div>
                        <div class="position-relative mt-2">
                            <input type="text" class="form-control form-control-lg patient-search-input" id="historyPatientNameSearch"
                                   placeholder="Start typing patient name or username..." autocomplete="off"
                                   style="border: 2px solid #0d6efd; border-radius: 8px; padding-left: 45px;">
                            <i class="bi bi-search position-absolute" style="left: 15px; top: 50%; transform: translateY(-50%); color: #0d6efd; font-size: 1.2rem;"></i>
                            <div id="historyPatientNameSearchResults" class="search-results-dropdown"></div>
                    </div>
                </div>

                    <!-- Selected Patient Info Alert (Hidden by default) -->
                    <div id="selectedHistoryPatientInfoAlert" class="alert alert-info d-none mb-4" style="background: linear-gradient(135deg, #e7f1ff 0%, #cfe2ff 100%); border: 1px solid #9ec5fe; border-radius: 8px;">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-check-circle-fill text-success me-2" style="font-size: 1.5rem;"></i>
                            <div>
                                <strong style="color: #0a4275;">Patient Selected:</strong>
                                <span id="selectedHistoryPatientInfoText" class="ms-2"></span>
                            </div>
                        </div>
                    </div>

                    <!-- Patient Name (Read-only) -->
                    <div class="row mb-3">
                        <div class="col-12 mb-2">
                            <label class="form-label fw-bold mb-0" style="color: #495057;">Patient's Name</label>
                                </div>
                        <div class="col-md-4">
                            <label class="text-muted" style="font-size: 0.7rem; margin-bottom: 4px;">(LAST NAME)</label>
                            <input type="text" class="form-control readonly-field" id="historyLastName" readonly
                                   style="background: #e9ecef; border: 1px solid #ced4da; cursor: not-allowed;">
                            </div>
                        <div class="col-md-4">
                            <label class="text-muted" style="font-size: 0.7rem; margin-bottom: 4px;">(GIVEN NAME)</label>
                            <input type="text" class="form-control readonly-field" id="historyGivenName" readonly
                                   style="background: #e9ecef; border: 1px solid #ced4da; cursor: not-allowed;">
                                    </div>
                        <div class="col-md-4">
                            <label class="text-muted" style="font-size: 0.7rem; margin-bottom: 4px;">(MIDDLE NAME)</label>
                            <input type="text" class="form-control readonly-field" id="historyMiddleName" readonly
                                   style="background: #e9ecef; border: 1px solid #ced4da; cursor: not-allowed;">
                                </div>
                            </div>

                    <hr style="border-top: 1px dashed #dee2e6; margin: 1.5rem 0;">

                    <!-- Medical History Form Container -->
                    <div id="medicalHistoryFormContainer"></div>
                </div>
            </div>

            <!-- Progress Notes Section -->
            <div class="content-section d-none" id="progress-notes-section">
                <div class="form-header mb-4" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%); color: white; padding: 1.5rem; border-radius: 12px; box-shadow: 0 4px 15px rgba(13, 110, 253, 0.2);">
                    <h5 class="mb-0 fw-bold">
                        <i class="bi bi-journal-text me-2"></i>Progress Notes
                    </h5>
                    <p class="mb-0 mt-1" style="font-size: 0.9rem; opacity: 0.95;">Document patient treatment progress and observations</p>
                </div>

                <!-- Patient Selection -->
                <div class="card mb-4" style="border: 2px solid #0d6efd; border-radius: 12px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
                    <div class="card-header" style="background: linear-gradient(135deg, #e7f1ff 0%, #cfe2ff 100%); border-bottom: 2px solid #0d6efd;">
                        <h6 class="mb-0 fw-bold text-primary">
                            <i class="bi bi-person-circle me-2"></i>Select Patient
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="position-relative mt-2">
                            <input type="text" class="form-control form-control-lg patient-search-input" id="progressNotePatientSearch"
                                   placeholder="Start typing patient name or username..." autocomplete="off"
                                   style="border: 2px solid #0d6efd; border-radius: 8px; padding-left: 45px;">
                            <i class="bi bi-search position-absolute" style="left: 15px; top: 50%; transform: translateY(-50%); color: #0d6efd; font-size: 1.2rem;"></i>
                            <div id="progressNotePatientSearchResults" class="search-results-dropdown"></div>
                        </div>
                    </div>
                </div>

                <!-- Selected Patient Info Alert -->
                <div id="selectedProgressNotePatientAlert" class="alert alert-info d-none mb-4" style="background: linear-gradient(135deg, #e7f1ff 0%, #cfe2ff 100%); border: 1px solid #0d6efd; border-radius: 8px;">
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
                    <div class="card mb-4" style="border: 2px solid #0d6efd; border-radius: 12px;">
                        <div class="card-header" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%); color: white;">
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
                                            <th style="width: 12%;" class="text-center">DATE</th>
                                            <th style="width: 30%;" class="text-center">PROGRESS NOTES</th>
                                            <th style="width: 15%;" class="text-center">AMOUNT<br>PAID</th>
                                            <th style="width: 15%;" class="text-center">BALANCE</th>
                                            <th style="width: 18%;" class="text-center">CONFORME</th>
                                            <th style="width: 10%;" class="text-center">Actions</th>
                                        </tr>
                                    </thead>
                                    <tbody id="progressNotesTableBody">
                                        <!-- Rows will be added dynamically -->
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>

                    <!-- Save Progress Notes (auto-sent to patient on save) -->
                    <div class="card mb-4" style="border: 2px solid #0d6efd; border-radius: 12px; background: linear-gradient(135deg, #f8f9ff 0%, #f0f4ff 100%);">
                        <div class="card-body">
                            <div class="row align-items-end">
                                <div class="col-md-12">
                                    <button type="button" class="btn btn-primary btn-lg w-100" id="sendProgressNoteBtn"
                                            style="border-radius: 10px; font-weight: 600; padding: 0.75rem;">
                                        <i class="bi bi-floppy-fill me-2"></i>Save Progress Notes
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

<!-- Custom Confirmation Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" data-bs-backdrop="static" style="z-index: 1060;">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content" style="border-radius: 15px; overflow: hidden; border: none; box-shadow: 0 10px 40px rgba(0,0,0,0.2); position: relative; z-index: 1061;">
            <div class="modal-header" id="confirmModalHeader" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; padding: 1.5rem;">
                <h5 class="modal-title fw-bold" id="confirmModalTitle">
                    <i class="bi bi-question-circle-fill me-2"></i>Confirm Action
                </h5>
            </div>
            <div class="modal-body text-center" style="padding: 2rem;">
                <div id="confirmModalIcon" class="mb-3" style="font-size: 4rem; color: #667eea;">
                    <i class="bi bi-question-circle"></i>
                </div>
                <p id="confirmModalMessage" class="fs-5 mb-0" style="color: #495057;"></p>
            </div>
            <div class="modal-footer" style="border: none; padding: 1rem 1.5rem 1.5rem; justify-content: center; gap: 1rem;">
                <button type="button" class="btn btn-secondary btn-lg px-4" data-bs-dismiss="modal" style="border-radius: 10px; font-weight: 600;">
                    <i class="bi bi-x-circle me-2"></i>Cancel
                </button>
                <button type="button" class="btn btn-primary btn-lg px-4" id="confirmModalOkBtn" style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; border-radius: 10px; font-weight: 600;">
                    <i class="bi bi-check-circle me-2"></i>Confirm
                </button>
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


<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content delete-modal-content">
            <div class="modal-body text-center p-4">
                <div class="delete-icon-wrapper mb-3">
                    <i class="bi bi-exclamation-triangle text-warning"></i>
                </div>
                <h5 class="delete-modal-title mb-2">Delete Record</h5>
                <p class="delete-modal-message mb-4">Are you sure you want to delete this record?</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-delete" id="confirmDeleteBtn">OK</button>
                </div>
            </div>
        </div>
    </div>
</div>

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

let recordToDelete = null;

// Load Patient Records Function
let allRecords = []; // Store all records for sorting

function loadPatientRecords() {
    fetch('/admin/post-procedural/records')
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                allRecords = data.records; // Store records globally
                renderPatientRecords(allRecords);
            } else {
                console.error('Failed to load records:', data.message);
            }
        })
        .catch(error => {
            console.error('Error loading records:', error);
        });
}

// Render patient records in table
function renderPatientRecords(records) {
    const tbody = document.getElementById('recordsTableBody');
    if (!tbody) return;

    if (records.length === 0) {
        tbody.innerHTML = `
            <tr>
                <td colspan="7" class="text-center text-muted py-5">
                    <i class="bi bi-inbox" style="font-size: 3rem;"></i>
                    <p class="mt-2">No patient records found</p>
                </td>
            </tr>
        `;
        return;
    }

    // Group records by patient (using user_id as primary key for consistency)
    const groupedRecords = {};
    records.forEach(record => {
        // Use user_id as the primary grouping key to ensure consistency
        const key = record.user_id || record.patient_name || 'Unknown';
        if (!groupedRecords[key]) {
            groupedRecords[key] = {
                patient_name: record.patient_name || 'N/A',
                username: record.username || 'N/A',
                patient_number: record.patient_number || 'N/A',
                treatment: 'N/A',
                patient_record: null,
                patient_history: null,
                progress_notes: null,
                created_at: record.created_at,
                user_id: record.user_id || record.data?.user_id || null
            };
        }

        // Update patient info if not set yet
        if (record.patient_name && record.patient_name !== 'N/A') {
            groupedRecords[key].patient_name = record.patient_name;
        }
        if (record.username && record.username !== 'N/A') {
            groupedRecords[key].username = record.username;
        }
        if (record.patient_number && record.patient_number !== 'N/A') {
            groupedRecords[key].patient_number = record.patient_number;
        }

        // Update treatment from patient_record if available
        if (record.type === 'patient_record') {
            // Try to get service name from multiple possible paths
            let serviceName = groupedRecords[key].treatment === 'N/A' ? 'N/A' : groupedRecords[key].treatment;
            
            // Check appointment service first (most reliable)
            if (record.data?.appointment) {
                if (record.data.appointment.service?.service_name) {
                    serviceName = record.data.appointment.service.service_name;
                }
            }
            
            // Check direct service relationship
            if (serviceName === 'N/A' && record.data?.service?.service_name) {
                serviceName = record.data.service.service_name;
            }
            
            // Check treatment_done field
            if (serviceName === 'N/A' && record.data?.treatment_done) {
                serviceName = record.data.treatment_done;
            }
            
            groupedRecords[key].treatment = serviceName;
            groupedRecords[key].patient_record = record;
            groupedRecords[key].user_id = record.user_id || record.data?.user_id || groupedRecords[key].user_id;
        } else if (record.type === 'patient_history') {
            groupedRecords[key].patient_history = record;
            // Get treatment from patient history treatment_done if available
            if (record.data?.treatment_done) {
                groupedRecords[key].treatment = record.data.treatment_done;
            } else if (record.data?.treatment_done && groupedRecords[key].treatment === 'N/A') {
                groupedRecords[key].treatment = record.data.treatment_done;
            }
        } else if (record.type === 'progress_note') {
            groupedRecords[key].progress_notes = record;
        }
    });

    // Final pass: If treatment is still N/A, try to get it from patient history
    Object.keys(groupedRecords).forEach(key => {
        const group = groupedRecords[key];
        if (group.treatment === 'N/A' && group.patient_history) {
            if (group.patient_history.data?.treatment_done) {
                group.treatment = group.patient_history.data.treatment_done;
            } else if (group.patient_history.data?.treatment_done) {
                group.treatment = group.patient_history.data.treatment_done;
            }
        }
    });

    // Convert to array and sort by creation date
    const patientGroups = Object.values(groupedRecords).sort((a, b) =>
        new Date(b.created_at) - new Date(a.created_at)
    );

    tbody.innerHTML = patientGroups.map((group, index) => {
        const patientName = group.patient_name || '<span class="text-muted">N/A</span>';
        const username = group.username || '<span class="text-muted">N/A</span>';
        const treatment = group.treatment || '<span class="text-muted">N/A</span>';
        const patientNumber = group.patient_number || '<span class="text-muted">N/A</span>';

        // Get record IDs for each type - use the main patient record ID for all views
        const recordId = group.patient_record?.id || group.patient_record?.patient_record_id || group.user_id || 'N/A';
        const historyId = recordId; // Use same ID for history
        const notesId = recordId; // Use same ID for notes

        const createdDate = new Date(group.created_at).toLocaleDateString('en-US', {
            year: 'numeric',
            month: 'short',
            day: 'numeric'
        });

        return `
            <tr>
                <td class="text-center">${index + 1}</td>
                <td style="padding: 0.65rem 0.5rem;">
                    <div class="d-flex align-items-center" style="gap: 0.6rem;">
                        <div class="avatar-sm bg-primary text-white rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 36px; height: 36px; font-size: 14px; font-weight: 600;">
                            ${patientName.charAt(0).toUpperCase()}
                        </div>
                        <div style="flex: 1; min-width: 0;">
                            <div class="fw-semibold mb-1" style="font-size: 0.875rem; line-height: 1.3; word-wrap: break-word; overflow-wrap: break-word;">${patientName}</div>
                            <small class="text-muted" style="font-size: 0.8rem; line-height: 1.2; display: block;">@${username}</small>
                        </div>
                    </div>
                </td>
                <td class="text-center">
                    <span class="badge bg-info text-wrap" style="font-size: 0.75rem; padding: 0.25rem 0.5rem; max-width: 100%; word-break: break-word;">${treatment}</span>
                </td>
                <td class="text-center">
                    ${group.patient_record ? `
                        <button type="button" class="btn btn-sm btn-outline-primary edit-action-btn" onclick="openEditRecordModal(${recordId})" title="Edit Patient Info">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                    ` : `
                        <span class="badge bg-light text-muted no-data-badge">No data</span>
                    `}
                </td>
                <td class="text-center">
                    ${group.patient_history ? `
                        <button type="button" class="btn btn-sm btn-outline-info edit-action-btn" onclick="openEditHistoryModal(${historyId})" title="Edit History">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                    ` : `
                        <span class="badge bg-light text-muted no-data-badge">No data</span>
                    `}
                </td>
                <td class="text-center">
                    ${group.progress_notes ? `
                        <button type="button" class="btn btn-sm btn-outline-secondary edit-action-btn" onclick="openEditNotesModal(${notesId})" title="Edit Notes">
                            <i class="bi bi-pencil-square"></i>
                        </button>
                    ` : `
                        <span class="badge bg-light text-muted no-data-badge">No data</span>
                    `}
                </td>
                <td class="text-center">
                    <button type="button" class="btn btn-sm btn-outline-danger edit-action-btn" onclick="removeRecord(${recordId})" ${recordId === 'N/A' ? 'disabled' : ''} title="Delete">
                        <i class="bi bi-trash"></i>
                    </button>
                </td>
            </tr>
        `;
    }).join('');
}


function confirmDeleteRecordByType(id, type) {
    // Only allow deleting patient records directly
    if (type === 'patient_record') {
        confirmDeleteRecord(id);
    } else {
        showNotification('Please delete history and progress notes from the patient record view.', 'warning');
    }
}

// Notification System
function showNotification(message, type = 'info') {
    // Remove existing notifications
    const existingNotifications = document.querySelectorAll('.custom-notification');
    existingNotifications.forEach(notif => notif.remove());

    // Create notification element
    const notification = document.createElement('div');
    notification.className = `custom-notification alert alert-${type} alert-dismissible fade show`;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 10000;
        min-width: 350px;
        max-width: 500px;
        box-shadow: 0 8px 25px rgba(0,0,0,0.15);
        border: none;
        border-radius: 12px;
        font-weight: 500;
        padding: 16px 20px;
        backdrop-filter: blur(10px);
        animation: slideInRight 0.3s ease-out;
    `;

    // Set icon based on type
    let icon = '';
    let bgColor = '';
    let textColor = '';
    let borderColor = '';

    switch(type) {
        case 'success':
            icon = '<i class="bi bi-check-circle-fill me-2" style="font-size: 1.1rem;"></i>';
            bgColor = 'rgba(25, 135, 84, 0.1)';
            textColor = '#0f5132';
            borderColor = 'rgba(25, 135, 84, 0.2)';
            break;
        case 'error':
        case 'danger':
            icon = '<i class="bi bi-x-circle-fill me-2" style="font-size: 1.1rem;"></i>';
            bgColor = 'rgba(220, 53, 69, 0.1)';
            textColor = '#842029';
            borderColor = 'rgba(220, 53, 69, 0.2)';
            break;
        case 'warning':
            icon = '<i class="bi bi-exclamation-triangle-fill me-2" style="font-size: 1.1rem;"></i>';
            bgColor = 'rgba(255, 193, 7, 0.1)';
            textColor = '#664d03';
            borderColor = 'rgba(255, 193, 7, 0.2)';
            break;
        case 'info':
        default:
            icon = '<i class="bi bi-info-circle-fill me-2" style="font-size: 1.1rem;"></i>';
            bgColor = 'rgba(13, 202, 240, 0.1)';
            textColor = '#055160';
            borderColor = 'rgba(13, 202, 240, 0.2)';
            break;
    }

    notification.style.backgroundColor = bgColor;
    notification.style.color = textColor;
    notification.style.borderLeft = `4px solid ${borderColor}`;

    notification.innerHTML = `
        <div class="d-flex align-items-center">
            ${icon}
            <span style="flex: 1; line-height: 1.4;">${message}</span>
            <button type="button" class="btn-close btn-close-sm" data-bs-dismiss="alert" style="margin-left: 10px; opacity: 0.7;"></button>
        </div>
    `;

    // Add to body
    document.body.appendChild(notification);

    // Auto-remove after 6 seconds
    setTimeout(() => {
        if (notification.parentNode) {
            notification.style.animation = 'slideOutRight 0.3s ease-in';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 300);
        }
    }, 6000);
}

// Add animation keyframes
if (!document.getElementById('notification-styles')) {
    const style = document.createElement('style');
    style.id = 'notification-styles';
    style.textContent = `
        @keyframes slideInRight {
            from {
                transform: translateX(100%);
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
                transform: translateX(100%);
                opacity: 0;
            }
        }
    `;
    document.head.appendChild(style);
}

// Custom Confirmation Modal
function showConfirmModal(message, options = {}) {
    return new Promise((resolve) => {
        const modal = document.getElementById('confirmModal');
        const modalInstance = new bootstrap.Modal(modal);
        const okBtn = document.getElementById('confirmModalOkBtn');
        const messageEl = document.getElementById('confirmModalMessage');
        const titleEl = document.getElementById('confirmModalTitle');
        const headerEl = document.getElementById('confirmModalHeader');
        const iconEl = document.getElementById('confirmModalIcon');

        // Set defaults
        const {
            title = 'Confirm Action',
            icon = 'question-circle',
            type = 'default', // default, danger, warning, success
            okText = 'Confirm',
            cancelText = 'Cancel'
        } = options;

        // Set message
        messageEl.textContent = message;
        titleEl.innerHTML = `<i class="bi bi-${icon}-fill me-2"></i>${title}`;

        // Set colors based on type
        let headerColor, iconColor, btnColor;
        switch(type) {
            case 'danger':
                headerColor = 'linear-gradient(135deg, #dc3545 0%, #c82333 100%)';
                iconColor = '#dc3545';
                btnColor = 'linear-gradient(135deg, #dc3545 0%, #c82333 100%)';
                break;
            case 'warning':
                headerColor = 'linear-gradient(135deg, #ffc107 0%, #e0a800 100%)';
                iconColor = '#ffc107';
                btnColor = 'linear-gradient(135deg, #ffc107 0%, #e0a800 100%)';
                break;
            case 'success':
                headerColor = 'linear-gradient(135deg, #198754 0%, #146c43 100%)';
                iconColor = '#198754';
                btnColor = 'linear-gradient(135deg, #198754 0%, #146c43 100%)';
                break;
            default:
                headerColor = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
                iconColor = '#667eea';
                btnColor = 'linear-gradient(135deg, #667eea 0%, #764ba2 100%)';
        }

        headerEl.style.background = headerColor;
        iconEl.style.color = iconColor;
        iconEl.innerHTML = `<i class="bi bi-${icon}"></i>`;
        okBtn.style.background = btnColor;
        okBtn.innerHTML = `<i class="bi bi-check-circle me-2"></i>${okText}`;

        // Handle OK button
        const handleOk = () => {
            if (document.activeElement) document.activeElement.blur();
            modalInstance.hide();
            resolve(true);
            cleanup();
        };

        // Handle cancel/close
        const handleCancel = () => {
            if (document.activeElement) document.activeElement.blur();
            modalInstance.hide();
            resolve(false);
            cleanup();
        };

        // Cleanup function
        const cleanup = () => {
            okBtn.removeEventListener('click', handleOk);
            modal.removeEventListener('hidden.bs.modal', handleCancel);
        };

        // Add event listeners
        okBtn.addEventListener('click', handleOk);
        modal.addEventListener('hidden.bs.modal', handleCancel, { once: true });

        // Show modal
        modalInstance.show();

        // Fix backdrop z-index after modal is shown (needs a small delay)
        setTimeout(() => {
            // Get all backdrops and apply z-index to the last one (confirm modal's backdrop)
            const backdrops = document.querySelectorAll('.modal-backdrop');
            if (backdrops.length > 0) {
                const lastBackdrop = backdrops[backdrops.length - 1];
                lastBackdrop.style.zIndex = '1055';
            }
        }, 50);
    });
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

        // Initialize patient history form when tab is shown
        if (section === 'patient-history') {
            initializePatientHistoryForm();
        }

        // Initialize progress notes when Progress Notes tab is shown
        if (section === 'progress-notes') {
            initializeProgressNotesSearch();
        }
    });
});

// Reset to Medical Documents when modal closes
const detailsModal = document.getElementById('detailsModal');
if (detailsModal) {
    detailsModal.addEventListener('hidden.bs.modal', function() {
        // Clear modal content
        document.getElementById('modalTitle').innerHTML = '';
        document.getElementById('editModeContent').innerHTML = '';
        document.getElementById('patient-record-content').innerHTML = '';
        document.getElementById('patient-history-content').innerHTML = '';
        document.getElementById('progress-notes-content').innerHTML = '';

        // Reset modal tabs to first tab (Patient Record)
        const firstTab = document.querySelector('#modalTabs .nav-link');
        if (firstTab) {
            const tab = new bootstrap.Tab(firstTab);
            tab.show();
        }

        // Hide save button
        document.getElementById('saveBtn').style.display = 'none';

        // Clear any stored state
        window.currentEditingRecordId = null;
        window.currentPatientRecord = null;
        selectedPatientForHistory = null;

        // Reset to Medical Documents view
        document.querySelectorAll('.nav-item').forEach(nav => nav.classList.remove('active'));
        document.querySelector('.nav-item[data-section="form-list"]').classList.add('active');

        // Show Medical Documents section, hide others
        document.querySelectorAll('.content-section').forEach(sec => sec.classList.add('d-none'));
        document.getElementById('form-list-section').classList.remove('d-none');

        // Reload the form list to show any updates
        loadPatientRecords();
    });
}



// Remove patient record - with selective deletion
async function removeRecord(recordId) {
    if (!recordId || recordId === 'N/A') return;
    
    // Check if password is already verified for this record
    if (isPasswordVerified && passwordVerifiedRecordId === recordId) {
        removeRecordDirect(recordId);
        return;
    }
    
    // Show password verification first
    showPasswordVerificationModal(recordId, 'delete');
}

async function removeRecordDirect(recordId) {
    // Show confirmation dialog
    const confirmed = await showConfirmModal(
        'Are you sure you want to delete all records for this patient? This will delete the Patient Information Record, Patient History, and Progress Notes. This action cannot be undone!',
        {
            title: 'Delete All Patient Records',
            icon: 'trash',
            type: 'danger',
            okText: 'Yes, Delete All'
        }
    );

    if (!confirmed) return;

    // Show loading notification
    showNotification('Deleting all patient records...', 'info');

    // Delete all record types
    const recordsToDelete = [
        { type: 'Patient Record', url: `/admin/post-procedural/patient-record/${recordId}` },
        { type: 'Patient History', url: `/admin/post-procedural/patient-history/${recordId}` },
        { type: 'Progress Notes', url: `/admin/post-procedural/progress-notes/${recordId}` }
    ];

    // Delete all records
    const deletePromises = recordsToDelete.map(record => {
        return fetch(record.url, {
            method: 'DELETE',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            }
        })
        .then(response => {
            if (response.status === 200 || response.status === 204) {
                console.log(`${record.type} deleted successfully`);
                return { success: true, type: record.type };
            }
            if (response.status === 404) {
                console.log(`${record.type} not found - skipping`);
                return { success: true, type: record.type, skipped: true };
            }
            console.warn(`${record.type} delete returned status ${response.status}`);
            return { success: false, type: record.type };
        })
        .catch(error => {
            console.error(`Error deleting ${record.type}:`, error);
            return { success: false, type: record.type, error: error.message };
        });
    });

    // Wait for all deletions to complete
    Promise.allSettled(deletePromises)
    .then(results => {
        const successes = results.filter(r => r.value && r.value.success);
        const failures = results.filter(r => r.value && !r.value.success);

        if (failures.length > 0) {
            showNotification('Some records could not be deleted', 'warning');
        } else {
            showNotification('All patient records deleted successfully!', 'success');
        }

        // Reload the patient records list
        loadPatientRecords();
    })
    .catch(error => {
        console.error('Unexpected error during deletion:', error);
        showNotification('An unexpected error occurred during deletion', 'error');
    });
}

// Delete record (old function - keeping for compatibility)
function deleteRecord(id) {
    removeRecord(id);
}

// Confirm delete
document.getElementById('confirmDeleteBtn').addEventListener('click', function() {
    if (!recordToDelete) return;

    fetch(`/admin/post-procedural/patient-record/${recordToDelete}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (document.activeElement) document.activeElement.blur();
            bootstrap.Modal.getInstance(document.getElementById('deleteModal')).hide();
            location.reload();
        }
    })
    .catch(error => console.error('Error:', error));
});

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
            <input type="hidden" name="user_id" value="${record.user_id}">

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
                <input type="text" class="form-control" id="homeAddress" name="home_address" value="${record.home_address || ''}" style="border: 1px solid #ced4da;">
            </div>

            <!-- Date of birth, Age, Sex, Nickname -->
            <div class="row g-3 mb-3">
                <div class="col-md-3">
                    <label class="form-label fw-bold" style="color: #2c3e50;">Date of birth</label>
                    <input type="date" class="form-control" id="dateOfBirth" name="date_of_birth" value="${record.date_of_birth ? formatDateForInput(record.date_of_birth) : ''}" readonly style="background: #e9ecef; border: 1px solid #ced4da; cursor: not-allowed;">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold" style="color: #2c3e50;">Age <span class="text-muted fw-normal" style="font-size: 0.75rem;">(auto-calculated)</span></label>
                    <input type="number" class="form-control" id="age" name="age" value="${record.age || ''}" readonly style="background: #e9ecef; border: 1px solid #ced4da;">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold" style="color: #2c3e50;">Sex</label>
                    <select class="form-select" id="sex" disabled style="background: #e9ecef; border: 1px solid #ced4da; cursor: not-allowed;">
                        <option value="" ${!record.sex ? 'selected' : ''}>Select...</option>
                        <option value="Male" ${record.sex === 'Male' ? 'selected' : ''}>Male</option>
                        <option value="Female" ${record.sex === 'Female' ? 'selected' : ''}>Female</option>
                    </select>
                    <input type="hidden" id="sex_hidden" name="sex" value="${record.sex || ''}">
                </div>
                <div class="col-md-3">
                    <label class="form-label fw-bold" style="color: #2c3e50;">Nickname</label>
                    <input type="text" class="form-control" id="nickname" name="nickname" value="${record.nickname || ''}" style="border: 1px solid #ced4da;">
                </div>
            </div>

            <!-- Religion, Occupation, Contact -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label fw-bold" style="color: #2c3e50;">Religion</label>
                    <input type="text" class="form-control" id="religion" name="religion" value="${record.religion || ''}" style="border: 1px solid #ced4da;">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold" style="color: #2c3e50;">Occupation</label>
                    <input type="text" class="form-control" id="occupation" name="occupation" value="${record.occupation || ''}" style="border: 1px solid #ced4da;">
                </div>
                <div class="col-md-4">
                    <label class="form-label fw-bold" style="color: #2c3e50;">Contact</label>
                    <input type="text" class="form-control" id="contact" name="contact" value="${record.contact || ''}" readonly style="border: 1px solid #ced4da; background: #e9ecef; cursor: not-allowed;">
                </div>
            </div>

            <!-- For Minors -->
            <div class="mb-4 p-3" style="background: #f8f9fa; border-left: 4px solid #0d6efd;">
                <h6 class="fw-bold mb-3" style="color: #0d6efd;">
                    <i class="bi bi-person-badge me-2"></i>For minors:
                </h6>
                <div class="mb-3">
                    <label class="form-label fw-bold" style="color: #2c3e50;">Parent/Guardian's Name</label>
                    <input type="text" class="form-control" id="guardianName" name="guardian_name" value="${record.guardian_name || ''}" style="border: 1px solid #ced4da;">
                </div>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold" style="color: #2c3e50;">Contact No.</label>
                        <input type="text" class="form-control" id="guardianContact" name="guardian_contact" value="${record.guardian_contact || ''}" style="border: 1px solid #ced4da;">
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold" style="color: #2c3e50;">Occupation</label>
                        <input type="text" class="form-control" id="guardianOccupation" name="guardian_occupation" value="${record.guardian_occupation || ''}" style="border: 1px solid #ced4da;">
                    </div>
                </div>
            </div>

            <!-- Other Notes -->
            <div class="mb-3">
                <label class="form-label fw-bold" style="color: #2c3e50;">Other Notes:</label>
                <textarea class="form-control" id="notes" name="notes" rows="3" style="border: 1px solid #ced4da;">${record.notes || ''}</textarea>
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
                            <i class="bi bi-calendar-check me-2"></i>Medical History Record #${index + 1}
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
                    </div>
                </div>
            `).join('')}
        </div>
    `;
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
                    <div class="card-header bg-light">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="mb-0" style="color: #0a4275;">
                                <i class="bi bi-calendar-event me-2"></i>Visit #${index + 1}
                            </h6>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deletePatientHistory(${h.id})">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </div>
                    </div>
                    <div class="card-body">
                        <form id="historyForm${h.id}">
                            <!-- DENTAL HISTORY -->
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3" style="color: #0a4275; border-bottom: 2px solid #0a4275; padding-bottom: 0.5rem;">DENTAL HISTORY</h6>
                                <div class="row g-3">
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Previous Dentist</label>
                                        <input type="text" class="form-control" name="previous_dentist" value="${h.previous_dentist || ''}" style="border: 1px solid #000;">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Last dental visit</label>
                                        <input type="date" class="form-control" name="last_dental_visit" value="${h.last_dental_visit || ''}" style="border: 1px solid #000;">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Treatment done</label>
                                        <input type="text" class="form-control" name="treatment_done" value="${h.treatment_done || ''}" style="border: 1px solid #000;">
                                    </div>
                                </div>
                            </div>

                            <!-- MEDICAL HISTORY -->
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3" style="color: #0a4275; border-bottom: 2px solid #0a4275; padding-bottom: 0.5rem;">MEDICAL HISTORY</h6>
                            <div class="row g-3">
                                <div class="col-md-6">
                                        <label class="form-label fw-semibold">Name of Physician</label>
                                        <input type="text" class="form-control" name="physician_name" value="${h.physician_name || ''}" style="border: 1px solid #000;">
                                </div>
                                <div class="col-md-6">
                                        <label class="form-label fw-semibold">Specialty</label>
                                        <input type="text" class="form-control" name="physician_specialty" value="${h.physician_specialty || ''}" style="border: 1px solid #000;">
                                    </div>
                                    <div class="col-md-8">
                                        <label class="form-label fw-semibold">Office address</label>
                                        <input type="text" class="form-control" name="physician_office_address" value="${h.physician_office_address || ''}" style="border: 1px solid #000;">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label fw-semibold">Contact No.</label>
                                        <input type="text" class="form-control" name="physician_contact" value="${h.physician_contact || ''}" style="border: 1px solid #000;">
                                    </div>
                                </div>
                            </div>

                            <!-- HEALTH QUESTIONS - Condensed View for Editing -->
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3" style="color: #0a4275; border-bottom: 2px solid #0a4275; padding-bottom: 0.5rem;">HEALTH ASSESSMENT</h6>
                                <div class="row g-2">
                                    <div class="col-md-6">
                                        <label class="form-label">Good health</label>
                                        <select class="form-select form-select-sm" name="good_health">
                                            <option value="">Select...</option>
                                            <option value="yes" ${h.good_health === 'yes' ? 'selected' : ''}>YES</option>
                                            <option value="no" ${h.good_health === 'no' ? 'selected' : ''}>NO</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Under treatment</label>
                                        <select class="form-select form-select-sm" name="under_treatment">
                                            <option value="">Select...</option>
                                            <option value="yes" ${h.under_treatment === 'yes' ? 'selected' : ''}>YES</option>
                                            <option value="no" ${h.under_treatment === 'no' ? 'selected' : ''}>NO</option>
                                        </select>
                                </div>
                                <div class="col-12">
                                        <label class="form-label">Treatment condition</label>
                                        <input type="text" class="form-control form-control-sm" name="treatment_condition" value="${h.treatment_condition || ''}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Serious illness/surgery</label>
                                        <select class="form-select form-select-sm" name="serious_illness">
                                            <option value="">Select...</option>
                                            <option value="yes" ${h.serious_illness === 'yes' ? 'selected' : ''}>YES</option>
                                            <option value="no" ${h.serious_illness === 'no' ? 'selected' : ''}>NO</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Illness details</label>
                                        <input type="text" class="form-control form-control-sm" name="illness_details" value="${h.illness_details || ''}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Hospitalized</label>
                                        <select class="form-select form-select-sm" name="been_hospitalized">
                                            <option value="">Select...</option>
                                            <option value="yes" ${h.been_hospitalized === 'yes' ? 'selected' : ''}>YES</option>
                                            <option value="no" ${h.been_hospitalized === 'no' ? 'selected' : ''}>NO</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Hospitalization reason</label>
                                        <input type="text" class="form-control form-control-sm" name="hospitalization_reason" value="${h.hospitalization_reason || ''}">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Taking drugs</label>
                                        <select class="form-select form-select-sm" name="taking_drugs">
                                            <option value="">Select...</option>
                                            <option value="yes" ${h.taking_drugs === 'yes' ? 'selected' : ''}>YES</option>
                                            <option value="no" ${h.taking_drugs === 'no' ? 'selected' : ''}>NO</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Medications</label>
                                        <input type="text" class="form-control form-control-sm" name="medications" value="${h.medications || ''}">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Tobacco use</label>
                                        <select class="form-select form-select-sm" name="tobacco_use">
                                            <option value="">Select...</option>
                                            <option value="yes" ${h.tobacco_use === 'yes' ? 'selected' : ''}>YES</option>
                                            <option value="no" ${h.tobacco_use === 'no' ? 'selected' : ''}>NO</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Alcohol use</label>
                                        <select class="form-select form-select-sm" name="alcohol_use">
                                            <option value="">Select...</option>
                                            <option value="yes" ${h.alcohol_use === 'yes' ? 'selected' : ''}>YES</option>
                                            <option value="no" ${h.alcohol_use === 'no' ? 'selected' : ''}>NO</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Recreational drugs</label>
                                        <select class="form-select form-select-sm" name="recreational_drugs">
                                            <option value="">Select...</option>
                                            <option value="yes" ${h.recreational_drugs === 'yes' ? 'selected' : ''}>YES</option>
                                            <option value="no" ${h.recreational_drugs === 'no' ? 'selected' : ''}>NO</option>
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <!-- ALLERGIES -->
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3" style="color: #0a4275; border-bottom: 2px solid #0a4275; padding-bottom: 0.5rem;">ALLERGIES</h6>
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="allergy_anesthesia" ${h.allergy_anesthesia ? 'checked' : ''}>
                                            <label class="form-check-label">Local Anesthesia</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="allergy_sulfa" ${h.allergy_sulfa ? 'checked' : ''}>
                                            <label class="form-check-label">Sulfa drugs</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="allergy_antibiotics" ${h.allergy_antibiotics ? 'checked' : ''}>
                                            <label class="form-check-label">Antibiotics</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="allergy_aspirin" ${h.allergy_aspirin ? 'checked' : ''}>
                                            <label class="form-check-label">Aspirin</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="allergy_analgesics" ${h.allergy_analgesics ? 'checked' : ''}>
                                            <label class="form-check-label">Analgesics</label>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" name="allergy_latex" ${h.allergy_latex ? 'checked' : ''}>
                                            <label class="form-check-label">Latex</label>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Food allergies</label>
                                        <input type="text" class="form-control form-control-sm" name="food_allergy_details" value="${h.food_allergy_details || ''}" placeholder="Specify food allergies">
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Other allergies</label>
                                        <input type="text" class="form-control form-control-sm" name="other_allergy_details" value="${h.other_allergy_details || ''}" placeholder="Specify other allergies">
                                    </div>
                                </div>
                            </div>

                            <!-- FOR WOMEN -->
                            <div class="mb-4">
                                <h6 class="fw-bold mb-3" style="color: #0a4275; border-bottom: 2px solid #0a4275; padding-bottom: 0.5rem;">FOR WOMEN</h6>
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <label class="form-label">Pregnant</label>
                                        <select class="form-select form-select-sm" name="is_pregnant">
                                            <option value="">Select...</option>
                                            <option value="yes" ${h.is_pregnant === 'yes' ? 'selected' : ''}>YES</option>
                                            <option value="no" ${h.is_pregnant === 'no' ? 'selected' : ''}>NO</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Nursing</label>
                                        <select class="form-select form-select-sm" name="is_nursing">
                                            <option value="">Select...</option>
                                            <option value="yes" ${h.is_nursing === 'yes' ? 'selected' : ''}>YES</option>
                                            <option value="no" ${h.is_nursing === 'no' ? 'selected' : ''}>NO</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Birth control</label>
                                        <select class="form-select form-select-sm" name="birth_control">
                                            <option value="">Select...</option>
                                            <option value="yes" ${h.birth_control === 'yes' ? 'selected' : ''}>YES</option>
                                            <option value="no" ${h.birth_control === 'no' ? 'selected' : ''}>NO</option>
                                        </select>
                                    </div>
                                </div>
                            </div>


                            <!-- Save Button -->
                            <div class="col-12 mt-3">
                                <button type="button" class="btn btn-success btn-lg" onclick="savePatientHistory(${h.id}, ${recordId})">
                                        <i class="bi bi-check-circle me-1"></i>Save Changes
                                    </button>
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
                <h6 class="mb-3"><i class="bi bi-plus-circle me-2"></i>Add Medical History Record</h6>
                <form id="newHistoryForm">
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
                                <div id="treatmentConditionContainer" class="ps-4 mt-3 mb-2" style="display: none; border-left: 3px solid #0d6efd; padding-left: 1rem; background-color: #f8f9fa; border-radius: 4px; padding-top: 0.75rem; padding-bottom: 0.75rem;">
                                    <label class="form-label fw-semibold text-primary mb-2" style="font-size: 0.9rem;">
                                        <i class="bi bi-arrow-return-right me-1"></i>If YES, please specify:
                                    </label>
                                    <input type="text" class="form-control form-control-sm" name="treatment_condition" id="treatmentCondition" placeholder="What condition is being treated?" style="border: 1px solid #0d6efd;">
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
                                <div id="illnessDetailsContainer" class="ps-4 mt-3 mb-2" style="display: none; border-left: 3px solid #0d6efd; padding-left: 1rem; background-color: #f8f9fa; border-radius: 4px; padding-top: 0.75rem; padding-bottom: 0.75rem;">
                                    <label class="form-label fw-semibold text-primary mb-2" style="font-size: 0.9rem;">
                                        <i class="bi bi-arrow-return-right me-1"></i>If YES, please specify:
                                    </label>
                                    <input type="text" class="form-control form-control-sm" name="illness_details" id="illnessDetails" placeholder="What illness or surgery?" style="border: 1px solid #0d6efd;">
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
                                <div id="hospitalizationReasonContainer" class="ps-4 mt-3 mb-2" style="display: none; border-left: 3px solid #0d6efd; padding-left: 1rem; background-color: #f8f9fa; border-radius: 4px; padding-top: 0.75rem; padding-bottom: 0.75rem;">
                                    <label class="form-label fw-semibold text-primary mb-2" style="font-size: 0.9rem;">
                                        <i class="bi bi-arrow-return-right me-1"></i>If YES, please specify:
                                    </label>
                                    <input type="text" class="form-control form-control-sm" name="hospitalization_reason" id="hospitalizationReason" placeholder="When and why were you hospitalized?" style="border: 1px solid #0d6efd;">
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
                                <div id="medicationsContainer" class="ps-4 mt-3 mb-2" style="display: none; border-left: 3px solid #0d6efd; padding-left: 1rem; background-color: #f8f9fa; border-radius: 4px; padding-top: 0.75rem; padding-bottom: 0.75rem;">
                                    <label class="form-label fw-semibold text-primary mb-2" style="font-size: 0.9rem;">
                                        <i class="bi bi-arrow-return-right me-1"></i>If YES, please specify:
                                    </label>
                                    <input type="text" class="form-control form-control-sm" name="medications" id="medications" placeholder="What medications are you taking?" style="border: 1px solid #0d6efd;">
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

                    <div class="col-12 mt-3">
                        <button type="button" class="btn btn-primary btn-lg" onclick="addNewPatientHistory(${recordId})" style="background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%); border: none; padding: 10px 24px; font-weight: 600;">
                            <i class="bi bi-plus-circle me-1"></i>Add Medical History Record
                        </button>
                        <button type="button" class="btn btn-secondary btn-lg" onclick="hideAddHistoryForm()" style="padding: 10px 24px; font-weight: 600;">
                            <i class="bi bi-x-circle me-1"></i>Cancel
                        </button>
                    </div>
                </form>
            </div>
        </div>
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
                                    // Extract just the date part to avoid timezone issues
                                    const datePart = n.note_date.split('T')[0].split(' ')[0];
                                    const [year, month, day] = datePart.split('-');
                                    const date = new Date(parseInt(year), parseInt(month) - 1, parseInt(day));
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


// Editable Progress Notes List
function renderProgressNotesEditList(notes, recordId) {
    const statusColors = {
        'ongoing': 'warning',
        'completed': 'success',
        'followup_needed': 'info'
    };

    // Helper function to escape HTML and preserve newlines
    const escapeHtml = (text) => {
        if (!text) return '';
        const div = document.createElement('div');
        div.textContent = text;
        return div.innerHTML;
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

            ${notes.map((n, index) => {
                // Escape values to prevent XSS and ensure proper rendering
                const progressDesc = escapeHtml(n.progress_description || '');

                return `
                <div class="card mb-3" style="border-left: 4px solid #${statusColors[n.status] === 'warning' ? 'ffc107' : statusColors[n.status] === 'success' ? '198754' : '0dcaf0'};">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-3">
                            <h6 class="mb-0" style="color: #0a4275;">Note #${index + 1}</h6>
                            <button type="button" class="btn btn-sm btn-outline-danger" onclick="deleteProgressNote(${n.id})">
                                <i class="bi bi-trash"></i> Delete
                            </button>
                        </div>
                        <form id="noteForm${n.id}_${recordId}" data-note-id="${n.id}">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label">Note Date</label>
                                    <input type="date" class="form-control note-date-input" name="note_date" value="${n.note_date || ''}" required>
                                </div>
                                <div class="col-12">
                                    <label class="form-label">Progress Description</label>
                                    <textarea class="form-control note-progress-textarea" name="progress_description" rows="3" required>${progressDesc}</textarea>
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Amount Paid</label>
                                    <input type="number" class="form-control" name="amount_paid" step="0.01" min="0" value="${n.amount_paid || ''}" placeholder="0.00">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Balance</label>
                                    <input type="number" class="form-control" name="balance" step="0.01" min="0" value="${n.balance || ''}" placeholder="0.00">
                                </div>
                                <div class="col-md-4">
                                    <label class="form-label">Conforme</label>
                                    <input type="text" class="form-control" name="conforme" value="${n.conforme || ''}" placeholder="Conforme...">
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
            `;
            }).join('')}
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
                        <div class="col-md-12">
                            <label class="form-label">Note Date</label>
                            <input type="date" class="form-control" name="note_date" required>
                        </div>
                        <div class="col-12">
                            <label class="form-label">Progress Description</label>
                            <textarea class="form-control" name="progress_description" rows="3" required></textarea>
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Amount Paid</label>
                            <input type="number" class="form-control" name="amount_paid" step="0.01" min="0" placeholder="0.00">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Balance</label>
                            <input type="number" class="form-control" name="balance" step="0.01" min="0" placeholder="0.00">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label">Conforme</label>
                            <input type="text" class="form-control" name="conforme" placeholder="Conforme...">
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
    // Date of birth is read-only and auto-filled from user management
    // Age is automatically calculated when birthdate is set
    const dateOfBirthInput = document.getElementById('dateOfBirth');
    const ageInput = document.getElementById('age');

    // Make date of birth read-only if it exists
    if (dateOfBirthInput) {
        dateOfBirthInput.setAttribute('readonly', 'readonly');
        dateOfBirthInput.style.backgroundColor = '#f8f9fa';
        dateOfBirthInput.style.cursor = 'not-allowed';
    }

    // Toggle minor form visibility based on checkbox
    const isMinorCheckbox = document.getElementById('isMinorCheckbox');
    const minorFormContainer = document.getElementById('minorFormContainer');

    if (isMinorCheckbox && minorFormContainer) {
        isMinorCheckbox.addEventListener('change', function() {
            if (this.checked) {
                minorFormContainer.style.display = 'block';
            } else {
                minorFormContainer.style.display = 'none';
                // Clear the form fields when hidden
                document.getElementById('guardianName').value = '';
                document.getElementById('guardianContact').value = '';
                document.getElementById('guardianOccupation').value = '';
            }
        });
    }

    // Toggle medical history "If yes..." input fields based on radio button selections
    function setupConditionalInputs(container) {
        if (!container) return;

        // Question 2: Medical treatment
        const treatmentYes = container.querySelector('input[name="under_treatment"][value="yes"]');
        const treatmentNo = container.querySelector('input[name="under_treatment"][value="no"]');
        const treatmentContainer = container.querySelector('[name="treatment_condition"]')?.parentElement;
        const treatmentInput = container.querySelector('[name="treatment_condition"]');

        if (treatmentYes && treatmentNo && treatmentContainer && treatmentInput) {
            const toggleTreatment = () => {
                if (treatmentYes.checked) {
                    treatmentContainer.style.display = 'block';
                } else {
                    treatmentContainer.style.display = 'none';
                    treatmentInput.value = '';
                }
            };
            treatmentYes.addEventListener('change', toggleTreatment);
            treatmentNo.addEventListener('change', toggleTreatment);
            toggleTreatment();
        }

        // Question 3: Serious illness
        const illnessYes = container.querySelector('input[name="serious_illness"][value="yes"]');
        const illnessNo = container.querySelector('input[name="serious_illness"][value="no"]');
        const illnessContainer = container.querySelector('[name="illness_details"]')?.parentElement;
        const illnessInput = container.querySelector('[name="illness_details"]');

        if (illnessYes && illnessNo && illnessContainer && illnessInput) {
            const toggleIllness = () => {
                if (illnessYes.checked) {
                    illnessContainer.style.display = 'block';
                } else {
                    illnessContainer.style.display = 'none';
                    illnessInput.value = '';
                }
            };
            illnessYes.addEventListener('change', toggleIllness);
            illnessNo.addEventListener('change', toggleIllness);
            toggleIllness();
        }

        // Question 4: Hospitalized
        const hospitalizedYes = container.querySelector('input[name="been_hospitalized"][value="yes"]');
        const hospitalizedNo = container.querySelector('input[name="been_hospitalized"][value="no"]');
        const hospitalizedContainer = container.querySelector('[name="hospitalization_reason"]')?.parentElement;
        const hospitalizedInput = container.querySelector('[name="hospitalization_reason"]');

        if (hospitalizedYes && hospitalizedNo && hospitalizedContainer && hospitalizedInput) {
            const toggleHospitalized = () => {
                if (hospitalizedYes.checked) {
                    hospitalizedContainer.style.display = 'block';
                } else {
                    hospitalizedContainer.style.display = 'none';
                    hospitalizedInput.value = '';
                }
            };
            hospitalizedYes.addEventListener('change', toggleHospitalized);
            hospitalizedNo.addEventListener('change', toggleHospitalized);
            toggleHospitalized();
        }

        // Question 5: Taking drugs
        const drugsYes = container.querySelector('input[name="taking_drugs"][value="yes"]');
        const drugsNo = container.querySelector('input[name="taking_drugs"][value="no"]');
        const drugsContainer = container.querySelector('[name="medications"]')?.parentElement;
        const drugsInput = container.querySelector('[name="medications"]');

        if (drugsYes && drugsNo && drugsContainer && drugsInput) {
            const toggleDrugs = () => {
                if (drugsYes.checked) {
                    drugsContainer.style.display = 'block';
                } else {
                    drugsContainer.style.display = 'none';
                    drugsInput.value = '';
                }
            };
            drugsYes.addEventListener('change', toggleDrugs);
            drugsNo.addEventListener('change', toggleDrugs);
            toggleDrugs();
        }
    }

    // Setup for main form (by ID if exists, otherwise by querySelector)
    const mainFormContainer = document.getElementById('treatmentConditionContainer')?.closest('.bg-white') ||
                              document.querySelector('form [name="treatment_condition"]')?.closest('.bg-white');
    if (mainFormContainer) {
        setupConditionalInputs(mainFormContainer);
    }

    // Also setup for any dynamically created edit modals
    const observer = new MutationObserver(function(mutations) {
        mutations.forEach(function(mutation) {
            mutation.addedNodes.forEach(function(node) {
                if (node.nodeType === 1 && (node.classList.contains('modal-body') || node.querySelector('[name="treatment_condition"]'))) {
                    setupConditionalInputs(node);
                }
            });
        });
    });
    observer.observe(document.body, { childList: true, subtree: true });

    // Patient name search in Patient Record form
    const patientNameSearch = document.getElementById('patientNameSearch');
    let selectedPatientIndex = -1;
    if (patientNameSearch) {
        patientNameSearch.addEventListener('input', function() {
            clearTimeout(patientSearchTimeout);
            const searchTerm = this.value.trim();
            selectedPatientIndex = -1; // Reset selection

            if (searchTerm.length < 2) {
                document.getElementById('patientNameSearchResults').innerHTML = '';
                return;
            }

            patientSearchTimeout = setTimeout(() => {
                searchPatientsForPatientName(searchTerm);
            }, 300);
        });

        // Keyboard navigation for search dropdown
        patientNameSearch.addEventListener('keydown', function(e) {
            const resultsDiv = document.getElementById('patientNameSearchResults');
            const resultItems = resultsDiv.querySelectorAll('.search-result-item');

            if (resultItems.length === 0) return;

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                selectedPatientIndex = Math.min(selectedPatientIndex + 1, resultItems.length - 1);
                updateSearchSelection(resultItems, selectedPatientIndex);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                selectedPatientIndex = Math.max(selectedPatientIndex - 1, -1);
                updateSearchSelection(resultItems, selectedPatientIndex);
            } else if (e.key === 'Enter' && selectedPatientIndex >= 0) {
                e.preventDefault();
                resultItems[selectedPatientIndex].click();
            } else if (e.key === 'Enter' && this.value.trim().length >= 2) {
                // If Enter pressed with search term but no selection, trigger search
                e.preventDefault();
                const searchTerm = this.value.trim();
                searchPatientsForPatientName(searchTerm);
            } else if (e.key === 'Escape') {
                e.preventDefault();
                resultsDiv.innerHTML = '';
                selectedPatientIndex = -1;
            }
        });

        // Clear results when clicking outside
        document.addEventListener('click', function(e) {
            if (!patientNameSearch.contains(e.target) &&
                !document.getElementById('patientNameSearchResults').contains(e.target)) {
                document.getElementById('patientNameSearchResults').innerHTML = '';
                selectedPatientIndex = -1;
            }
        });
    }

    // Helper function to update search selection highlighting
    function updateSearchSelection(items, index) {
        items.forEach((item, i) => {
            if (i === index) {
                item.style.backgroundColor = '#e7f1ff';
                item.style.cursor = 'pointer';
                item.scrollIntoView({ block: 'nearest', behavior: 'smooth' });
            } else {
                item.style.backgroundColor = '';
                item.style.cursor = 'default';
            }
        });
    }

    // Patient search in "Sent to" section
    const patientSearchInput = document.getElementById('patientSearchInput');
    let selectedSendToIndex = -1;
    if (patientSearchInput) {
        patientSearchInput.addEventListener('input', function() {
            clearTimeout(patientSearchTimeout);
            const searchTerm = this.value.trim();
            selectedSendToIndex = -1; // Reset selection

            if (searchTerm.length < 2) {
                document.getElementById('patientSearchResults').innerHTML = '';
                return;
            }

            patientSearchTimeout = setTimeout(() => {
                searchPatientsForSendTo(searchTerm);
            }, 300);
        });

        // Keyboard navigation for search dropdown
        patientSearchInput.addEventListener('keydown', function(e) {
            const resultsDiv = document.getElementById('patientSearchResults');
            const resultItems = resultsDiv.querySelectorAll('.search-result-item');

            if (resultItems.length === 0) return;

            if (e.key === 'ArrowDown') {
                e.preventDefault();
                selectedSendToIndex = Math.min(selectedSendToIndex + 1, resultItems.length - 1);
                updateSearchSelection(resultItems, selectedSendToIndex);
            } else if (e.key === 'ArrowUp') {
                e.preventDefault();
                selectedSendToIndex = Math.max(selectedSendToIndex - 1, -1);
                updateSearchSelection(resultItems, selectedSendToIndex);
            } else if (e.key === 'Enter' && selectedSendToIndex >= 0) {
                e.preventDefault();
                resultItems[selectedSendToIndex].click();
            } else if (e.key === 'Enter' && this.value.trim().length >= 2) {
                // If Enter pressed with search term but no selection, trigger search
                e.preventDefault();
                const searchTerm = this.value.trim();
                searchPatientsForSendTo(searchTerm);
            } else if (e.key === 'Escape') {
                e.preventDefault();
                resultsDiv.innerHTML = '';
                selectedSendToIndex = -1;
            }
        });

        // Clear results when clicking outside
        document.addEventListener('click', function(e) {
            if (!patientSearchInput.contains(e.target) &&
                !document.getElementById('patientSearchResults').contains(e.target)) {
                document.getElementById('patientSearchResults').innerHTML = '';
                selectedSendToIndex = -1;
            }
        });
    }

    // Initialize patient record search if on that tab
    const patientRecordSection = document.getElementById('patient-record-section');
    if (patientRecordSection && !patientRecordSection.classList.contains('d-none')) {
        initializePatientRecordSearch();
    }

    // When navigating to Patient Record Section from sidebar, clear the form
    document.addEventListener('click', function(ev) {
        const navItem = ev.target.closest('.nav-item[data-section="patient-record"]');
        if (navItem) {
            // Clear immediately without confirmation so the form is fresh
            if (typeof clearPatientRecordForm === 'function') {
                clearPatientRecordForm(true);
            }
        }
    });
});

// Helper: open modal with tabs populated (same design as sections)
function openDetailsTabsModal(activeTab) {
    const modalEl = document.getElementById('detailsModal');
    if (!modalEl) return;

    // Show tabs layout, hide inline edit mode, hide Save button
    const tabsEl = document.getElementById('modalTabs');
    const viewMode = document.getElementById('viewModeContent');
    const editMode = document.getElementById('editModeContent');
    const saveBtn = document.getElementById('saveBtn');

    if (tabsEl) tabsEl.classList.remove('d-none');
    if (viewMode) viewMode.classList.remove('d-none');
    if (editMode) editMode.innerHTML = '';
    if (saveBtn) saveBtn.style.display = 'none';

    const titleEl = document.getElementById('modalTitle');
    if (titleEl) titleEl.innerHTML = '<i class="bi bi-file-medical me-2"></i>Patient Details';

    // Activate requested tab
    const tabId = activeTab === 'history' ? 'patient-history-tab' : activeTab === 'notes' ? 'progress-notes-tab' : 'patient-record-tab';
    const tabBtn = document.getElementById(tabId);
    if (tabBtn) {
        const tab = new bootstrap.Tab(tabBtn);
        tab.show();
    }

    new bootstrap.Modal(modalEl).show();
}

// Load all sections for a record and show in tabs
function loadAndShowRecordTabs(recordId, activeTab = 'record') {
    // Clear previous content
    const pr = document.getElementById('patient-record-content');
    const ph = document.getElementById('patient-history-content');
    const pn = document.getElementById('progress-notes-content');
    if (pr) pr.innerHTML = '<div class="p-3 text-muted">Loading record...</div>';
    if (ph) ph.innerHTML = '<div class="p-3 text-muted">Loading history...</div>';
    if (pn) pn.innerHTML = '<div class="p-3 text-muted">Loading notes...</div>';

    // Fetch all in parallel
    Promise.all([
        fetch(`/admin/post-procedural/patient-record/${recordId}`).then(r => r.json()).catch(() => null),
        fetch(`/admin/post-procedural/patient-history/${recordId}`).then(r => r.json()).catch(() => null),
        fetch(`/admin/post-procedural/progress-notes/${recordId}`).then(r => r.json()).catch(() => null)
    ]).then(([recordRes, historyRes, notesRes]) => {
        try {
            // Be resilient to various response shapes
            const record = (recordRes && (recordRes.data || recordRes.record || recordRes.patient_record)) || (typeof recordRes === 'object' ? recordRes : null);
            if (pr) {
                if (record) {
                    // Render the full Patient Record form layout and make it read-only to match section design
                    pr.innerHTML = renderPatientInfoForm(record);
                    // Populate fields with existing data
                    try { if (typeof populateFormWithData === 'function') populateFormWithData(record); } catch (e) { /* ignore */ }
                    // Make all inputs read-only/disabled inside this tab
                    makeContainerReadOnly(pr);
                } else {
                    pr.innerHTML = '<div class="p-3 text-danger">Unable to load patient record.</div>';
                }
            }
        } catch (e) {
            if (pr) pr.innerHTML = '<div class="p-3 text-danger">Error rendering patient record.</div>';
        }

        try {
            const histories = (historyRes && (historyRes.data || historyRes.histories)) || (Array.isArray(historyRes) ? historyRes : []);
            if (ph) {
                const list = Array.isArray(histories) ? histories : [];
                if (list.length > 0) {
                    // Render the same full medical history form and fill with the latest record
                    const latest = list[0] || {};
                    ph.innerHTML = renderMedicalHistoryFormOnly();
                    try { if (typeof populateMedicalHistoryFormWithData === 'function') populateMedicalHistoryFormWithData(ph, latest); } catch (e) { /* ignore */ }
                    makeContainerReadOnly(ph);
                } else {
                    // Fallback to view list if no single history exists
                    ph.innerHTML = renderPatientHistoryView([]);
                }
            }
        } catch (e) {
            if (ph) ph.innerHTML = '<div class="p-3 text-danger">Error rendering patient history.</div>';
        }

        try {
            const notes = notesRes && (notesRes.data || []);
            if (pn) {
                pn.innerHTML = renderProgressNotesView(notes || []);
            }
        } catch (e) {
            if (pn) pn.innerHTML = '<div class="p-3 text-danger">Error rendering progress notes.</div>';
        }

        openDetailsTabsModal(activeTab);
    }).catch(err => {
        console.error('Error loading modal data:', err);
        showNotification('Error loading patient details', 'danger');
    });
}

// Button handlers to open modal with correct active tab
function viewPatientRecordTab(recordId) {
    loadAndShowRecordTabs(recordId, 'record');
}
function viewPatientHistoryTab(recordId) {
    loadAndShowRecordTabs(recordId, 'history');
}
function viewProgressNotesTab(recordId) {
    loadAndShowRecordTabs(recordId, 'notes');
}





// Utility: make all inputs in a container read-only/disabled for view mode
function makeContainerReadOnly(container) {
    if (!container) return;
    container.querySelectorAll('input, textarea, select, button').forEach(el => {
        const tag = el.tagName.toLowerCase();
        if (tag === 'button') {
            // Hide action buttons within the rendered form when viewing
            el.style.display = 'none';
            return;
        }
        if (tag === 'select') {
            el.disabled = true;
        } else if (tag === 'input' || tag === 'textarea') {
            // Keep visual style but prevent editing
            el.readOnly = true;
            // Also disable interactive inputs like checkboxes/radios/date
            if (['checkbox', 'radio', 'date', 'time', 'datetime-local'].includes(el.type)) {
                el.disabled = true;
            }
        }
        // Subtle visual cue for read-only state
        el.classList.add('bg-light');
    });
}

// Helper function to format date for HTML date input (YYYY-MM-DD)
function formatDateForInput(dateString) {
    if (!dateString) return '';
    try {
        // Simply extract the date part without any Date object conversion
        if (typeof dateString === 'string') {
            // If it's already in YYYY-MM-DD format or YYYY-MM-DD HH:MM:SS format
            const datePart = dateString.split('T')[0].split(' ')[0].trim();

            // Validate it's a proper date format (YYYY-MM-DD)
            if (/^\d{4}-\d{2}-\d{2}$/.test(datePart)) {
                return datePart;
            }
            
            // Try to parse other common date formats
            // Handle formats like "YYYY/MM/DD" or "MM/DD/YYYY" or "DD/MM/YYYY"
            const dateObj = new Date(dateString);
            if (!isNaN(dateObj.getTime())) {
                const year = dateObj.getFullYear();
                const month = String(dateObj.getMonth() + 1).padStart(2, '0');
                const day = String(dateObj.getDate()).padStart(2, '0');
                return `${year}-${month}-${day}`;
            }
        }

        // If we get here, just return empty - don't try to parse with Date object
        // as that causes timezone issues
        console.warn('Unexpected date format:', dateString);
        return '';
    } catch (e) {
        console.error('Error formatting date:', e);
        return '';
    }
}

// Fill the Patient Medical History form inputs with a history object
function populateMedicalHistoryFormWithData(container, h) {
    if (!container || !h) return;
    const q = (name) => container.querySelector(`[name="${name}"]`);

    // Dental history
    if (q('previous_dentist')) q('previous_dentist').value = h.previous_dentist || '';
    if (q('last_dental_visit')) q('last_dental_visit').value = h.last_dental_visit ? formatDateForInput(h.last_dental_visit) : '';
    if (q('treatment_done')) q('treatment_done').value = h.treatment_done || '';

    // Medical history
    if (q('physician_name')) q('physician_name').value = h.physician_name || '';
    if (q('physician_specialty')) q('physician_specialty').value = h.physician_specialty || '';
    if (q('physician_office_address')) q('physician_office_address').value = h.physician_office_address || '';
    if (q('physician_contact')) q('physician_contact').value = h.physician_contact || '';

    // Health questions
    const setRadio = (name, val) => {
        if (val === undefined || val === null) return;
        const normalized = typeof val === 'string' ? val.toLowerCase() : (val === true ? 'yes' : val === false ? 'no' : String(val).toLowerCase());
        const input = container.querySelector(`input[name="${name}"][value="${normalized}"]`);
        if (input) input.checked = true;
    };
    setRadio('good_health', h.good_health);
    setRadio('under_treatment', h.under_treatment);
    if (q('treatment_condition')) q('treatment_condition').value = h.treatment_condition || '';
    setRadio('serious_illness', h.serious_illness);
    if (q('illness_details')) q('illness_details').value = h.illness_details || '';
    setRadio('been_hospitalized', h.been_hospitalized);
    if (q('hospitalization_reason')) q('hospitalization_reason').value = h.hospitalization_reason || '';
    setRadio('taking_drugs', h.taking_drugs);
    if (q('medications')) q('medications').value = h.medications || '';
    if (q('tobacco_use')) q('tobacco_use').value = h.tobacco_use || '';
    if (q('alcohol_use')) q('alcohol_use').value = h.alcohol_use || '';
    if (q('recreational_drugs')) q('recreational_drugs').value = h.recreational_drugs || '';

    // Allergies (checkboxes + details)
    const setCheck = (name, val) => {
        const cb = q(name);
        if (cb) cb.checked = !!(val === 1 || val === true || val === '1' || (typeof val === 'string' && val.toLowerCase() === 'yes'));
    };
    setCheck('allergy_anesthesia', h.allergy_anesthesia);
    setCheck('allergy_sulfa', h.allergy_sulfa);
    setCheck('allergy_antibiotics', h.allergy_antibiotics);
    setCheck('allergy_aspirin', h.allergy_aspirin);
    setCheck('allergy_analgesics', h.allergy_analgesics);
    setCheck('allergy_latex', h.allergy_latex);
    if (q('food_allergy_details')) q('food_allergy_details').value = h.food_allergy_details || '';
    if (q('other_allergy_details')) q('other_allergy_details').value = h.other_allergy_details || '';

    // For women
    setRadio('is_pregnant', h.is_pregnant);
    setRadio('is_nursing', h.is_nursing);
    setRadio('birth_control', h.birth_control);

}

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
async function savePatientRecordForm(callback) {
    // Show confirmation modal before saving
    const confirmed = await showConfirmModal('Are you sure you want to save this patient record?', {
        title: 'Confirm Save',
        icon: 'check-circle',
        type: 'success',
        okText: 'Yes, Save It'
    });

    if (!confirmed) {
        if (callback) callback();
        return; // User cancelled
    }

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

    // Ensure notes includes value from standalone #otherNotes when present
    if (!data.notes) {
        const otherNotesEl = document.getElementById('otherNotes');
        if (otherNotesEl) data.notes = otherNotesEl.value || '';
    }

    // Ensure user_id is set (from currentPatientRecord if available)
    if (!data.user_id && currentPatientRecord && currentPatientRecord.user_id) {
        data.user_id = currentPatientRecord.user_id;
    }

    // Check if user_id is present, if not show error
    if (!data.user_id) {
        showNotification('Please search and select a patient first using the patient name search above', 'error');
        if (callback) callback();
        return;
    }

    console.log('Saving record for user_id:', data.user_id);

    // If editing an existing record, include the record ID
    if (window.currentEditingRecordId) {
        data.id = window.currentEditingRecordId;
        console.log('Updating existing record ID:', data.id);
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

    fetch('/admin/post-procedural/patient-record/store', {
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
                // Remove focus from any active element to prevent aria-hidden warning
                if (document.activeElement) {
                    document.activeElement.blur();
                }
                detailsModal.hide();
            }

            // Show success message
            showNotification('✅ Record saved successfully!', 'success');

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
            showNotification('❌ Error: ' + errorMessage, 'error');
        }
    })
    .catch(error => {
        console.error('Error saving record:', error);
        showNotification('❌ An error occurred while saving the record: ' + error.message, 'error');
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

    // Add Enter key support for forms - trigger save on Ctrl+Enter or Enter in textareas
    document.addEventListener('keydown', function(e) {
        // Ctrl+Enter or Cmd+Enter to save in post-procedural forms
        if ((e.ctrlKey || e.metaKey) && e.key === 'Enter') {
            const patientRecordSection = document.getElementById('patient-record-section');
            if (patientRecordSection && !patientRecordSection.classList.contains('d-none')) {
                e.preventDefault();
                const saveBtn = document.querySelector('button[onclick="savePatientRecordFromTab()"]');
                if (saveBtn && !saveBtn.disabled) {
                    saveBtn.click();
                }
            }
        }
    });

    // Enter key support in form inputs (except textareas) - save when Enter is pressed
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' || e.keyCode === 13) {
            const target = e.target;
            // Don't trigger on textareas, selects, or inputs inside modals that might have other handlers
            if (target.tagName === 'TEXTAREA') {
                return; // Allow Enter in textareas
            }

            // Check if we're in patient record form section
            const patientRecordSection = document.getElementById('patient-record-section');
            if (patientRecordSection && !patientRecordSection.classList.contains('d-none')) {
                // If Enter is pressed in a search input with results, let that handler take precedence
                if (target.id === 'patientNameSearch' || target.id === 'patientSearchInput') {
                    return; // Let search keyboard navigation handle it
                }

                // For other inputs in patient record form, trigger save on Enter
                if (target.tagName === 'INPUT' && target.closest('#patient-record-section')) {
                    e.preventDefault();
                    const saveBtn = document.querySelector('button[onclick="savePatientRecordFromTab()"]');
                    if (saveBtn && !saveBtn.disabled) {
                        saveBtn.click();
                    }
                }
            }
        }
    });
});

// Trigger patient search manually
function triggerPatientSearch() {
    const searchInput = document.getElementById('patientSearchInput');
    const searchTerm = searchInput ? searchInput.value.trim() : '';

    console.log('Manual search triggered for:', searchTerm);

    if (searchTerm.length < 2) {
        showNotification('Please enter at least 2 characters to search', 'warning');
        return;
    }

    searchPatientsForSendTo(searchTerm);
}

// Search patients for patient name autocomplete in Patient Record form
function searchPatientsForPatientName(searchTerm) {
    console.log('Fetching patients for patient name:', searchTerm);

    fetch(`/admin/post-procedural/search-patients?q=${encodeURIComponent(searchTerm)}`)
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
    const searchResults = document.getElementById('patientNameSearchResults');
    if (searchResults) searchResults.innerHTML = '';
    
    const searchInput = document.getElementById('patientNameSearch');
    if (searchInput) searchInput.value = '';

    const firstName = patientData.first_name || '';
    const lastName = patientData.last_name || '';
    const username = patientData.username || patientData.name || '';

    // Show selected patient alert
    const alertBox = document.getElementById('selectedPatientInfoAlert');
    const alertText = document.getElementById('selectedPatientInfoText');
    if (alertText) alertText.textContent = `${username} - ${firstName} ${lastName}`;
    if (alertBox) alertBox.classList.remove('d-none');

    // Auto-populate the patient name fields
    const lastNameElement = document.getElementById('lastName');
    if (lastNameElement) lastNameElement.value = lastName;
    
    const givenNameElement = document.getElementById('givenName');
    if (givenNameElement) givenNameElement.value = firstName;
    
    const middleNameElement = document.getElementById('middleName');
    if (middleNameElement) middleNameElement.value = ''; // Not provided by API

    // Auto-populate home address
    if (patientData.home_address) {
        const homeAddressElement = document.getElementById('homeAddress');
        if (homeAddressElement) homeAddressElement.value = patientData.home_address;
    }

    // Auto-populate birthdate and age
    // Get birthdate from patientData.birthdate or patientData.info.birthdate
    const birthdate = patientData.birthdate || (patientData.info && patientData.info.birthdate) || '';
    console.log('Birthdate from patient data:', birthdate);
    
    const dateOfBirthElement = document.getElementById('dateOfBirth');
    if (dateOfBirthElement && birthdate) {
        // Format birthdate for HTML date input (YYYY-MM-DD)
        const formattedBirthdate = formatDateForInput(birthdate);
        console.log('Formatted birthdate:', formattedBirthdate);
        
        if (formattedBirthdate) {
            dateOfBirthElement.value = formattedBirthdate;

            // Calculate age
            const today = new Date();
            const birthDate = new Date(birthdate);
            if (!isNaN(birthDate.getTime())) {
                let age = today.getFullYear() - birthDate.getFullYear();
                const monthDiff = today.getMonth() - birthDate.getMonth();
                if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                    age--;
                }
                const ageElement = document.getElementById('age');
                if (ageElement) {
                    ageElement.value = age;
                }
            }
        } else {
            console.warn('Failed to format birthdate:', birthdate);
        }
    } else {
        console.warn('Date of birth element not found or birthdate is empty:', {
            elementExists: !!dateOfBirthElement,
            birthdate: birthdate
        });
    }

    // Auto-populate sex/gender
    // Get sex from patientData.sex, patientData.gender, or patientData.info.sex/gender
    const sex = patientData.sex || patientData.gender || (patientData.info && (patientData.info.sex || patientData.info.gender)) || '';
    console.log('Sex/Gender from patient data:', sex);
    
    const sexElement = document.getElementById('sex');
    if (sexElement && sex) {
        // Capitalize first letter and format properly
        const formattedSex = sex.charAt(0).toUpperCase() + sex.slice(1).toLowerCase();
        // Handle common variations
        let finalSex = formattedSex;
        if (formattedSex.toLowerCase() === 'male' || formattedSex.toLowerCase() === 'm') {
            finalSex = 'Male';
        } else if (formattedSex.toLowerCase() === 'female' || formattedSex.toLowerCase() === 'f') {
            finalSex = 'Female';
        }
        sexElement.value = finalSex;
        // Also update the hidden input for form submission
        const sexHiddenElement = document.getElementById('sex_hidden');
        if (sexHiddenElement) {
            sexHiddenElement.value = finalSex;
        }
        console.log('Formatted sex/gender:', finalSex);
    } else if (!sexElement) {
        console.warn('Sex element not found');
    } else if (!sex) {
        console.warn('Sex/Gender is empty in patient data');
    }

    // Auto-populate religion
    if (patientData.religion) {
        const religionElement = document.getElementById('religion');
        if (religionElement) religionElement.value = patientData.religion;
    }

    // Auto-populate occupation
    if (patientData.occupation) {
        const occupationElement = document.getElementById('occupation');
        if (occupationElement) occupationElement.value = patientData.occupation;
    }

    // Auto-populate contact number
    // Get contact from patientData.contact_number or patientData.phone
    const contact = patientData.contact_number || patientData.phone || '';
    if (contact) {
        const contactElement = document.getElementById('contact');
        if (contactElement) contactElement.value = contact;
    }

    // Set currentPatientRecord for save functions
    currentPatientRecord = {
        id: null, // New record
        user_id: patientData.id,
        user: {
            id: patientData.id,
            username: username,
            info: {
                first_name: firstName,
                last_name: lastName,
                middle_name: '',
                home_address: patientData.home_address || '',
                birthdate: patientData.birthdate || '',
                sex: patientData.sex || '',
                religion: patientData.religion || '',
                occupation: patientData.occupation || '',
                phone: patientData.contact_number || patientData.phone || ''
            }
        }
    };

    // Auto-populate "Sent to" section
    const patientSearchInput = document.getElementById('patientSearchInput');
    if (patientSearchInput) patientSearchInput.value = `${username} - ${firstName} ${lastName}`;
    
    const selectedPatientId = document.getElementById('selectedPatientId');
    if (selectedPatientId) selectedPatientId.value = patientData.id;
    
    const selectedPatientDisplay = document.getElementById('selectedPatientDisplay');
    if (selectedPatientDisplay) selectedPatientDisplay.classList.remove('d-none');
    
    const selectedPatientText = document.getElementById('selectedPatientText');
    if (selectedPatientText) selectedPatientText.textContent = `${username} - ${firstName} ${lastName}`;

    // Show success message
    showNotification('Patient information auto-filled successfully!', 'success');

    // Scroll to form fields smoothly
    setTimeout(() => {
        document.getElementById('homeAddress').scrollIntoView({ behavior: 'smooth', block: 'center' });
    }, 300);
}

// Search patients for "Send To" functionality
function searchPatientsForSendTo(searchTerm) {
    console.log('Fetching patients for send to:', searchTerm);

    fetch(`/admin/post-procedural/search-patients?q=${encodeURIComponent(searchTerm)}`)
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

    fetch(`/admin/post-procedural/search-patients?q=${encodeURIComponent(searchTerm)}`)
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
        resultsDiv.innerHTML = '<div class="search-result-item text-muted"><i class="bi bi-info-circle me-2"></i>No patients found</div>';
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
    fetch(`/admin/post-procedural/patient-record-by-user/${userId}`)
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
            showNotification('Failed to load patient record', 'error');
        });
}

function createNewPatientRecord() {
    const searchValue = document.getElementById('patientRecordSearch').value;
    if (!searchValue || !currentPatientRecord) {
        showNotification('Please search and select a patient first using the search box above', 'warning');
        return;
    }

    // Show the form container
    document.getElementById('formContainerWrapper').classList.remove('d-none');

    // Clear all form fields first to prevent data from previous patient
    const setElementValue = (id, value) => {
        const element = document.getElementById(id);
        if (element) element.value = value;
    };

    const addClass = (id, className) => {
        const element = document.getElementById(id);
        if (element) element.classList.add(className);
    };

    const setElementHTML = (id, html) => {
        const element = document.getElementById(id);
        if (element) element.innerHTML = html;
    };

    setElementValue('lastName', '');
    setElementValue('givenName', '');
    setElementValue('middleName', '');
    setElementValue('homeAddress', '');
    setElementValue('dateOfBirth', '');
    setElementValue('age', '');
    setElementValue('sex', '');
    setElementValue('nickname', '');
    setElementValue('religion', '');
    setElementValue('occupation', '');
    setElementValue('contact', '');
    setElementValue('guardianName', '');
    setElementValue('guardianContact', '');
    setElementValue('guardianOccupation', '');
    setElementValue('otherNotes', '');

    // Clear "Sent to" section
    setElementValue('patientSearchInput', '');
    setElementValue('selectedPatientId', '');
    addClass('selectedPatientDisplay', 'd-none');
    setElementHTML('patientSearchResults', '');

    // Populate the form with patient data from the system
    if (currentPatientRecord.user && currentPatientRecord.user.info) {
        const info = currentPatientRecord.user.info;
        setElementValue('lastName', info.last_name || '');
        setElementValue('givenName', info.first_name || '');
        setElementValue('middleName', info.middle_name || '');
        setElementValue('contact', info.phone || '');

        // Calculate age if birthdate exists
        if (info.birthdate) {
            // Format birthdate for HTML date input (YYYY-MM-DD)
            const formattedBirthdate = formatDateForInput(info.birthdate);
            if (formattedBirthdate) {
                setElementValue('dateOfBirth', formattedBirthdate);
                const today = new Date();
                const birthDate = new Date(info.birthdate);
                if (!isNaN(birthDate.getTime())) {
                    let age = today.getFullYear() - birthDate.getFullYear();
                    const monthDiff = today.getMonth() - birthDate.getMonth();
                    if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                        age--;
                    }
                    setElementValue('age', age);
                }
            }
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

    // Set basic fields
    if (document.getElementById('homeAddress')) document.getElementById('homeAddress').value = record.home_address || '';

    // Set date of birth
    const dateOfBirthElement = document.getElementById('dateOfBirth');
    if (dateOfBirthElement) {
        const formattedDate = record.date_of_birth ? formatDateForInput(record.date_of_birth) : '';
        dateOfBirthElement.value = formattedDate;
    }

    if (document.getElementById('age')) document.getElementById('age').value = record.age || '';
    if (document.getElementById('sex')) document.getElementById('sex').value = record.sex || '';
    if (document.getElementById('nickname')) document.getElementById('nickname').value = record.nickname || '';
    if (document.getElementById('religion')) document.getElementById('religion').value = record.religion || '';
    if (document.getElementById('occupation')) document.getElementById('occupation').value = record.occupation || '';
    if (document.getElementById('contact')) document.getElementById('contact').value = record.contact || '';
    if (document.getElementById('guardianName')) document.getElementById('guardianName').value = record.guardian_name || '';
    if (document.getElementById('guardianContact')) document.getElementById('guardianContact').value = record.guardian_contact || '';
    if (document.getElementById('guardianOccupation')) document.getElementById('guardianOccupation').value = record.guardian_occupation || '';
    if (document.getElementById('notes')) document.getElementById('notes').value = record.notes || '';

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
    // Helper function to safely get element value
    const getElementValue = (id) => {
        const element = document.getElementById(id);
        return element ? element.value : '';
    };

    // Collect form data with null checks
    const data = {
        home_address: getElementValue('homeAddress'),
        date_of_birth: getElementValue('dateOfBirth'),
        age: getElementValue('age'),
        sex: getElementValue('sex'),
        nickname: getElementValue('nickname'),
        religion: getElementValue('religion'),
        occupation: getElementValue('occupation'),
        contact: getElementValue('contact'),
        guardian_name: getElementValue('guardianName'),
        guardian_contact: getElementValue('guardianContact'),
        guardian_occupation: getElementValue('guardianOccupation'),
        notes: getElementValue('notes')
    };

    // Fallback for notes: main section may use #otherNotes
    if (!data.notes) {
        const otherNotesEl = document.getElementById('otherNotes');
        if (otherNotesEl) data.notes = otherNotesEl.value || '';
    }

    // Check if patient is selected in "Sent to" or from patient name search
    const selectedPatientIdElement = document.getElementById('selectedPatientId');
    const selectedPatientId = selectedPatientIdElement ? selectedPatientIdElement.value : '';

    // Get user_id from either selectedPatientId or currentPatientRecord
    let userId = selectedPatientId;
    if (!userId && currentPatientRecord && currentPatientRecord.user_id) {
        userId = currentPatientRecord.user_id;
    }

    if (userId) {
        data.user_id = userId;
        if (selectedPatientId) {
            data.sent_to_patient = true;
        }
    } else {
        // If no patient is selected, show error
        showNotification('Please search and select a patient first using the patient name search above', 'error');
        return;
    }

    console.log('Saving patient record with data:', data);

    fetch('/admin/post-procedural/patient-record/store', {
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
                showNotification('Record saved and sent to patient successfully!', 'success');
            } else {
                showNotification('Record saved successfully!', 'success');
            }
            clearPatientRecordForm(true); // Skip confirmation after successful save
            loadPatientRecords(); // Reload the records table
        } else {
            let errorMessage = 'Failed to save record';
            if (result.message) {
                errorMessage = result.message;
            } else if (result.errors) {
                const firstError = Object.values(result.errors)[0];
                errorMessage = Array.isArray(firstError) ? firstError[0] : firstError;
            }
            showNotification('Error: ' + errorMessage, 'error');
        }
    })
    .catch(error => {
        console.error('Error saving record:', error);
        showNotification('An error occurred while saving the record: ' + error.message, 'error');
    });
}

// Send record to patient
function sendRecordToPatient() {
    // Helper function to safely get element value
    const getElementValue = (id) => {
        const element = document.getElementById(id);
        return element ? element.value : '';
    };

    const selectedPatientIdElement = document.getElementById('selectedPatientId');
    const selectedPatientId = selectedPatientIdElement ? selectedPatientIdElement.value : '';

    if (!selectedPatientId) {
        showNotification('Please search and select a patient first', 'warning');
        return;
    }

    // Collect form data and save with patient assignment
    const data = {
        user_id: selectedPatientId,
        home_address: getElementValue('homeAddress'),
        date_of_birth: getElementValue('dateOfBirth'),
        age: getElementValue('age'),
        sex: getElementValue('sex'),
        nickname: getElementValue('nickname'),
        religion: getElementValue('religion'),
        occupation: getElementValue('occupation'),
        contact: getElementValue('contact'),
        guardian_name: getElementValue('guardianName'),
        guardian_contact: getElementValue('guardianContact'),
        guardian_occupation: getElementValue('guardianOccupation'),
        notes: getElementValue('notes'),
        sent_to_patient: true
    };

    console.log('Sending record to patient:', data);

    fetch('/admin/post-procedural/patient-record/store', {
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
            showNotification(`Record successfully sent to ${patientText}!`, 'success');
            clearPatientRecordForm(true); // Skip confirmation after successful send
        } else {
            let errorMessage = 'Failed to send record';
            if (result.message) {
                errorMessage = result.message;
            } else if (result.errors) {
                const firstError = Object.values(result.errors)[0];
                errorMessage = Array.isArray(firstError) ? firstError[0] : firstError;
            }
            showNotification('Error: ' + errorMessage, 'error');
        }
    })
    .catch(error => {
        console.error('Error sending record:', error);
        showNotification('An error occurred while sending the record: ' + error.message, 'error');
    });
}

async function clearPatientRecordForm(skipConfirmation = false) {
    // If skipConfirmation is false, show confirmation modal
    if (!skipConfirmation) {
        const confirmed = await showConfirmModal('Are you sure you want to clear the form?', {
            title: 'Clear Form',
            icon: 'exclamation-triangle',
            type: 'warning',
            okText: 'Yes, Clear It'
        });

        if (!confirmed) return;
    }

    {
        // Helper function to safely set element value
        const setElementValue = (id, value) => {
            const element = document.getElementById(id);
            if (element) element.value = value;
        };

        const setElementHTML = (id, html) => {
            const element = document.getElementById(id);
            if (element) element.innerHTML = html;
        };

        const addClass = (id, className) => {
            const element = document.getElementById(id);
            if (element) element.classList.add(className);
        };

        // Hide selected patient alert
        addClass('selectedPatientInfoAlert', 'd-none');

        // Clear patient name search
        setElementValue('patientNameSearch', '');
        setElementHTML('patientNameSearchResults', '');

        // Clear all form fields
        setElementValue('lastName', '');
        setElementValue('givenName', '');
        setElementValue('middleName', '');
        setElementValue('homeAddress', '');
        setElementValue('dateOfBirth', '');
        setElementValue('age', '');
        setElementValue('sex', '');
        setElementValue('nickname', '');
        setElementValue('religion', '');
        setElementValue('occupation', '');
        setElementValue('contact', '');
        setElementValue('guardianName', '');
        setElementValue('guardianContact', '');
        setElementValue('guardianOccupation', '');
        setElementValue('otherNotes', '');

        // Reset "Sent to" section
        setElementValue('patientSearchInput', '');
        setElementValue('selectedPatientId', '');
        addClass('selectedPatientDisplay', 'd-none');
        setElementHTML('patientSearchResults', '');

        currentPatientRecord = null;

        // Scroll back to top
        window.scrollTo({ top: 0, behavior: 'smooth' });
    }
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

async function addNewPatientHistory(recordId) {
    // Show confirmation modal before saving
    const confirmed = await showConfirmModal('Are you sure you want to save this medical history record?', {
        title: 'Confirm Save',
        icon: 'check-circle',
        type: 'success',
        okText: 'Yes, Save It'
    });

    if (!confirmed) {
        return; // User cancelled
    }

    const form = document.getElementById('newHistoryForm');
    const formData = new FormData(form);
    const data = {
        patient_record_id: recordId,
        // Dental History
        previous_dentist: formData.get('previous_dentist'),
        last_dental_visit: formData.get('last_dental_visit'),
        treatment_done: formData.get('treatment_done'),
        // Medical History
        physician_name: formData.get('physician_name'),
        physician_specialty: formData.get('physician_specialty'),
        physician_office_address: formData.get('physician_office_address'),
        physician_contact: formData.get('physician_contact'),
        // Health Questions
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
        // Allergies
        allergy_anesthesia: formData.get('allergy_anesthesia') ? 1 : 0,
        allergy_sulfa: formData.get('allergy_sulfa') ? 1 : 0,
        allergy_antibiotics: formData.get('allergy_antibiotics') ? 1 : 0,
        allergy_aspirin: formData.get('allergy_aspirin') ? 1 : 0,
        allergy_analgesics: formData.get('allergy_analgesics') ? 1 : 0,
        allergy_latex: formData.get('allergy_latex') ? 1 : 0,
        food_allergy_details: formData.get('food_allergy_details'),
        other_allergy_details: formData.get('other_allergy_details'),
        // For Women
        is_pregnant: formData.get('is_pregnant'),
        is_nursing: formData.get('is_nursing'),
        birth_control: formData.get('birth_control'),
    };

    fetch('/admin/post-procedural/patient-history', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => {
                throw new Error(err.message || 'Server error');
            });
        }
        return response.json();
    })
    .then(result => {
        if (result.success) {
            showNotification('Patient history saved and sent to patient successfully!', 'success');

            // Auto-refresh: reload form list to show updates
            loadPatientRecords();

            // Refresh modal to show new history
            if (typeof editPatientInfo === 'function') {
                editPatientInfo(window.currentEditingRecordId);
            }
        } else {
            showNotification('Failed to add patient history: ' + (result.message || 'Unknown error'), 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error adding patient history: ' + error.message, 'error');
    });
}

async function savePatientHistory(historyId, recordId) {
    // Show confirmation modal before saving
    const confirmed = await showConfirmModal('Are you sure you want to save changes to this medical history record?', {
        title: 'Confirm Save',
        icon: 'check-circle',
        type: 'success',
        okText: 'Yes, Save Changes'
    });

    if (!confirmed) {
        return; // User cancelled
    }

    const form = document.getElementById(`historyForm${historyId}`);
    const formData = new FormData(form);
    const data = {
        id: historyId,
        patient_record_id: recordId,
        // Dental History
        previous_dentist: formData.get('previous_dentist'),
        last_dental_visit: formData.get('last_dental_visit'),
        treatment_done: formData.get('treatment_done'),
        // Medical History
        physician_name: formData.get('physician_name'),
        physician_specialty: formData.get('physician_specialty'),
        physician_office_address: formData.get('physician_office_address'),
        physician_contact: formData.get('physician_contact'),
        // Health Questions
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
        // Allergies
        allergy_anesthesia: formData.get('allergy_anesthesia') ? 1 : 0,
        allergy_sulfa: formData.get('allergy_sulfa') ? 1 : 0,
        allergy_antibiotics: formData.get('allergy_antibiotics') ? 1 : 0,
        allergy_aspirin: formData.get('allergy_aspirin') ? 1 : 0,
        allergy_analgesics: formData.get('allergy_analgesics') ? 1 : 0,
        allergy_latex: formData.get('allergy_latex') ? 1 : 0,
        food_allergy_details: formData.get('food_allergy_details'),
        other_allergy_details: formData.get('other_allergy_details'),
        // For Women
        is_pregnant: formData.get('is_pregnant'),
        is_nursing: formData.get('is_nursing'),
        birth_control: formData.get('birth_control'),
    };

    fetch('/admin/post-procedural/patient-history', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => {
                throw new Error(err.message || 'Server error');
            });
        }
        return response.json();
    })
    .then(result => {
        if (result.success) {
            showNotification('Patient history updated and sent to patient successfully!', 'success');

            // Auto-refresh: reload form list to show updates
            loadPatientRecords();

            // Refresh modal to show updated history
            if (typeof editPatientInfo === 'function') {
                editPatientInfo(recordId);
            }
        } else {
            showNotification('Failed to update patient history: ' + (result.message || 'Unknown error'), 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error updating patient history: ' + error.message, 'error');
    });
}

async function deletePatientHistory(historyId) {
    const confirmed = await showConfirmModal('Are you sure you want to delete this visit record? This action cannot be undone.', {
        title: 'Delete Visit Record',
        icon: 'trash',
        type: 'danger',
        okText: 'Yes, Delete'
    });

    if (!confirmed) return;

    fetch(`/admin/post-procedural/patient-history/${historyId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => {
                throw new Error(err.message || 'Server error');
            });
        }
        return response.json();
    })
    .then(result => {
        if (result.success) {
            showNotification('Patient history deleted successfully!', 'success');

            // Auto-refresh: reload form list to show updates
            loadPatientRecords();

            // Refresh modal to show updated history
            if (typeof editPatientInfo === 'function') {
                editPatientInfo(window.currentEditingRecordId);
            }
        } else {
            showNotification('Failed to delete patient history: ' + (result.message || 'Unknown error'), 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error deleting patient history: ' + error.message, 'error');
    });
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
        amount_paid: formData.get('amount_paid'),
        balance: formData.get('balance'),
        conforme: formData.get('conforme')
    };

    fetch('/admin/post-procedural/progress-notes', {
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
            showNotification('Progress note added successfully!', 'success');

            // Auto-refresh: reload form list to show updates
            loadPatientRecords();

            // Refresh modal to show new note
            if (typeof editPatientInfo === 'function') {
                editPatientInfo(window.currentEditingRecordId);
            }
        } else {
            showNotification('Failed to add progress note', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error adding progress note', 'error');
    });
}

function saveProgressNote(noteId, recordId) {
    const form = document.getElementById(`noteForm${noteId}_${recordId}`);
    if (!form) {
        console.error('Form not found:', `noteForm${noteId}_${recordId}`);
        showNotification('Form not found. Please refresh and try again.', 'error');
        return;
    }

    const formData = new FormData(form);
    const data = {
        id: noteId,
        patient_record_id: recordId,
        note_date: formData.get('note_date'),
        progress_description: formData.get('progress_description'),
        amount_paid: formData.get('amount_paid'),
        balance: formData.get('balance'),
        conforme: formData.get('conforme')
    };

    console.log('Saving progress note:', data);

    fetch('/admin/post-procedural/progress-notes', {
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
            // Close the edit notes modal first
            const editNotesModal = bootstrap.Modal.getInstance(document.getElementById('editNotesModal'));
            if (editNotesModal) {
                editNotesModal.hide();
            }

            // Show success notification (will appear above any remaining modal backdrop)
            setTimeout(() => {
                showNotification('Progress note updated successfully!', 'success');
            }, 300);

            // Auto-refresh: reload form list to show updates
            loadPatientRecords();

            // Refresh modal to show updated note
            if (window.currentEditingRecordId && typeof editPatientInfo === 'function') {
                editPatientInfo(window.currentEditingRecordId);
            }
        } else {
            showNotification('Failed to update progress note', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error updating progress note', 'error');
    });
}

async function deleteProgressNote(noteId) {
    const confirmed = await showConfirmModal('Are you sure you want to delete this progress note? This action cannot be undone.', {
        title: 'Delete Progress Note',
        icon: 'trash',
        type: 'danger',
        okText: 'Yes, Delete'
    });

    if (!confirmed) return;

    fetch(`/admin/post-procedural/progress-notes/${noteId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(result => {
        if (result.success) {
            showNotification('Progress note deleted successfully!', 'success');

            // Auto-refresh: reload form list to show updates
            loadPatientRecords();

            // Refresh modal to show updated notes
            if (typeof editPatientInfo === 'function') {
                editPatientInfo(window.currentEditingRecordId);
            }
        } else {
            showNotification('Failed to delete progress note', 'error');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error deleting progress note', 'error');
    });
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

    fetch(`/admin/post-procedural/search-patients?search=${encodeURIComponent(searchTerm)}`)
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

// Select patient and load their medical history
function selectPatientForHistory(patientId, patientName) {
    selectedPatientForHistory = { id: patientId, name: patientName };

    // Show toggle button
    document.getElementById('toggleHistoryView').style.display = 'block';

    // Display the fillable medical history form
    displayMedicalHistoryForm(patientId, patientName);
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
                            Medical History Record
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

                </div>
                <div class="card-footer bg-light text-muted small">
                    <i class="bi bi-clock me-1"></i>Recorded: ${record.created_at ? new Date(record.created_at).toLocaleDateString() : 'N/A'}
                </div>
            </div>
        `).join('')}
    `;

    document.getElementById('patientHistoryDetailsContainer').innerHTML = html;
}

// Send medical history to patient
async function sendHistoryToPatient() {
    if (!selectedPatientForHistory) {
        showNotification('Please select a patient first', 'warning');
        return;
    }

    const confirmed = await showConfirmModal(`Send medical history form to ${selectedPatientForHistory.name}?`, {
        title: 'Send Medical History',
        icon: 'send',
        type: 'success',
        okText: 'Yes, Send'
    });

    if (!confirmed) return;

    const btn = document.getElementById('sendHistoryToPatientBtn');
    const originalContent = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="bi bi-hourglass-split me-1"></i>Sending...';

    fetch(`/admin/post-procedural/send-history/${selectedPatientForHistory.id}`, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        btn.disabled = false;
        btn.innerHTML = originalContent;

        if (data.success) {
            showNotification(`Medical history sent to ${selectedPatientForHistory.name} successfully!`, 'success');
        } else {
            showNotification(data.message || 'Failed to send medical history', 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        btn.disabled = false;
        btn.innerHTML = originalContent;
        showNotification('Error sending medical history', 'danger');
    });
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
    document.getElementById('sendHistoryToPatientBtn').style.display = 'none';
    selectedPatientForHistory = null;
}

// Initialize patient history form
let historyPatientSearchInitialized = false;
let selectedHistoryPatient = null;

function initializePatientHistoryForm() {
    if (historyPatientSearchInitialized) return;

    const historyPatientSearch = document.getElementById('historyPatientNameSearch');
    if (historyPatientSearch) {
        historyPatientSearch.addEventListener('input', function() {
            clearTimeout(patientSearchTimeout);
            const searchTerm = this.value.trim();

            if (searchTerm.length < 2) {
                document.getElementById('historyPatientNameSearchResults').innerHTML = '';
                return;
            }

            patientSearchTimeout = setTimeout(() => {
                searchPatientsForHistoryName(searchTerm);
            }, 300);
        });

        // Clear results when clicking outside
        document.addEventListener('click', function(e) {
            if (!historyPatientSearch.contains(e.target) &&
                !document.getElementById('historyPatientNameSearchResults').contains(e.target)) {
                document.getElementById('historyPatientNameSearchResults').innerHTML = '';
            }
        });

        historyPatientSearchInitialized = true;
    }

    // Render empty form by default
    renderMedicalHistoryFormOnly();
}

// Search patients for history name autocomplete
function searchPatientsForHistoryName(searchTerm) {
    fetch(`/admin/post-procedural/search-patients?q=${encodeURIComponent(searchTerm)}`)
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayHistoryPatientNameResults(data.data);
            } else {
                document.getElementById('historyPatientNameSearchResults').innerHTML =
                    '<div class="search-result-item text-danger">Error loading patients</div>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('historyPatientNameSearchResults').innerHTML =
                '<div class="search-result-item text-danger">Error: ' + error.message + '</div>';
        });
}

function displayHistoryPatientNameResults(patients) {
    const resultsDiv = document.getElementById('historyPatientNameSearchResults');

    if (!patients || patients.length === 0) {
        resultsDiv.innerHTML = '<div class="search-result-item text-muted"><i class="bi bi-info-circle me-2"></i>No patients with appointments found</div>';
        return;
    }

    resultsDiv.innerHTML = patients.map(patient => {
        const firstName = patient.first_name || '';
        const lastName = patient.last_name || '';
        const fullName = `${firstName} ${lastName}`.trim();

        return `
            <div class="search-result-item" onclick='selectHistoryPatient(${JSON.stringify(patient).replace(/'/g, "&#39;")})'>
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

function selectHistoryPatient(patientData) {
    console.log('Selected patient for history:', patientData);

    const userId = patientData.id;
    const username = patientData.username || patientData.name || '';
    const firstName = patientData.first_name || '';
    const lastName = patientData.last_name || '';

    // Clear search results and input
    document.getElementById('historyPatientNameSearchResults').innerHTML = '';
    document.getElementById('historyPatientNameSearch').value = '';

    // Show selected patient alert
    const alertBox = document.getElementById('selectedHistoryPatientInfoAlert');
    const alertText = document.getElementById('selectedHistoryPatientInfoText');
    alertText.textContent = `${username} - ${firstName} ${lastName}`;
    alertBox.classList.remove('d-none');

    // Auto-populate the patient name fields (readonly)
    document.getElementById('historyLastName').value = lastName;
    document.getElementById('historyGivenName').value = firstName;
    document.getElementById('historyMiddleName').value = ''; // Not provided by API

    // Store selected patient
    selectedHistoryPatient = { id: userId, name: username };

    // Fetch patient record to get patient_record_id
    fetch(`/admin/post-procedural/patient-record-by-user/${userId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data && data.data.id) {
                const patientRecordId = data.data.id;

                // Fetch existing patient history records
                fetch(`/admin/post-procedural/patient-history/${patientRecordId}`)
                    .then(response => response.json())
                    .then(historyData => {
                        if (historyData.success && historyData.data && historyData.data.length > 0) {
                            // Show existing history records
                            renderExistingHistoryRecords(historyData.data, patientRecordId, userId);
                        } else {
                            // No history found, render empty form
                            renderMedicalHistoryFormOnly(userId);
                            showNotification('No existing history found for this patient. You can create a new one.', 'info');
                        }

                        // Scroll to form
                        setTimeout(() => {
                            document.getElementById('medicalHistoryFormContainer').scrollIntoView({ behavior: 'smooth', block: 'start' });
                        }, 300);
                    })
                    .catch(error => {
                        console.error('Error fetching patient history:', error);
                        renderMedicalHistoryFormOnly(userId);
                    });
            } else {
                // No patient record exists, render empty form
                renderMedicalHistoryFormOnly(userId);
                showNotification('No patient record found. Creating a new history will auto-create a patient record.', 'info');

                setTimeout(() => {
                    document.getElementById('medicalHistoryFormContainer').scrollIntoView({ behavior: 'smooth', block: 'start' });
                }, 300);
            }
        })
        .catch(error => {
            console.error('Error fetching patient record:', error);
            renderMedicalHistoryFormOnly(userId);
        });
}

// Render existing history records
function renderExistingHistoryRecords(histories, patientRecordId, userId) {
    let html = `
        <div class="alert alert-success mb-4" style="background: linear-gradient(135deg, #d1e7dd 0%, #badbcc 100%); border: 1px solid #a3cfbb;">
            <i class="bi bi-info-circle-fill me-2"></i>
            <strong>Found ${histories.length} existing history record${histories.length > 1 ? 's' : ''}</strong> for this patient.
        </div>

        <div class="mb-4">
            <h6 class="fw-bold mb-3" style="color: #0a4275;">EXISTING HISTORY RECORDS</h6>
    `;

    histories.forEach((history, index) => {
        const visitDate = 'Medical History Record';

        html += `
            <div class="card mb-3" style="border: 2px solid #0d6efd;">
                <div class="card-header" style="background: linear-gradient(135deg, #e7f1ff 0%, #cfe2ff 100%);">
                    <div class="d-flex justify-content-between align-items-center">
                        <strong style="color: #0a4275;"><i class="bi bi-calendar-event me-2"></i>Visit Date: ${visitDate}</strong>
                        <div class="btn-group btn-group-sm">
                            <button type="button" class="btn btn-primary" onclick="viewHistoryDetails(${history.id})" title="View Details">
                                <i class="bi bi-eye me-1"></i>View
                            </button>
                            <button type="button" class="btn btn-success" onclick="editHistoryRecord(${history.id})" title="Edit">
                                <i class="bi bi-pencil me-1"></i>Edit
                            </button>
                            <button type="button" class="btn btn-danger" onclick="deleteHistoryRecord(${history.id})" title="Delete">
                                <i class="bi bi-trash me-1"></i>Delete
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body" id="historyDetails${history.id}" style="display: none; background: #f8f9fa;">
                    <!-- DENTAL HISTORY -->
                    <div class="mb-3">
                        <h6 class="fw-bold mb-3" style="color: #0d6efd; border-bottom: 2px solid #0d6efd; padding-bottom: 8px;">DENTAL HISTORY</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <strong class="d-block" style="color: #6c757d; font-size: 0.9rem;">Previous Dentist:</strong>
                                <span>${history.previous_dentist || 'N/A'}</span>
                            </div>
                            <div class="col-md-4">
                                <strong class="d-block" style="color: #6c757d; font-size: 0.9rem;">Last Dental Visit:</strong>
                                <span>${history.last_dental_visit ? new Date(history.last_dental_visit).toLocaleDateString('en-US', {year: 'numeric', month: '2-digit', day: '2-digit'}) : 'N/A'}</span>
                            </div>
                            <div class="col-md-4">
                                <strong class="d-block" style="color: #6c757d; font-size: 0.9rem;">Treatment Done:</strong>
                                <span>${history.treatment_done || 'N/A'}</span>
                            </div>
                        </div>
                    </div>

                    <!-- MEDICAL HISTORY -->
                    <div class="mb-3">
                        <h6 class="fw-bold mb-3" style="color: #0d6efd; border-bottom: 2px solid #0d6efd; padding-bottom: 8px;">MEDICAL HISTORY</h6>
                        <div class="row g-3">
                            <div class="col-md-12">
                                <strong class="d-block" style="color: #6c757d; font-size: 0.9rem;">Physician Name:</strong>
                                <span>${history.physician_name || 'N/A'}</span>
                            </div>
                            <div class="col-md-8">
                                <strong class="d-block" style="color: #6c757d; font-size: 0.9rem;">Office Address:</strong>
                                <span>${history.physician_office_address || 'N/A'}</span>
                            </div>
                            <div class="col-md-4">
                                <strong class="d-block" style="color: #6c757d; font-size: 0.9rem;">Contact:</strong>
                                <span>${history.physician_contact || 'N/A'}</span>
                            </div>
                        </div>
                    </div>

                    <!-- HEALTH QUESTIONS -->
                    <div class="mb-3">
                        <h6 class="fw-bold mb-3" style="color: #0d6efd; border-bottom: 2px solid #0d6efd; padding-bottom: 8px;">HEALTH QUESTIONS</h6>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <strong class="d-block" style="color: #6c757d; font-size: 0.9rem;">Are you in good health?</strong>
                                <span class="badge ${history.good_health && history.good_health.toLowerCase() === 'yes' ? 'bg-success' : 'bg-secondary'}">${history.good_health || 'N/A'}</span>
                            </div>
                            <div class="col-md-6">
                                <strong class="d-block" style="color: #6c757d; font-size: 0.9rem;">Under medical treatment?</strong>
                                <span>${history.under_treatment || 'N/A'}</span>
                            </div>
                            <div class="col-md-6">
                                <strong class="d-block" style="color: #6c757d; font-size: 0.9rem;">Serious illness/operation?</strong>
                                <span>${history.serious_illness || 'no'}</span>
                            </div>
                            <div class="col-md-6">
                                <strong class="d-block" style="color: #6c757d; font-size: 0.9rem;">Been hospitalized?</strong>
                                <span>${history.been_hospitalized || 'no'}</span>
                            </div>
                            <div class="col-md-6">
                                <strong class="d-block" style="color: #6c757d; font-size: 0.9rem;">Taking prescription drugs?</strong>
                                <span>${history.taking_drugs || 'N/A'}</span>
                            </div>
                            <div class="col-md-6">
                                <strong class="d-block" style="color: #6c757d; font-size: 0.9rem;">Tobacco Use:</strong>
                                <span>${history.tobacco_use || 'N/A'}</span>
                            </div>
                            <div class="col-md-6">
                                <strong class="d-block" style="color: #6c757d; font-size: 0.9rem;">Alcohol Use:</strong>
                                <span>${history.alcohol_use || 'no'}</span>
                            </div>
                            <div class="col-md-6">
                                <strong class="d-block" style="color: #6c757d; font-size: 0.9rem;">Recreational Drugs:</strong>
                                <span>${history.recreational_drugs || 'no'}</span>
                            </div>
                        </div>
                    </div>


                    <!-- FOR WOMEN -->
                    <div class="mb-3">
                        <h6 class="fw-bold mb-3" style="color: #0d6efd; border-bottom: 2px solid #0d6efd; padding-bottom: 8px;">FOR WOMEN</h6>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <strong class="d-block" style="color: #6c757d; font-size: 0.9rem;">Pregnant:</strong>
                                <span>${history.is_pregnant || 'no'}</span>
                            </div>
                            <div class="col-md-4">
                                <strong class="d-block" style="color: #6c757d; font-size: 0.9rem;">Nursing:</strong>
                                <span>${history.is_nursing || 'no'}</span>
                            </div>
                            <div class="col-md-4">
                                <strong class="d-block" style="color: #6c757d; font-size: 0.9rem;">Taking Birth Control Pills:</strong>
                                <span>${history.birth_control || 'no'}</span>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        `;
    });

    html += `
        </div>
        <hr class="my-4">
        <div class="text-center mb-3">
            <button type="button" class="btn btn-lg btn-primary" onclick="renderMedicalHistoryFormOnly(${userId})">
                <i class="bi bi-plus-circle me-2"></i>Add New History Record
            </button>
        </div>
    `;

    document.getElementById('medicalHistoryFormContainer').innerHTML = html;
}

// View history details
function viewHistoryDetails(historyId) {
    const detailsDiv = document.getElementById('historyDetails' + historyId);
    const isVisible = detailsDiv.style.display !== 'none';

    // Hide all details first
    document.querySelectorAll('[id^="historyDetails"]').forEach(div => {
        div.style.display = 'none';
    });

    // Toggle this one
    if (!isVisible) {
        detailsDiv.style.display = 'block';
    }
}

// Edit history record
function editHistoryRecord(historyId) {
    showNotification('Loading history record for editing...', 'info');

    fetch(`/admin/post-procedural/patient-history/${historyId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data) {
                // TODO: Populate form with history data for editing
                showNotification('Edit functionality coming soon', 'warning');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showNotification('Error loading history record', 'danger');
        });
}

// Delete history record
function deleteHistoryRecord(historyId) {
    if (!confirm('Are you sure you want to delete this history record? This action cannot be undone.')) {
        return;
    }

    fetch(`/admin/post-procedural/patient-history/${historyId}`, {
        method: 'DELETE',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('✅ History record deleted successfully!', 'success');
            // Refresh the patient selection to reload the list
            if (selectedHistoryPatient) {
                const patient = selectedHistoryPatient;
                selectHistoryPatient(patient.id, patient.name,
                    document.getElementById('historyGivenName').value,
                    document.getElementById('historyLastName').value,
                    document.getElementById('historyMiddleName').value);
            }
        } else {
            showNotification('❌ Failed to delete history record', 'danger');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('❌ Error deleting history record', 'danger');
    });
}

// Render medical history form only (without patient info header)
function renderMedicalHistoryFormOnly(patientId = null) {
    const html = `
        <form id="medicalHistoryForm">
            ${patientId ? `<input type="hidden" name="patient_id" value="${patientId}">` : ''}

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

            <!-- Action Buttons -->
            <div class="mt-4 pt-3" style="border-top: 2px solid #dee2e6;">
                <div class="d-flex gap-3 justify-content-end">
                    <button type="button" class="btn btn-secondary btn-lg" onclick="clearMedicalHistoryForm()"
                            style="border-radius: 8px; padding: 10px 24px; font-weight: 600;">
                        <i class="bi bi-x-circle me-2"></i> CLEAR
                    </button>
                    <button type="button" class="btn btn-primary btn-lg" onclick="saveMedicalHistoryFormFromTab()"
                            style="background: linear-gradient(135deg, #198754 0%, #146c43 100%); border: none; border-radius: 8px; padding: 10px 24px; font-weight: 600;">
                        <i class="bi bi-floppy-fill me-2"></i> SAVE RECORD
                    </button>
                </div>
            </div>
        </form>
    `;

    document.getElementById('medicalHistoryFormContainer').innerHTML = html;
}

// Clear medical history form
async function clearMedicalHistoryForm(skipConfirmation = false) {
    // If skipConfirmation is false, show confirmation modal
    if (!skipConfirmation) {
        const confirmed = await showConfirmModal('Are you sure you want to clear the form?', {
            title: 'Clear Form',
            icon: 'exclamation-triangle',
            type: 'warning',
            okText: 'Yes, Clear It'
        });

        if (!confirmed) return;
    }

    // Hide selected patient alert
    document.getElementById('selectedHistoryPatientInfoAlert').classList.add('d-none');

    // Clear patient name search
    document.getElementById('historyPatientNameSearch').value = '';
    document.getElementById('historyPatientNameSearchResults').innerHTML = '';

    // Clear readonly name fields
    document.getElementById('historyLastName').value = '';
    document.getElementById('historyGivenName').value = '';
    document.getElementById('historyMiddleName').value = '';

    selectedHistoryPatient = null;

    // Re-render empty form
    renderMedicalHistoryFormOnly();

    // Scroll back to top
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Save medical history from tab
async function saveMedicalHistoryFormFromTab() {
    const form = document.getElementById('medicalHistoryForm');
    if (!form) {
        showNotification('Form not found', 'error');
        return;
    }

    const formData = new FormData(form);

    // Determine if we're in modal context (editing a patient) or standalone tab
    const isModalContext = !!(window.currentEditingRecordId && (
        (document.getElementById('detailsModal') && document.getElementById('detailsModal').classList.contains('show')) ||
        (document.getElementById('editHistoryModal') && document.getElementById('editHistoryModal').classList.contains('show'))
    ));

    // Validate patient is selected (only for standalone tab, not modal)
    if (!isModalContext && (!selectedHistoryPatient || !selectedHistoryPatient.id)) {
        showNotification('Please search and select a patient first', 'warning');
        return;
    }


    // Show confirmation modal before saving
    const confirmed = await showConfirmModal('Are you sure you want to save this medical history record?', {
        title: 'Confirm Save',
        icon: 'check-circle',
        type: 'success',
        okText: 'Yes, Save It'
    });

    if (!confirmed) {
        return; // User cancelled
    }

    // Build the data object
    const data = {
        // If in modal context, use patient_record_id; otherwise use patient_id
        ...(isModalContext
            ? { patient_record_id: window.currentEditingRecordId }
            : { patient_id: selectedHistoryPatient.id }),
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
    };

    // Send to server
    fetch('/admin/post-procedural/patient-history', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify(data)
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => {
                throw new Error(err.message || 'Server error');
            });
        }
        return response.json();
    })
    .then(result => {
        if (result.success) {
            showNotification('Medical history saved and sent to patient successfully!', 'success');

            // Auto-refresh: reload form list to show updates
            loadPatientRecords();

            // If in modal context, refresh the patient info to show new history
            if (isModalContext) {
                if (typeof editPatientInfo === 'function') {
                    editPatientInfo(window.currentEditingRecordId);
                }
                // Switch to Patient History tab to show the new record
                const historyTab = document.getElementById('patient-history-tab');
                if (historyTab) historyTab.click();
            } else {
                // Standalone tab - clear the form WITHOUT confirmation (skipConfirmation = true)
                clearMedicalHistoryForm(true);
            }
        } else {
            showNotification('Failed to save medical history: ' + (result.message || 'Unknown error'), 'error');
            console.error('Save error:', result);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        showNotification('Error saving medical history: ' + error.message, 'error');
    });
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

// Save medical history form
function saveMedicalHistoryForm() {
    const form = document.getElementById('medicalHistoryForm');
    const formData = new FormData(form);


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
    };

    // Show loading notification
    showNotification('Saving medical history...', 'info');

    // Send to server
    fetch('/admin/post-procedural/patient-history', {
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
        fetch(`/admin/post-procedural/patient-history/${selectedPatientForHistory.id}`)
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

    // Clear search - with null checks
    const searchInput = document.getElementById('progressNotePatientSearch');
    if (searchInput) searchInput.value = '';

    const resultsDiv = document.getElementById('progressNotePatientSearchResults');
    if (resultsDiv) resultsDiv.innerHTML = '';

    // Show selected patient alert - with null checks
    const alertEl = document.getElementById('selectedProgressNotePatientAlert');
    if (alertEl) alertEl.classList.remove('d-none');

    const textEl = document.getElementById('selectedProgressNotePatientText');
    if (textEl) textEl.textContent = `${patientName} (@${username})`;

    // Set send to patient field - with null check
    const sendToInput = document.getElementById('progressNoteSendToPatient');
    if (sendToInput) sendToInput.value = patientName;

    // Load existing progress notes for this patient
    loadProgressNotes(patientId);

    showNotification(`Patient ${patientName} selected`, 'success');
}

// Load existing progress notes for patient
function loadProgressNotes(patientId) {
    // First, we need to get or create the patient record by user ID
    fetch(`/admin/post-procedural/patient-record-by-user/${patientId}`)
        .then(response => response.json())
        .then(data => {
            if (data.success && data.data) {
                const recordId = data.data.id;
                // Now load progress notes for this record
                fetch(`/admin/post-procedural/progress-notes/${recordId}`)
                    .then(response => response.json())
                    .then(notesData => {
                        if (notesData.success && notesData.data.length > 0) {
                            showNotification(`✅ Found ${notesData.data.length} existing progress note${notesData.data.length > 1 ? 's' : ''} for this patient. You can add new rows below.`, 'success');
                            progressNoteRows = notesData.data.map((note, index) => ({
                                id: note.id,
                                date: note.note_date,
                                progressNote: note.progress_description || '',
                                amountPaid: note.amount_paid || '',
                                balance: note.balance || '',
                                conforme: note.conforme || '',
                                rowId: progressNoteRowCounter++
                            }));
                            // Add one empty row for new entry
                            addProgressNoteRow();
                            renderProgressNotesTable();
                        } else {
                            // No existing notes, start fresh
                            showNotification('ℹ️ No existing progress notes found. You can create new ones.', 'info');
                            progressNoteRows = [];
                            addProgressNoteRow();
                        }
                    })
                    .catch(error => {
                        console.error('Error loading progress notes:', error);
                        showNotification('⚠️ Could not load progress notes. Starting fresh.', 'warning');
                        progressNoteRows = [];
                        addProgressNoteRow();
                    });
            } else {
                // No patient record yet, start fresh
                showNotification('ℹ️ No patient record found. Creating notes will auto-create a patient record.', 'info');
                progressNoteRows = [];
                addProgressNoteRow();
            }
        })
        .catch(error => {
            console.error('Error loading patient record:', error);
            showNotification('❌ Error loading patient record', 'danger');
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
        amountPaid: '',
        balance: '',
        conforme: '',
        rowId: rowId
    });

    renderProgressNotesTable();
}

// Render progress notes table
function renderProgressNotesTable() {
    const tbody = document.getElementById('progressNotesTableBody');
    let html = '';

    progressNoteRows.forEach((row, index) => {
        const isExisting = row.id !== null && row.id !== undefined;
        const rowClass = isExisting ? 'existing-note-row' : 'new-note-row';
        const readonlyAttr = isExisting ? 'readonly' : '';
        const disabledAttr = isExisting ? 'disabled' : '';
        const bgColor = isExisting ? 'background-color: #f8f9fa;' : '';
        
        html += `
            <tr data-row-id="${row.rowId}" data-note-id="${row.id || ''}" class="${rowClass}" style="${bgColor}">
                <td>
                    <input type="date" class="form-control form-control-sm" value="${row.date || ''}"
                           onchange="updateProgressNoteRow(${row.rowId}, 'date', this.value)"
                           ${readonlyAttr} style="${bgColor}">
                </td>
                <td>
                    <textarea class="form-control form-control-sm" rows="2"
                              onchange="updateProgressNoteRow(${row.rowId}, 'progressNote', this.value)"
                              placeholder="Treatment progress..." ${readonlyAttr} style="${bgColor}">${row.progressNote || ''}</textarea>
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm" step="0.01" min="0"
                           value="${row.amountPaid || ''}"
                           onchange="updateProgressNoteRow(${row.rowId}, 'amountPaid', this.value)"
                           placeholder="0.00" ${readonlyAttr} style="${bgColor}">
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm" step="0.01" min="0"
                           value="${row.balance || ''}"
                           onchange="updateProgressNoteRow(${row.rowId}, 'balance', this.value)"
                           placeholder="0.00" ${readonlyAttr} style="${bgColor}">
                </td>
                <td>
                    <input type="text" class="form-control form-control-sm"
                           value="${row.conforme || ''}"
                           onchange="updateProgressNoteRow(${row.rowId}, 'conforme', this.value)"
                           placeholder="Conforme..." ${readonlyAttr} style="${bgColor}">
                </td>
                <td class="text-center">
                    ${isExisting ? `
                        <span class="badge bg-secondary" title="Existing note - cannot be deleted">Existing</span>
                    ` : `
                        <button type="button" class="btn btn-danger btn-sm"
                                onclick="deleteProgressNoteRow(${row.rowId})" title="Delete row">
                            <i class="bi bi-trash"></i>
                        </button>
                    `}
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
    const row = progressNoteRows.find(r => r.rowId === rowId);
    if (row && row.id) {
        // Cannot delete existing notes from here
        showNotification('Cannot delete existing progress notes. They are read-only.', 'warning');
        return;
    }
    
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

    // Add null checks to prevent errors
    const searchInput = document.getElementById('progressNotePatientSearch');
    if (searchInput) searchInput.value = '';

    const alertEl = document.getElementById('selectedProgressNotePatientAlert');
    if (alertEl) alertEl.classList.add('d-none');

    const sendToInput = document.getElementById('progressNoteSendToPatient');
    if (sendToInput) sendToInput.value = '';

    // Add one empty row to start fresh
    if (typeof addProgressNoteRow === 'function') {
        addProgressNoteRow();
    }
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
            fetch(`/admin/post-procedural/search-patients?q=${encodeURIComponent(searchTerm)}`)
                .then(response => response.json())
                .then(data => {
                    console.log('Search response:', data);
                    if (data.success && data.data.length > 0) {
                        displayProgressNoteSearchResults(data.data);
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

            // Filter out existing notes (those with id) - only send new rows
            const newRows = progressNoteRows.filter(row => !row.id);
            
            // Validate that at least one new row has data
            const hasData = newRows.some(row =>
                row.progressNote || row.amountPaid || row.balance || row.conforme
            );

            if (!hasData) {
                showNotification('Please add at least one new progress note entry', 'warning');
                return;
            }

            // Prepare the data to send - only new rows (without id)
            const progressNotesData = {
                patient_id: selectedProgressNotePatient.id,
                notes: newRows,
                send_to_patient: true
            };

            // Show loading state
            const btn = this;
            const originalContent = btn.innerHTML;
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Sending...';

            // Send to backend
            fetch('/admin/post-procedural/store-progress-notes', {
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

// Utility: create and show a Bootstrap modal dynamically
function createAndShowModal(modalId, title, bodyHtml, footerHtml, dialogClass = '') {
    // Remove existing modal if present
    const existing = document.getElementById(modalId);
    if (existing) existing.remove();

    const wrapper = document.createElement('div');
    wrapper.innerHTML = `
        <div class="modal fade" id="${modalId}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog ${dialogClass}">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">${title}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
                            <i class="bi bi-x"></i>
                        </button>
                    </div>
                    <div class="modal-body" id="${modalId}-body">
                        ${bodyHtml || ''}
                    </div>
                    <div class="modal-footer" id="${modalId}-footer">
                        ${footerHtml || ''}
                    </div>
                </div>
            </div>
        </div>`;
    document.body.appendChild(wrapper.firstElementChild);
    const modalEl = document.getElementById(modalId);
    const modal = new bootstrap.Modal(modalEl);
    modal.show();
    return modal;
}

// Utility: make all inputs editable within container
function makeContainerEditable(container) {
    if (!container) return;
    container.querySelectorAll('input, select, textarea, button').forEach(el => {
        el.removeAttribute('readonly');
        el.removeAttribute('disabled');
    });
}

// Password verification state
let isPasswordVerified = false;
let passwordVerifiedRecordId = null;
let pendingAction = null; // 'edit_record', 'edit_history', 'edit_notes', 'delete'

// Show password verification modal
function showPasswordVerificationModal(recordId, action) {
    pendingAction = action;
    passwordVerifiedRecordId = null;
    isPasswordVerified = false;
    
    const actionText = action === 'delete' ? 'delete' : action === 'edit_record' ? 'edit the patient record' : action === 'edit_history' ? 'edit the patient history' : action === 'edit_notes' ? 'edit the progress notes' : 'edit this record';
    
    const modalHtml = `
        <div class="modal fade" id="passwordVerificationModal" tabindex="-1" aria-labelledby="passwordVerificationModalLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered">
                <div class="modal-content password-verification-modal">
                    <div class="modal-header password-modal-header-custom" style="display: flex !important; justify-content: space-between !important; align-items: center !important; width: 100% !important;">
                        <h5 class="modal-title fw-bold" id="passwordVerificationModalLabel" style="margin: 0 !important; flex: 1 !important;">
                            <i class="bi bi-shield-lock me-2"></i>Password Verification
                        </h5>
                        <button type="button" class="btn-close password-modal-close-btn" data-bs-dismiss="modal" aria-label="Close" style="background: transparent !important; background-color: transparent !important; background-image: none !important; border: none !important; opacity: 1 !important; position: relative !important; width: 32px !important; height: 32px !important; padding: 0.5rem !important; margin: 0 !important; margin-left: auto !important; order: 2 !important;">
                            <span style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 1.75rem; line-height: 1; color: #64748b; font-weight: 300;">×</span>
                        </button>
                    </div>
                    <div class="modal-body password-modal-body-custom">
                        <p class="password-instruction-text">Please enter your password to ${actionText}.</p>
                        <div class="password-input-wrapper-custom">
                            <input type="password" class="form-control password-input-field-custom" id="adminPasswordInput" placeholder="Enter your password" autocomplete="current-password">
                            <div id="adminPasswordError" class="password-error-message"></div>
                        </div>
                    </div>
                    <div class="modal-footer password-modal-footer-custom">
                        <button type="button" class="btn btn-cancel-password-custom" data-bs-dismiss="modal" style="background: #e2e8f0 !important; color: #64748b !important; border: 2px solid #cbd5e1 !important; padding: 0.625rem 1.25rem !important; border-radius: 8px !important; font-weight: 600 !important; transition: all 0.2s ease !important; outline: none !important;">Cancel</button>
                        <button type="button" class="btn btn-verify-password-custom" id="verifyAdminPasswordBtn" style="background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important; color: white !important; border: 2px solid #2563eb !important; padding: 0.625rem 1.5rem !important; border-radius: 8px !important; font-weight: 600 !important; transition: all 0.2s ease !important; box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3) !important; display: flex !important; align-items: center !important; outline: none !important;">
                            <i class="bi bi-check-circle me-2"></i>Verify
                        </button>
                    </div>
                </div>
            </div>
        </div>
    `;
    
    // Remove existing modal if any
    const existingModal = document.getElementById('passwordVerificationModal');
    if (existingModal) {
        existingModal.remove();
    }
    
    // Add modal to body
    document.body.insertAdjacentHTML('beforeend', modalHtml);
    
    // Show modal
    const modal = new bootstrap.Modal(document.getElementById('passwordVerificationModal'), {
        backdrop: true,
        keyboard: true,
        focus: true
    });
    modal.show();
    
    // Apply styles after modal is shown to ensure they override any conflicting CSS
    setTimeout(() => {
        const modalHeader = document.querySelector('#passwordVerificationModal .password-modal-header-custom');
        const modalTitle = document.querySelector('#passwordVerificationModal .password-modal-header-custom .modal-title');
        const closeBtn = document.querySelector('#passwordVerificationModal .password-modal-close-btn');
        const cancelBtn = document.querySelector('#passwordVerificationModal .btn-cancel-password-custom');
        const verifyBtn = document.querySelector('#passwordVerificationModal .btn-verify-password-custom');
        
        if (modalHeader) {
            modalHeader.style.setProperty('display', 'flex', 'important');
            modalHeader.style.setProperty('justify-content', 'space-between', 'important');
            modalHeader.style.setProperty('align-items', 'center', 'important');
            modalHeader.style.setProperty('width', '100%', 'important');
        }
        
        if (modalTitle) {
            modalTitle.style.setProperty('margin', '0', 'important');
            modalTitle.style.setProperty('flex', '1', 'important');
        }
        
        if (closeBtn) {
            closeBtn.style.setProperty('margin-left', 'auto', 'important');
            closeBtn.style.setProperty('order', '2', 'important');
            closeBtn.style.setProperty('background', 'transparent', 'important');
            closeBtn.style.setProperty('background-color', 'transparent', 'important');
            closeBtn.style.setProperty('background-image', 'none', 'important');
            closeBtn.style.setProperty('border', 'none', 'important');
            closeBtn.style.setProperty('opacity', '1', 'important');
            closeBtn.style.setProperty('position', 'relative', 'important');
            closeBtn.style.setProperty('width', '32px', 'important');
            closeBtn.style.setProperty('height', '32px', 'important');
            closeBtn.style.setProperty('padding', '0.5rem', 'important');
            closeBtn.style.setProperty('margin', '0', 'important');
        }
        
        if (cancelBtn) {
            cancelBtn.style.setProperty('border', '2px solid #cbd5e1', 'important');
            cancelBtn.style.setProperty('background', '#e2e8f0', 'important');
            cancelBtn.style.setProperty('color', '#64748b', 'important');
            cancelBtn.addEventListener('mouseenter', function() {
                this.style.setProperty('border', '2px solid #94a3b8', 'important');
                this.style.setProperty('background', '#cbd5e1', 'important');
            });
            cancelBtn.addEventListener('mouseleave', function() {
                this.style.setProperty('border', '2px solid #cbd5e1', 'important');
                this.style.setProperty('background', '#e2e8f0', 'important');
            });
        }
        
        if (verifyBtn) {
            verifyBtn.style.setProperty('border', '2px solid #2563eb', 'important');
            verifyBtn.addEventListener('mouseenter', function() {
                this.style.setProperty('border', '2px solid #1d4ed8', 'important');
            });
            verifyBtn.addEventListener('mouseleave', function() {
                this.style.setProperty('border', '2px solid #2563eb', 'important');
            });
        }
        
        // Dark mode adjustments
        const isDarkMode = document.documentElement.getAttribute('data-theme') === 'dark';
        if (isDarkMode) {
            if (closeBtn) {
                const closeSpan = closeBtn.querySelector('span');
                if (closeSpan) closeSpan.style.setProperty('color', '#cbd5e1', 'important');
                closeBtn.addEventListener('mouseenter', function() {
                    const span = this.querySelector('span');
                    if (span) span.style.setProperty('color', '#ffffff', 'important');
                });
                closeBtn.addEventListener('mouseleave', function() {
                    const span = this.querySelector('span');
                    if (span) span.style.setProperty('color', '#cbd5e1', 'important');
                });
            }
            if (cancelBtn) {
                cancelBtn.style.setProperty('background', 'rgba(51, 65, 85, 0.8)', 'important');
                cancelBtn.style.setProperty('color', '#e2e8f0', 'important');
                cancelBtn.style.setProperty('border', '2px solid rgba(148, 163, 184, 0.5)', 'important');
                cancelBtn.addEventListener('mouseenter', function() {
                    this.style.setProperty('border', '2px solid rgba(148, 163, 184, 0.7)', 'important');
                    this.style.setProperty('background', 'rgba(71, 85, 105, 0.9)', 'important');
                });
                cancelBtn.addEventListener('mouseleave', function() {
                    this.style.setProperty('border', '2px solid rgba(148, 163, 184, 0.5)', 'important');
                    this.style.setProperty('background', 'rgba(51, 65, 85, 0.8)', 'important');
                });
            }
            if (verifyBtn) {
                verifyBtn.style.setProperty('border', '2px solid #2563eb', 'important');
            }
        }
    }, 100);
    
    // Focus on password input
    setTimeout(() => {
        document.getElementById('adminPasswordInput').focus();
    }, 300);
    
    // Handle verify button click
    document.getElementById('verifyAdminPasswordBtn').addEventListener('click', function() {
        verifyAdminPassword(recordId, action, modal);
    });
    
    // Handle Enter key in password input
    document.getElementById('adminPasswordInput').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            e.preventDefault();
            verifyAdminPassword(recordId, action, modal);
        }
    });
    
    // Clear password when modal is closed
    document.getElementById('passwordVerificationModal').addEventListener('hidden.bs.modal', function() {
        const passwordInput = document.getElementById('adminPasswordInput');
        const passwordError = document.getElementById('adminPasswordError');
        if (passwordInput) passwordInput.value = '';
        if (passwordError) {
            passwordError.classList.remove('show');
            passwordError.textContent = '';
        }
        pendingAction = null;
    });
}

// Verify admin password
function verifyAdminPassword(recordId, action, modal) {
    const passwordInput = document.getElementById('adminPasswordInput');
    const passwordError = document.getElementById('adminPasswordError');
    const verifyBtn = document.getElementById('verifyAdminPasswordBtn');
    
    const password = passwordInput.value.trim();
    
    if (!password) {
        passwordError.textContent = 'Please enter your password.';
        passwordError.classList.add('show');
        return;
    }
    
    // Disable button during verification
    verifyBtn.disabled = true;
    verifyBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Verifying...';
    passwordError.classList.remove('show');
    
    fetch('{{ route("admin-post-procedural.verify-password") }}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}',
            'Accept': 'application/json'
        },
        body: JSON.stringify({ password: password })
    })
    .then(response => {
        if (!response.ok) {
            return response.json().then(err => {
                throw new Error(err.message || 'Network response was not ok');
            });
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            // Password verified successfully
            isPasswordVerified = true;
            passwordVerifiedRecordId = recordId;
            
            // Close password modal
            modal.hide();
            
            // Execute the pending action
            if (action === 'edit_record') {
                openEditRecordModalDirect(recordId);
            } else if (action === 'edit_history') {
                openEditHistoryModalDirect(recordId);
            } else if (action === 'edit_notes') {
                openEditNotesModalDirect(recordId);
            } else if (action === 'delete') {
                removeRecordDirect(recordId);
            }
            
            // Show success notification
            showNotification('Password verified successfully. Access granted.', 'success');
        } else {
            // Password incorrect
            passwordError.textContent = data.message || 'Incorrect password. Please try again.';
            passwordError.classList.add('show');
            passwordInput.focus();
        }
    })
    .catch(error => {
        console.error('Error verifying password:', error);
        passwordError.textContent = error.message || 'An error occurred. Please try again.';
        passwordError.classList.add('show');
        passwordInput.focus();
    })
    .finally(() => {
        // Re-enable button
        verifyBtn.disabled = false;
        verifyBtn.innerHTML = '<i class="bi bi-check-circle me-2"></i>Verify';
    });
}

// Open separate edit modal: Patient Record
function openEditRecordModal(recordId) {
    if (!recordId || recordId === 'N/A') return;
    
    // Check if password is already verified for this record
    if (isPasswordVerified && passwordVerifiedRecordId === recordId) {
        openEditRecordModalDirect(recordId);
        return;
    }
    
    // Show password verification first
    showPasswordVerificationModal(recordId, 'edit_record');
}

function openEditRecordModalDirect(recordId) {
    if (!recordId || recordId === 'N/A') return;
    window.currentEditingRecordId = recordId;

    const loading = '<div class="text-center p-4"><div class="spinner-border text-primary" role="status"></div><p class="mt-2 text-muted">Loading Patient Record...</p></div>';
    const modal = createAndShowModal('editRecordModal', 'Edit Patient Information Record', loading, `
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary" id="save-record-btn">Save Changes</button>
    `, 'modal-xl');

    fetch(`/admin/post-procedural/patient-record/${recordId}`)
        .then(r => r.json())
        .then(res => {
            const container = document.getElementById('editRecordModal-body');
            if (res && res.success && res.data) {
                const record = res.data;
                if (typeof renderPatientInfoForm === 'function') {
                    container.innerHTML = renderPatientInfoForm(record);
                    // Ensure all fields, including notes, are populated
                    try { if (typeof populateFormWithData === 'function') populateFormWithData(record); } catch(e) { /* no-op */ }
                } else {
                    container.innerHTML = '<div class="alert alert-info">Form renderer missing. Please fill from main tab.</div>';
                }
                makeContainerEditable(container);

                const saveBtn = document.getElementById('save-record-btn');
                if (saveBtn && typeof savePatientRecordForm === 'function') {
                    saveBtn.onclick = async function() {
                        await savePatientRecordForm(() => {
                            if (document.activeElement) document.activeElement.blur();
                            modal.hide();
                            loadPatientRecords();
                        });
                    };
                }
            } else {
                container.innerHTML = '<div class="alert alert-danger">Unable to load patient record.</div>';
            }
        })
        .catch(() => {
            const container = document.getElementById('editRecordModal-body');
            if (container) container.innerHTML = '<div class="alert alert-danger">Error loading patient record.</div>';
        });
}

// Open separate edit modal: Patient History
function openEditHistoryModal(recordId) {
    if (!recordId || recordId === 'N/A') return;
    
    // Check if password is already verified for this record
    if (isPasswordVerified && passwordVerifiedRecordId === recordId) {
        openEditHistoryModalDirect(recordId);
        return;
    }
    
    // Show password verification first
    showPasswordVerificationModal(recordId, 'edit_history');
}

function openEditHistoryModalDirect(recordId) {
    if (!recordId || recordId === 'N/A') return;
    window.currentEditingRecordId = recordId;

    const loading = '<div class="text-center p-4"><div class="spinner-border text-info" role="status"></div><p class="mt-2 text-muted">Loading Patient History...</p></div>';
    const modal = createAndShowModal('editHistoryModal', 'Edit Patient History', loading, `
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        <button type="button" class="btn btn-info text-white" id="save-history-btn">Save Changes</button>
    `, 'modal-xl');

    fetch(`/admin/post-procedural/patient-history/${recordId}`)
        .then(r => r.json())
        .then(res => {
            const container = document.getElementById('editHistoryModal-body');
            const histories = (res && res.data) ? (Array.isArray(res.data) ? res.data : [res.data]) : [];

            // Remove the save button from modal footer since we'll use individual save buttons for each history record
            const saveBtn = document.getElementById('save-history-btn');
            if (saveBtn) {
                saveBtn.remove();
            }

            // Display all history records using renderPatientHistoryEditList
            if (typeof renderPatientHistoryEditList === 'function') {
                container.innerHTML = renderPatientHistoryEditList(histories, recordId);
                        } else {
                // Fallback: display history records in a simple list format
                if (histories.length > 0) {
                    let html = '<div class="p-3"><h6 class="mb-3"><i class="bi bi-clock-history me-2"></i>Patient History Records</h6>';
                    histories.forEach((h, index) => {
                        html += `
                            <div class="card mb-3" style="border-left: 4px solid #0d6efd;">
                                <div class="card-header bg-light">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <h6 class="mb-0" style="color: #0a4275;">
                                            <i class="bi bi-calendar-event me-2"></i>Visit #${index + 1}
                                        </h6>
                                        <button type="button" class="btn btn-sm btn-outline-danger" onclick="deletePatientHistory(${h.id})">
                                            <i class="bi bi-trash"></i> Delete
                                        </button>
                                    </div>
                                </div>
                                <div class="card-body">
                                    <form id="historyForm${h.id}">
                                        <div class="row g-3">
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Previous Dentist</label>
                                                <input type="text" class="form-control" name="previous_dentist" value="${h.previous_dentist || ''}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Last Dental Visit</label>
                                                <input type="date" class="form-control" name="last_dental_visit" value="${h.last_dental_visit || ''}">
                                            </div>
                                            <div class="col-md-4">
                                                <label class="form-label fw-semibold">Treatment Done</label>
                                                <input type="text" class="form-control" name="treatment_done" value="${h.treatment_done || ''}">
                                            </div>
                                        </div>
                                        <div class="mt-3 text-end">
                                            <button type="button" class="btn btn-success" onclick="savePatientHistory(${h.id}, ${recordId})">
                                                <i class="bi bi-save me-1"></i>Save Changes
                                            </button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        `;
                    });
                    html += '</div>';
                    container.innerHTML = html;
                        } else {
                    container.innerHTML = '<div class="alert alert-info p-4"><p class="mb-0">No patient history records found. Please add a new record using the Patient History Form tab.</p></div>';
                        }
            }
        })
        .catch(() => {
            const container = document.getElementById('editHistoryModal-body');
            if (container) container.innerHTML = '<div class="alert alert-danger">Error loading patient history.</div>';
        });
}

// Open separate edit modal: Progress Notes
function openEditNotesModal(recordId) {
    if (!recordId || recordId === 'N/A') return;
    
    // Check if password is already verified for this record
    if (isPasswordVerified && passwordVerifiedRecordId === recordId) {
        openEditNotesModalDirect(recordId);
        return;
    }
    
    // Show password verification first
    showPasswordVerificationModal(recordId, 'edit_notes');
}

function openEditNotesModalDirect(recordId) {
    if (!recordId || recordId === 'N/A') return;
    window.currentEditingRecordId = recordId;

    const formHtml = `
        <style>
            #edit-notes-form .form-label {
                font-weight: 600;
                color: #2c3e50;
                margin-bottom: 0.5rem;
                font-size: 0.95rem;
            }
            #edit-notes-form .form-control {
                border: 2px solid #e0e6ed;
                border-radius: 8px;
                padding: 0.75rem;
                transition: all 0.3s ease;
            }
            #edit-notes-form .form-control:focus {
                border-color: #4a90e2;
                box-shadow: 0 0 0 0.2rem rgba(74, 144, 226, 0.15);
            }
            .progress-notes-section {
                background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
                border-radius: 12px;
                padding: 1.5rem;
                margin-bottom: 1.5rem;
                border: 1px solid #dee2e6;
            }
            .section-header {
                display: flex;
                align-items: center;
                margin-bottom: 1rem;
                padding-bottom: 0.75rem;
                border-bottom: 2px solid #4a90e2;
            }
            .section-header i {
                font-size: 1.25rem;
                color: #4a90e2;
                margin-right: 0.5rem;
            }
            .section-header h6 {
                margin: 0;
                color: #2c3e50;
                font-weight: 700;
                font-size: 1.1rem;
            }
            .date-badge {
                display: inline-flex;
                align-items: center;
                background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
                color: white;
                padding: 0.5rem 1rem;
                border-radius: 20px;
                font-weight: 600;
                margin-bottom: 1rem;
            }
            .date-badge i {
                margin-right: 0.5rem;
            }
        </style>
        <form id="edit-notes-form">
            <!-- Date Section -->
            <div class="progress-notes-section">
                <div class="section-header">
                    <i class="bi bi-calendar-event"></i>
                    <h6>Date Information</h6>
                </div>
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label">
                            <i class="bi bi-calendar3 me-2"></i>Note Date
                        </label>
                        <input type="date" class="form-control" name="note_date" required>
                    </div>
                </div>
            </div>

            <!-- Progress Description Section -->
            <div class="progress-notes-section">
                <div class="section-header">
                    <i class="bi bi-journal-text"></i>
                    <h6>Progress Description</h6>
                </div>
                <div class="row g-3">
                    <div class="col-md-12">
                        <label class="form-label">
                            <i class="bi bi-file-text me-2"></i>Description
                        </label>
                        <textarea class="form-control" name="progress_description" rows="4"
                                  placeholder="Enter detailed progress description..." required></textarea>
                        <small class="text-muted">Provide comprehensive details about the patient's progress</small>
                    </div>
                </div>
            </div>

            <!-- Payment Information Section -->
            <div class="progress-notes-section">
                <div class="section-header">
                    <i class="bi bi-cash-coin"></i>
                    <h6>Payment Information</h6>
                </div>
                <div class="row g-3">
                    <div class="col-md-4">
                        <label class="form-label">
                            <i class="bi bi-currency-dollar me-2"></i>Amount Paid
                        </label>
                        <input type="number" class="form-control" name="amount_paid" step="0.01" min="0" placeholder="0.00">
                        <small class="text-muted">Enter the amount paid</small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">
                            <i class="bi bi-wallet2 me-2"></i>Balance
                        </label>
                        <input type="number" class="form-control" name="balance" step="0.01" min="0" placeholder="0.00">
                        <small class="text-muted">Enter the remaining balance</small>
                    </div>
                    <div class="col-md-4">
                        <label class="form-label">
                            <i class="bi bi-person-check me-2"></i>Conforme
                        </label>
                        <input type="text" class="form-control" name="conforme" placeholder="Conforme...">
                        <small class="text-muted">Enter conforme information</small>
                    </div>
                </div>
            </div>

        </form>`;

    const modal = createAndShowModal('editNotesModal',
        '<i class="bi bi-journal-text me-2"></i>Edit Progress Notes',
        formHtml, `
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
            <i class="bi bi-x-circle me-2"></i>Close
        </button>
        <button type="button" class="btn btn-primary" id="save-notes-btn"
                style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none;">
            <i class="bi bi-save me-2"></i>Save Note
        </button>
    `, 'modal-xl');

    // Preload latest note values and check if we have existing notes
    let existingNotes = [];
    let latestNoteId = null;

    // First, clear all form fields to prevent showing old data
    setTimeout(() => {
        const form = document.getElementById('edit-notes-form');
        if (form) {
            // Add null checks for each field
            if (form.note_date) form.note_date.value = '';
            if (form.progress_description) form.progress_description.value = '';
            if (form.amount_paid) form.amount_paid.value = '';
            if (form.balance) form.balance.value = '';
            if (form.conforme) form.conforme.value = '';
        }
    }, 100);

    // Fetch all progress notes and display them
    fetch(`/admin/post-procedural/progress-notes/${recordId}`)
        .then(r => {
            if (!r.ok) {
                throw new Error(`HTTP error! status: ${r.status}`);
            }
            return r.json();
        })
        .then(res => {
            console.log('Progress notes response:', res);
            existingNotes = (res && res.data) ? (Array.isArray(res.data) ? res.data : [res.data]) : [];

            // Replace the single form with a list of all notes
            const modalBody = document.querySelector('#editNotesModal .modal-body');
            if (modalBody && typeof renderProgressNotesEditList === 'function') {
                modalBody.innerHTML = renderProgressNotesEditList(existingNotes, recordId);
            }

            console.log('Rendered all progress notes:', existingNotes.length, 'notes');
        })
        .catch((error) => {
            console.error('Error fetching progress notes:', error);
            showNotification('Could not load existing progress notes.', 'info');
        });

    // Hide the save button in footer since each note has its own save button
    const saveBtn = document.getElementById('save-notes-btn');
    if (saveBtn) {
        saveBtn.style.display = 'none';
    }

    // Clear form when modal is closed to prevent data persistence
    const modalElement = document.getElementById('editNotesModal');
    if (modalElement) {
        modalElement.addEventListener('hidden.bs.modal', function () {
            const form = document.getElementById('edit-notes-form');
            if (form) {
                // Add null checks for each field
                if (form.note_date) form.note_date.value = '';
                if (form.progress_description) form.progress_description.value = '';
                if (form.treatment_response) form.treatment_response.value = '';
                if (form.next_steps) form.next_steps.value = '';
                if (form.other_notes) form.other_notes.value = '';
            }
            existingNotes = [];
            latestNoteId = null;
            console.log('Progress notes form cleared on modal close');
        }, { once: true }); // Use once: true so this doesn't pile up multiple listeners
    }
}

// Load patient records on page load
document.addEventListener('DOMContentLoaded', function() {
    loadPatientRecords();
});

</script>

<style>
/* Post-Procedural Table Styles */
.post-procedural-table {
    background: white;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    border: none !important;
}

.post-procedural-table thead {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.post-procedural-table thead th {
    color: #ffffff !important;
    font-weight: 600;
    font-size: 0.75rem;
    text-transform: uppercase;
    letter-spacing: 0.3px;
    padding: 0.65rem 0.5rem;
    border: none !important;
    white-space: nowrap;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%) !important;
}

.post-procedural-table tbody {
    background: white;
}

.post-procedural-table tbody tr {
    border-bottom: 1px solid #f0f0f0;
    transition: all 0.2s ease;
}

.post-procedural-table tbody tr:hover {
    background: #f8f9ff !important;
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
}

.post-procedural-table tbody td {
    padding: 0.65rem 0.5rem;
    vertical-align: middle;
    border: none !important;
    color: #495057;
    font-size: 0.875rem;
}

.post-procedural-table .avatar-sm {
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

/* Patient Name Column - Better Spacing and Organization */
.post-procedural-table tbody td:nth-child(2) {
    padding: 0.65rem 0.5rem !important;
    vertical-align: middle;
}

.post-procedural-table tbody td:nth-child(2) > div {
    gap: 0.6rem !important;
}

.post-procedural-table tbody td:nth-child(2) .fw-semibold {
    font-size: 0.875rem !important;
    line-height: 1.3 !important;
    word-wrap: break-word !important;
    overflow-wrap: break-word !important;
    margin-bottom: 0.2rem !important;
}

.post-procedural-table tbody td:nth-child(2) small {
    font-size: 0.8rem !important;
    line-height: 1.2 !important;
    display: block !important;
}

/* Table Container - Fit Screen */
.post-procedural-table {
    width: 100% !important;
    table-layout: fixed !important;
    max-width: 100% !important;
}

.table-responsive {
    overflow-x: visible !important;
    max-width: 100% !important;
}

/* Ensure columns don't overflow (except Patient Name and Treatment) */
.post-procedural-table th {
    overflow: hidden;
    text-overflow: ellipsis;
}

.post-procedural-table td {
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}

.post-procedural-table tbody td:nth-child(2),
.post-procedural-table tbody td:nth-child(3) {
    white-space: normal !important;
}

.post-procedural-table .badge {
    padding: 0.25rem 0.5rem;
    font-weight: 500;
    border-radius: 4px;
    display: inline-block;
    max-width: 100%;
    font-size: 0.75rem;
    line-height: 1.2;
}

.post-procedural-table .badge.bg-info {
    background: linear-gradient(135deg, #17a2b8 0%, #138496 100%) !important;
    color: white;
}

.post-procedural-table .no-data-badge {
    background: #f8f9fa !important;
    color: #6c757d !important;
    border: 1px solid #dee2e6 !important;
    padding: 0.3rem 0.5rem;
    font-size: 0.7rem;
}

.post-procedural-table .edit-action-btn {
    border-radius: 4px;
    font-weight: 500;
    padding: 0.35rem 0.5rem;
    transition: all 0.2s ease;
    border-width: 1.5px;
    font-size: 0.8rem;
    width: 36px;
    height: 36px;
    display: inline-flex;
    align-items: center;
    justify-content: center;
    flex-shrink: 0;
}

.post-procedural-table .edit-action-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.15);
}

.post-procedural-table .edit-action-btn.btn-outline-primary:hover {
    background: linear-gradient(135deg, #0d6efd 0%, #0a58ca 100%);
    border-color: #0d6efd;
    color: white;
}

.post-procedural-table .edit-action-btn.btn-outline-info:hover {
    background: linear-gradient(135deg, #0dcaf0 0%, #0aa2c0 100%);
    border-color: #0dcaf0;
    color: white;
}

.post-procedural-table .edit-action-btn.btn-outline-secondary:hover {
    background: linear-gradient(135deg, #6c757d 0%, #5c636a 100%);
    border-color: #6c757d;
    color: white;
}

.post-procedural-table .edit-action-btn.btn-outline-danger:hover {
    background: linear-gradient(135deg, #dc3545 0%, #c82333 100%);
    border-color: #dc3545;
    color: white;
}

/* Responsive Design */
@media (max-width: 1200px) {
    .post-procedural-table thead th,
    .post-procedural-table tbody td {
        padding: 0.75rem 0.5rem;
        font-size: 0.85rem;
    }

    .post-procedural-table .edit-action-btn {
        padding: 0.35rem 0.65rem;
        font-size: 0.8rem;
    }
}

@media (max-width: 768px) {
    .post-procedural-table {
        font-size: 0.8rem;
    }

    .post-procedural-table thead th {
        font-size: 0.75rem;
        padding: 0.65rem 0.4rem;
    }

    .post-procedural-table tbody td {
        padding: 0.65rem 0.4rem;
    }

    .post-procedural-table .avatar-sm {
        width: 28px !important;
        height: 28px !important;
        font-size: 12px !important;
    }

    .post-procedural-table .badge {
        padding: 0.35rem 0.5rem;
        font-size: 0.75rem;
    }

    .post-procedural-table .edit-action-btn {
        padding: 0.3rem 0.5rem;
        font-size: 0.75rem;
    }

.post-procedural-table .edit-action-btn i {
    margin: 0 !important;
    font-size: 0.9rem;
}

/* Remove table-responsive overflow */
.table-responsive {
    overflow-x: visible !important;
    max-width: 100%;
}

/* Compact avatar size */
.post-procedural-table .avatar-sm {
    width: 36px !important;
    height: 36px !important;
    font-size: 14px !important;
}

/* Dark Mode Support */
[data-theme="dark"] .post-procedural-table {
    background: var(--dm-card-bg, #1e293b);
    box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
}

[data-theme="dark"] .post-procedural-table thead {
    background: linear-gradient(135deg, #4c63d2 0%, #5a3a8a 100%) !important;
}

[data-theme="dark"] .post-procedural-table thead th {
    color: #ffffff !important;
    background: linear-gradient(135deg, #4c63d2 0%, #5a3a8a 100%) !important;
    text-shadow: 0 1px 2px rgba(0, 0, 0, 0.3);
}

[data-theme="dark"] .post-procedural-table tbody {
    background: var(--dm-card-bg, #1e293b);
}

[data-theme="dark"] .post-procedural-table tbody tr {
    border-bottom-color: var(--dm-border-color, #334155);
}

[data-theme="dark"] .post-procedural-table tbody tr:hover {
    background: var(--dm-bg-secondary, #334155) !important;
}

[data-theme="dark"] .post-procedural-table tbody td {
    color: var(--dm-text-primary, #e5e7eb);
}

[data-theme="dark"] .post-procedural-table .badge.bg-info {
    background: linear-gradient(135deg, #0dcaf0 0%, #0aa2c0 100%) !important;
}

[data-theme="dark"] .post-procedural-table .no-data-badge {
    background: var(--dm-input-bg, #0f172a) !important;
    color: var(--dm-text-muted, #94a3b8) !important;
    border-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] .post-procedural-table .fw-semibold {
    color: var(--dm-text-primary, #e5e7eb);
}

[data-theme="dark"] .post-procedural-table .text-muted {
    color: var(--dm-text-muted, #94a3b8) !important;
}

/* Dark Mode - For Minors Section */
[data-theme="dark"] #minorFormContainer {
    background: var(--dm-card-bg, #1e293b) !important;
    border-color: var(--dm-border-color, #334155) !important;
}

[data-theme="dark"] #minorFormContainer label {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] #minorFormContainer input.form-control {
    background: var(--dm-input-bg, #0f172a) !important;
    border-color: var(--dm-border-color, #334155) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] #minorFormContainer input.form-control:focus {
    background: var(--dm-input-bg, #0f172a) !important;
    border-color: var(--primary-blue, #2563eb) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] #minorFormContainer input.form-control::placeholder {
    color: var(--dm-text-muted, #94a3b8) !important;
}

/* Dark Mode - History Record Cards */
[data-theme="dark"] .card[style*="border: 2px solid #0d6efd"] {
    border-color: var(--primary-blue, #2563eb) !important;
    background: var(--dm-card-bg, #1e293b) !important;
}

[data-theme="dark"] .card-header[style*="background: linear-gradient(135deg, #e7f1ff"] {
    background: linear-gradient(135deg, var(--dm-bg-tertiary, #334155) 0%, var(--dm-card-bg, #1e293b) 100%) !important;
}

[data-theme="dark"] .card-header[style*="background: linear-gradient(135deg, #e7f1ff"] strong {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .card-header[style*="background: linear-gradient(135deg, #e7f1ff"] .btn-primary,
[data-theme="dark"] .card-header[style*="background: linear-gradient(135deg, #e7f1ff"] .btn-success,
[data-theme="dark"] .card-header[style*="background: linear-gradient(135deg, #e7f1ff"] .btn-danger {
    color: white !important;
}

[data-theme="dark"] .card-body[style*="background: #f8f9fa"] {
    background: var(--dm-card-bg, #1e293b) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .card-body[style*="background: #f8f9fa"] h6,
[data-theme="dark"] .card-body[style*="background: #f8f9fa"] strong,
[data-theme="dark"] .card-body[style*="background: #f8f9fa"] label,
[data-theme="dark"] .card-body[style*="background: #f8f9fa"] .form-label {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .card-body[style*="background: #f8f9fa"] input,
[data-theme="dark"] .card-body[style*="background: #f8f9fa"] textarea,
[data-theme="dark"] .card-body[style*="background: #f8f9fa"] select {
    background: var(--dm-input-bg, #0f172a) !important;
    border-color: var(--dm-border-color, #334155) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .card-body[style*="background: #f8f9fa"] input:focus,
[data-theme="dark"] .card-body[style*="background: #f8f9fa"] textarea:focus,
[data-theme="dark"] .card-body[style*="background: #f8f9fa"] select:focus {
    background: var(--dm-input-bg, #0f172a) !important;
    border-color: var(--primary-blue, #2563eb) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

/* Dark Mode - History Alert */
[data-theme="dark"] .alert-success[style*="background: linear-gradient(135deg, #d1e7dd"] {
    background: linear-gradient(135deg, rgba(34, 197, 94, 0.15) 0%, rgba(22, 163, 74, 0.1) 100%) !important;
    border-color: rgba(34, 197, 94, 0.3) !important;
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .alert-success[style*="background: linear-gradient(135deg, #d1e7dd"] strong {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

[data-theme="dark"] .alert-success[style*="background: linear-gradient(135deg, #d1e7dd"] i {
    color: #22c55e !important;
}

/* Dark Mode - Existing History Records Heading */
[data-theme="dark"] h6[style*="color: #0a4275"] {
    color: var(--dm-text-primary, #f1f5f9) !important;
}

/* Password Verification Modal - Base Styles */
.password-verification-modal .modal-content {
    border-radius: 16px;
    border: none;
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.3);
    overflow: hidden;
    max-width: 100%;
}

.password-verification-modal .modal-dialog {
    max-width: 450px;
    margin: 1rem auto;
}

/* Responsive Design */
@media (max-width: 576px) {
    .password-verification-modal .modal-dialog {
        max-width: calc(100% - 2rem);
        margin: 1rem;
    }
    
    .password-modal-header-custom {
        padding: 1.25rem 1rem 0.75rem 1rem;
    }
    
    .password-modal-header-custom .modal-title {
        font-size: 1.25rem;
    }
    
    .password-modal-body-custom {
        padding: 1.25rem 1rem;
    }
    
    .password-modal-footer-custom {
        padding: 1rem;
        flex-direction: column-reverse;
    }
    
    .btn-cancel-password-custom,
    .btn-verify-password-custom {
        width: 100%;
        justify-content: center;
    }
}

.password-modal-header-custom {
    background: white;
    border-bottom: 1px solid #e2e8f0;
    padding: 1.5rem 1.5rem 1rem 1.5rem;
    position: relative;
}

.password-modal-header-custom .modal-title {
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
    margin: 0;
    display: flex;
    align-items: center;
}

.password-modal-header-custom .modal-title i {
    color: #1e293b;
    font-size: 1.5rem;
}

.password-modal-header-custom .btn-close.password-modal-close-btn,
.password-modal-close-btn.btn-close {
    opacity: 1 !important;
    background: transparent !important;
    background-color: transparent !important;
    background-image: none !important;
    background-size: 0 !important;
    border: none !important;
    border-radius: 0 !important;
    padding: 0.5rem !important;
    width: auto !important;
    height: auto !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    transition: all 0.2s ease !important;
    position: relative !important;
    box-shadow: none !important;
    margin: 0 !important;
}

.password-modal-header-custom .btn-close.password-modal-close-btn::before,
.password-modal-close-btn.btn-close::before {
    content: '' !important;
    display: none !important;
}

.password-modal-header-custom .btn-close.password-modal-close-btn::after,
.password-modal-close-btn.btn-close::after {
    content: '×' !important;
    display: flex !important;
    font-size: 1.75rem !important;
    line-height: 1 !important;
    color: #64748b !important;
    font-weight: 300 !important;
    position: absolute !important;
    top: 50% !important;
    left: 50% !important;
    transform: translate(-50%, -50%) !important;
    width: 24px !important;
    height: 24px !important;
    align-items: center !important;
    justify-content: center !important;
}

.password-modal-header-custom .btn-close.password-modal-close-btn:hover,
.password-modal-close-btn.btn-close:hover {
    background: transparent !important;
    background-color: transparent !important;
    border: none !important;
    opacity: 1 !important;
}

.password-modal-header-custom .btn-close.password-modal-close-btn:hover::after,
.password-modal-close-btn.btn-close:hover::after {
    color: #1e293b !important;
}

.password-modal-body-custom {
    padding: 1.5rem;
    background: white;
}

.password-instruction-text {
    color: #64748b;
    font-size: 1rem;
    margin-bottom: 1.5rem;
    line-height: 1.6;
}

.password-input-wrapper-custom {
    position: relative;
    margin-bottom: 0;
}

.password-input-field-custom {
    width: 100%;
    padding: 0.875rem 1rem;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    font-size: 1rem;
    transition: all 0.3s ease;
    background: white;
    color: #1e293b;
}

.password-input-field-custom:focus {
    outline: none;
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
    background: white;
}

.password-input-field-custom::placeholder {
    color: #94a3b8;
}

.password-error-message {
    color: #ef4444;
    font-size: 0.875rem;
    margin-top: 0.5rem;
    display: none;
    padding: 0.5rem 0.75rem;
    background: rgba(239, 68, 68, 0.05);
    border-radius: 6px;
    border: 1px solid rgba(239, 68, 68, 0.2);
}

.password-error-message.show {
    display: block;
}

.password-modal-footer-custom {
    background: white;
    border-top: 1px solid #e2e8f0;
    padding: 1rem 1.5rem 1.5rem 1.5rem;
    display: flex;
    gap: 0.75rem;
    justify-content: flex-end;
}

.btn.btn-cancel-password-custom,
.password-modal-footer-custom .btn-cancel-password-custom {
    background: #e2e8f0 !important;
    color: #64748b !important;
    border: 2px solid #cbd5e1 !important;
    padding: 0.625rem 1.25rem !important;
    border-radius: 8px !important;
    font-weight: 600 !important;
    transition: all 0.2s ease !important;
    outline: none !important;
}

.btn.btn-cancel-password-custom:hover,
.password-modal-footer-custom .btn-cancel-password-custom:hover {
    background: #cbd5e1 !important;
    color: #475569 !important;
    border-color: #94a3b8 !important;
    border-width: 2px !important;
    transform: translateY(-1px) !important;
}

.btn.btn-verify-password-custom,
.password-modal-footer-custom .btn-verify-password-custom {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
    color: white !important;
    border: 2px solid #2563eb !important;
    padding: 0.625rem 1.5rem !important;
    border-radius: 8px !important;
    font-weight: 600 !important;
    transition: all 0.2s ease !important;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3) !important;
    display: flex !important;
    align-items: center !important;
    outline: none !important;
}

.btn.btn-verify-password-custom:hover,
.password-modal-footer-custom .btn-verify-password-custom:hover {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%) !important;
    color: white !important;
    border: 2px solid #1d4ed8 !important;
    transform: translateY(-1px) !important;
    box-shadow: 0 6px 16px rgba(59, 130, 246, 0.4) !important;
}

.btn-verify-password-custom:active {
    transform: translateY(0);
}

.btn-verify-password-custom:disabled {
    opacity: 0.7;
    cursor: not-allowed;
    transform: none;
}

/* Password Verification Modal - Dark Mode */
[data-theme="dark"] .password-verification-modal .modal-content {
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    border: 1px solid rgba(255, 255, 255, 0.1);
    box-shadow: 0 20px 60px rgba(0, 0, 0, 0.7);
}

[data-theme="dark"] .password-modal-header-custom {
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    border-bottom-color: rgba(255, 255, 255, 0.1);
}

[data-theme="dark"] .password-modal-header-custom .modal-title {
    color: #ffffff;
}

[data-theme="dark"] .password-modal-header-custom .modal-title i {
    color: #ffffff;
}

[data-theme="dark"] .password-modal-header-custom .btn-close.password-modal-close-btn,
[data-theme="dark"] .password-modal-close-btn.btn-close {
    background: transparent !important;
    background-color: transparent !important;
    border: none !important;
    background-image: none !important;
}

[data-theme="dark"] .password-modal-header-custom .btn-close.password-modal-close-btn::after,
[data-theme="dark"] .password-modal-close-btn.btn-close::after {
    color: #cbd5e1 !important;
}

[data-theme="dark"] .password-modal-header-custom .btn-close.password-modal-close-btn:hover,
[data-theme="dark"] .password-modal-close-btn.btn-close:hover {
    background: transparent !important;
    background-color: transparent !important;
    border: none !important;
    background-image: none !important;
}

[data-theme="dark"] .password-modal-header-custom .btn-close.password-modal-close-btn:hover::after,
[data-theme="dark"] .password-modal-close-btn.btn-close:hover::after {
    color: #ffffff !important;
}

[data-theme="dark"] .password-modal-body-custom {
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
}

[data-theme="dark"] .password-instruction-text {
    color: #cbd5e1;
}

[data-theme="dark"] .password-input-field-custom {
    background: rgba(15, 23, 42, 0.8);
    border-color: rgba(59, 130, 246, 0.4);
    color: #ffffff;
}

[data-theme="dark"] .password-input-field-custom:focus {
    background: rgba(15, 23, 42, 0.95);
    border-color: #3b82f6;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.2);
}

[data-theme="dark"] .password-input-field-custom::placeholder {
    color: #94a3b8;
}

[data-theme="dark"] .password-error-message {
    color: #f87171;
    background: rgba(239, 68, 68, 0.1);
    border-color: rgba(239, 68, 68, 0.3);
}

[data-theme="dark"] .password-modal-footer-custom {
    background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
    border-top-color: rgba(255, 255, 255, 0.1);
}

[data-theme="dark"] .btn.btn-cancel-password-custom,
[data-theme="dark"] .password-modal-footer-custom .btn-cancel-password-custom {
    background: rgba(51, 65, 85, 0.8) !important;
    color: #e2e8f0 !important;
    border: 2px solid rgba(148, 163, 184, 0.5) !important;
}

[data-theme="dark"] .btn.btn-cancel-password-custom:hover,
[data-theme="dark"] .password-modal-footer-custom .btn-cancel-password-custom:hover {
    background: rgba(71, 85, 105, 0.9) !important;
    color: #ffffff !important;
    border: 2px solid rgba(148, 163, 184, 0.7) !important;
}

[data-theme="dark"] .btn.btn-verify-password-custom,
[data-theme="dark"] .password-modal-footer-custom .btn-verify-password-custom {
    background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
    border: 2px solid #2563eb !important;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.4) !important;
}

[data-theme="dark"] .btn.btn-verify-password-custom:hover,
[data-theme="dark"] .password-modal-footer-custom .btn-verify-password-custom:hover {
    background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%) !important;
    border: 2px solid #1d4ed8 !important;
    box-shadow: 0 6px 16px rgba(59, 130, 246, 0.5) !important;
}
</style>
@endsection
