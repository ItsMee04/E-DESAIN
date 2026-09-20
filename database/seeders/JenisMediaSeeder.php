<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class JenisMediaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            'STICKER',
            'LEAFLET',
            'AKRILIK',
            'LEMBAR BALIK',
            'BROSUR',
            'BANNER',
            'BALIHO',
            'POSTER',
        ];

        foreach ($data as $item) {
            DB::table('jenismedia')->updateOrInsert(
                [
                    'jenismedia' => $item,
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
