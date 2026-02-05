<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Plan;

class PlanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (! Plan::query()->exists()) {
            $plan = Plan::create([
                'name' => 'Sole Trader / ABN Holder incl. GST',
                'description' => 'Includes Business Income, Car/Tools/Expenses, Document Uploads, Professional Review',
                'price' => 100.00,
            ]);

            $options = [
                'Includes Business Income',
                'Car/Tools/Expenses',
                'Document Uploads',
                'Professional Review',
            ];

            foreach ($options as $opt) {
                $plan->options()->create(['name' => $opt]);
            }
        }
    }
}
