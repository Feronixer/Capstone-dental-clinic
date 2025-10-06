@extends('layout.admin.app')
@section('content')
<h1 class="mb-4">Patient History</h1>
<div class="d-flex flex-column flex-lg-row gap-4">
    <aside class="w-15 w-lg-25">
        <div class="sub-nav">
            <a href="{{ route('admin-post-procedural') }}" class="sub-nav-link ">Form List</a>
            <a href="{{ route('admin-patientrecord') }}" class="sub-nav-link">Patient Record</a>
            <a href="{{ route('admin-patienthistory') }}" class="sub-nav-link active">Patient History</a>
            <a href="{{ route('admin-progressnote') }}" class="sub-nav-link">Progress note</a>

        </div>
    </aside>
<section class="flex-grow-1">
        <div class="form-container">
            <h3 class="form-section-title">DENTAL HISTORY</h3>
            <form>
                <div class="form-field">
                    <label for="previousDentist">Previous Dentist</label>
                    <input type="text" id="previousDentist" name="previousDentist" class="form-input-line">
                </div>
                <div class="form-field">
                    <label for="lastDentalVisit">Last dental visit</label>
                    <input type="text" id="lastDentalVisit" name="lastDentalVisit" class="form-input-line" placeholder="MM/DD/YYYY">
                </div>
                <div class="form-field">
                    <label for="treatmentDone">Treatment done</label>
                    <input type="text" id="treatmentDone" name="treatmentDone" class="form-input-line">
                </div>

                <h3 class="form-section-title">MEDICAL HISTORY</h3>
                <div class="form-field">
                    <label for="physicianName">Name of Physician</label>
                    <input type="text" id="physicianName" name="physicianName" class="form-input-line">
                </div>
                <div class="form-field">
                    <label for="physicianSpecialty">Specialty</label>
                    <input type="text" id="physicianSpecialty" name="physicianSpecialty" class="form-input-line">
                </div>
                <div class="form-field">
                    <label for="physicianAddress">Office address</label>
                    <input type="text" id="physicianAddress" name="physicianAddress" class="form-input-line">
                </div>
                <div class="form-field">
                    <label for="physicianContact">Contact No.</label>
                    <input type="text" id="physicianContact" name="physicianContact" class="form-input-line">
                </div>

                @php
                $medical_questions_data = [
                    1 => ["text" => "Are you in good health?", "conditional_prompt" => null],
                    2 => ["text" => "Are you under any medical treatment now?", "conditional_prompt" => "If yes, what condition is being treated?"],
                    3 => ["text" => "Have you ever had any serious illness or surgery?", "conditional_prompt" => "If yes, what illness or surgery?"],
                    4 => ["text" => "Have you ever been hospitalized?", "conditional_prompt" => "If yes, when and why?"],
                    5 => ["text" => "Are you taking any prescription or non prescription drugs?", "conditional_prompt" => "If yes, what medications?"],
                    6 => ["text" => "Do you use any tobacco products?", "conditional_prompt" => null],
                    7 => ["text" => "Do you drink alcoholic beverages?", "conditional_prompt" => null],
                    8 => ["text" => "Do you take any recreational drugs?", "conditional_prompt" => null],
                ];
                @endphp

                @foreach($medical_questions_data as $key => $q)
                    <div class="question-item">
                        <span class="question-text">{{ $key }}. {{ $q['text'] }}</span>
                        <div class="question-options">
                            <label><input type="radio" name="q{{ $key }}" value="yes"> YES</label>
                            <label><input type="radio" name="q{{ $key }}" value="no"> NO</label>
                        </div>
                    </div>
                    @if($q['conditional_prompt'])
                        <div class="form-field conditional-input" style="display: none;" id="q{{ $key }}_details">
                            <label for="q{{ $key }}_input">{{ $q['conditional_prompt'] }}</label>
                            <input type="text" id="q{{ $key }}_input" name="q{{ $key }}_input" class="form-input-line">
                        </div>
                    @endif
                @endforeach

                <div class="question-item mt-4">
                    <span class="question-text">9. Are you allergic to the following?</span>
                    <div></div>
                </div>
                <div class="allergy-grid mt-2 mb-4">
                    <div class="allergy-item"><label><input type="checkbox" name="allergy_anesthesia"> Local Anesthesia (e.g., Lidocaine)</label></div>
                    <div class="allergy-item"><label><input type="checkbox" name="allergy_sulfa"> Sulfa drugs</label></div>
                    <div class="allergy-item"><label><input type="checkbox" name="allergy_antibiotics"> Antibiotics (e.g., Amoxicillin)</label></div>
                    <div class="allergy-item"><label><input type="checkbox" name="allergy_aspirin"> Aspirin</label></div>
                    <div class="allergy-item"><label><input type="checkbox" name="allergy_analgesics"> Analgesics (e.g., Mefenamic Acid)</label></div>
                    <div class="allergy-item"><label><input type="checkbox" name="allergy_latex"> Latex (e.g., Gloves)</label></div>
                </div>
                <div class="form-grid form-grid-cols-2 gap-x-4 mb-6">
                    <div class="form-field">
                        <label for="allergy_food">Food (Please specify: <input type="text" name="allergy_food_details" class="form-input-line inline w-auto flex-grow ml-1">)</label>
                    </div>
                    <div class="form-field">
                        <label for="allergy_others">Others (Please specify: <input type="text" name="allergy_others_details" class="form-input-line inline w-auto flex-grow ml-1">)</label>
                    </div>
                </div>

                <h4 class="mt-4 mb-2">For women:</h4>
                @php
                $women_questions_data = [
                    1 => ["text" => "Are you pregnant?"],
                    2 => ["text" => "Are you currently nursing?"],
                    3 => ["text" => "Are you currently taking birth control pills?"],
                ];
                @endphp
                @foreach($women_questions_data as $key => $q)
                    <div class="question-item">
                        <span class="question-text">{{ $key }}. {{ $q['text'] }}</span>
                        <div class="question-options">
                            <label><input type="radio" name="wq{{ $key }}" value="yes"> YES</label>
                            <label><input type="radio" name="wq{{ $key }}" value="no"> NO</label>
                        </div>
                    </div>
                @endforeach

                <div class="row mt-4">
                    <div class="col-md-6 mb-3">
                        <label for="otherNotes" class="form-label">Other Notes:</label>
                        <textarea id="otherNotes" name="otherNotes" class="notes-textarea"></textarea>
                    </div>
                    <div class="col-md-6 send-section">
                        <label for="sentToPatient" class="form-label">Sent to:</label>
                        <div class="send-input-wrapper">
                            <input type="text" id="sentToPatient" name="sentToPatient" class="send-input" placeholder="Patient Name*">
                            <button type="button" class="send-search-icon">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                        <button type="submit" class="btn-send">SEND</button>
                    </div>
                </div>
            </form>
        </div>
    </section>
</div>
@endsection
