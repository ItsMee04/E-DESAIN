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
                'name' => 'Menunggu Verifikasi IT',
                'key' => 'menunggu_verifikasi_it',
                'description' => 'Pengajuan desain sedang menunggu proses verifikasi dari Tim IT.',
                'sort_order' => 2,
                'status' => 1,
            ],
            [
                'name' => 'Menunggu ACC PKRS',
                'key' => 'menunggu_acc_pkrs',
                'description' => 'Pengajuan desain sedang menunggu proses persetujuan dari PKRS.',
                'sort_order' => 3,
                'status' => 1,
            ],
            [
                'name' => 'Diproses',
                'key' => 'diproses',
                'description' => 'Pengajuan desain telah divalidasi oleh Tim IT dan sedang diproses.',
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
                'name' => 'Siap Diambil',
                'key' => 'siap_diambil',
                'description' => 'Pengajuan desain telah siap diambil oleh pegawai.',
                'sort_order' => 6,
                'status' => 1,
            ],
            [
                'name' => 'Ditolak',
                'key' => 'ditolak',
                'description' => 'Pengajuan desain ditolak dan tidak dapat diproses.',
                'sort_order' => 7,
                'status' => 1,
            ],
            [
                'name' => 'Dibatalkan',
                'key' => 'dibatalkan',
                'description' => 'Pengajuan desain dibatalkan.',
                'sort_order' => 8,
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
