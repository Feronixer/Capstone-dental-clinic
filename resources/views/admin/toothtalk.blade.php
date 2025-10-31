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
        <h2 class="mb-0"><i class="bi bi-robot"></i> ToothTalk Chatbot Configuration</h2>
    </div>

    <!-- Settings Form -->
    <div class="card shadow-sm mb-4">
        <div class="card-header bg-primary text-white">
            <h5 class="mb-0"><i class="bi bi-gear"></i> Chatbot Settings</h5>
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
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <input type="text" name="quick_intents[{{ $idx }}][label]" value="{{ $intent['label'] ?? '' }}"
                                           placeholder="Chip Label" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="quick_intents[{{ $idx }}][value]" value="{{ $intent['value'] ?? '' }}"
                                           placeholder="Question to ask" class="form-control form-control-sm">
                                </div>
                            </div>
                        @empty
                            <div class="row mb-2">
                                <div class="col-md-4">
                                    <input type="text" name="quick_intents[0][label]" value="Clinic Hours"
                                           placeholder="Chip Label" class="form-control form-control-sm">
                                </div>
                                <div class="col-md-8">
                                    <input type="text" name="quick_intents[0][value]" value="What are your clinic hours?"
                                           placeholder="Question to ask" class="form-control form-control-sm">
                                </div>
                            </div>
                        @endforelse
                    </div>
                    <button type="button" id="add-intent" class="btn btn-outline-primary btn-sm">
                        <i class="bi bi-plus"></i> Add Intent
                    </button>
                </div>

                <div class="d-flex justify-content-end">
                    <button type="button" class="btn btn-primary" onclick="confirmSaveSettings()">
                        <i class="bi bi-save"></i> Save Settings
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
        <div class="card-header bg-success text-white">
            <h5 class="mb-0"><i class="bi bi-question-circle"></i> FAQ Management</h5>
        </div>
        <div class="card-body">
            <!-- Add FAQ Form -->
            <form method="POST" action="{{ route('admin-toothtalk.faq.store') }}" class="mb-4">
                @csrf
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Question</label>
                        <input type="text" name="question" placeholder="e.g., How do I book an appointment?" class="form-control" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-bold">Answer</label>
                        <input type="text" name="answer" placeholder="e.g., Click Patient Login and use our booking system" class="form-control" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="is_active" value="1" id="is_active" checked>
                            <label class="form-check-label" for="is_active">Active (show in chatbot)</label>
                        </div>
                    </div>
                </div>
                <button type="button" class="btn btn-success" onclick="confirmAddFaq()">
                    <i class="bi bi-plus"></i> Add FAQ
                </button>
            </form>
            <form id="addFaqForm" method="POST" action="{{ route('admin-toothtalk.faq.store') }}" style="display:none;">
                @csrf
            </form>

            <!-- FAQ List -->
            @if($faqs->count() > 0)
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">#</th>
                                <th width="35%">Question</th>
                                <th width="50%">Answer</th>
                                <th width="10%">Status</th>
                                <th width="10%">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($faqs as $faq)
                                <form method="POST" action="{{ route('admin-toothtalk.faq.update', $faq->id) }}">
                                    @csrf
                                    @method('PUT')
                                    <tr>
                                        <td class="text-muted">{{ $faq->order }}</td>
                                        <td>
                                            <input type="text" name="question" value="{{ $faq->question }}" class="form-control form-control-sm" required>
                                        </td>
                                        <td>
                                            <input type="text" name="answer" value="{{ $faq->answer }}" class="form-control form-control-sm" required>
                                        </td>
                                        <td>
                                            <div class="form-check form-switch">
                                                <input class="form-check-input" type="checkbox" name="is_active" value="1" {{ $faq->is_active ? 'checked' : '' }}>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <button type="button" class="btn btn-sm btn-primary me-1"
                                                        onclick="confirmUpdateFaq({{ $faq->id }}, '{{ addslashes($faq->question) }}')"
                                                        title="Save">
                                                    <i class="bi bi-check"></i>
                                                </button>
                                                <button type="button" class="btn btn-sm btn-outline-danger"
                                                        onclick="confirmDeleteFaq({{ $faq->id }}, '{{ addslashes($faq->question) }}')"
                                                        title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </button>
                                            </div>
                                        </td>
                                    </tr>
                                </form>
                                <form id="update-faq-{{ $faq->id }}" method="POST" action="{{ route('admin-toothtalk.faq.update', $faq->id) }}" style="display:none;">
                                    @csrf
                                    @method('PUT')
                                </form>
                                <form id="delete-faq-{{ $faq->id }}" method="POST" action="{{ route('admin-toothtalk.faq.delete', $faq->id) }}" style="display:none;">
                                    @csrf
                                    @method('DELETE')
                                </form>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            @else
                <div class="text-center py-4">
                    <i class="bi bi-question-circle display-1 text-muted"></i>
                    <h5 class="text-muted mt-3">No FAQs yet</h5>
                    <p class="text-muted">Add some frequently asked questions to help your patients.</p>
                </div>
            @endif
        </div>
    </div>

