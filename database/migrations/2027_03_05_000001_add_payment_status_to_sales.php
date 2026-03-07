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
        Schema::table('sales', function (Blueprint $table) {
            $table->enum('payment_status', ['paid', 'pending', 'partial'])->default('paid')->after('payment_method');
            $table->decimal('amount_paid', 10, 2)->default(0)->after('payment_status');
            $table->decimal('amount_due', 10, 2)->default(0)->after('amount_paid');
            $table->date('due_date')->nullable()->after('amount_due');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sales', function (Blueprint $table) {
            $table->dropColumn(['payment_status', 'amount_paid', 'amount_due', 'due_date']);
        });
    }
};
