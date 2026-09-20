<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ProfesiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $profesi = [
            'ADMINISTRASI',
            'ANALIS',
            'BIDAN',
            'DOKTER',
            'FARMASI',
            'PERAWAT',
            'PEREKAM MEDIS',
            'RADIOGRAFER',
            'FISIOTERAPIS',
            'PENATA ANASTESI',
            'NUTRISIONIS',
            'TERAPIS WICARA',
            'OKUPASI TERAPIS',
        ];

        foreach ($profesi as $namaProfesi) {
            DB::table('profesi')->updateOrInsert(
                [
                    'profesi' => $namaProfesi,
                ],
                [
                    'status' => 1,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}
