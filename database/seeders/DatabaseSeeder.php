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
        // User::factory(10)->create();

        User::factory()->create([
            'name' => 'Test User 1111',
            'email' => 'test@example.com',
        ]);

        User::factory()->create([
            'name' => 'Test User 2222',
            'email' => 'test2222@example.com',
        ]);
    }
}
