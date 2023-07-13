<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\ProjectLink;
use App\Models\User;
use Database\Factories\ProjectLinkFeedbackFactory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AdminSeeder::class);

//        $users = User::factory()->count(3)->create();


        $this->call(UserSeeder::class);
        $this->call(ProjectSeeder::class);
        $this->call(FeedbackSeeder::class);

//        User::factory()
//            ->count(50)
//            ->has(
//                Project::factory()
//                    ->count(3)
//            )
//            ->create();


    }
}
