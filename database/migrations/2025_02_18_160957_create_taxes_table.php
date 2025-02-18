<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::create('taxes', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->decimal('percentage', 5, 2);
            $table->boolean('is_active')->default(1);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down() {
        Schema::dropIfExists('taxes');
    }
};
