<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\ProjectLink;
use Illuminate\Database\Seeder;

class ProjectSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Project::factory()
            ->count(50)
            ->has(
                ProjectLink::factory(),
                'link'
            )
            ->create();
    }
}
