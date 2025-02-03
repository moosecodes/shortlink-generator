<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ClicksOverTimeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Fetch all existing short link IDs
        $shortlinkIds = DB::table('shortlinks')->pluck('id')->toArray();

        if (empty($shortlinkIds)) {
            $this->command->info('No shortlinks found. Seed some shortlinks first!');
            return;
        }

        $clicks = [];

        // Generate clicks over the past 30 days
        foreach (range(1, 5000) as $i) {
            $randomDate = Carbon::now()->subDays(rand(0, 30))->setHour(rand(0, 23))->setMinute(rand(0, 59))->setSecond(rand(0, 59));

            $clicks[] = [
                'id'          => Str::uuid(),
                'shortlink_id' => $shortlinkIds[array_rand($shortlinkIds)],
                'ip_address'  => long2ip(mt_rand(0, 4294967295)), // Random IP
                'referrer'    => rand(0, 1) ? 'https://example.com/' . Str::random(10) : null,
                'clicked_at'  => $randomDate,
                'created_at'  => $randomDate,
                'updated_at'  => $randomDate,
            ];
        }

        // Insert into database
        DB::table('clicks_over_times')->insert($clicks);
    }
}
