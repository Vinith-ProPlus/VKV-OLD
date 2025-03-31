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
        Schema::create('blog_details', static function (Blueprint $table) {
            $table->id();
            $table->foreignId('blog_id')->constrained('blogs')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('project_stage_id')->constrained('project_stages')->cascadeOnUpdate()->restrictOnDelete();
            $table->text('remarks');
            $table->boolean('is_damage')->default(false);
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('blog_details');
    }
};
