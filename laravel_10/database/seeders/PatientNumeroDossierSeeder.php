<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PatientNumeroDossierSeeder extends Seeder
{
    public function run(): void
    {
        $patients = DB::table('patient')
            ->select('idenregistremetpatient')
            ->whereNull('numdossier')
            ->get();

        foreach ($patients as $patient) {

            do {
                $numDossier = 'DS' . random_int(100000, 999999);

                $exists = DB::table('patient')
                    ->where('numdossier', $numDossier)
                    ->exists();

            } while ($exists);

            DB::table('patient')
                ->where(
                    'idenregistremetpatient',
                    $patient->idenregistremetpatient
                )
                ->update([
                    'numdossier' => $numDossier,
                ]);
        }
    }
}