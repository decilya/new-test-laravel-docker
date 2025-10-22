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

            // Основная информация о бронировании
            $table->string('tour_name');
            $table->string('hunter_name');
            $table->foreignId('guide_id')->nullable()->constrained('guides')->onDelete('set null');
            $table->date('date');
            $table->integer('participants_count');

            // Временные метки
            $table->timestamps();

            // Индексы для оптимизации поиска
            $table->index('date');
            $table->index('guide_id');
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
