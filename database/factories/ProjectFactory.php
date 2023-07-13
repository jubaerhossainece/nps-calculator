<?php

namespace Database\Factories;

use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class ProjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $startDate = Carbon::now()->subYears(2);

        // Generate a random end date within the desired range
        $endDate = Carbon::now();
        $randomDateTime = $this->faker->dateTimeBetween($startDate, $endDate);

        return [
            'user_id' => User::first(),
            'name' => $this->faker->name(),
            'created_at' => $randomDateTime,
            'updated_at' => $randomDateTime,
        ];
    }
}
