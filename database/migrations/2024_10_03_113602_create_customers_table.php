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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('name');                // Customer's first name
            $table->string('email')->unique()->nullable(); // Customer's email address (optional, unique)
            $table->string('phone')->unique()->nullable(); // Customer's phone number (optional, unique)
            $table->text('address')->nullable();         // Customer's address (optional)
            $table->string('image')->nullable();   // Postal code (optional)
            // Loyalty Program (if any)
            $table->integer('loyalty_points')->default(0);      // Loyalty points (if using a points system)
            $table->date('date_of_birth')->nullable();
            // Status
            $table->enum('status', ['active', 'inactive'])->default('active'); // Customer status

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
