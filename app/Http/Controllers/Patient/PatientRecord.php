<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\PatientRecord as PatientRecordModel;
use App\Models\PatientHistory;
use App\Models\ProgressNote;

class PatientRecord extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $userId = Auth::id();

        // Get all records for the authenticated patient with histories and progress notes
        $records = PatientRecordModel::where('user_id', $userId)
            ->where('sent_to_patient', true) // Only show records that have been sent
            ->with(['patientHistories' => function($query) {
                $query->where('sent_to_patient', true) // Only show histories that have been sent
                      ->orderBy('visit_date', 'desc');
            }, 'progressNotes' => function($query) {
                $query->orderBy('note_date', 'desc');
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        return view("patient.record", compact('records'));
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $userId = Auth::id();

        // Get the record and ensure it belongs to the authenticated patient
        $record = PatientRecordModel::where('id', $id)
            ->where('user_id', $userId)
            ->where('sent_to_patient', true)
            ->with(['patientHistories' => function($query) {
                $query->where('sent_to_patient', true)
                      ->orderBy('visit_date', 'desc');
            }, 'progressNotes' => function($query) {
                $query->orderBy('note_date', 'desc');
            }])
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'record' => $record
        ]);
    }

    /**
     * Download/Print the specified record
     */
    public function download($id)
    {
        $userId = Auth::id();

        // Get the record and ensure it belongs to the authenticated patient
        $record = PatientRecordModel::where('id', $id)
            ->where('user_id', $userId)
            ->firstOrFail();

        // Return a print-friendly view
        return view('patient.pdf.record', compact('record'));
    }

    /**
     * Get all records for the authenticated patient
     */
    public function getRecords()
    {
        $userId = Auth::id();

        $records = PatientRecordModel::where('user_id', $userId)
            ->where('sent_to_patient', true)
            ->with(['patientHistories' => function($query) {
                $query->where('sent_to_patient', true)
                      ->orderBy('visit_date', 'desc');
            }, 'progressNotes' => function($query) {
                $query->orderBy('note_date', 'desc');
            }])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json([
            'success' => true,
            'records' => $records
        ]);
    }

    /**
     * Get a specific patient history
     */
    public function showHistory($id)
    {
        $userId = Auth::id();

        // Get the history and ensure it belongs to a record of the authenticated patient
        $history = PatientHistory::where('id', $id)
            ->where('sent_to_patient', true)
            ->whereHas('patientRecord', function($query) use ($userId) {
                $query->where('user_id', $userId)
                      ->where('sent_to_patient', true);
            })
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'history' => $history
        ]);
    }

    /**
     * Download/Print a specific patient history
     */
    public function downloadHistory($id)
    {
        $userId = Auth::id();

        // Get the history and ensure it belongs to a record of the authenticated patient
        $history = PatientHistory::where('id', $id)
            ->where('sent_to_patient', true)
            ->whereHas('patientRecord', function($query) use ($userId) {
                $query->where('user_id', $userId)
                      ->where('sent_to_patient', true);
            })
            ->with('patientRecord.user')
            ->firstOrFail();

        return view('patient.pdf.history', compact('history'));
    }

    /**
     * Get a specific progress note
     */
    public function showProgressNote($id)
    {
        $userId = Auth::id();

        // Get the note and ensure it belongs to a record of the authenticated patient
        $note = ProgressNote::where('id', $id)
            ->whereHas('patientRecord', function($query) use ($userId) {
                $query->where('user_id', $userId)
                      ->where('sent_to_patient', true);
            })
            ->firstOrFail();

        return response()->json([
            'success' => true,
            'note' => $note
        ]);
    }
}
