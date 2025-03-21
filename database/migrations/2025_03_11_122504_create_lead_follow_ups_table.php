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
        Schema::create('lead_follow_ups', function (Blueprint $table) {
            $table->id();
            $table->foreignId('lead_id')->constrained()->onDelete('cascade');
            $table->foreignId('employee_id')->constrained('users')->onDelete('cascade');
            $table->date('next_follow_up_date');
            $table->decimal('estimated_amount', 15, 2);
            $table->date('estimated_closing_date')->nullable();
            $table->text('remarks')->nullable();
            $table->foreignId('followup_status_id')->constrained('lead_statuses')->onDelete('cascade');
            $table->unsignedTinyInteger('confirmation_percentage')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('lead_follow_ups');
    }
};
