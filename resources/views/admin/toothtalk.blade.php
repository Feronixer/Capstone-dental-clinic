@extends('layout.admin.app')
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

    <!-- Settings Form -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-gear me-2"></i><b>Chatbot Settings</b></h5>
            <div></div>
        </div>
        <div class="card-body">
            <form method="POST" action="{{ route('admin-toothtalk.settings.save') }}">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" name="enabled" value="1" id="enabled" {{ old('enabled', $setting->enabled ?? true) ? 'checked' : '' }}>
                            <label class="form-check-label fw-bold" for="enabled">Enable Chatbot on Homepage</label>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="welcome_message" class="form-label fw-bold">Welcome Message</label>
                    <input type="text" name="welcome_message" id="welcome_message"
                           value="{{ old('welcome_message', $setting->welcome_message ?: 'Hi! I\'m your ToothTalk Assistant. How can I help today?') }}"
                           class="form-control" placeholder="Enter welcome message...">
                    @error('welcome_message')
                        <div class="text-danger small">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label class="form-label fw-bold">Quick Intent Chips</label>
                    <div id="intent-list">
                        @php $intents = old('quick_intents', $setting->quick_intents ?? []); @endphp
                        @forelse($intents as $idx => $intent)
                            <div class="row mb-2 align-items-center intent-row g-1">
                                <div class="col-12 col-sm-5 col-md-4">
                                    <input type="text" name="quick_intents[{{ $idx }}][label]" value="{{ $intent['label'] ?? '' }}"
                                           placeholder="Chip Label" class="form-control form-control-sm">
                                </div>
                                <div class="col-12 col-sm-7 col-md-8 intent-value-col">
                                    <div class="input-group input-group-sm">
                                        <input type="text" name="quick_intents[{{ $idx }}][value]" value="{{ $intent['value'] ?? '' }}"
                                               placeholder="Question to ask" class="form-control form-control-sm">
                                        <button type="button" class="btn btn-outline-danger delete-intent intent-delete-btn" title="Delete intent">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @empty
                            <div class="row mb-2 align-items-center intent-row g-1">
                                <div class="col-12 col-sm-5 col-md-4">
                                    <input type="text" name="quick_intents[0][label]" value="Clinic Hours"
                                           placeholder="Chip Label" class="form-control form-control-sm">
                                </div>
                                <div class="col-12 col-sm-7 col-md-8 intent-value-col">
                                    <div class="input-group input-group-sm">
                                        <input type="text" name="quick_intents[0][value]" value="What are your clinic hours?"
                                               placeholder="Question to ask" class="form-control form-control-sm">
                                        <button type="button" class="btn btn-outline-danger delete-intent intent-delete-btn" title="Delete intent">
                                            <i class="bi bi-x-lg"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endforelse
                    </div>
                    <button type="button" id="add-intent" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-plus"></i> Add Intent
                    </button>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-primary save-settings-btn" onclick="confirmSaveSettings()">
                        <i class="bi bi-save me-2"></i> Save Settings
                    </button>
                </div>
            </form>
            <form id="settingsForm" method="POST" action="{{ route('admin-toothtalk.settings.save') }}" style="display:none;">
                @csrf
            </form>
        </div>
    </div>

    <!-- FAQ Management -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-success text-white d-flex justify-content-between align-items-center">
            <h5 class="mb-0"><i class="bi bi-question-circle"></i> <b>FAQ Management</b><h5>
            <button type="button" class="btn btn-light btn-sm add-faq-header-btn" onclick="addNewFaqRow()">
                <span class="add-faq-icon">+</span> Add
            </button>
        </div>
        <div class="card-body">

            <form id="addFaqForm" method="POST" action="{{ route('admin-toothtalk.faq.store') }}" style="display:none;">
                @csrf
            </form>

            <!-- FAQ List -->
            @php
                $nextOrder = $faqs->count() > 0 ? $faqs->max('order') + 1 : 1;
            @endphp
            <div class="table-responsive" id="faqTableContainer">
                <table class="table table-hover" id="faqTable">
                        <thead class="table-light">
                            <tr>
                            <th class="d-none d-md-table-cell" style="width: 5%;">#</th>
                            <th style="width: 30%; min-width: 150px;">Question</th>
                            <th style="width: 40%; min-width: 200px;">Answer</th>
                            <th class="text-center" style="width: 10%; min-width: 80px;">Status</th>
                            <th class="text-center" style="width: 15%; min-width: 100px;">Actions</th>
                            </tr>
                        </thead>
                    <tbody id="faqTableBody">
                        @forelse($faqs as $faq)
                            <tr data-faq-id="{{ $faq->id }}">
                                <td class="text-muted d-none d-md-table-cell">{{ $faq->order }}</td>
                                        <td>
                                            <input type="text" name="question" value="{{ $faq->question }}" class="form-control form-control-sm" required>
                                        </td>
                                        <td>
                                    <textarea name="answer" class="form-control form-control-sm faq-answer-textarea" rows="3" required>{{ $faq->answer }}</textarea>
                                        </td>
                                        <td>
                                    <div class="form-check form-switch d-flex justify-content-center">
                                                <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ $faq->is_active ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td>
                                    <div class="d-flex justify-content-center gap-1">
                                        <button type="button" class="btn btn-sm faq-save-btn"
                                                        onclick="confirmUpdateFaq({{ $faq->id }}, '{{ addslashes($faq->question) }}')"
                                                        title="Save">
                                            <i class="bi bi-save"></i>
                                                </button>
                                        <button type="button" class="btn btn-sm faq-delete-btn"
                                                        onclick="confirmDeleteFaq({{ $faq->id }}, '{{ addslashes($faq->question) }}')"
                                                        title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                        @empty
                            <!-- Empty state - will be replaced when first FAQ is added -->
                        @endforelse
                    </tbody>
                </table>
            </div>

            <!-- Hidden forms for each FAQ (for update/delete) -->
            @foreach($faqs as $faq)
                                <form id="update-faq-{{ $faq->id }}" method="POST" action="{{ route('admin-toothtalk.faq.update', $faq->id) }}" style="display:none;">
                                    @csrf
                                    @method('PUT')
                                </form>
                                <form id="delete-faq-{{ $faq->id }}" method="POST" action="{{ route('admin-toothtalk.faq.delete', $faq->id) }}" style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            @endforeach
        </div>
    </div>

</div>

<!-- Save Settings Confirmation Modal -->
<div class="modal fade" id="saveSettingsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content save-modal-content">
            <div class="modal-body text-center p-4">
                <div class="save-icon-wrapper mb-3">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <h5 class="save-modal-title mb-2">Save Settings</h5>
                <p class="save-modal-message mb-4">Are you sure you want to save these chatbot settings?</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-save" id="confirmSaveSettingsBtn">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Update FAQ Confirmation Modal -->
<div class="modal fade" id="updateFaqModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content update-modal-content">
            <div class="modal-body text-center p-4">
                <div class="update-icon-wrapper mb-3">
                    <i class="bi bi-check-circle-fill"></i>
                </div>
                <h5 class="update-modal-title mb-2">Update FAQ</h5>
                <p class="update-modal-message mb-4" id="updateFaqMessage">Are you sure you want to save changes to this FAQ?</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-update" id="confirmUpdateFaqBtn">Save</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete FAQ Confirmation Modal -->
