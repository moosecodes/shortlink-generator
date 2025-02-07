<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\Shortlink;

class InfluencerGrowthSeeder extends Seeder
{
    public function run(): void
    {
        // Ensure there is at least one shortlink before seeding clicks
        if (Shortlink::count() == 0) {
            $this->command->info('No shortlinks found. Creating a default shortlink.');

            $shortlinkId = Str::uuid();

            $userId = DB::table('users')->where('email', '=', 'premium1@moosecodes.com')->first()->id;

            $shortCode = 'moosecode';

            DB::table('shortlinks')->insert([
                'id'           => $shortlinkId,
                'user_id'      => $userId,
                'target_url'     => 'https://www.leetcode.com',
                'short_code'   => $shortCode,
                'hash'         => Str::random(10),
                'short_url'    => 'https://localhost/' . $shortCode,
                'total_clicks' => 0,
                'qr_scans'     => 0,
                'unique_clicks' => 0,
                'is_active'    => true,
                'is_premium'   => true,
                'expires_at'   => Carbon::now()->addMonths(2),
                'created_at'   => now(),
                'updated_at'   => now(),
            ]);
        } else {
            $shortlink = Shortlink::inRandomOrder()->first();
            $shortCode = $shortlink->short_code;
            $shortlinkId = $shortlink->id;
        }

        $clicks = [];

        $startDate = Carbon::now()->subDays(60); // Start tracking 60 days ago

        foreach (range(0, 29) as $day) {
            $date = $startDate->copy()->addDays($day); // Oldest first, newest last

            // 📈 **Reverse Growth Pattern (Now Increasing Over Time)**
            if ($day < 10) {
                $clickCount = rand(10, 50);
            } elseif ($day < 25) {
                $clickCount = rand(100, 300);
            } elseif ($day < 35) {
                $clickCount = rand(300, 800);
            } elseif ($day == 40 || $day == 50) {
                $clickCount = rand(1000, 2000);
            } else {
                $clickCount = rand(100, 500);
            }

            // 🚀 **Weekend Traffic Boost**
            if (in_array($date->dayOfWeek, [5, 6])) { // 5 = Friday, 6 = Saturday
                $clickCount += rand(200, 500);
            }

            for ($i = 0; $i < $clickCount; $i++) {
                $clicks[] = [
                    'id'           => Str::uuid(),
                    'shortlink_id' => $shortlinkId,
                    'ip_address'   => long2ip(mt_rand(0, 4294967295)), // Random IP
                    'referrer'     => $i % 5 === 0 ? 'https://instagram.com' : ($i % 3 === 0 ? 'https://twitter.com' : 'https://example.com'),
                    'clicked_at'   => $date,
                    'created_at'   => $date,
                    'updated_at'   => $date,
                ];
            }

            // **Batch Insert Every 5,000 Rows**
            if (count($clicks) >= 5000) {
                DB::table('clicks_over_times')->insert($clicks);
                $clicks = []; // Reset the array
            }
        }

        // Insert any remaining records
        if (!empty($clicks)) {
            DB::table('clicks_over_times')->insert($clicks);
        }

        $shortlink->total_clicks = DB::table('clicks_over_times')->where('shortlink_id', $shortlinkId)->count();
        $shortlink->unique_clicks = DB::table('unique_clicks')->where('shortlink_id', $shortlinkId)->distinct('ip_address')->count('ip_address');
        $shortlink->save();

        $this->command->info($shortlink);
        $this->command->info('✅ Influencer Growth Seeder completed: ' . $shortCode);
    }
}
