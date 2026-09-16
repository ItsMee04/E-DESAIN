<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisKelaminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'LAKI-LAKI',
            'PEREMPUAN'
        ];

        foreach ($data as $dataitem) {
            DB::table('jeniskelamin')->updateOrInsert(
                [
                    'jeniskelamin' => $dataitem,
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
