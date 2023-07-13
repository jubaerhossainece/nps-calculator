<?php

namespace Database\Factories;

use App\Models\Project;
use App\Models\ProjectLink;
use App\Models\ProjectLinkFeedback;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\Factory;

class ProjectLinkFeedbackFactory extends Factory
{
    protected $model = ProjectLinkFeedback::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        // $startDate = Carbon::now()->subYears(2);

        // Generate a random end date within the desired range
        $endDate = Carbon::now();
        // $randomDateTime = $this->faker->dateTimeBetween($startDate, $endDate);

        $project_link = ProjectLink::find(1);

        return [
            'project_link_id' => $project_link->id,
            'project_id' => $project_link->project_id,
            'name' => $this->faker->optional()->name(),
            'email' => $this->faker->optional()->email(),
            'rating' => $this->faker->numberBetween(0, 10),
            'created_at' => $endDate,
            'updated_at' => $endDate,
        ];
    }
}
