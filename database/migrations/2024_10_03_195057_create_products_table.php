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
        Schema::create('products', function (Blueprint $table) {
            $table->id(); // Product ID
            $table->string('name'); // Product name
            $table->string('barcode')->unique()->nullable(); // Barcode for scanning
            $table->string('sku')->unique()->nullable(); // SKU
            $table->text('description')->nullable(); // Product description
            $table->decimal('price', 10, 2); // Selling price
            $table->decimal('cost_price', 10, 2)->nullable(); // Cost price
            $table->integer('stock_quantity'); // Inventory tracking
            $table->foreignId('category_id')->constrained('categories')->onDelete('cascade'); // Foreign key for category
            $table->foreignId('supplier_id')->nullable()->constrained('suppliers')->onDelete('set null'); // Supplier ID
            $table->date('manufacture_date')->nullable(); // Manufacture date for products
            $table->date('expiry_date')->nullable(); // Expiry date for products
            $table->string('image', 2048)->nullable();
            $table->timestamps(); // Timestamps
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
