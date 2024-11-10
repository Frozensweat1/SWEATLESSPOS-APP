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
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Supplier name
            $table->string('contact_person')->nullable(); // Contact person for the supplier
            $table->string('email')->unique()->nullable(); // Supplier email
            $table->string('phone')->nullable(); // Supplier phone number
            $table->string('address')->nullable(); // Supplier address
            $table->text('notes')->nullable(); // Any additional notes about the supplier
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
