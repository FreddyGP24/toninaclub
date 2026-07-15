<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->foreignId('hotel_id')->constrained('hotels')
                ->cascadeOnUpdate()->cascadeOnDelete();
            $table->string('room_number', 20);
            $table->string('type', 80);
            $table->unsignedTinyInteger('capacity')->default(1);
            $table->decimal('price_per_night', 10, 2);
            $table->text('description')->nullable();
            $table->json('amenities')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->unique(['hotel_id', 'room_number'], 'hotel_room_number_unique');
            $table->index(['hotel_id', 'active', 'capacity']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rooms');
    }
};