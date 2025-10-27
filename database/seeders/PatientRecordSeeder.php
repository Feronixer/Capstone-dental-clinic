<?php

namespace Database\Seeders;

use App\Models\PatientRecord;
use App\Models\User;
use App\Models\Appointment;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PatientRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Get some users and appointments for linking
        $users = User::where('role_id', 3)->limit(4)->get(); // Patient role
        $appointments = Appointment::limit(4)->get();

        if ($users->count() < 4) {
            $this->command->warn('Not enough users with patient role. Skipping seeder.');
            return;
        }

        $records = [
            [
                'user_id' => $users[0]->id,
                'appointment_id' => $appointments[0]->id ?? null,
                'patient_number' => '25-0101',
                'medical_history' => 'No significant medical history',
                'allergies' => 'Penicillin',
                'current_medications' => 'None',
                'chief_complaint' => 'Tooth pain on upper right molar',
                'diagnosis' => 'Dental caries requiring crown',
                'treatment_plan' => 'Emax dental crown placement'
            ],
            [
                'user_id' => $users[1]->id,
                'appointment_id' => $appointments[1]->id ?? null,
                'patient_number' => '25-0102',
                'medical_history' => 'Hypertension controlled with medication',
                'allergies' => 'None known',
                'current_medications' => 'Losartan 50mg daily',
                'chief_complaint' => 'Missing teeth in lower jaw',
                'diagnosis' => 'Partial edentulism',
                'treatment_plan' => 'Flexible denture fitting'
            ],
            [
                'user_id' => $users[2]->id,
                'appointment_id' => $appointments[2]->id ?? null,
                'patient_number' => '25-0103',
                'medical_history' => 'Diabetes Type 2',
                'allergies' => 'Latex',
                'current_medications' => 'Metformin 500mg twice daily',
                'chief_complaint' => 'Worn tooth structure on back molars',
                'diagnosis' => 'Severe tooth wear requiring crown restoration',
                'treatment_plan' => 'Zirconia crown placement'
            ],
            [
                'user_id' => $users[3]->id,
                'appointment_id' => $appointments[3]->id ?? null,
                'patient_number' => '25-0104',
                'medical_history' => 'No significant medical history',
                'allergies' => 'None',
                'current_medications' => 'Multivitamins',
                'chief_complaint' => 'Multiple missing teeth',
                'diagnosis' => 'Edentulism requiring denture',
                'treatment_plan' => 'US Plastic Denture fabrication'
            ],
        ];

        foreach ($records as $record) {
            PatientRecord::create($record);
        }
    }
}
