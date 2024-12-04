<?php

namespace Database\Factories;

use App\Models\Space;
use Illuminate\Database\Eloquent\Factories\Factory;

class SpaceFactory extends Factory
{
    protected $model = Space::class;

    public function definition()
    {
        return [
            'name' => $this->faker->word,
            'description' => $this->faker->sentence,
            'rate_hourly' => $this->faker->randomFloat(2, 5, 20),
            'rate_daily' => $this->faker->randomFloat(2, 30, 100),
            'rate_weekly' => $this->faker->randomFloat(2, 150, 500),
            'rate_monthly' => $this->faker->randomFloat(2, 600, 2000),
            'availability' => true,
        ];
    }
}
