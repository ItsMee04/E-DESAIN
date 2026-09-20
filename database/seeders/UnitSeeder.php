<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UnitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $units = [
            'ADMINISTRASI',
            'AS SYIFA',
            'ATEM',
            'AZ ZAETUN',
            'AZ ZAHRA',
            'BINA ROHANI',
            'CS IGD',
            'CS POLI',
            'CSSD & LAUNDRY',
            'FARMASI',
            'FILING',
            'GIZI',
            'HUMAS',
            'IBS',
            'ICU',
            'IGD',
            'KAMAR BERSALIN',
            'KESLING',
            'KEUANGAN',
            'MATERNAL',
            'PERINA',
            'POLIKLINIK',
            'PSDI',
            'RADIOLOGI',
            'REKAM MEDIS',
            'RUMAH TANGGA',
            'SECURITY',
            'TPPRI',
        ];

        foreach ($units as $unit) {
            DB::table('unit')->updateOrInsert(
                ['unit' => $unit],
                [
                    'status' => 1,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]
            );
        }
    }
}
