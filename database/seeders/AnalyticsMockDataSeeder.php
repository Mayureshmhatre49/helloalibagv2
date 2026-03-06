<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AnalyticsMockDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Generate past 6 months of data
        for ($i = 0; $i < 40; $i++) {
            $date = now()->subDays(rand(1, 180));
            
            // Randomly create missing Users to prevent constraint errors if needed or just use random dates
            \App\Models\User::factory()->create([
                'created_at' => $date,
                'updated_at' => $date
            ]);

            // Fake 30 Days of inquiries
            if ($i < 20) {
                $inquiryDate = now()->subDays(rand(1, 30));
                \App\Models\Inquiry::create([
                    'listing_id' => 1, // Assumes at least one listing exists
                    'name' => 'Test Lead',
                    'email' => 'test@example.com',
                    'phone' => '1234567890',
                    'check_in' => $inquiryDate->addDays(5),
                    'check_out' => $inquiryDate->addDays(7),
                    'guests' => 2,
                    'message' => 'Analytics test',
                    'created_at' => $inquiryDate,
                    'updated_at' => $inquiryDate,
                ]);
            }
        }
    }
}