<div class="modal fade" id="deleteFaqModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content delete-modal-content">
            <div class="modal-body text-center p-4">
                <div class="delete-icon-wrapper mb-3">
                    <i class="bi bi-exclamation-triangle-fill"></i>
                </div>
                <h5 class="delete-modal-title mb-2">Delete FAQ</h5>
                <p class="delete-modal-message mb-4" id="deleteFaqMessage">Are you sure you want to delete this FAQ?</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-delete" id="confirmDeleteFaqBtn">Delete</button>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
    /* Delete Modal Styles - Enhanced with Red Design */
    .delete-modal-content {
        border: none;
        border-radius: 24px;
        box-shadow: 0 25px 80px rgba(239, 68, 68, 0.4),
                    0 10px 40px rgba(239, 68, 68, 0.3),
                    0 0 0 1px rgba(239, 68, 68, 0.1) inset !important;
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        animation: modalSlideIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: scale(0.9) translateY(-20px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    .delete-icon-wrapper {
        width: 110px;
        height: 110px;
        margin: 0 auto;
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 50%, #b91c1c 100%);
        border: 5px solid #dc2626;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 12px 35px rgba(239, 68, 68, 0.6),
                    0 6px 20px rgba(239, 68, 68, 0.5),
                    0 0 0 10px rgba(239, 68, 68, 0.2),
                    0 0 0 5px rgba(255, 255, 255, 0.3) inset,
                    0 0 60px rgba(239, 68, 68, 0.3) !important;
        animation: deletePulse 2s infinite;
        position: relative;
    }

    @keyframes deletePulse {
        0%, 100% {
            transform: scale(1);
            box-shadow: 0 12px 35px rgba(239, 68, 68, 0.6),
                        0 6px 20px rgba(239, 68, 68, 0.5),
                        0 0 0 10px rgba(239, 68, 68, 0.2),
                        0 0 0 5px rgba(255, 255, 255, 0.3) inset,
                        0 0 60px rgba(239, 68, 68, 0.3);
        }
        50% {
            transform: scale(1.08);
            box-shadow: 0 16px 45px rgba(239, 68, 68, 0.8),
                        0 8px 25px rgba(239, 68, 68, 0.7),
                        0 0 0 14px rgba(239, 68, 68, 0.3),
                        0 0 0 5px rgba(255, 255, 255, 0.4) inset,
                        0 0 80px rgba(239, 68, 68, 0.5);
        }
    }

    .delete-icon-wrapper {
        will-change: transform, box-shadow;
    }

    .delete-icon-wrapper i {
        font-size: 4rem;
        color: white;
        font-weight: 900;
        text-shadow: 0 3px 6px rgba(0, 0, 0, 0.4),
                     0 0 10px rgba(255, 255, 255, 0.3),
                     0 0 20px rgba(239, 68, 68, 0.4);
        filter: drop-shadow(0 2px 6px rgba(0, 0, 0, 0.3));
        z-index: 1;
        position: relative;
    }

    .delete-modal-title {
        font-size: 1.5rem !important;
        font-weight: 800 !important;
        color: #1e293b;
        margin-bottom: 0.75rem;
        letter-spacing: -0.5px;
    }

    .delete-modal-message {
        font-size: 1.0625rem;
        color: #64748b;
        margin-bottom: 2rem;
        line-height: 1.6;
        font-weight: 500;
    }

    .btn-cancel {
        background: #ffffff;
        color: #64748b;
        border: 2px solid #e2e8f0;
        padding: 0.625rem 1.75rem;
        font-size: 0.875rem;
        font-weight: 600;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .btn-cancel:hover {
        background: #f8fafc;
        color: #2563eb;
        border-color: #2563eb;
        transform: translateY(-2px);
    }

    .btn-delete {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
        color: white !important;
        border: none !important;
        padding: 0.75rem 2rem !important;
        font-size: 0.9375rem !important;
        font-weight: 700 !important;
        border-radius: 12px !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        box-shadow: 0 4px 15px rgba(239, 68, 68, 0.4),
                    0 2px 8px rgba(239, 68, 68, 0.3) !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-delete:hover {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%) !important;
        transform: translateY(-3px) scale(1.02) !important;
        box-shadow: 0 8px 25px rgba(239, 68, 68, 0.5),
                    0 4px 12px rgba(239, 68, 68, 0.4) !important;
    }

    .btn-delete:active {
        transform: translateY(-1px) scale(0.98) !important;
    }

    /* Save Modal Styles */
    .save-modal-content {
        border: none;
        border-radius: 24px;
        box-shadow: 0 25px 80px rgba(16, 185, 129, 0.4),
                    0 10px 40px rgba(16, 185, 129, 0.3),
                    0 0 0 1px rgba(16, 185, 129, 0.1) inset !important;
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        animation: modalSlideIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    @keyframes modalSlideIn {
        from {
            opacity: 0;
            transform: scale(0.9) translateY(-20px);
        }
        to {
            opacity: 1;
            transform: scale(1) translateY(0);
        }
    }

    .save-icon-wrapper {
        width: 100px;
        height: 100px;
        margin: 0 auto;
        background: linear-gradient(135deg, #10b981 0%, #059669 50%, #047857 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 12px 30px rgba(16, 185, 129, 0.5),
                    0 4px 15px rgba(16, 185, 129, 0.4),
                    0 0 0 8px rgba(16, 185, 129, 0.1),
                    0 0 0 4px rgba(255, 255, 255, 0.5) inset !important;
        position: relative;
        animation: iconPulse 2s infinite;
    }

    @keyframes iconPulse {
        0%, 100% {
            transform: scale(1);
            box-shadow: 0 12px 30px rgba(16, 185, 129, 0.5),
                        0 4px 15px rgba(16, 185, 129, 0.4),
                        0 0 0 8px rgba(16, 185, 129, 0.1),
                        0 0 0 4px rgba(255, 255, 255, 0.5) inset;
        }
        50% {
            transform: scale(1.05);
            box-shadow: 0 15px 40px rgba(16, 185, 129, 0.6),
                        0 6px 20px rgba(16, 185, 129, 0.5),
                        0 0 0 12px rgba(16, 185, 129, 0.15),
                        0 0 0 4px rgba(255, 255, 255, 0.6) inset;
        }
    }

    .save-icon-wrapper {
        will-change: transform, box-shadow;
    }

    .save-icon-wrapper i {
        font-size: 3.5rem;
        color: white;
        font-weight: 900;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2));
        text-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
        z-index: 1;
        position: relative;
    }

    .save-modal-title {
        font-size: 1.5rem !important;
        font-weight: 800 !important;
        color: #1e293b;
        margin-bottom: 0.75rem;
        letter-spacing: -0.5px;
    }

    .save-modal-message {
        font-size: 1.0625rem;
        color: #64748b;
        margin-bottom: 2rem;
        line-height: 1.6;
        font-weight: 500;
    }

    .btn-save {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
        color: white !important;
        border: none !important;
        padding: 0.75rem 2rem !important;
        font-size: 0.9375rem !important;
        font-weight: 700 !important;
        border-radius: 12px !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.4),
                    0 2px 8px rgba(16, 185, 129, 0.3) !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-save:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;
        transform: translateY(-3px) scale(1.02) !important;
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.5),
                    0 4px 12px rgba(16, 185, 129, 0.4) !important;
    }

    .btn-save:active {
        transform: translateY(-1px) scale(0.98) !important;
    }

    /* Update Modal Styles - Enhanced Blue Design */
    .update-modal-content {
        border: none;
        border-radius: 24px;
        box-shadow: 0 25px 80px rgba(59, 130, 246, 0.4),
                    0 10px 40px rgba(59, 130, 246, 0.3),
                    0 0 0 1px rgba(59, 130, 246, 0.1) inset !important;
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
        animation: modalSlideIn 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    }

    .update-icon-wrapper {
        width: 110px;
        height: 110px;
        margin: 0 auto;
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 50%, #1d4ed8 100%);
        border: 5px solid #2563eb;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 12px 35px rgba(59, 130, 246, 0.6),
                    0 6px 20px rgba(59, 130, 246, 0.5),
                    0 0 0 10px rgba(59, 130, 246, 0.2),
                    0 0 0 5px rgba(255, 255, 255, 0.3) inset,
                    0 0 60px rgba(59, 130, 246, 0.3) !important;
        animation: updatePulse 2s infinite;
        position: relative;
    }

    @keyframes updatePulse {
        0%, 100% {
            transform: scale(1);
            box-shadow: 0 12px 35px rgba(59, 130, 246, 0.6),
                        0 6px 20px rgba(59, 130, 246, 0.5),
                        0 0 0 10px rgba(59, 130, 246, 0.2),
                        0 0 0 5px rgba(255, 255, 255, 0.3) inset,
                        0 0 60px rgba(59, 130, 246, 0.3);
        }
        50% {
            transform: scale(1.08);
            box-shadow: 0 16px 45px rgba(59, 130, 246, 0.8),
                        0 8px 25px rgba(59, 130, 246, 0.7),
                        0 0 0 14px rgba(59, 130, 246, 0.3),
                        0 0 0 5px rgba(255, 255, 255, 0.4) inset,
                        0 0 80px rgba(59, 130, 246, 0.5);
        }
    }

    .update-icon-wrapper {
        will-change: transform, box-shadow;
    }

    .update-icon-wrapper i {
        font-size: 4rem;
        color: white;
        font-weight: 900;
        text-shadow: 0 3px 6px rgba(0, 0, 0, 0.4),
                     0 0 10px rgba(255, 255, 255, 0.3),
                     0 0 20px rgba(59, 130, 246, 0.4);
        filter: drop-shadow(0 2px 6px rgba(0, 0, 0, 0.3));
        z-index: 1;
        position: relative;
    }

    .update-modal-title {
        font-size: 1.5rem !important;
        font-weight: 800 !important;
        color: #1e293b;
        margin-bottom: 0.75rem;
        letter-spacing: -0.5px;
    }

    .update-modal-message {
        font-size: 1.0625rem;
        color: #64748b;
        margin-bottom: 2rem;
        line-height: 1.6;
        font-weight: 500;
    }

    .btn-update {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
        color: white !important;
        border: none !important;
        padding: 0.75rem 2rem !important;
        font-size: 0.9375rem !important;
        font-weight: 700 !important;
        border-radius: 12px !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4),
                    0 2px 8px rgba(59, 130, 246, 0.3) !important;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }

    .btn-update:hover {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%) !important;
        transform: translateY(-3px) scale(1.02) !important;
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.5),
                    0 4px 12px rgba(59, 130, 246, 0.4) !important;
    }

    .btn-update:active {
        transform: translateY(-1px) scale(0.98) !important;
    }

    /* Dark Mode Styles for Save Modal */
    [data-theme="dark"] .save-modal-content {
        background: linear-gradient(135deg, var(--dm-card-bg, #1e293b) 0%, var(--dm-bg-secondary, #0f172a) 100%) !important;
        box-shadow: 0 25px 80px rgba(16, 185, 129, 0.5),
                    0 10px 40px rgba(0, 0, 0, 0.6),
                    0 0 0 1px rgba(16, 185, 129, 0.2) inset !important;
    }

    [data-theme="dark"] .save-icon-wrapper {
        background: linear-gradient(135deg, #10b981 0%, #059669 50%, #047857 100%) !important;
        box-shadow: 0 12px 30px rgba(16, 185, 129, 0.6),
                    0 4px 15px rgba(16, 185, 129, 0.5),
                    0 0 0 8px rgba(16, 185, 129, 0.2),
                    0 0 0 4px rgba(255, 255, 255, 0.1) inset !important;
    }

    [data-theme="dark"] .save-icon-wrapper i {
        color: white !important;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3));
    }

    [data-theme="dark"] .save-modal-title {
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    [data-theme="dark"] .save-modal-message {
        color: var(--dm-text-muted, #94a3b8) !important;
    }

    [data-theme="dark"] .btn-save {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
        color: white !important;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.5),
                    0 2px 8px rgba(16, 185, 129, 0.4) !important;
    }

    [data-theme="dark"] .btn-save:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;
        color: white !important;
        box-shadow: 0 8px 25px rgba(16, 185, 129, 0.6),
                    0 4px 12px rgba(16, 185, 129, 0.5) !important;
    }

    [data-theme="dark"] .btn-update {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
        color: white !important;
    }

    [data-theme="dark"] .btn-update:hover {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%) !important;
        color: white !important;
    }

    /* Dark Mode Styles for Delete Modal - Enhanced Red Design */
    [data-theme="dark"] .delete-modal-content {
        background: linear-gradient(135deg, var(--dm-card-bg, #1e293b) 0%, var(--dm-bg-secondary, #0f172a) 100%) !important;
        box-shadow: 0 25px 80px rgba(239, 68, 68, 0.5),
                    0 10px 40px rgba(0, 0, 0, 0.6),
                    0 0 0 1px rgba(239, 68, 68, 0.2) inset !important;
    }

    [data-theme="dark"] .delete-icon-wrapper {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 50%, #b91c1c 100%) !important;
        border: 5px solid #dc2626 !important;
        box-shadow: 0 12px 35px rgba(239, 68, 68, 0.7),
                    0 6px 20px rgba(239, 68, 68, 0.6),
                    0 0 0 10px rgba(239, 68, 68, 0.3),
                    0 0 0 5px rgba(255, 255, 255, 0.15) inset,
                    0 0 60px rgba(239, 68, 68, 0.4) !important;
    }

    [data-theme="dark"] .delete-icon-wrapper i {
        color: white !important;
        text-shadow: 0 3px 6px rgba(0, 0, 0, 0.5),
                     0 0 15px rgba(255, 255, 255, 0.4),
                     0 0 25px rgba(239, 68, 68, 0.6) !important;
        filter: drop-shadow(0 2px 6px rgba(0, 0, 0, 0.4)) !important;
    }

    [data-theme="dark"] .delete-modal-title {
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    [data-theme="dark"] .delete-modal-message {
        color: var(--dm-text-muted, #94a3b8) !important;
    }

    [data-theme="dark"] .btn-cancel {
        background: var(--dm-bg-tertiary, #334155) !important;
        color: var(--dm-text-primary, #f1f5f9) !important;
        border-color: var(--dm-border-color, #334155) !important;
    }

    [data-theme="dark"] .btn-cancel:hover {
        background: var(--dm-bg-secondary, #1e293b) !important;
        color: var(--dm-text-primary, #f1f5f9) !important;
        border-color: #3b82f6 !important;
    }

    /* Dark Mode Styles for Update Modal - Enhanced Blue Design */
    [data-theme="dark"] .update-modal-content {
        background: linear-gradient(135deg, var(--dm-card-bg, #1e293b) 0%, var(--dm-bg-secondary, #0f172a) 100%) !important;
        box-shadow: 0 25px 80px rgba(59, 130, 246, 0.5),
                    0 10px 40px rgba(0, 0, 0, 0.6),
                    0 0 0 1px rgba(59, 130, 246, 0.2) inset !important;
    }

    [data-theme="dark"] .update-icon-wrapper {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 50%, #1d4ed8 100%) !important;
        border: 5px solid #2563eb !important;
        box-shadow: 0 12px 35px rgba(59, 130, 246, 0.7),
                    0 6px 20px rgba(59, 130, 246, 0.6),
                    0 0 0 10px rgba(59, 130, 246, 0.3),
                    0 0 0 5px rgba(255, 255, 255, 0.15) inset,
                    0 0 60px rgba(59, 130, 246, 0.4) !important;
    }

    [data-theme="dark"] .update-icon-wrapper i {
        color: white !important;
        text-shadow: 0 3px 6px rgba(0, 0, 0, 0.5),
                     0 0 15px rgba(255, 255, 255, 0.4),
                     0 0 25px rgba(59, 130, 246, 0.6) !important;
        filter: drop-shadow(0 2px 6px rgba(0, 0, 0, 0.4)) !important;
    }

    [data-theme="dark"] .update-modal-title {
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    [data-theme="dark"] .update-modal-message {
        color: var(--dm-text-muted, #94a3b8) !important;
    }

    [data-theme="dark"] .btn-update {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
        color: white !important;
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.5),
                    0 2px 8px rgba(59, 130, 246, 0.4) !important;
    }

    [data-theme="dark"] .btn-update:hover {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%) !important;
        color: white !important;
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.6),
                    0 4px 12px rgba(59, 130, 246, 0.5) !important;
    }

    [data-theme="dark"] .btn-delete {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
        color: white !important;
        box-shadow: 0 4px 15px rgba(239, 68, 68, 0.5),
                    0 2px 8px rgba(239, 68, 68, 0.4) !important;
    }

    [data-theme="dark"] .btn-delete:hover {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%) !important;
        color: white !important;
        box-shadow: 0 8px 25px rgba(239, 68, 68, 0.6),
                    0 4px 12px rgba(239, 68, 68, 0.5) !important;
    }

    /* Intent Delete Button Styles - Enhanced Cross Icon */
    .intent-delete-btn {
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        padding: 0.375rem 0.75rem !important;
        min-width: 42px !important;
        height: 31px !important;
        border: 2px solid #ef4444 !important;
        background: white !important;
        color: #ef4444 !important;
        border-radius: 0 6px 6px 0 !important;
        border-left: none !important;
        box-shadow: 0 2px 6px rgba(239, 68, 68, 0.2),
                    0 1px 3px rgba(239, 68, 68, 0.15) !important;
        position: relative !important;
        overflow: hidden !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }

    .intent-delete-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(239, 68, 68, 0.1), transparent);
        transition: left 0.5s;
        will-change: left;
    }

    .intent-delete-btn:hover::before {
        left: 100%;
    }

    .intent-delete-btn:hover {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
        color: white !important;
        border-color: #dc2626 !important;
        transform: scale(1.05) translateX(-2px) !important;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.4),
                    0 2px 6px rgba(239, 68, 68, 0.3) !important;
    }

    .intent-delete-btn:active {
        transform: scale(0.98) translateX(0) !important;
        box-shadow: 0 1px 4px rgba(239, 68, 68, 0.3) !important;
    }

    .intent-delete-btn i {
        font-size: 1rem !important;
        font-weight: 700 !important;
        transition: transform 0.3s ease !important;
        filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.1)) !important;
    }

    .intent-delete-btn:hover i {
        transform: scale(1.2) rotate(90deg) !important;
        filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.2)) !important;
    }

    /* Ensure intent rows are properly aligned */
    .intent-row {
        margin-bottom: 0.75rem;
    }

    /* Input group styling for intent rows */
    .intent-row .input-group {
        width: 100%;
    }

    .intent-row .input-group .form-control {
        border-radius: 6px 0 0 6px !important;
        border-right: none !important;
    }

    .intent-row .input-group .form-control:focus {
        border-right: none !important;
        box-shadow: none !important;
    }

    .intent-row .input-group .intent-delete-btn {
        border-left: 2px solid #ef4444 !important;
    }

    .intent-row .col-sm-5 .form-control {
        border-radius: 6px !important;
    }

    .intent-value-col {
        padding-left: 0.25rem !important;
    }

    @media (min-width: 576px) {
        .intent-value-col {
            padding-left: 0.375rem !important;
        }
    }

    @media (min-width: 768px) {
        .intent-value-col {
            padding-left: 0.5rem !important;
        }
    }

    /* Responsive adjustments for intent rows */
    @media (max-width: 576px) {
        .intent-row .input-group {
            flex-direction: row;
        }

        .intent-row .input-group .form-control {
            border-radius: 6px 0 0 6px !important;
            border-right: none !important;
        }

        .intent-row .input-group .intent-delete-btn {
            width: auto;
            border-radius: 0 6px 6px 0 !important;
            border-left: 2px solid #ef4444 !important;
            border-top: 2px solid #ef4444 !important;
            border-bottom: 2px solid #ef4444 !important;
        }

        .intent-row .col-sm-5 .form-control {
            border-radius: 6px !important;
        }

        [data-theme="dark"] .intent-row .input-group .form-control {
            border-right: none !important;
        }

        [data-theme="dark"] .intent-row .input-group .intent-delete-btn {
            border-left: 2px solid #ef4444 !important;
        }
    }

    /* FAQ Management Responsive Styles */
    @media (max-width: 768px) {
        .table-responsive {
            border: none;
        }

        .table thead th {
            font-size: 0.875rem;
            padding: 0.5rem;
        }

        .table td {
            padding: 0.5rem;
            font-size: 0.875rem;
        }

        .table td input {
            font-size: 0.875rem;
        }

        .table .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
        }
    }

    @media (max-width: 576px) {
        .table thead th,
        .table td {
            padding: 0.4rem;
        }

        .table td input {
            font-size: 0.8rem;
            padding: 0.25rem;
        }

        .table .btn-sm {
            padding: 0.2rem 0.4rem;
            font-size: 0.7rem;
        }

        .table .btn-sm i {
            font-size: 0.8rem;
        }
    }

    /* Improve form spacing on mobile */
    @media (max-width: 768px) {
        .card-body .mb-4 {
            margin-bottom: 1.5rem !important;
        }
    }

    /* FAQ Answer Textarea Styles */
    .faq-answer-textarea {
        resize: vertical;
        min-height: 80px;
        max-height: 300px;
        overflow-y: auto;
        white-space: pre-wrap;
        word-wrap: break-word;
        overflow-wrap: break-word;
        line-height: 1.5;
    }

    .faq-answer-textarea:focus {
        min-height: 120px;
        border-color: #86b7fe;
        box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
    }

    .table .faq-answer-textarea {
        height: 85px !important;
        min-height: 85px !important;
        max-height: 250px;
        transition: height 0.2s ease, min-height 0.2s ease;
    }

    .table .faq-answer-textarea:focus {
        min-height: 120px !important;
    }

    /* Ensure uniform height for all table answer fields */
    .table tbody tr td:nth-child(3) {
        vertical-align: middle;
    }

    .table tbody tr td:nth-child(3) .faq-answer-textarea {
        height: 85px !important;
        min-height: 85px !important;
    }

    /* Dark mode for textareas */
    [data-theme="dark"] .faq-answer-textarea {
        background: var(--dm-bg-secondary, #0f172a) !important;
        border-color: var(--dm-border-color, #334155) !important;
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    [data-theme="dark"] .faq-answer-textarea:focus {
        background: var(--dm-bg-secondary, #0f172a) !important;
        border-color: #3b82f6 !important;
        color: var(--dm-text-primary, #f1f5f9) !important;
        box-shadow: 0 0 0 0.25rem rgba(59, 130, 246, 0.25) !important;
    }

    /* Responsive textarea adjustments */
    @media (max-width: 768px) {
        .faq-answer-textarea {
            min-height: 70px;
        }

        .faq-answer-textarea:focus {
            min-height: 100px;
        }

        .table .faq-answer-textarea {
            height: 70px !important;
            min-height: 70px !important;
        }

        .table .faq-answer-textarea:focus {
            min-height: 100px !important;
        }

        .table tbody tr td:nth-child(3) .faq-answer-textarea {
            height: 70px !important;
            min-height: 70px !important;
        }
    }

    @media (max-width: 576px) {
        .table .faq-answer-textarea {
            height: 60px !important;
            min-height: 60px !important;
        }

        .table .faq-answer-textarea:focus {
            min-height: 90px !important;
        }

        .table tbody tr td:nth-child(3) .faq-answer-textarea {
            height: 60px !important;
            min-height: 60px !important;
        }
    }

    /* Dark Mode for Intent Delete Button - Cross Icon */
    [data-theme="dark"] .intent-delete-btn {
        background: var(--dm-card-bg, #1e293b) !important;
        border: 2px solid #ef4444 !important;
        border-left: none !important;
        color: #ef4444 !important;
        box-shadow: 0 2px 6px rgba(239, 68, 68, 0.3),
                    0 1px 3px rgba(239, 68, 68, 0.2),
                    0 0 10px rgba(239, 68, 68, 0.1) !important;
    }

    [data-theme="dark"] .intent-delete-btn::before {
        background: linear-gradient(90deg, transparent, rgba(239, 68, 68, 0.2), transparent) !important;
    }

    [data-theme="dark"] .intent-delete-btn:hover {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
        color: white !important;
        border-color: #dc2626 !important;
        box-shadow: 0 4px 15px rgba(239, 68, 68, 0.5),
                    0 2px 8px rgba(239, 68, 68, 0.4),
                    0 0 15px rgba(239, 68, 68, 0.3) !important;
    }

    [data-theme="dark"] .intent-delete-btn i {
        filter: drop-shadow(0 0 3px rgba(239, 68, 68, 0.5)) !important;
    }

    [data-theme="dark"] .intent-delete-btn:hover i {
        filter: drop-shadow(0 0 6px rgba(239, 68, 68, 0.8)),
                drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3)) !important;
    }

    [data-theme="dark"] .intent-row .input-group .form-control {
        background: var(--dm-input-bg, #0f172a) !important;
        border-color: var(--dm-border-color, #334155) !important;
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    [data-theme="dark"] .intent-row .input-group .form-control:focus {
        background: var(--dm-input-bg, #0f172a) !important;
        border-color: #3b82f6 !important;
        color: var(--dm-text-primary, #f1f5f9) !important;
    }

    /* New FAQ Row Styling */
    .new-faq-row {
        background-color: #f8f9fa !important;
        border-left: 3px solid #10b981;
        animation: slideIn 0.3s ease-out;
    }

    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    [data-theme="dark"] .new-faq-row {
        background-color: var(--dm-bg-tertiary, #334155) !important;
        border-left-color: #10b981 !important;
    }

    .save-new-faq:hover {
        background-color: #059669 !important;
        border-color: #059669 !important;
    }

    .cancel-new-faq:hover {
        background-color: #6b7280 !important;
        border-color: #6b7280 !important;
        color: white !important;
    }

    /* Add FAQ Header Button Styling */
    .add-faq-header-btn {
        background-color: white !important;
        border: none !important;
        color: #10b981 !important;
        font-weight: 600 !important;
        padding: 0.5rem 1rem !important;
        border-radius: 8px !important;
        transition: all 0.2s ease !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 0.5rem !important;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1) !important;
    }

    .add-faq-header-btn:hover {
        background-color: #f8f9fa !important;
        transform: translateY(-1px) !important;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2) !important;
    }

    .add-faq-icon {
        font-size: 1.5rem !important;
        font-weight: 900 !important;
        color: #10b981 !important;
        line-height: 1 !important;
        display: inline-block !important;
    }

    /* Dark mode for Add FAQ button */
    [data-theme="dark"] .add-faq-header-btn {
        background-color: #1e293b !important;
        border: 1px solid #334155 !important;
        color: #10b981 !important;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.15),
                    0 1px 3px rgba(0, 0, 0, 0.5) !important;
    }

    [data-theme="dark"] .add-faq-header-btn:hover {
        background-color: #0f172a !important;
        border-color: #10b981 !important;
        color: #34d399 !important;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.3),
                    0 2px 6px rgba(0, 0, 0, 0.6) !important;
        transform: translateY(-2px) !important;
    }

    [data-theme="dark"] .add-faq-icon {
        color: #10b981 !important;
        text-shadow: 0 0 8px rgba(16, 185, 129, 0.3) !important;
    }

    [data-theme="dark"] .add-faq-header-btn:hover .add-faq-icon {
        color: #34d399 !important;
        text-shadow: 0 0 12px rgba(52, 211, 153, 0.5) !important;
    }

    [data-theme="dark"] .delete-intent:hover {
        background-color: #dc2626 !important;
        color: white !important;
        border-color: #dc2626 !important;
    }

    /* Toggle Switch Styling - Green when ON */
    .form-check-input:checked {
        background-color: #10b981 !important;
        border-color: #10b981 !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e") !important;
    }

    .form-check-input:focus {
        border-color: #10b981 !important;
        outline: 0 !important;
        box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.25) !important;
    }

    .form-check-input:checked:focus {
        border-color: #10b981 !important;
        box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.25) !important;
    }

    /* Dark Mode - Neon Green Toggle */
    [data-theme="dark"] .form-check-input:checked {
        background-color: #10b981 !important;
        border-color: #10b981 !important;
        box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.3),
                    0 0 10px rgba(16, 185, 129, 0.5),
                    0 0 20px rgba(16, 185, 129, 0.3) !important;
        background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='-4 -4 8 8'%3e%3ccircle r='3' fill='%23fff'/%3e%3c/svg%3e") !important;
    }

    [data-theme="dark"] .form-check-input:focus {
        border-color: #10b981 !important;
        box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.3) !important;
    }

    [data-theme="dark"] .form-check-input:checked:focus {
        border-color: #10b981 !important;
        box-shadow: 0 0 0 0.25rem rgba(16, 185, 129, 0.4),
                    0 0 15px rgba(16, 185, 129, 0.6),
                    0 0 25px rgba(16, 185, 129, 0.4) !important;
    }

    /* Hover effects for toggle switches */
    .form-check-input:hover:not(:disabled) {
        border-color: #10b981 !important;
    }

    .form-check-input:checked:hover:not(:disabled) {
        background-color: #059669 !important;
        border-color: #059669 !important;
    }

    [data-theme="dark"] .form-check-input:checked:hover:not(:disabled) {
        background-color: #34d399 !important;
        border-color: #34d399 !important;
        box-shadow: 0 0 0 0.25rem rgba(52, 211, 153, 0.4),
                    0 0 15px rgba(52, 211, 153, 0.7),
                    0 0 25px rgba(52, 211, 153, 0.5) !important;
    }

    /* Save Settings Button Enhanced Styling */
    .save-settings-btn {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 50%, #1d4ed8 100%) !important;
        border: none !important;
        color: white !important;
        font-weight: 700 !important;
        font-size: 1rem !important;
        padding: 0.75rem 2rem !important;
        border-radius: 12px !important;
        box-shadow: 0 4px 15px rgba(59, 130, 246, 0.4),
                    0 2px 8px rgba(37, 99, 235, 0.3),
                    0 0 0 1px rgba(255, 255, 255, 0.1) inset !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        position: relative !important;
        overflow: hidden !important;
        text-transform: uppercase !important;
        letter-spacing: 0.5px !important;
    }

    .save-settings-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s;
        will-change: left;
    }

    .save-settings-btn:hover::before {
        left: 100%;
    }

    .save-settings-btn:hover {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 50%, #1e40af 100%) !important;
        transform: translateY(-2px) scale(1.02) !important;
        box-shadow: 0 8px 25px rgba(59, 130, 246, 0.6),
                    0 4px 12px rgba(37, 99, 235, 0.5),
                    0 0 0 1px rgba(255, 255, 255, 0.15) inset !important;
    }

    .save-settings-btn:active {
        transform: translateY(0) scale(0.98) !important;
        box-shadow: 0 2px 10px rgba(59, 130, 246, 0.4) !important;
    }

    .save-settings-btn i {
        font-size: 1.1rem !important;
        filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.2)) !important;
    }

    .save-settings-btn:hover i {
        transform: scale(1.1);
        transition: transform 0.3s ease;
    }

    /* Dark mode for Save Settings Button - Deep Blue with Neon Glow */
    [data-theme="dark"] .save-settings-btn {
        background: linear-gradient(135deg, #1e40af 0%, #1e3a8a 50%, #1e293b 100%) !important;
        border: 1px solid rgba(59, 130, 246, 0.3) !important;
        color: white !important;
        box-shadow: 0 4px 20px rgba(59, 130, 246, 0.5),
                    0 2px 10px rgba(59, 130, 246, 0.4),
                    0 0 30px rgba(59, 130, 246, 0.3),
                    0 0 60px rgba(59, 130, 246, 0.2),
                    0 0 0 1px rgba(255, 255, 255, 0.1) inset !important;
        text-shadow: 0 0 10px rgba(255, 255, 255, 0.3) !important;
    }

    [data-theme="dark"] .save-settings-btn::before {
        background: linear-gradient(90deg, transparent, rgba(59, 130, 246, 0.4), transparent) !important;
    }

    [data-theme="dark"] .save-settings-btn:hover {
        background: linear-gradient(135deg, #2563eb 0%, #1e40af 50%, #1e3a8a 100%) !important;
        border-color: rgba(96, 165, 250, 0.5) !important;
        transform: translateY(-3px) scale(1.03) !important;
        box-shadow: 0 8px 30px rgba(59, 130, 246, 0.7),
                    0 4px 15px rgba(59, 130, 246, 0.6),
                    0 0 40px rgba(59, 130, 246, 0.5),
                    0 0 80px rgba(59, 130, 246, 0.3),
                    0 0 0 1px rgba(255, 255, 255, 0.15) inset !important;
        text-shadow: 0 0 15px rgba(255, 255, 255, 0.5),
                     0 0 25px rgba(59, 130, 246, 0.5) !important;
    }

    [data-theme="dark"] .save-settings-btn:active {
        transform: translateY(-1px) scale(1.01) !important;
        box-shadow: 0 4px 20px rgba(59, 130, 246, 0.6),
                    0 0 30px rgba(59, 130, 246, 0.4),
                    0 0 0 1px rgba(255, 255, 255, 0.1) inset !important;
    }

    [data-theme="dark"] .save-settings-btn i {
        filter: drop-shadow(0 0 5px rgba(59, 130, 246, 0.8)),
                drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3)) !important;
        text-shadow: 0 0 10px rgba(59, 130, 246, 0.8) !important;
    }

    [data-theme="dark"] .save-settings-btn:hover i {
        filter: drop-shadow(0 0 8px rgba(96, 165, 250, 1)),
                drop-shadow(0 2px 4px rgba(0, 0, 0, 0.3)) !important;
        transform: scale(1.15) !important;
    }

    /* FAQ Action Buttons - Save and Delete Styling */
    .faq-save-btn {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
        border: none !important;
        color: white !important;
        width: 38px !important;
        height: 38px !important;
        padding: 0 !important;
        border-radius: 10px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        box-shadow: 0 4px 12px rgba(16, 185, 129, 0.4),
                    0 2px 6px rgba(16, 185, 129, 0.3) !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        position: relative !important;
        overflow: hidden !important;
    }

    .faq-save-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
        transition: left 0.5s;
        will-change: left;
    }

    .faq-save-btn:hover::before {
        left: 100%;
    }

    .faq-save-btn:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;
        transform: translateY(-2px) scale(1.1) !important;
        box-shadow: 0 6px 20px rgba(16, 185, 129, 0.5),
                    0 4px 10px rgba(16, 185, 129, 0.4) !important;
    }

    .faq-save-btn:active {
        transform: translateY(0) scale(1.05) !important;
        box-shadow: 0 2px 8px rgba(16, 185, 129, 0.4) !important;
    }

    .faq-save-btn i {
        font-size: 1rem !important;
        filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.2)) !important;
        transition: transform 0.3s ease !important;
    }

    .faq-save-btn:hover i {
        transform: scale(1.15) !important;
    }

    .faq-delete-btn {
        background: white !important;
        border: 2px solid #ef4444 !important;
        color: #ef4444 !important;
        width: 38px !important;
        height: 38px !important;
        padding: 0 !important;
        border-radius: 10px !important;
        display: inline-flex !important;
        align-items: center !important;
        justify-content: center !important;
        box-shadow: 0 4px 12px rgba(239, 68, 68, 0.3),
                    0 2px 6px rgba(239, 68, 68, 0.2) !important;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1) !important;
        position: relative !important;
        overflow: hidden !important;
    }

    .faq-delete-btn::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(239, 68, 68, 0.1), transparent);
        transition: left 0.5s;
        will-change: left;
    }

    .faq-delete-btn:hover::before {
        left: 100%;
    }

    .faq-delete-btn:hover {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
        border-color: #dc2626 !important;
        color: white !important;
        transform: translateY(-2px) scale(1.1) !important;
        box-shadow: 0 6px 20px rgba(239, 68, 68, 0.5),
                    0 4px 10px rgba(239, 68, 68, 0.4) !important;
    }

    .faq-delete-btn:active {
        transform: translateY(0) scale(1.05) !important;
        box-shadow: 0 2px 8px rgba(239, 68, 68, 0.4) !important;
    }

    .faq-delete-btn i {
        font-size: 1rem !important;
        transition: transform 0.3s ease !important;
    }

    .faq-delete-btn:hover i {
        transform: scale(1.15) !important;
        filter: drop-shadow(0 1px 2px rgba(0, 0, 0, 0.2)) !important;
    }

    /* Dark Mode for FAQ Action Buttons */
    [data-theme="dark"] .faq-save-btn {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%) !important;
        box-shadow: 0 4px 15px rgba(16, 185, 129, 0.5),
                    0 2px 8px rgba(16, 185, 129, 0.4),
                    0 0 15px rgba(16, 185, 129, 0.3) !important;
    }

    [data-theme="dark"] .faq-save-btn::before {
        background: linear-gradient(90deg, transparent, rgba(52, 211, 153, 0.4), transparent) !important;
    }

    [data-theme="dark"] .faq-save-btn:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;
        box-shadow: 0 6px 25px rgba(16, 185, 129, 0.6),
                    0 4px 12px rgba(16, 185, 129, 0.5),
                    0 0 20px rgba(16, 185, 129, 0.4) !important;
    }

    [data-theme="dark"] .faq-save-btn i {
        filter: drop-shadow(0 0 5px rgba(16, 185, 129, 0.6)) !important;
    }

    [data-theme="dark"] .faq-save-btn:hover i {
        filter: drop-shadow(0 0 8px rgba(52, 211, 153, 0.8)) !important;
    }

    [data-theme="dark"] .faq-delete-btn {
        background: var(--dm-card-bg, #1e293b) !important;
        border: 2px solid #ef4444 !important;
        color: #ef4444 !important;
        box-shadow: 0 4px 15px rgba(239, 68, 68, 0.4),
                    0 2px 8px rgba(239, 68, 68, 0.3),
                    0 0 15px rgba(239, 68, 68, 0.2) !important;
    }

    [data-theme="dark"] .faq-delete-btn::before {
        background: linear-gradient(90deg, transparent, rgba(239, 68, 68, 0.2), transparent) !important;
    }

    [data-theme="dark"] .faq-delete-btn:hover {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
        border-color: #dc2626 !important;
        color: white !important;
        box-shadow: 0 6px 25px rgba(239, 68, 68, 0.6),
                    0 4px 12px rgba(239, 68, 68, 0.5),
                    0 0 20px rgba(239, 68, 68, 0.4) !important;
    }

    [data-theme="dark"] .faq-delete-btn i {
        filter: drop-shadow(0 0 5px rgba(239, 68, 68, 0.5)) !important;
    }

    [data-theme="dark"] .faq-delete-btn:hover i {
        filter: drop-shadow(0 0 8px rgba(239, 68, 68, 0.8)) !important;
    }
</style>

<script>
    (function(){
        const addBtn = document.getElementById('add-intent');
        const list = document.getElementById('intent-list');
        if (!addBtn || !list) return;

        addBtn.addEventListener('click', () => {
            const idx = list.querySelectorAll('.intent-row').length;
            const div = document.createElement('div');
            div.className = 'row mb-2 align-items-center intent-row g-1';
            div.innerHTML = `
                <div class="col-12 col-sm-5 col-md-4">
                    <div class="input-group input-group-sm">
                        <input type="text" name="quick_intents[${idx}][label]" placeholder="Chip Label" class="form-control form-control-sm">
                        <button type="button" class="btn btn-outline-danger delete-intent intent-delete-btn" title="Delete intent">
                            <i class="bi bi-x-lg"></i>
                        </button>
                    </div>
                </div>
                <div class="col-12 col-sm-7 col-md-8 intent-value-col">
                    <input type="text" name="quick_intents[${idx}][value]" placeholder="Question to ask" class="form-control form-control-sm">
                </div>
            `;
            list.appendChild(div);

            // Attach delete event listener to the new delete button
            const deleteBtn = div.querySelector('.delete-intent');
            if (deleteBtn) {
                deleteBtn.addEventListener('click', function() {
                    div.remove();
                    // Renumber remaining intents
                    renumberIntents();
                });
            }
        });

        // Function to renumber intent indices after deletion
        function renumberIntents() {
            const rows = list.querySelectorAll('.intent-row');
            rows.forEach((row, index) => {
                const labelInput = row.querySelector('input[name*="[label]"]');
                const valueInput = row.querySelector('input[name*="[value]"]');

                if (labelInput) {
                    labelInput.name = `quick_intents[${index}][label]`;
                }
                if (valueInput) {
                    valueInput.name = `quick_intents[${index}][value]`;
                }
            });
        }

        // Attach delete event listeners to existing intent rows
        document.querySelectorAll('.delete-intent').forEach(btn => {
            btn.addEventListener('click', function() {
                const row = this.closest('.intent-row');
                if (row) {
                    row.remove();
                    renumberIntents();
                }
            });
        });
    })();

    // Save Settings Confirmation
    function confirmSaveSettings() {
        const saveModal = new bootstrap.Modal(document.getElementById('saveSettingsModal'));
        saveModal.show();
    }

    document.getElementById('confirmSaveSettingsBtn').addEventListener('click', function() {
        // Clone the visible form data to the hidden form
        const visibleForm = document.querySelector('form[action="{{ route('admin-toothtalk.settings.save') }}"]');
        const hiddenForm = document.getElementById('settingsForm');

        // Get all form inputs from visible form
        const inputs = visibleForm.querySelectorAll('input, textarea, select');
        inputs.forEach(input => {
            if (input.type === 'checkbox') {
                const newInput = document.createElement('input');
                newInput.type = 'hidden';
                newInput.name = input.name;
                newInput.value = input.checked ? '1' : '0';
                hiddenForm.appendChild(newInput);
            } else if (input.type !== 'submit' && input.type !== 'button') {
                const newInput = input.cloneNode(true);
                hiddenForm.appendChild(newInput);
            }
        });

        hiddenForm.submit();

        const saveModal = bootstrap.Modal.getInstance(document.getElementById('saveSettingsModal'));
        if (saveModal) {
            saveModal.hide();
        }
    });

    // Add new FAQ row function
    let newFaqRowCounter = 0;

    function addNewFaqRow() {
        const tbody = document.getElementById('faqTableBody');
        const nextOrder = tbody.querySelectorAll('tr').length + 1;
        const tempId = 'new-' + (++newFaqRowCounter);

        // Create new row
        const newRow = document.createElement('tr');
        newRow.className = 'new-faq-row';
        newRow.dataset.tempId = tempId;
        newRow.innerHTML = `
            <td class="text-muted d-none d-md-table-cell">${nextOrder}</td>
            <td>
                <input type="text" name="question" placeholder="Enter question..." class="form-control form-control-sm" required>
            </td>
            <td>
                <textarea name="answer" placeholder="Enter answer..." class="form-control form-control-sm faq-answer-textarea" rows="3" required></textarea>
            </td>
            <td>
                <div class="form-check form-switch d-flex justify-content-center">
                    <input class="form-check-input" type="checkbox" name="is_active" value="1" checked>
                </div>
            </td>
            <td>
                <div class="d-flex justify-content-center gap-1">
                    <button type="button" class="btn btn-sm faq-save-btn save-new-faq" onclick="saveNewFaq(this, '${tempId}')" title="Save">
                        <i class="bi bi-save"></i>
                    </button>
                    <button type="button" class="btn btn-sm btn-outline-secondary cancel-new-faq" onclick="cancelNewFaq(this)" title="Cancel">
                        <i class="bi bi-x"></i>
                    </button>
                </div>
            </td>
        `;

        // Insert at the beginning of tbody
        tbody.insertBefore(newRow, tbody.firstChild);

        // Initialize textarea auto-resize (optimized)
        const textarea = newRow.querySelector('.faq-answer-textarea');
        if (textarea) {
            initializeTextarea(textarea);
            // Set initial height immediately
            const baseHeight = cachedWindowWidth <= 576 ? 60 : (cachedWindowWidth <= 768 ? 70 : 85);
            textarea.style.height = baseHeight + 'px';
            textarea.style.minHeight = baseHeight + 'px';
        }

        // Focus on question input
        newRow.querySelector('input[name="question"]').focus();
    }

    function saveNewFaq(button, tempId) {
        const row = button.closest('tr');
        const questionInput = row.querySelector('input[name="question"]');
        const answerInput = row.querySelector('textarea[name="answer"]');
        const isActiveCheckbox = row.querySelector('input[name="is_active"]');

        const question = questionInput.value.trim();
        const answer = answerInput.value.trim();

        if (!question || !answer) {
            alert('Please fill in both question and answer fields.');
            return;
        }

        // Disable button during save
        button.disabled = true;
        button.innerHTML = '<i class="bi bi-hourglass-split"></i>';

        // Create form data
        const formData = new FormData();
        formData.append('question', question);
        formData.append('answer', answer);
        formData.append('is_active', isActiveCheckbox.checked ? '1' : '0');
        formData.append('_token', document.querySelector('meta[name="csrf-token"]').content);

        // Submit via AJAX
        fetch('{{ route("admin-toothtalk.faq.store") }}', {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            },
            body: formData
        })
        .then(response => {
            if (response.ok) {
                return response.json();
            }
            throw new Error('Network response was not ok');
        })
        .then(data => {
            if (data.success) {
                // Reload page to show new FAQ
                window.location.reload();
            } else {
                alert('Error: ' + (data.message || 'Failed to save FAQ'));
                button.disabled = false;
                button.innerHTML = '<i class="bi bi-check"></i>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while saving the FAQ. Please try again.');
            button.disabled = false;
            button.innerHTML = '<i class="bi bi-check"></i>';
        });
    }

    function cancelNewFaq(button) {
        const row = button.closest('tr');
        if (row && row.classList.contains('new-faq-row')) {
            row.remove();
            // Re-number remaining rows
            renumberTableRows();
        }
    }

    function renumberTableRows() {
        const rows = document.querySelectorAll('#faqTableBody tr:not(.new-faq-row)');
        rows.forEach((row, index) => {
            const orderCell = row.querySelector('td:first-child');
            if (orderCell && !orderCell.classList.contains('d-none')) {
                orderCell.textContent = index + 1;
            }
        });
    }

    // Update FAQ Confirmation
    let faqToUpdate = null;

    function confirmUpdateFaq(faqId, currentQuestion) {
        faqToUpdate = faqId;

        // Get the updated question from the form - find form that contains this FAQ's row
        const forms = document.querySelectorAll(`form[action*="/toothtalk/faq/"]`);
        let updatedQuestion = currentQuestion;
        for (let form of forms) {
            if (form.action.includes(`/${faqId}`) && !form.id) {
                const questionInput = form.querySelector('input[name="question"]');
                if (questionInput) {
                    updatedQuestion = questionInput.value;
                    break;
                }
            }
        }

        const message = `Are you sure you want to save changes to this FAQ?<br><strong>"${updatedQuestion}"</strong>`;
        document.getElementById('updateFaqMessage').innerHTML = message;

        const updateModal = new bootstrap.Modal(document.getElementById('updateFaqModal'));
        updateModal.show();
    }

    document.getElementById('confirmUpdateFaqBtn').addEventListener('click', function() {
        if (!faqToUpdate) return;

        // Find the visible form that wraps the table row for this FAQ
        const forms = document.querySelectorAll(`form[method="POST"]`);
        let visibleForm = null;
        for (let form of forms) {
            if (form.action.includes(`/toothtalk/faq/${faqToUpdate}`) && !form.id) {
                visibleForm = form;
                break;
            }
        }

        if (visibleForm) {
            visibleForm.submit();
        } else {
            // Fallback: use the hidden form and populate it from inputs with matching FAQ
            const allInputs = document.querySelectorAll(`input[name="question"]`);
            let targetRow = null;
            for (let input of allInputs) {
                const form = input.closest('form');
                if (form && form.action.includes(`/toothtalk/faq/${faqToUpdate}`) && !form.id) {
                    targetRow = input.closest('tr');
                    break;
                }
            }

            if (targetRow) {
                const hiddenForm = document.getElementById(`update-faq-${faqToUpdate}`);
                if (hiddenForm) {
                    // Clear existing inputs except CSRF and method
                    const existingInputs = hiddenForm.querySelectorAll('input:not([name="_token"]):not([name="_method"])');
                    existingInputs.forEach(input => input.remove());

                    // Get all inputs from the row
                    const questionInput = targetRow.querySelector('input[name="question"]');
                    const answerInput = targetRow.querySelector('textarea[name="answer"]') || targetRow.querySelector('input[name="answer"]');
                    const isActiveCheckbox = targetRow.querySelector('input[name="is_active"]');

                    // Add form data
                    if (questionInput) {
                        const newInput = questionInput.cloneNode(true);
                        hiddenForm.appendChild(newInput);
                    }
                    if (answerInput) {
                        const newInput = answerInput.cloneNode(true);
                        hiddenForm.appendChild(newInput);
                    }
                    if (isActiveCheckbox) {
                        const newInput = document.createElement('input');
                        newInput.type = 'hidden';
                        newInput.name = 'is_active';
                        newInput.value = isActiveCheckbox.checked ? '1' : '0';
                        hiddenForm.appendChild(newInput);
                    }

                    hiddenForm.submit();
                }
            }
        }

        const updateModal = bootstrap.Modal.getInstance(document.getElementById('updateFaqModal'));
        if (updateModal) {
            updateModal.hide();
        }

        faqToUpdate = null;
    });

    // Reset faqToUpdate when modal is closed
    document.getElementById('updateFaqModal').addEventListener('hidden.bs.modal', function() {
        faqToUpdate = null;
    });

    // Delete FAQ Confirmation
    let faqToDelete = null;

    function confirmDeleteFaq(faqId, question) {
        faqToDelete = faqId;
        const message = `Are you sure you want to delete this FAQ?<br><strong>"${question}"</strong>`;
        document.getElementById('deleteFaqMessage').innerHTML = message;

        const deleteModal = new bootstrap.Modal(document.getElementById('deleteFaqModal'));
        deleteModal.show();
    }

    document.getElementById('confirmDeleteFaqBtn').addEventListener('click', function() {
        if (!faqToDelete) return;

        const deleteForm = document.getElementById('delete-faq-' + faqToDelete);
        if (deleteForm) {
            deleteForm.submit();
        } else {
            // Fallback: create and submit form dynamically
            const form = document.createElement('form');
            form.method = 'POST';
            form.action = `/admin/toothtalk/faq/${faqToDelete}`;

            const csrfInput = document.createElement('input');
            csrfInput.type = 'hidden';
            csrfInput.name = '_token';
            csrfInput.value = document.querySelector('meta[name="csrf-token"]').content;
            form.appendChild(csrfInput);

            const methodInput = document.createElement('input');
            methodInput.type = 'hidden';
            methodInput.name = '_method';
            methodInput.value = 'DELETE';
            form.appendChild(methodInput);

            document.body.appendChild(form);
            form.submit();
        }

        const deleteModal = bootstrap.Modal.getInstance(document.getElementById('deleteFaqModal'));
        if (deleteModal) {
            deleteModal.hide();
        }

        faqToDelete = null;
    });

    // Reset faqToDelete when modal is closed
    document.getElementById('deleteFaqModal').addEventListener('hidden.bs.modal', function() {
        faqToDelete = null;
    });

    // Debounce helper function
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    // Throttle helper function using requestAnimationFrame
    function throttleRAF(func) {
        let rafId = null;
        return function(...args) {
            if (rafId === null) {
                rafId = requestAnimationFrame(() => {
                    func(...args);
                    rafId = null;
                });
            }
        };
    }

    // Cache window dimensions
    let cachedWindowWidth = window.innerWidth;
    const updateWindowWidth = throttleRAF(() => {
        cachedWindowWidth = window.innerWidth;
    });
    window.addEventListener('resize', updateWindowWidth, { passive: true });

    // Auto-resize textareas for FAQ answers (optimized)
    function autoResizeTextarea(textarea) {
        if (!textarea) return;

        const isInTable = textarea.closest('table') !== null;
        const baseHeight = isInTable ? 85 : 80;
        const maxHeight = isInTable ? 250 : 300;

        // Use requestAnimationFrame for smooth updates
        requestAnimationFrame(() => {
            // Reset height to calculate scrollHeight
            textarea.style.height = 'auto';

            // Calculate new height based on content
            const contentHeight = textarea.scrollHeight;
            const newHeight = contentHeight > baseHeight
                ? Math.min(contentHeight, maxHeight)
                : baseHeight;

            textarea.style.height = newHeight + 'px';
        });
    }

    // Debounced version for input events
    const debouncedAutoResize = debounce(autoResizeTextarea, 50);

    // Set uniform height for all table answer fields (optimized)
    const setUniformHeights = debounce(function() {
        const tableTextareas = document.querySelectorAll('.table .faq-answer-textarea');
        if (tableTextareas.length === 0) return;

        const baseHeight = cachedWindowWidth <= 576 ? 60 : (cachedWindowWidth <= 768 ? 70 : 85);
        const maxHeight = 250;

        requestAnimationFrame(() => {
            tableTextareas.forEach(textarea => {
                if (!textarea) return;
                const contentHeight = textarea.scrollHeight;
                if (contentHeight <= baseHeight) {
                    textarea.style.height = baseHeight + 'px';
                    textarea.style.minHeight = baseHeight + 'px';
                } else {
                    const newHeight = Math.min(contentHeight, maxHeight);
                    textarea.style.height = Math.max(newHeight, baseHeight) + 'px';
                }
            });
        });
    }, 100);

    // Initialize auto-resize for all FAQ answer textareas (optimized)
    function initializeTextarea(textarea) {
        if (!textarea || textarea.dataset.initialized === 'true') return;
        textarea.dataset.initialized = 'true';

        const isInTable = textarea.closest('table') !== null;

        // Set initial uniform height for table textareas
        if (isInTable) {
            const baseHeight = cachedWindowWidth <= 576 ? 60 : (cachedWindowWidth <= 768 ? 70 : 85);
            const contentHeight = textarea.scrollHeight;
            if (contentHeight <= baseHeight) {
                textarea.style.height = baseHeight + 'px';
                textarea.style.minHeight = baseHeight + 'px';
            }
        }

        // Use debounced resize for input events
        textarea.addEventListener('input', function() {
            debouncedAutoResize(this);
        }, { passive: true });

        // Expand on focus for better editing
        textarea.addEventListener('focus', function() {
            if (isInTable) {
                this.style.minHeight = cachedWindowWidth <= 576 ? '90px' : (cachedWindowWidth <= 768 ? '100px' : '120px');
            } else {
                this.style.minHeight = '120px';
            }
            autoResizeTextarea(this);
        }, { passive: true });

        // Reset to uniform height on blur if content fits
        textarea.addEventListener('blur', function() {
            if (isInTable) {
                const baseHeight = cachedWindowWidth <= 576 ? 60 : (cachedWindowWidth <= 768 ? 70 : 85);
                if (this.scrollHeight <= baseHeight) {
                    this.style.minHeight = baseHeight + 'px';
                    this.style.height = baseHeight + 'px';
                } else {
                    autoResizeTextarea(this);
                }
            } else {
                if (this.scrollHeight <= 80) {
                    this.style.minHeight = '80px';
                    this.style.height = '80px';
                }
            }
        }, { passive: true });
    }

    // Initialize on DOMContentLoaded
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            const textareas = document.querySelectorAll('.faq-answer-textarea');
            textareas.forEach(initializeTextarea);
            setUniformHeights();
        });
    } else {
        // DOM already loaded
        const textareas = document.querySelectorAll('.faq-answer-textarea');
        textareas.forEach(initializeTextarea);
        setUniformHeights();
    }

    // Optimized MutationObserver - only watch FAQ table, not entire document
    const faqTableBody = document.getElementById('faqTableBody');
    if (faqTableBody) {
        const observer = new MutationObserver(function(mutations) {
            let hasNewTextarea = false;

            mutations.forEach(function(mutation) {
                mutation.addedNodes.forEach(function(node) {
                    if (node.nodeType === 1) { // Element node
                        // Check if node is a textarea or contains one
                        if (node.classList && node.classList.contains('faq-answer-textarea')) {
                            initializeTextarea(node);
                            hasNewTextarea = true;
                        } else {
                            const newTextareas = node.querySelectorAll ? node.querySelectorAll('.faq-answer-textarea') : [];
                            if (newTextareas.length > 0) {
                                newTextareas.forEach(initializeTextarea);
                                hasNewTextarea = true;
                            }
                        }
                    }
                });
            });

            // Only call setUniformHeights if new textareas were added
            if (hasNewTextarea) {
                setUniformHeights();
            }
        });

        // Only observe the FAQ table body, not entire document
        observer.observe(faqTableBody, {
            childList: true,
            subtree: true
        });
    }

    // Optimized resize handler - already handled by updateWindowWidth and debounced
    window.addEventListener('resize', function() {
        setUniformHeights();
    }, { passive: true });

    // Initial alignment after page loads (single call, debounced)
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() {
            setTimeout(setUniformHeights, 100);
        });
    } else {
        setTimeout(setUniformHeights, 100);
    }
</script>
@endsection
