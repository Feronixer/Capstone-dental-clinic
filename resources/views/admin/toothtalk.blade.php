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
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-save"></i> Save Settings
                    </button>
                </div>
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
                <button type="submit" class="btn btn-success">
                    <i class="bi bi-plus"></i> Add FAQ
                </button>
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
                                                <button type="submit" class="btn btn-sm btn-primary me-1" title="Save">
                                                    <i class="bi bi-check"></i>
                                                </button>
                                                <a href="#" onclick="event.preventDefault();document.getElementById('delete-faq-{{ $faq->id }}').submit();"
                                                   class="btn btn-sm btn-outline-danger" title="Delete">
                                                    <i class="bi bi-trash"></i>
                                                </a>
                                            </div>
                                        </td>
                                    </tr>
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
</script>
@endsection
