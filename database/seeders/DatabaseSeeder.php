<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        User::factory()->create([
            'id' => 999,
            'name' => 'Free Users',
            'email' => 'freeusers@moosecodes.com',
        ]);
        User::factory()->create([
            'id' => env('TEST_USER_ID', 1000),
            'name' => 'Premium User 1',
            'email' => 'premium1@moosecodes.com',
        ]);

        User::factory()->create([
            'name' => 'Premium User 2',
            'email' => 'premium2@moosecodes.com',
        ]);

        User::factory(3)->create();
    }
}
