<?php

namespace Database\Factories;

use App\Models\Project;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectLinkFactory extends Factory
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
//            'project_id' => Project::factory()->count(1)->create()->first(),
            'name' => $this->faker->name(),
            'code' => $this->faker->unique()->randomNumber(6),
            'created_at' => $randomDateTime,
            'updated_at' => $randomDateTime,
        ];
    }
}
