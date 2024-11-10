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
        Schema::create('refunds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sale_id')->constrained('sales')->onDelete('cascade'); // Reference to the original sale
            $table->foreignId('sale_detail_id')->constrained('sale_details')->onDelete('cascade'); // Reference to the specific sale detail
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade'); // Product being returned
            $table->integer('quantity'); // Quantity being returned
            $table->decimal('refund_amount', 10, 2); // Amount refunded
            $table->string('reason')->nullable(); // Reason for the return
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // User processing the return
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('refunds');
    }
};
