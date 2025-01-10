<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::truncate();

        Role::create(['name' => 'admin']);
        Role::create(['name' => 'merchant']);
        Role::create(['name' => 'customer']);
        Role::create(['name' => 'distributor']);
        Role::create(['name' => 'master distributor']);
        Role::create(['name' => 'employee']);
    }
}
