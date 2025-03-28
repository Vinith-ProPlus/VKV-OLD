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
        Schema::create('purchase_order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_order_id')->constrained()->onDelete('cascade');
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->integer('qty')->default(1);
            $table->double('price', 10, 2)->default(0);
            $table->double('total_amt', 10, 2)->default(0);
            $table->foreignId('tax_id')->constrained();
            $table->enum('tax_type', ['include', 'exclude'])->default('exclude');
            $table->double('taxable', 10, 2)->default(0);
            $table->double('tax_amt', 10, 2)->default(0);
            $table->double('net_amt', 10, 2)->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('purchase_order_items');
    }
};
