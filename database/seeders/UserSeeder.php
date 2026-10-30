<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        DB::transaction(function () {

            // =========================================
            // 1. AMBIL DATA ROLE
            // =========================================

            $roleAdmin = DB::table('role')
                ->where('key', 'admin')
                ->first();

            $roleUser = DB::table('role')
                ->where('key', 'user')
                ->first();

            if (!$roleAdmin || !$roleUser) {
                throw new \Exception(
                    'Data role admin atau user belum tersedia.'
                );
            }

            // =========================================
            // 2. AKUN KHUSUS IT RSUWH
            // =========================================

            $pegawaiIT = DB::table('pegawai')
                ->where('nip', 'IT-RSUWH-001')
                ->first();

            if (!$pegawaiIT) {
                throw new \Exception(
                    'Data pegawai IT RSUWH belum tersedia.'
                );
            }

            $userIT = DB::table('users')
                ->where('pegawai_id', $pegawaiIT->id)
                ->first();

            if (!$userIT) {
                throw new \Exception(
                    'Akun pengguna IT RSUWH belum tersedia.'
                );
            }

            // Tetapkan role administrator
            $updateIT = [
                'role_id' => $roleAdmin->id,
                'updated_at' => now(),
            ];

            // Password khusus IT
            if (empty($userIT->password)) {
                $updateIT['password'] = Hash::make('123');
            }

            DB::table('users')
                ->where('id', $userIT->id)
                ->update($updateIT);

            // =========================================
            // 3. ROLE DEFAULT SELURUH PEGAWAI
            // =========================================

            DB::table('users')
                ->join(
                    'pegawai',
                    'users.pegawai_id',
                    '=',
                    'pegawai.id'
                )
                ->where('pegawai.status', 1)
                ->where('users.status', 1)
                ->whereNull('users.role_id')
                ->where('pegawai.id', '!=', $pegawaiIT->id)
                ->update([
                    'users.role_id' => $roleUser->id,
                    'users.updated_at' => now(),
                ]);

            // =========================================
            // 4. PASSWORD DEFAULT SELURUH PEGAWAI
            // =========================================

            $users = DB::table('users')
                ->join(
                    'pegawai',
                    'users.pegawai_id',
                    '=',
                    'pegawai.id'
                )
                ->where('pegawai.status', 1)
                ->where('users.status', 1)
                ->where(function ($query) {
                    $query->whereNull('users.password')
                        ->orWhere('users.password', '');
                })
                ->where('pegawai.id', '!=', $pegawaiIT->id)
                ->select('users.id')
                ->get();

            $ids = $users->pluck('id')->all();

            if (!empty($ids)) {
                $passwordHash = Hash::make('1234567890');

                DB::table('users')
                    ->whereIn('id', $ids)
                    ->update([
                        'password' => $passwordHash,
                        'updated_at' => now(),
                    ]);
            }
        });
    }
}
