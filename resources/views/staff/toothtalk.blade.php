@extends('layout.staff.app')
@section('content')
<div class="container-fluid" style="max-width: 1200px;">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="mb-0 fw-bold text-primary"><i class="bi bi-robot"></i> ToothTalk Chatbot Configuration</h2>
    </div>

    <!-- Settings View (Read-Only) -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-gear me-2"></i><b>Chatbot Settings</b></h5>
            <div></div>
        </div>
        <div class="card-body">
            <div class="row mb-3">
                <div class="col-md-6">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="enabled" {{ $setting->enabled ?? true ? 'checked' : '' }} disabled>
                        <label class="form-check-label fw-bold" for="enabled">Enable Chatbot on Homepage</label>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="welcome_message" class="form-label fw-bold">Welcome Message</label>
                <input type="text" id="welcome_message" value="{{ $setting->welcome_message ?: 'Hi! I\'m your ToothTalk Assistant. How can I help today?' }}" class="form-control" readonly>
            </div>

            <div class="mb-3">
                <label class="form-label fw-bold">Quick Intent Chips</label>
                <div id="intent-list">
                    @php $intents = $setting->quick_intents ?? []; @endphp
                    @forelse($intents as $idx => $intent)
                        <div class="row mb-2 align-items-center intent-row g-1">
                            <div class="col-12 col-sm-5 col-md-4">
                                <input type="text" value="{{ $intent['label'] ?? '' }}" class="form-control form-control-sm" readonly>
                            </div>
                            <div class="col-12 col-sm-7 col-md-8 intent-value-col">
                                <input type="text" value="{{ $intent['value'] ?? '' }}" class="form-control form-control-sm" readonly>
                            </div>
                        </div>
                    @empty
                        <p class="text-muted">No quick intent chips configured.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>

    <!-- FAQ Management (Read-Only) -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-question-circle"></i> <b>FAQ Management</b></h5>
        </div>
        <div class="card-body">
            <div class="table-responsive" id="faqTableContainer">
                <table class="table table-hover" id="faqTable">
                    <thead class="table-light">
                        <tr>
                            <th class="d-none d-md-table-cell" style="width: 5%;">#</th>
                            <th style="width: 30%; min-width: 150px;">Question</th>
                            <th style="width: 50%; min-width: 200px;">Answer</th>
                            <th class="text-center" style="width: 15%; min-width: 80px;">Status</th>
                        </tr>
                    </thead>
                    <tbody id="faqTableBody">
                        @forelse($faqs as $faq)
                            <tr data-faq-id="{{ $faq->id }}">
                                <td class="text-muted d-none d-md-table-cell">{{ $faq->order }}</td>
                                <td>
                                    <input type="text" value="{{ $faq->question }}" class="form-control form-control-sm" readonly>
                                </td>
                                <td>
                                    <textarea class="form-control form-control-sm faq-answer-textarea" rows="3" readonly>{{ $faq->answer }}</textarea>
                                </td>
                                <td>
                                    <div class="form-check form-switch d-flex justify-content-center">
                                        <input class="form-check-input" type="checkbox" {{ $faq->is_active ? 'checked' : '' }} disabled>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">No FAQs configured.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>

<style>
    .intent-row {
        margin-bottom: 0.5rem;
    }
    
    .faq-answer-textarea {
        resize: none;
    }
</style>

@endsection

