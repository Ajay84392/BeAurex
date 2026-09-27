<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    public function run(): void
    {
        Plan::truncate();

        Plan::create([
            'name' => 'Standard Plan',
            'price' => 24000,
            'billing_cycle' => 'Year',
            'type' => 'Standard',
            'short_description' => '✨ Equivalent to ₹2,000/month',
            'detailed_description' => '36000', // Storing the strikethrough original price here temporarily for display
            'features' => json_encode([
                'Customer retention system',
                'Free account setup',
                'QR code',
                'Unlimited QR code scans',
                'Standard Support'
            ]),
            'is_active' => true,
        ]);

        Plan::create([
            'name' => 'Professional Plan',
            'price' => 49000,
            'billing_cycle' => '3 Years',
            'type' => 'Professional', // We can use 'type' to know it's popular
            'short_description' => '🔥 Only ₹1,361/month',
            'detailed_description' => '72000',
            'features' => json_encode([
                'Customer retention system',
                'Free account setup',
                'QR code',
                'Unlimited QR code scans',
                'Priority Support',
                'Free Feature Updates'
            ]),
            'is_active' => true,
        ]);

        Plan::create([
            'name' => 'Legacy Plan',
            'price' => 75000,
            'billing_cycle' => 'One-Time Payment',
            'type' => 'Legacy',
            'short_description' => 'No Renewals',
            'detailed_description' => '120000',
            'features' => json_encode([
                'Customer retention system',
                'Free account setup',
                'QR code',
                'Unlimited QR code scans',
                'Free Feature Updates',
                'Priority Support',
                'Dedicated Relationship Manager',
                'All Future Updates'
            ]),
            'is_active' => true,
        ]);
    }
}
