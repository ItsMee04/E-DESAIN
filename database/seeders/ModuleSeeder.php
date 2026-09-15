<?php

namespace Database\Seeders;

use App\Models\Master\Module;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ModuleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // =========================
        // PARENT MODULE
        // =========================

        Module::create([
            'parent_id' => null,
            'name' => 'Dashboard',
            'key' => 'dashboard',
            'route' => '/dashboard',
            'icon' => 'LayoutDashboard',
            'sort_order' => 1,
            'status' => 1,
        ]);

        Module::create([
            'parent_id' => null,
            'name' => 'Master Data',
            'key' => 'master',
            'route' => null,
            'icon' => 'Database',
            'sort_order' => 2,
            'status' => 1,
        ]);

        Module::create([
            'parent_id' => null,
            'name' => 'Management User',
            'key' => 'management_user',
            'route' => null,
            'icon' => 'Users',
            'sort_order' => 3,
            'status' => 1,
        ]);

        // =========================
        // AMBIL PARENT
        // =========================

        $master = Module::where('key', 'master')->first();
        $managementUser = Module::where('key', 'management_user')->first();

        // =========================
        // MASTER DATA
        // =========================

        Module::create([
            'parent_id' => $master->id,
            'name' => 'Profesi',
            'key' => 'profesi',
            'route' => '/master/profesi',
            'icon' => null,
            'sort_order' => 1,
            'status' => 1,
        ]);

        Module::create([
            'parent_id' => $master->id,
            'name' => 'Jenis Kelamin',
            'key' => 'jeniskelamin',
            'route' => '/master/jeniskelamin',
            'icon' => null,
            'sort_order' => 2,
            'status' => 1,
        ]);

        Module::create([
            'parent_id' => $master->id,
            'name' => 'Agama',
            'key' => 'agama',
            'route' => '/master/agama',
            'icon' => null,
            'sort_order' => 3,
            'status' => 1,
        ]);

        Module::create([
            'parent_id' => $master->id,
            'name' => 'Jenis Media',
            'key' => 'jenismedia',
            'route' => '/master/jenismedia',
            'icon' => null,
            'sort_order' => 4,
            'status' => 1,
        ]);

        Module::create([
            'parent_id' => $master->id,
            'name' => 'Unit',
            'key' => 'unit',
            'route' => '/master/unit',
            'icon' => null,
            'sort_order' => 5,
            'status' => 1,
        ]);

        // =========================
        // MANAGEMENT USER
        // =========================

        Module::create([
            'parent_id' => $managementUser->id,
            'name' => 'Pegawai',
            'key' => 'pegawai',
            'route' => '/management-user/pegawai',
            'icon' => null,
            'sort_order' => 1,
            'status' => 1,
        ]);

        Module::create([
            'parent_id' => $managementUser->id,
            'name' => 'Pengguna',
            'key' => 'pengguna',
            'route' => '/management-user/pengguna',
            'icon' => null,
            'sort_order' => 2,
            'status' => 1,
        ]);
    }
}
