<?php

use App\Models\ProjectLink;
use App\Models\ProjectLinkFeedback;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProjectLinkFeedbackTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('project_link_feedback', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(ProjectLink::class)->unique()->constrained()->cascadeOnDelete();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->enum('rating', ProjectLinkFeedback::RATING_VALUE);
            $table->text('comment')->nullable();
            $table->json('data')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('project_link_feedback');
    }
}
