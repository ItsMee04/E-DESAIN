<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'Administrator',
                'key' => 'admin',
            ],
            [
                'name' => 'Pegawai',
                'key' => 'user',
            ],
            [
                'name' => 'PKRS',
                'key' => 'pkrs',
            ],
        ];

        foreach ($roles as $role) {
            DB::table('role')->updateOrInsert(
                [
                    'key' => $role['key'],
                ],
                [
                    'name' => $role['name'],
                    'status' => 1,
                    'updated_at' => now(),
                    'created_at' => now(),
                ]
            );
        }
    }
}
