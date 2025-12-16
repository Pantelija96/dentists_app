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
        Schema::create('users', function (Blueprint $table) {
            $table->id();

            $table->foreignId('region_id')->nullable()->constrained('regions')->nullOnDelete();

            $table->enum('type', ['person','legal'])->default('person')->comment('person or legal entity');
            $table->enum('role', ['admin', 'user','super_admin'])->default('user')->comment('user roles');

            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->string('company_name')->nullable();
            $table->string('pib')->nullable();

            $table->string('email')->nullable()->unique();
            $table->string('password');

            $table->string('phone')->nullable();
            $table->string('language')->default('en');

            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('municipality')->nullable();
            $table->string('postal_code')->nullable();

            $table->boolean('is_approved')->default(false);
            $table->integer('number_of_notifications')->default(0);
            $table->dateTime('last_login')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users');
    }
};
