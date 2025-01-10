<?php

namespace Database\Seeders;

use App\Models\Contact;
use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Setting::truncate();
        Setting::create(['name' => 'brahmastras', 'value' => null,'image'=>'']);
        Setting::create(['name' => 'timing', 'value' => null,'image'=>'']);
        Setting::create(['name' => 'email', 'value' => null,'image'=>'']);
    }
}
