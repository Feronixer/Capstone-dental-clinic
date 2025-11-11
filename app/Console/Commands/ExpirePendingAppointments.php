<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use Carbon\Carbon;

class ExpirePendingAppointments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:expire-pending';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Automatically cancel pending appointments whose date has passed without status update';

    /**
     * Execute the console command.
     */
    public function handle(): int
    {
        $now = Carbon::now('Asia/Manila');

        $appointments = Appointment::where('status', 'Pending')
            ->where('start_datetime', '<', $now)
            ->get();

        $updatedCount = 0;

        foreach ($appointments as $appointment) {
            $note = trim((string) $appointment->notes);
            $autoNote = 'Auto-cancelled due to no status update on ' . $now->format('Y-m-d H:i');

            if (stripos($note, 'Auto-cancelled due to no status update') === false) {
                $note = $note ? $note . "\n" . $autoNote : $autoNote;
            }

            $appointment->status = 'Cancelled';
            $appointment->notes = $note;
            $appointment->save();

            $updatedCount++;
        }

        if ($updatedCount > 0) {
            $this->info("Cancelled {$updatedCount} pending appointment(s) that passed without status update.");
        } else {
            $this->info('No pending appointments needed auto-cancellation.');
        }

        return Command::SUCCESS;
    }
}

