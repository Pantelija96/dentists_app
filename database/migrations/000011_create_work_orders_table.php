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
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('status_id')->nullable()->constrained('work_order_statuses')->nullOnDelete();
            $table->foreignId('delivery_option_id')->nullable()->constrained('delivery_options')->nullOnDelete();

            $table->string('name');
            $table->boolean('finished')->default(false);
            $table->float('total_price')->default(0);
            $table->boolean('draft')->default(false);
            $table->boolean('locked')->default(false);
            $table->boolean('i_want_to_deliver')->default(false);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_orders');
    }
};
