<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pegawai = DB::table('pegawai')
            ->where('nip', 'IT-RSUWH-001')
            ->first();

        if (!$pegawai) {
            throw new \Exception(
                'Data pegawai IT RSUWH belum tersedia.'
            );
        }

        DB::table('users')->updateOrInsert(
            [
                'username' => 'itrsuwh',
            ],
            [
                'pegawai_id' => $pegawai->id,
                'email' => 'itrsuwh@gmail.com',
                'password' => Hash::make('123'),
                'email_verified_at' => now(),
                'status' => 1,
                'updated_at' => now(),
                'created_at' => now(),
            ]
        );
    }
}
