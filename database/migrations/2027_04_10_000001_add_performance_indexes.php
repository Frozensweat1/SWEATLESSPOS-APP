<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Helper function to check if index exists
        $indexExists = function ($table, $indexName) {
            return Schema::hasIndex($table, $indexName);
        };

        // Sales table indexes
        Schema::table('sales', function (Blueprint $table) use ($indexExists) {
            if (!$indexExists('sales', 'sales_store_id_index')) $table->index('store_id');
            if (!$indexExists('sales', 'sales_user_id_index')) $table->index('user_id');
            if (!$indexExists('sales', 'sales_customer_id_index')) $table->index('customer_id');
            if (!$indexExists('sales', 'sales_payment_method_index')) $table->index('payment_method');
            if (!$indexExists('sales', 'sales_payment_status_index')) $table->index('payment_status');
            if (!$indexExists('sales', 'sales_created_at_index')) $table->index('created_at');
            if (!$indexExists('sales', 'sales_store_id_created_at_index')) $table->index(['store_id', 'created_at']);
            if (!$indexExists('sales', 'sales_store_id_payment_method_index')) $table->index(['store_id', 'payment_method']);
        });

        // Sale details table indexes
        Schema::table('sale_details', function (Blueprint $table) use ($indexExists) {
            if (!$indexExists('sale_details', 'sale_details_sale_id_index')) $table->index('sale_id');
            if (!$indexExists('sale_details', 'sale_details_product_id_index')) $table->index('product_id');
            if (!$indexExists('sale_details', 'sale_details_created_at_index')) $table->index('created_at');
        });

        // Cash drawer sessions table indexes
        Schema::table('cash_drawer_sessions', function (Blueprint $table) use ($indexExists) {
            if (!$indexExists('cash_drawer_sessions', 'cash_drawer_sessions_store_id_index')) $table->index('store_id');
            if (!$indexExists('cash_drawer_sessions', 'cash_drawer_sessions_user_id_index')) $table->index('user_id');
            if (!$indexExists('cash_drawer_sessions', 'cash_drawer_sessions_status_index')) $table->index('status');
            if (!$indexExists('cash_drawer_sessions', 'cash_drawer_sessions_opened_at_index')) $table->index('opened_at');
            if (!$indexExists('cash_drawer_sessions', 'cash_drawer_sessions_store_id_user_id_status_index')) $table->index(['store_id', 'user_id', 'status']);
            if (!$indexExists('cash_drawer_sessions', 'cash_drawer_sessions_store_id_opened_at_index')) $table->index(['store_id', 'opened_at']);
        });

        // Refunds table indexes
        Schema::table('refunds', function (Blueprint $table) use ($indexExists) {
            if (!$indexExists('refunds', 'refunds_store_id_index')) $table->index('store_id');
            if (!$indexExists('refunds', 'refunds_sale_id_index')) $table->index('sale_id');
            if (!$indexExists('refunds', 'refunds_product_id_index')) $table->index('product_id');
            if (!$indexExists('refunds', 'refunds_user_id_index')) $table->index('user_id');
            if (!$indexExists('refunds', 'refunds_created_at_index')) $table->index('created_at');
            if (!$indexExists('refunds', 'refunds_store_id_created_at_index')) $table->index(['store_id', 'created_at']);
        });

        // Products table indexes
        Schema::table('products', function (Blueprint $table) use ($indexExists) {
            if (!$indexExists('products', 'products_store_id_index')) $table->index('store_id');
            if (!$indexExists('products', 'products_category_id_index')) $table->index('category_id');
            if (!$indexExists('products', 'products_barcode_index')) $table->index('barcode');
            if (!$indexExists('products', 'products_store_id_category_id_index')) $table->index(['store_id', 'category_id']);
        });

        // Customers table indexes
        Schema::table('customers', function (Blueprint $table) use ($indexExists) {
            if (!$indexExists('customers', 'customers_store_id_index')) $table->index('store_id');
            if (!$indexExists('customers', 'customers_status_index')) $table->index('status');
            if (!$indexExists('customers', 'customers_store_id_status_index')) $table->index(['store_id', 'status']);
        });

        // Stock adjustments table indexes
        Schema::table('stock_adjustments', function (Blueprint $table) use ($indexExists) {
            if (!$indexExists('stock_adjustments', 'stock_adjustments_store_id_index')) $table->index('store_id');
            if (!$indexExists('stock_adjustments', 'stock_adjustments_product_id_index')) $table->index('product_id');
            if (!$indexExists('stock_adjustments', 'stock_adjustments_user_id_index')) $table->index('user_id');
            if (!$indexExists('stock_adjustments', 'stock_adjustments_type_index')) $table->index('type');
            if (!$indexExists('stock_adjustments', 'stock_adjustments_created_at_index')) $table->index('created_at');
            if (!$indexExists('stock_adjustments', 'stock_adjustments_store_id_created_at_index')) $table->index(['store_id', 'created_at']);
        });

        // Expenditures table indexes
        Schema::table('expenditures', function (Blueprint $table) use ($indexExists) {
            if (!$indexExists('expenditures', 'expenditures_store_id_index')) $table->index('store_id');
            if (!$indexExists('expenditures', 'expenditures_user_id_index')) $table->index('user_id');
            if (!$indexExists('expenditures', 'expenditures_payment_method_index')) $table->index('payment_method');
            if (!$indexExists('expenditures', 'expenditures_expenditure_date_index')) $table->index('expenditure_date');
            if (!$indexExists('expenditures', 'expenditures_store_id_expenditure_date_index')) $table->index(['store_id', 'expenditure_date']);
        });

        // Users table indexes
        Schema::table('users', function (Blueprint $table) use ($indexExists) {
            if (!$indexExists('users', 'users_store_id_index')) $table->index('store_id');
            if (!$indexExists('users', 'users_email_index')) $table->index('email');
        });

        // Suppliers table indexes
        Schema::table('suppliers', function (Blueprint $table) use ($indexExists) {
            if (!$indexExists('suppliers', 'suppliers_store_id_index')) $table->index('store_id');
        });

        // Categories table indexes
        Schema::table('categories', function (Blueprint $table) use ($indexExists) {
            if (!$indexExists('categories', 'categories_store_id_index')) $table->index('store_id');
        });

        // Purchase orders table indexes
        Schema::table('purchase_orders', function (Blueprint $table) use ($indexExists) {
            if (!$indexExists('purchase_orders', 'purchase_orders_store_id_index')) $table->index('store_id');
            if (!$indexExists('purchase_orders', 'purchase_orders_supplier_id_index')) $table->index('supplier_id');
            if (!$indexExists('purchase_orders', 'purchase_orders_status_index')) $table->index('status');
            if (!$indexExists('purchase_orders', 'purchase_orders_created_at_index')) $table->index('created_at');
            if (!$indexExists('purchase_orders', 'purchase_orders_store_id_status_index')) $table->index(['store_id', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop indexes from sales table
        Schema::table('sales', function (Blueprint $table) {
            $table->dropIndex(['store_id']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['customer_id']);
            $table->dropIndex(['payment_method']);
            $table->dropIndex(['payment_status']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['store_id', 'created_at']);
            $table->dropIndex(['store_id', 'payment_method']);
        });

        // Drop indexes from sale_details table
        Schema::table('sale_details', function (Blueprint $table) {
            $table->dropIndex(['sale_id']);
            $table->dropIndex(['product_id']);
            $table->dropIndex(['created_at']);
        });

        // Drop indexes from cash_drawer_sessions table
        Schema::table('cash_drawer_sessions', function (Blueprint $table) {
            $table->dropIndex(['store_id']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['opened_at']);
            $table->dropIndex(['store_id', 'user_id', 'status']);
            $table->dropIndex(['store_id', 'opened_at']);
        });

        // Drop indexes from refunds table
        Schema::table('refunds', function (Blueprint $table) {
            $table->dropIndex(['store_id']);
            $table->dropIndex(['sale_id']);
            $table->dropIndex(['product_id']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['store_id', 'created_at']);
        });

        // Drop indexes from products table
        Schema::table('products', function (Blueprint $table) {
            $table->dropIndex(['store_id']);
            $table->dropIndex(['category_id']);
            $table->dropIndex(['barcode']);
            $table->dropIndex(['store_id', 'category_id']);
        });

        // Drop indexes from customers table
        Schema::table('customers', function (Blueprint $table) {
            $table->dropIndex(['store_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['store_id', 'status']);
        });

        // Drop indexes from stock_adjustments table
        Schema::table('stock_adjustments', function (Blueprint $table) {
            $table->dropIndex(['store_id']);
            $table->dropIndex(['product_id']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['type']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['store_id', 'created_at']);
        });

        // Drop indexes from expenditures table
        Schema::table('expenditures', function (Blueprint $table) {
            $table->dropIndex(['store_id']);
            $table->dropIndex(['user_id']);
            $table->dropIndex(['payment_method']);
            $table->dropIndex(['expenditure_date']);
            $table->dropIndex(['store_id', 'expenditure_date']);
        });

        // Drop indexes from users table
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['store_id']);
            $table->dropIndex(['email']);
        });

        // Drop indexes from suppliers table
        Schema::table('suppliers', function (Blueprint $table) {
            $table->dropIndex(['store_id']);
        });

        // Drop indexes from categories table
        Schema::table('categories', function (Blueprint $table) {
            $table->dropIndex(['store_id']);
        });

        // Drop indexes from purchase_orders table
        Schema::table('purchase_orders', function (Blueprint $table) {
            $table->dropIndex(['store_id']);
            $table->dropIndex(['supplier_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['created_at']);
            $table->dropIndex(['store_id', 'status']);
        });
    }
};
