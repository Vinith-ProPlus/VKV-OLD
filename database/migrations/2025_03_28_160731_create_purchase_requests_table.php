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
        Schema::create('purchase_requests', function (Blueprint $table) {
            $table->id();
            $table->string('req_no')->unique();
            $table->date('req_date');
            $table->string('req_by');
            $table->foreignId('project_id')->constrained()->onDelete('cascade');
            $table->foreignId('order_id')->nullable();
            $table->enum('status', ['pending', 'purchased','deleted'])->default('pending');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_requests');
    }
};
