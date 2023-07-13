<?php

namespace Database\Seeders;

use App\Models\ProjectLinkFeedback;
use Illuminate\Database\Seeder;

class FeedbackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProjectLinkFeedback::factory()->count(500)->create();
    }
}
