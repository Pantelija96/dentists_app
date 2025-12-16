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
        Schema::create('parameter_work_type', function (Blueprint $table) {
            $table->id();
            $table->foreignId('work_type_id')->constrained('work_types')->cascadeOnDelete();
            $table->foreignId('parameter_id')->constrained('parameters')->cascadeOnDelete();
            $table->boolean('required')->default(false);
            $table->timestamps();

            $table->unique(['work_type_id','parameter_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parameter_work_type');
    }
};
