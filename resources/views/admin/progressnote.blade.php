@extends('layout.admin.app')

@section('content')
<div class="container-fluid">
    <h1 class="mb-4">Progress Notes</h1>
<div class="d-flex flex-column flex-lg-row gap-4">
    {{-- Sidebar --}}
    <aside class="w-15 w-lg-25">
        <div class="sub-nav">
            <a href="{{ route('admin-post-procedural') }}" class="sub-nav-link">Form List</a>
            <a href="{{ route('admin-patientrecord') }}" class="sub-nav-link">Patient Record</a>
            <a href="{{ route('admin-patienthistory') }}" class="sub-nav-link ">Patient History</a>
            <a href="{{ route('admin-progressnote') }}" class="sub-nav-link active">Progress Note</a>
        </div>
    </aside>


        {{-- Main --}}
        <section class="col-12 col-lg-9">
            <div class="card">
                <div class="card-header">
                    <strong>Progress Notes Form</strong>
                </div>
                <div class="card-body">

                        @csrf

                        <div class="table-responsive mb-3">
                            <table class="table table-bordered align-middle" id="notesTable">
                                <thead class="table-light">
                                    <tr>
                                        <th style="width: 12rem;">Date</th>
                                        <th>Progress Note</th>
                                        <th style="width: 14rem;">Oral Hygiene</th>
                                        <th style="width: 16rem;">Conformed Practices</th>
                                        <th style="width: 4rem;" class="text-center">—</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @for ($i = 0; $i < 6; $i++)
                                        <tr>
                                            <td>
                                                <input type="date" name="date[]" class="form-control form-control-sm" />
                                            </td>
                                            <td>
                                                <input type="text" name="progress_note[]" class="form-control form-control-sm" placeholder="e.g., Scaling completed, no bleeding" />
                                            </td>
                                            <td>
                                                <select name="oral_hygiene[]" class="form-select form-select-sm">
                                                    <option value="">Select…</option>
                                                    <option>Good</option>
                                                    <option>Fair</option>
                                                    <option>Poor</option>
                                                </select>
                                            </td>
                                            <td>
                                                <input type="text" name="conformed_practices[]" class="form-control form-control-sm" placeholder="e.g., Brushing 2x/day, flossing nightly" />
                                            </td>
                                            <td class="text-center">
                                                <button type="button" class="btn btn-sm btn-outline-danger remove-row" title="Remove row">&times;</button>
                                            </td>
                                        </tr>
                                    @endfor
                                </tbody>
                            </table>
                        </div>

                        <div class="d-flex gap-2 mb-4">
                            <button type="button" id="addRow" class="btn btn-outline-primary btn-sm">
                                + Add Row
                            </button>
                            <small class="text-muted ms-1 align-self-center">You can add or remove rows as needed.</small>
                        </div>

                        <div class="mb-3">
                            <label for="otherNotes" class="form-label">Other Notes</label>
                            <textarea id="otherNotes" name="otherNotes" class="form-control" rows="3" placeholder="Additional observations, instructions, or follow-ups…"></textarea>
                        </div>

                        <div class="row g-3 mb-4">
                            <div class="col-sm-6">
                                <label for="sentToPatient" class="form-label">Sent To</label>
                                <input type="text" id="sentToPatient" name="sentToPatient" class="form-control" placeholder="Patient Name*" />
                            </div>
                            <div class="col-sm-6">
                                <label for="sentToMethod" class="form-label">Method</label>
                                <select id="sentToMethod" name="sentToMethod" class="form-select">
                                    <option value="">Select…</option>
                                    <option>Email</option>
                                    <option>Patient Portal</option>
                                </select>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">Cancel</a>
                            <button type="submit" class="btn btn-success">Send</button>
                        </div>
                    </form>
                </div>
            </div>

            {{-- Optional: validation feedback --}}
            {{-- @if ($errors->any())
                <div class="alert alert-danger mt-3">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif --}}
        </section>
    </div>
</div>

{{-- Minimal JS for add/remove row --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const table = document.getElementById('notesTable').querySelector('tbody');
    const addBtn = document.getElementById('addRow');

    addBtn.addEventListener('click', function () {
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td><input type="date" name="date[]" class="form-control form-control-sm" /></td>
            <td><input type="text" name="progress_note[]" class="form-control form-control-sm" placeholder="e.g., Scaling completed, no bleeding" /></td>
            <td>
                <select name="oral_hygiene[]" class="form-select form-select-sm">
                    <option value="">Select…</option>
                    <option>Good</option>
                    <option>Fair</option>
                    <option>Poor</option>
                </select>
            </td>
            <td><input type="text" name="conformed_practices[]" class="form-control form-control-sm" placeholder="e.g., Brushing 2x/day, flossing nightly" /></td>
            <td class="text-center">
                <button type="button" class="btn btn-sm btn-outline-danger remove-row" title="Remove row">&times;</button>
            </td>
        `;
        table.appendChild(tr);
    });

    document.addEventListener('click', function (e) {
        if (e.target.classList.contains('remove-row')) {
            const row = e.target.closest('tr');
            if (row && table.rows.length > 1) row.remove();
        }
    });
});
</script>
@endpush
@endsection
