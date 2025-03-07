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
        Schema::create('tables_in_booking', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')
                ->constrained('bookings')
                ->onDelete('cascade');
            $table->foreignId('table_id')
                ->constrained('tables')
                ->onDelete('cascade');
            $table->timestamps();

            // Додаємо унікальний індекс, щоб уникнути дублювання столів у межах одного бронювання
            $table->unique(['booking_id', 'table_id'], 'unique_booking_table');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tables_in_booking');
    }
};
