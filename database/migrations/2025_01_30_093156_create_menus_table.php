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
        Schema::create('tbl_menus', function (Blueprint $table) {
            $table->string('MID', 50)->primary();
            $table->string('Slug', 150)->nullable();
            $table->string('MenuName', 150)->nullable();
            $table->string('ActiveName', 150)->nullable();
            $table->text('Icon')->nullable();
            $table->text('PageUrl')->nullable();
            $table->string('ParentID', 50)->nullable();
            $table->string('Level', 10)->nullable();
            $table->integer('hasSubMenu')->nullable();
            $table->integer('Ordering')->nullable();
            $table->boolean('isAdd')->default(1);
            $table->boolean('view')->default(1);
            $table->boolean('edit')->default(1);
            $table->boolean('isDelete')->default(1);
            $table->boolean('copy')->default(1);
            $table->boolean('excel')->default(1);
            $table->boolean('csv')->default(1);
            $table->boolean('print')->default(1);
            $table->boolean('pdf')->default(1);
            $table->boolean('restore')->default(1);
            $table->boolean('showpwd')->default(1);
            $table->enum('ActiveStatus', ['Active', 'Inactive'])->default('Active');
            $table->integer('DFlag')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_menus');
    }
};
