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
        Schema::create('material_work_type', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_type_id')->constrained('work_types');
            $table->foreignId('material_id')->constrained('materials');
            $table->float('additional_price')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('material_work_type');
    }
};
