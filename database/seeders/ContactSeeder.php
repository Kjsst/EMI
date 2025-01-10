<?php

namespace Database\Seeders;

use App\Models\Contact;
use Illuminate\Database\Seeder;

class ContactSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Contact::create(['name' => 'contact_number', 'value' => '919216248651']);
        Contact::create(['name' => 'timing', 'value' => '10AM TO 6PM']);
        Contact::create(['name' => 'email', 'value' => 'support@kjsst.com']);
        Contact::create(['name' => 'address', 'value' => 'Lavaniya place 1 floor 06, Dholpur, Rajasthan India 328001']);
    }
}
