<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class StatusPengajuanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = [
            [
                'name' => 'Diajukan',
                'key' => 'diajukan',
                'description' => 'Pengajuan desain telah dibuat oleh pegawai.',
                'sort_order' => 1,
                'status' => 1,
            ],
            [
                'name' => 'Menunggu Validasi',
                'key' => 'menunggu_validasi',
                'description' => 'Pengajuan desain sedang menunggu proses validasi.',
                'sort_order' => 2,
                'status' => 1,
            ],
            [
                'name' => 'Diproses',
                'key' => 'diproses',
                'description' => 'Pengajuan desain telah divalidasi dan sedang diproses oleh Tim IT.',
                'sort_order' => 3,
                'status' => 1,
            ],
            [
                'name' => 'Revisi',
                'key' => 'revisi',
                'description' => 'Pengajuan desain memerlukan perbaikan atau perubahan.',
                'sort_order' => 4,
                'status' => 1,
            ],
            [
                'name' => 'Selesai',
                'key' => 'selesai',
                'description' => 'Pengajuan desain telah selesai dikerjakan.',
                'sort_order' => 5,
                'status' => 1,
            ],
            [
                'name' => 'Ditolak',
                'key' => 'ditolak',
                'description' => 'Pengajuan desain ditolak dan tidak dapat diproses.',
                'sort_order' => 6,
                'status' => 1,
            ],
            [
                'name' => 'Dibatalkan',
                'key' => 'dibatalkan',
                'description' => 'Pengajuan desain dibatalkan.',
                'sort_order' => 7,
                'status' => 1,
            ],
        ];

        foreach ($data as $item) {
            DB::table('statuspengajuan')->updateOrInsert(
                [
                    'key' => $item['key'],
                ],
                [
                    'name' => $item['name'],
                    'description' => $item['description'],
                    'sort_order' => $item['sort_order'],
                    'status' => $item['status'],
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}
