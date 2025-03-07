<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('booking_statuses', function (Blueprint $table) {
            $table->smallInteger('id')->primary();
            $table->string('name', 10)->unique();
            $table->timestamps();
        });

        DB::table('booking_statuses')->insert([
            ['id' => 1, 'name' => 'active'],
            ['id' => 2, 'name' => 'completed'],
            ['id' => 3, 'name' => 'cancelled'],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('booking_statuses');
    }
};
