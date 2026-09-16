<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PegawaiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $profesi = DB::table('profesi')
            ->where('profesi', 'IT')
            ->first();

        $jeniskelamin = DB::table('jeniskelamin')
            ->where('jeniskelamin', 'LAKI-LAKI')
            ->first();

        $agama = DB::table('agama')
            ->where('agama', 'ISLAM')
            ->first();

        if (!$profesi || !$jeniskelamin || !$agama) {
            throw new \Exception(
                'Data master profesi, jenis kelamin, atau agama belum tersedia.'
            );
        }

        DB::table('pegawai')->updateOrInsert(
            [
                'nip' => 'IT-RSUWH-001',
            ],
            [
                'nama' => 'IT RSUWH',
                'tempat' => 'Purwokerto',
                'tanggal' => '1990-01-01',
                'jeniskelamin_id' => $jeniskelamin->id,
                'alamat' => 'RSU Wiradadi Husada',
                'agama_id' => $agama->id,
                'profesi_id' => $profesi->id,
                'kontak' => null,
                'image' => null,
                'status' => 1,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }
}
