<?php

namespace App\Console\Commands;

use App\Models\Shortlink;
use App\Models\User;
use App\Models\UniqueClick;
use App\Models\Location;
use App\Models\ClicksOverTime;
use Illuminate\Console\Command;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Faker\Factory as Faker;

class GenerateShortlinkTestData extends Command
{
    protected $signature = 'app:generate-shortlink-test-data';
    protected $description = 'Generate test data for shortlinks with associated clicks and locations';

    public function handle()
    {
        $faker = Faker::create();

        // Get all users except the free users account
        $users = User::where('id', '!=', 999)->get();

        foreach ($users as $user) {
            // Generate 3 shortlinks for each user
            for ($i = 0; $i < 3; $i++) {
                // Create Shortlink
                $shortlink = Shortlink::create([
                    'id' => Str::uuid(),
                    'user_id' => $user->id,
                    'target_url' => $faker->url(),
                    'short_code' => Str::random(6),
                    'short_url' => 'https://short.link/' . Str::random(6),
                    'total_clicks' => 0,
                    'unique_clicks' => 0,
                    'is_active' => $faker->boolean(80),
                    'is_premium' => $faker->boolean(30),
                    'expires_at' => $faker->boolean(50) ? now()->addDays(rand(30, 365)) : null,
                ]);

                // Generate Click Data
                $this->generateSequentialClickData($shortlink, $faker);
            }
        }

        $this->info('Shortlink test data generated successfully!');
    }

    protected function generateSequentialClickData(Shortlink $shortlink, $faker)
    {
        // Generate dates for the past year
        $endDate = now();
        $startDate = $endDate->copy()->subYear();
        $dates = [];
        $currentDate = $startDate->copy();
        while ($currentDate <= $endDate) {
            $dates[] = $currentDate->format('Y-m-d');
            $currentDate->addDay();
        }

        $totalClicks = 0;
        $dailyClicks = [];

        // Generate sequential daily clicks with more realistic distribution
        foreach ($dates as $date) {
            // Use a more natural distribution of clicks
            $dailyClickCount = $this->generateNaturalClickCount($faker);
            $totalClicks += $dailyClickCount;
            $dailyClicks[$date] = $dailyClickCount;
        }

        // Update shortlink click counts
        $shortlink->update([
            'total_clicks' => $totalClicks,
            'unique_clicks' => 0,
        ]);

        // Generate unique clicks and clicks over time
        $uniqueIpAddresses = [];
        foreach ($dailyClicks as $date => $clickCount) {
            for ($i = 0; $i < $clickCount; $i++) {
                $ipAddress = $this->generateUniqueIpAddress($uniqueIpAddresses);
                $uniqueIpAddresses[] = $ipAddress;

                // Create Unique Click
                $uniqueClick = UniqueClick::create([
                    'id' => Str::uuid(),
                    'shortlink_id' => $shortlink->id,
                    'ip_address' => $ipAddress,
                    'device' => $faker->randomElement(['Mobile', 'Desktop', 'Tablet']),
                    'browser' => $faker->randomElement(['Chrome', 'Firefox', 'Safari', 'Edge']),
                    'referrer' => $faker->boolean(50) ? $faker->url() : null,
                    'user_agent' => $faker->userAgent(),
                ]);

                // Create Location for this Unique Click
                Location::create([
                    'id' => Str::uuid(),
                    'user_id' => $shortlink->user_id,
                    'ip_address' => $ipAddress,
                    'driver' => 'maxmind',
                    'country_name' => $faker->country(),
                    'country_code' => $faker->countryCode(),
                    'region_name' => $faker->state(),
                    'city_name' => $faker->city(),
                    'latitude' => $faker->latitude(),
                    'longitude' => $faker->longitude(),
                    'timezone' => $faker->timezone(),
                ]);

                // Create Clicks Over Time with specific date
                ClicksOverTime::create([
                    'id' => Str::uuid(),
                    'shortlink_id' => $shortlink->id,
                    'ip_address' => $ipAddress,
                    'referrer' => $faker->boolean(50) ? $faker->url() : null,
                    'clicked_at' => Carbon::parse($date)->setTime(
                        rand(0, 23),
                        rand(0, 59),
                        rand(0, 59)
                    ),
                ]);
            }
        }

        // Update unique clicks count
        $shortlink->update([
            'unique_clicks' => count($uniqueIpAddresses),
        ]);
    }

    protected function generateNaturalClickCount($faker)
    {
        // Generate a more natural distribution of clicks
        // Uses a combination of random and probabilistic approach
        $baseClick = $faker->numberBetween(0, 20);
        $variation = $faker->boolean(30) ? $faker->numberBetween(0, 50) : 0;
        $peakDay = $faker->boolean(10) ? $faker->numberBetween(50, 200) : 0;

        return $baseClick + $variation + $peakDay;
    }

    protected function generateUniqueIpAddress(&$existingIps)
    {
        do {
            $ip = sprintf(
                '%d.%d.%d.%d',
                mt_rand(1, 255),
                mt_rand(0, 255),
                mt_rand(0, 255),
                mt_rand(0, 255)
            );
        } while (in_array($ip, $existingIps));

        return $ip;
    }
}
