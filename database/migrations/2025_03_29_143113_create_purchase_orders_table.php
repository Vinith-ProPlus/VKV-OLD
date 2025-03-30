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
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_no')->unique();
            $table->date('order_date');
            $table->string('order_by');
            $table->foreignId('project_id')->constrained('projects')->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('req_id')->nullable();
            $table->foreignId('warehouse_id')->constrained('warehouses')->cascadeOnUpdate()->restrictOnDelete();
            $table->enum('status', ['new', 'delivered'])->default('new');
            $table->double('taxable_amount', 10, 2)->default(0);
            $table->double('tax_amount', 10, 2)->default(0);
            $table->double('total_amount', 10, 2)->default(0);
            $table->double('additional_amount', 10, 2)->default(0);
            $table->double('net_amount', 10, 2)->default(0);
            $table->double('paid_amount', 10, 2)->default(0);
            $table->boolean('is_secondary')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_orders');
    }
};
