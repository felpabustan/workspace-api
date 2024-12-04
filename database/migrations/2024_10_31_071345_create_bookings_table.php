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
        Schema::create('bookings', function (Blueprint $table) {
          $table->id();
          $table->foreignId('space_id')->constrained()->onDelete('cascade');
          $table->foreignId('user_id')->constrained()->onDelete('cascade'); // If you have a user model
          $table->timestamp('start_time');
          $table->timestamp('end_time');
          $table->integer('duration');
          $table->string('rate_type');
          $table->decimal('price', 8, 2);
          $table->string('status')->default('confirmed');
          $table->timestamps();
      });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bookings');
    }
};
