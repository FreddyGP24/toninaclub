<?php

use App\Enums\ReservationStatus;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')
                ->cascadeOnUpdate()->restrictOnDelete();
            $table->foreignId('room_id')->constrained('rooms')
                ->cascadeOnUpdate()->restrictOnDelete();
            $table->date('check_in');
            $table->date('check_out');
            $table->unsignedTinyInteger('guests');
            $table->decimal('price_per_night', 10, 2);
            $table->unsignedSmallInteger('nights');
            $table->decimal('total', 10, 2);
            $table->string('status', 30)->default(ReservationStatus::PENDING->value);
            $table->text('notes')->nullable();
            $table->timestamps();
            $table->index(
                ['room_id', 'status', 'check_in', 'check_out'],
                'reservation_availability_index'
            );
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};
