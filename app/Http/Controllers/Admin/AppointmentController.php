<?php

namespace App\Http\Controllers\Admin;

use App;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\BlockedSlot;
use App\Models\BlockedTime;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Config;

class AppointmentController extends Controller
{
    public function index()
{
    // fetch only users with patient role
    $patients = User::whereHas('role', function($q) {
        $q->where('role', 'patient');
    })->get();

    $procedures = array_keys(config('procedures.durations')) ?? [];

    return view('admin.appointments.appointment', compact('patients','procedures'));
}
    // ✅ Display appointments + blocked slots on calendar
    public function events()
{
    $appointments = Appointment::with('patient')->get()->map(function ($appt) {
        return [
            'id'    => $appt->id,
            'title' => ($appt->patient ? $appt->patient->name : 'Appointment') . ' (' . ($appt->status ?? 'pending') . ')',
            'start' => Carbon::parse($appt->start_time)->setTimezone(config('app.timezone'))->format('Y-m-d\TH:i:s'),
            'end'   => Carbon::parse($appt->end_time)->setTimezone(config('app.timezone'))->format('Y-m-d\TH:i:s'),
            'color' => '#3788d8',
            'extendedProps' => [
                'type'        => 'appointment',
                'patient_id'  => $appt->patient_id,
                'patient_name'=> $appt->patient ? $appt->patient->name : '',
                'service'     => $appt->service,
                'notes'       => $appt->notes,
                'status'      => $appt->status ?? (
                    now()->lt($appt->start_time) ? 'pending' :
                    (now()->between($appt->start_time, $appt->end_time) ? 'ongoing' : 'done')
                ),
            ],
        ];
    });

    $blocked = BlockedSlot::all()->map(function ($b) {
        $start = Carbon::parse($b->date.' '.$b->start_time)->setTimezone(config('app.timezone'));
        $end   = Carbon::parse($b->date.' '.$b->end_time)->setTimezone(config('app.timezone'));
        return [
            'id'      => 'blocked-'.$b->id,
            'title'   => 'Blocked',
            'start'   => $start->format('Y-m-d\TH:i:s'),
            'end'     => $end->format('Y-m-d\TH:i:s'),
            'allDay'  => false,
            'display' => 'background',
            'overlap' => false,
            'color'   => '#ffb3b3',
            'extendedProps' => [
                'type'  => 'blocked',
                'notes' => $b->notes,
            ],
        ];
    });

    return response()->json($appointments->merge($blocked));
}


  private function minutesForService(string $service): int
{
    $cfg = config('procedures.durations');
    if (!isset($cfg[$service])) {
        return 60; // sensible default
    }
    // Prefer max if set, else min, else 60
    return $cfg[$service]['max'] ?? $cfg[$service]['min'] ?? 60;
}

private function roundTo30(Carbon $dt): Carbon
{
    $minute = (int) $dt->format('i');
    $down = $minute - ($minute % 30);
    if ($minute % 30 === 0) {
        return $dt->copy()->minute($down)->second(0);
    }
    // round to nearest; if you want always floor, remove this branch
    $up = $down + 30;
    $toUp = abs($minute - $up);
    $toDown = abs($minute - $down);
    $rounded = $toUp < $toDown ? $up : $down;
    return $dt->copy()->minute($rounded)->second(0);
}

    // ✅ Create new appointment
 public function store(Request $request)
{
    $durationMap = config('procedures.durations') ?? [];
    $allowed = array_keys($durationMap);

    $request->validate([
        'patient_id'       => 'required|exists:users,id',
        'service'          => ['required','string','in:'.implode(',', $allowed)],
        'date'             => 'required|date|after_or_equal:today',
        'start_time_clock' => 'required|date_format:H:i',
        'notes'            => 'nullable|string',
    ]);

    $start = Carbon::parse($request->date.' '.$request->start_time_clock.':00');

    $rule = $durationMap[$request->service] ?? null;
    // use 'max' when given, else 'min', else 60; for week-long mark +7d
    if ($rule && isset($rule['days'])) {
        $end = (clone $start)->addDays($rule['days']); // Crowns to dentures
    } else {
        $minutes = $rule['max'] ?? ($rule['min'] ?? 60);
        $end = (clone $start)->addMinutes($minutes);
    }
    //check for overlaps with blocked slots
    $overlap = BlockedSlot::where('date', $start->toDateString())
    ->where(function($q) use ($start, $end) {
        $q->where(function($q2) use ($start, $end) {
            $q2->where('start_time', '<', $end->format('H:i:s'))
               ->where('end_time',   '>', $start->format('H:i:s'));
        });
    })->exists();

    if ($overlap) {
    return back()->withErrors(['start_time_clock' => 'This time is blocked. Please choose another slot.'])->withInput();
    }
    Appointment::create([
        'patient_id' => $request->patient_id,
        'service'    => $request->service,
        'notes'      => $request->notes,
        'start_time' => $start,
        'end_time'   => $end,
    ]);

    return back()->with('success', 'Appointment created successfully!');
}

    // ✅ Delete appointment
    public function destroy($id)
    {
        Appointment::findOrFail($id)->delete();
        return response()->json(['success'
        ]);
    }
    public function block(Request $request)
{
    $request->validate([
        'date'       => 'required|date',
        'start_time' => 'required|date_format:H:i',
        'end_time'   => 'required|date_format:H:i|after:start_time',
        'notes'      => 'nullable|string',
    ]);

    BlockedSlot::create([
        'date'       => $request->date,
        'start_time' => $request->start_time,
        'end_time'   => $request->end_time,
        'notes'      => $request->notes,
    ]);

    return back()->with('success', 'Time slot blocked successfully!');
}


public function update(Request $request, $id)
{
    $durationMap = config('procedures.durations') ?? [];
    $allowed = array_keys($durationMap);

    $request->validate([
        'service'    => ['required','string','in:'.implode(',', $allowed)],
        'start_time' => 'required|date',             // datetime-local from modal
        'end_time'   => 'nullable|date|after:start_time',
        'notes'      => 'nullable|string',
    ]);

    $appt = Appointment::findOrFail($id);
    $start = Carbon::parse($request->start_time);

    $end = $request->filled('end_time')
        ? Carbon::parse($request->end_time)
        : (function() use ($durationMap, $request, $start) {
            $rule = $durationMap[$request->service] ?? null;
            $minutes = 30;
            if ($rule) $minutes = isset($rule['days']) ? 30 : ($rule['max'] ?? 60);
            return (clone $start)->addMinutes($minutes);
        })();

    $appt->update([
        'service'    => $request->service,
        'start_time' => $start,
        'end_time'   => $end,
        'notes'      => $request->notes,
    ]);

    return redirect()->back()->with('success', 'Appointment rescheduled successfully.');
}



private function overlapsExistingAppointments(Carbon $start, Carbon $end, ?int $excludeId = null): bool
{
    $q = Appointment::query()
        ->where('start_time', '<', $end)   // starts before our end
        ->where('end_time',   '>', $start); // ends after our start

    if ($excludeId) {
        $q->where('id', '!=', $excludeId);
    }
    return $q->exists();
}

private function overlapsBlockedSlots(Carbon $start, Carbon $end): bool
{
    $date = $start->toDateString(); // assuming block is per-day
    return BlockedSlot::query()
        ->whereDate('date', $date)
        ->whereRaw('? < TIME(CONCAT(date, " ", end_time))', [$start->toDateTimeString()])
        ->whereRaw('? > TIME(CONCAT(date, " ", start_time))', [$end->toDateTimeString()])
        ->exists();
}
}
