<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Shortlink;

class MustafaGraphSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure there are Shortlink records before seeding clicks
        if (Shortlink::count() == 0) {
            $this->command->info('No shortlinks found. Seeding shortlinks first.');
            Shortlink::factory(10)->create(); // Seed 10 shortlinks if none exist
        }

        $shortlinkId = Shortlink::inRandomOrder()->first()->id; // Use any existing shortlink

        // Define the "Mustafa" pattern by day index (X-axis) and click count (Y-axis)
        $mustafa_pattern = [
            // 'M'
            1 => 15,
            2 => 30,
            3 => 45,
            4 => 60,
            5 => 45,
            6 => 30,
            7 => 15,
            // 'U'
            10 => 20,
            11 => 25,
            12 => 30,
            13 => 35,
            14 => 40,
            15 => 35,
            16 => 30,
            17 => 25,
            18 => 20,
            // 'S'
            20 => 50,
            21 => 30,
            22 => 20,
            23 => 50,
            24 => 70,
            25 => 80,
            // 'T'
            28 => 90,
            29 => 80,
            30 => 70,
            31 => 60,
            // 'A'
            35 => 40,
            36 => 50,
            37 => 60,
            38 => 70,
            39 => 80,
            40 => 90,
            41 => 80,
            42 => 70,
            43 => 60,
            // 'F'
            45 => 60,
            46 => 70,
            47 => 80,
            48 => 50,
            49 => 40,
            // 'A'
            52 => 40,
            53 => 50,
            54 => 60,
            55 => 70,
            56 => 80,
            57 => 90,
            58 => 80,
            59 => 70,
            60 => 60
        ];

        $clicks = [];

        foreach ($mustafa_pattern as $day => $clickCount) {
            $date = Carbon::now()->subDays(60 - $day); // Start 60 days back and move forward

            for ($i = 0; $i < $clickCount; $i++) {
                $clicks[] = [
                    'id'           => Str::uuid(),
                    'shortlink_id' => $shortlinkId,
                    'ip_address'   => long2ip(mt_rand(0, 4294967295)), // Random IP
                    'referrer'     => null,
                    'clicked_at'   => $date->copy()->setHour(rand(0, 23))->setMinute(rand(0, 59))->setSecond(rand(0, 59)),
                    'created_at'   => $date,
                    'updated_at'   => $date,
                ];
            }
        }

        // Insert the structured click data
        DB::table('clicks_over_times')->insert($clicks);

        $this->command->info('Mustafa graph seeder completed!');
    }
}
