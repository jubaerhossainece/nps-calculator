<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('projects', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('name');
            $table->text('logo')->nullable();
            $table->boolean('wt_visibility')->default(true);
            $table->boolean('name_field_visibility')->default(true);
            $table->boolean('email_field_visibility')->default(true);
            $table->boolean('comment_field_visibility')->default(true);
            $table->text('welcome_text')->nullable();
            $table->text('question')->nullable();
            $table->text('comment')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('projects');
    }
};
