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
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade'); // The user who create the notification
            $table->string('title'); // Title of the notification
            $table->text('message'); // The notification message
            $table->string('type')->nullable(); // Type of notification (e.g., 'info', 'success', 'error', etc.)
            $table->boolean('is_read')->default(false); // Status of the notification (read/unread)
            $table->timestamp('read_at')->nullable(); // Timestamp for when the notification was read
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
