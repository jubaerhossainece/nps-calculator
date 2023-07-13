<?php

namespace Database\Seeders;

use App\Models\ProjectLink;
use Illuminate\Database\Seeder;

class ProjectLinkSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ProjectLink::factory()->count(50)->create();
    }
}
