<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */

    public function run(): void
    {
        $user = DB::table('users')
            ->join('pegawai', 'users.pegawai_id', '=', 'pegawai.id')
            ->where('pegawai.nip', 'IT-RSUWH-001')
            ->select('users.id')
            ->first();

        if (!$user) {
            throw new \Exception(
                'User IT RSUWH belum tersedia.'
            );
        }

        $modules = DB::table('module')
            ->where('status', 1)
            ->get();

        foreach ($modules as $module) {
            DB::table('permission')->updateOrInsert(
                [
                    'user_id' => $user->id,
                    'module_id' => $module->id,
                ],
                [
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}
