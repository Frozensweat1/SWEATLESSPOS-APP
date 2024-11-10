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
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            // General settings
            $table->string('site_name')->nullable();                   // Store's name
            $table->string('currency')->nullable();                    // Default currency
            $table->decimal('tax_rate', 5, 2)->nullable();             // Default tax rate (%)
            $table->string('contact_email')->nullable();               // Store contact email
            $table->string('contact_phone')->nullable();               // Store contact phone number
            $table->text('address')->nullable();                       // Store address
            // Receipt and invoice settings
            $table->string('receipt_header')->nullable();              // Header message on receipts
            $table->string('receipt_footer')->nullable();              // Footer message on receipts
            $table->string('invoice_prefix')->nullable();              // Prefix for invoice numbers
            $table->boolean('auto_print_receipt')->default(true);      // Auto print receipts on sale
            // Appearance settings
            $table->string('theme_color')->default('default');          // Theme color for the store
            $table->string('logo')->nullable();                         // Store logo
            $table->string('favicon')->nullable();                      // Store favicon
            $table->string('background_img1')->nullable();                      // Store favicon
            $table->string('background_img2')->nullable();
            // Locale and regional settings
            $table->string('currency_symbol')->nullable();              // Currency symbol (e.g., $, £, €)
            // Security settings
            $table->boolean('enable_two_factor_auth')->default(false);  // Enable 2FA for admin/users
            $table->boolean('require_password_change')->default(false); // Force password change after first ogin
            // Inventory settings
            $table->boolean('track_inventory')->default(true);          // Enable inventory tracking
            $table->integer('low_stock_threshold')->default(10);        // Low stock threshold for alerts
            $table->boolean('enable_expiry_tracking')->default(true);   // Track product expiry dates

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings');
    }
};
