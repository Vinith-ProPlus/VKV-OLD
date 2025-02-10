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
        Schema::create('tbl_user_roles', function (Blueprint $table) {
            $table->id('role_id');
            $table->string('role_name', 150)->nullable();
            $table->mediumText('CRUD')->nullable();
            $table->integer('id_show')->default(1);
            $table->enum('active_status', ['Active', 'Inactive'])->default('Active');
            $table->integer('d_flag')->default(0);
            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->timestamp('deleted_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_user_roles');
    }
};
