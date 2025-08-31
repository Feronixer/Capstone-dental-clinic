@extends('layout.patient.app')
@section('content')


<div class="container">
    <div class="page-padding">
        <h1 class="page-title">PATIENT RECORDS</h1>
        <div class="main-content-area">
            <section class="forms-list-section">
                <table class="forms-table">
                    <thead>
                        <tr>
                            <th>FORM</th>
                            <th>DATE</th>
                            <th>ACTION</th>
                        </tr>
                    </thead>

                </table>
            </section>
            <section id="formPreviewSection" class="form-preview-section">
                <h3 id="formPreviewTitle" class="form-preview-title">Dental Medical Clearance Form</h3>
                <div id="formPreviewContent" class="form-preview-content">
                    <!-- Default preview content, can be replaced by JS -->
                    <h4>Patient information</h4>
                    <div class="field-group"><span class="field-label">Name:</span> <span class="field-value">Juan Dela Cruz</span></div>
                    <div class="field-group"><span class="field-label">Date of birth:</span> <span class="field-value">01/15/1990</span></div>
                    <div class="field-group"><span class="field-label">Gender:</span> <span class="field-value">Male</span></div>
                    <div class="field-group"><span class="field-label">Contact number:</span> <span class="field-value">09171234567</span></div>
                    <div class="field-group"><span class="field-label">Address:</span> <span class="field-value">123 Main St, Quezon City</span></div>
                    <h4>Dental provider information</h4>
                        <div class="field-group"><span class="field-label">Dental provider name:</span> <span class="field-value">Dr. J. Valera</span></div>
                        <div class="field-group"><span class="field-label">License number:</span> <span class="field-value">DMD-12345</span></div>
                        <div class="field-group"><span class="field-label">Dental office name:</span> <span class="field-value">JValera Dental Clinic</span></div>
                        <div class="field-group"><span class="field-label">Office number:</span> <span class="field-value">+63 15 622 9695</span></div>
                        <div class="field-group"><span class="field-label">Office address:</span> <span class="field-value">0190 Policapio St. Gen T. Deleon Valenzuela City</span></div>
                        <h4>Medical provider information</h4>
                        <div class="field-group"><span class="field-label">Referring physician name:</span> <span class="field-value">Dr. A. Santos</span></div>
                        <div class="field-group"><span class="field-label">License number:</span> <span class="field-value">MD-67890</span></div>
                        <div class="field-group"><span class="field-label">Office name:</span> <span class="field-value">Metro Health Clinic</span></div>
                        <div class="field-group"><span class="field-label">Office phone number:</span> <span class="field-value">02-888-7777</span></div>
                        <div class="field-group"><span class="field-label">Office address:</span> <span class="field-value">456 Health Ave, Manila</span></div>
                        <h4>Reason for medical clearance</h4>
                        <div class="field-group"><span class="field-label">Procedure planned:</span> <span class="field-value">Wisdom Tooth Extraction</span></div>
                        <div class="field-group"><span class="field-label">Planned date of procedure:</span> <span class="field-value">05/20/2025</span></div>
                        <div class="mt-2 mb-1 font-medium">Reason for medical clearance:</div>
                        <div class="checkbox-group">
                            <label><input type="checkbox" checked disabled> Surgical procedure</label>
                            <label><input type="checkbox" disabled> Oral infection</label>
                            <label><input type="checkbox" checked disabled> Presence of coronary artery disease</label>
                            <label><input type="checkbox" disabled> Chronic medical condition</label>
                            <label><input type="checkbox" disabled> Periodontal disease</label>
                            <label><input type="checkbox" disabled> Other: <span class="field-value inline-block min-w-[100px]"></span></label>
                        </div>
                </div>
            </section>
        </div>
    </div>
</div>

<div id="ratingModal" class="rating-modal-overlay">
    <div class="rating-modal-content">
        <button id="closeRatingModalBtn" class="rating-close-btn">&times;</button>
        <h2 class="rating-title">Rate our Service</h2>
        <div id="starsRatingContainer" class="stars-container">
            <span class="star" data-value="1"><i class="fas fa-star"></i><span class="star-number">1</span></span>
            <span class="star" data-value="2"><i class="fas fa-star"></i><span class="star-number">2</span></span>
            <span class="star" data-value="3"><i class="fas fa-star"></i><span class="star-number">3</span></span>
            <span class="star" data-value="4"><i class="fas fa-star"></i><span class="star-number">4</span></span>
            <span class="star" data-value="5"><i class="fas fa-star"></i><span class="star-number">5</span></span>
        </div>
        <input type="hidden" id="selectedRating" name="rating" value="0">
        <button id="submitRatingBtn" class="submit-rating-btn" disabled>Submit Rating</button>
        <p id="ratingMessage" class="rating-message" style="display:none;"></p>
    </div>
</div>
<script src="{{ asset('js/record.js') }}"></script>
@endsection
