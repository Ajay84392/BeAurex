<?php

namespace Database\Seeders;

use App\Models\Business;
use App\Models\RewardRequest;
use Illuminate\Database\Seeder;

class PendingRewardSeeder extends Seeder
{
    public function run(): void
    {
        $business = Business::first();

        if (! $business) {
            $this->command->error('No business found.');
            return;
        }

        $types = [
            ['type' => 'DISCOUNT', 'title' => "30%\nOFF",      'desc' => '30% OFF on Next Purchase'],
            ['type' => 'FREE',     'title' => "FREE\nCOFFEE",  'desc' => 'Free Coffee on Any Purchase'],
            ['type' => 'DISCOUNT', 'title' => "50%\nOFF",      'desc' => '50% OFF on Next Purchase'],
            ['type' => 'FREE',     'title' => "FREE\nSANDWICH",'desc' => 'Free Sandwich with Coffee'],
            ['type' => 'DISCOUNT', 'title' => "20%\nOFF",      'desc' => '20% OFF on Next Purchase'],
            ['type' => 'FREE',     'title' => "FREE\nTEA",     'desc' => 'Free Tea on Any Purchase'],
            ['type' => 'DISCOUNT', 'title' => "10%\nOFF",      'desc' => '10% OFF on Next Purchase'],
        ];

        $names = ['Sumit', 'Ajeet', 'Pooja', 'Rohit', 'Neha', 'Vikas', 'Karan', 'Ishita', 'Manish', 'Priya', 'Amit', 'Deepa', 'Ravi', 'Sneha', 'Arjun'];
        $codes = ['LQR-8F4A29','LQR-3A2B55','LQR-9Z8X44','LQR-1K2L3M','LQR-4N5O6P','LQR-7Q8R9S','LQR-2T3U4V','LQR-5W6X7Y','LQR-8Z9A0B','LQR-1C2D3E','LQR-4F5G6H','LQR-7I8J9K','LQR-0L1M2N','LQR-3O4P5Q','LQR-6R7S8T'];

        for ($i = 0; $i < 15; $i++) {
            $t = $types[$i % count($types)];
            RewardRequest::create([
                'business_id'        => $business->id,
                'customer_name'      => $names[$i],
                'reward_type'        => $t['type'],
                'reward_title'       => $t['title'],
                'reward_description' => $t['desc'],
                'code'               => $codes[$i],
                'status'             => 'pending',
                'expires_at'         => now()->addDays(rand(3, 30)),
                'created_at'         => now()->subMinutes(rand(1, 300)),
                'updated_at'         => now()->subMinutes(rand(1, 300)),
            ]);
        }

        $this->command->info('Seeded 15 pending reward requests.');
    }
}