</div>

<!-- Save Settings Confirmation Modal -->
<div class="modal fade" id="saveSettingsModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content save-modal-content">
            <div class="modal-body text-center p-4">
                <div class="save-icon-wrapper mb-3">
                    <i class="bi bi-check-circle text-success"></i>
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

<!-- Add FAQ Confirmation Modal -->
<div class="modal fade" id="addFaqModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content save-modal-content">
            <div class="modal-body text-center p-4">
                <div class="save-icon-wrapper mb-3">
                    <i class="bi bi-plus-circle text-success"></i>
                </div>
                <h5 class="save-modal-title mb-2">Add FAQ</h5>
                <p class="save-modal-message mb-4" id="addFaqMessage">Are you sure you want to add this FAQ?</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-cancel" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-save" id="confirmAddFaqBtn">Add</button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Update FAQ Confirmation Modal -->
<div class="modal fade" id="updateFaqModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content save-modal-content">
            <div class="modal-body text-center p-4">
                <div class="update-icon-wrapper mb-3">
                    <i class="bi bi-check-circle text-white"></i>
                </div>
                <h5 class="save-modal-title mb-2">Update FAQ</h5>
                <p class="save-modal-message mb-4" id="updateFaqMessage">Are you sure you want to save changes to this FAQ?</p>
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
                    <i class="bi bi-exclamation-triangle text-warning"></i>
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
    /* Delete Modal Styles */
    .delete-modal-content {
        border: none;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(37, 99, 235, 0.3);
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    }

    .delete-icon-wrapper {
        width: 80px;
        height: 80px;
        margin: 0 auto;
        background: linear-gradient(135deg, #fbbf24 0%, #f59e0b 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 20px rgba(251, 191, 36, 0.4);
        animation: pulse 2s infinite;
    }

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
            box-shadow: 0 8px 20px rgba(251, 191, 36, 0.4);
        }
        50% {
            transform: scale(1.05);
            box-shadow: 0 12px 30px rgba(251, 191, 36, 0.6);
        }
    }

    .delete-icon-wrapper i {
        font-size: 2.5rem;
        color: white;
    }

    .delete-modal-title {
        font-size: 1.375rem !important;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.5rem;
    }

    .delete-modal-message {
        font-size: 1rem;
        color: #64748b;
        margin-bottom: 1.5rem;
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
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%);
        color: white;
        border: none;
        padding: 0.625rem 1.75rem;
        font-size: 0.875rem;
        font-weight: 600;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .btn-delete:hover {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(239, 68, 68, 0.4);
    }

    /* Save Modal Styles */
    .save-modal-content {
        border: none;
        border-radius: 20px;
        box-shadow: 0 20px 60px rgba(37, 99, 235, 0.3);
        background: linear-gradient(135deg, #ffffff 0%, #f8fafc 100%);
    }

    .save-icon-wrapper {
        width: 80px;
        height: 80px;
        margin: 0 auto;
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
    }

    .save-icon-wrapper i {
        font-size: 2.5rem;
        color: white;
    }

    .save-modal-title {
        font-size: 1.375rem !important;
        font-weight: 700;
        color: #1e293b;
        margin-bottom: 0.5rem;
    }

    .save-modal-message {
        font-size: 1rem;
        color: #64748b;
        margin-bottom: 1.5rem;
    }

    .btn-save {
        background: linear-gradient(135deg, #10b981 0%, #059669 100%);
        color: white;
        border: none;
        padding: 0.625rem 1.75rem;
        font-size: 0.875rem;
        font-weight: 600;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .btn-save:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(16, 185, 129, 0.4);
    }

    .update-icon-wrapper {
        width: 80px;
        height: 80px;
        margin: 0 auto;
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
    }

    .update-icon-wrapper i {
        font-size: 2.5rem;
        color: white;
    }

    .btn-update {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%);
        color: white;
        border: none;
        padding: 0.625rem 1.75rem;
        font-size: 0.875rem;
        font-weight: 600;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .btn-update:hover {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%);
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(59, 130, 246, 0.4);
    }

    /* Dark Mode Styles for Save Modal */
    [data-theme="dark"] .save-modal-content {
        background: linear-gradient(135deg, var(--dm-card-bg, #1e293b) 0%, var(--dm-bg-secondary, #0f172a) 100%) !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5) !important;
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
    }

    [data-theme="dark"] .btn-save:hover {
        background: linear-gradient(135deg, #059669 0%, #047857 100%) !important;
        color: white !important;
    }

    [data-theme="dark"] .btn-update {
        background: linear-gradient(135deg, #3b82f6 0%, #2563eb 100%) !important;
        color: white !important;
    }

    [data-theme="dark"] .btn-update:hover {
        background: linear-gradient(135deg, #2563eb 0%, #1d4ed8 100%) !important;
        color: white !important;
    }

    /* Dark Mode Styles for Delete Modal */
    [data-theme="dark"] .delete-modal-content {
        background: linear-gradient(135deg, var(--dm-card-bg, #1e293b) 0%, var(--dm-bg-secondary, #0f172a) 100%) !important;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.5) !important;
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

    [data-theme="dark"] .btn-delete {
        background: linear-gradient(135deg, #ef4444 0%, #dc2626 100%) !important;
        color: white !important;
    }

    [data-theme="dark"] .btn-delete:hover {
        background: linear-gradient(135deg, #dc2626 0%, #b91c1c 100%) !important;
        color: white !important;
    }
</style>

<script>
    (function(){
        const addBtn = document.getElementById('add-intent');
        const list = document.getElementById('intent-list');
        if (!addBtn || !list) return;

        addBtn.addEventListener('click', () => {
            const idx = list.querySelectorAll('.row').length;
            const div = document.createElement('div');
            div.className = 'row mb-2';
            div.innerHTML = `
                <div class="col-md-4">
                    <input type="text" name="quick_intents[${idx}][label]" placeholder="Chip Label" class="form-control form-control-sm">
                </div>
                <div class="col-md-8">
                    <input type="text" name="quick_intents[${idx}][value]" placeholder="Question to ask" class="form-control form-control-sm">
                </div>
            `;
            list.appendChild(div);
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

    // Add FAQ Confirmation
    function confirmAddFaq() {
        const questionInput = document.querySelector('form[action="{{ route('admin-toothtalk.faq.store') }}"] input[name="question"]');
        const question = questionInput ? questionInput.value : '';
        const message = question ? `Are you sure you want to add this FAQ?<br><strong>"${question}"</strong>` : 'Are you sure you want to add this FAQ?';
        document.getElementById('addFaqMessage').innerHTML = message;

        const addModal = new bootstrap.Modal(document.getElementById('addFaqModal'));
        addModal.show();
    }

    document.getElementById('confirmAddFaqBtn').addEventListener('click', function() {
        // Clone the visible form data to the hidden form
        const visibleForm = document.querySelector('form[action="{{ route('admin-toothtalk.faq.store') }}"]');
        const hiddenForm = document.getElementById('addFaqForm');

        // Clear existing inputs in hidden form except CSRF
        const existingInputs = hiddenForm.querySelectorAll('input:not([name="_token"])');
        existingInputs.forEach(input => input.remove());

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

        const addModal = bootstrap.Modal.getInstance(document.getElementById('addFaqModal'));
        if (addModal) {
            addModal.hide();
        }
    });

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
                    const answerInput = targetRow.querySelector('input[name="answer"]');
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
</script>
@endsection
