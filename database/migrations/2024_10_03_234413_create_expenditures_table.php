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
        Schema::create('expenditures', function (Blueprint $table) {
            $table->id();
            $table->string('description'); // Description of the expenditure
            $table->decimal('amount', 10, 2); // Amount spent (e.g., 500.00)
            $table->string('category')->nullable(); // Category of the expenditure (e.g., utilities, supplies, maintenance)
            $table->date('expenditure_date'); // Date of the expenditure
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // Foreign key for the user who recorded the expenditure
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->onDelete('set null'); // Foreign key for the supplier (optional)
            $table->text('notes')->nullable(); // Additional notes (optional)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('expenditures');
    }
};
