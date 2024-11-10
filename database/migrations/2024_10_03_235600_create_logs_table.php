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
        Schema::create('logs', function (Blueprint $table) {
            $table->id();
            $table->string('action'); // Action type (e.g., 'created', 'updated', 'deleted')
            $table->string('event_type')->nullable(); // Event type (e.g., 'product', 'sale', 'user')
            $table->text('description')->nullable(); // Detailed description of the action
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('set null'); // User responsible for the action
            $table->ipAddress('ip_address')->nullable(); // IP address of the user (optional)
            $table->json('old_data')->nullable(); // Data before the action (optional, for updates)
            $table->json('new_data')->nullable(); // Data after the action (optional, for updates)
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('logs');
    }
};
