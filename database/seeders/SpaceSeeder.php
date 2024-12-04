<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Space;

class SpaceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Space::insert([
            [
                'name' => 'Private Office',
                'description' => 'A private, quiet office space for focused work.',
                'rate_hourly' => 15.00,
                'rate_daily' => 100.00,
                'rate_weekly' => 500.00,
                'rate_monthly' => 1500.00,
                'availability' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Shared Workspace',
                'description' => 'Open seating in a vibrant, collaborative area.',
                'rate_hourly' => 10.00,
                'rate_daily' => 60.00,
                'rate_weekly' => 300.00,
                'rate_monthly' => 900.00,
                'availability' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'name' => 'Conference Room',
                'description' => 'A fully equipped room for meetings and presentations.',
                'rate_hourly' => 20.00,
                'rate_daily' => 120.00,
                'rate_weekly' => 600.00,
                'rate_monthly' => null,
                'availability' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
