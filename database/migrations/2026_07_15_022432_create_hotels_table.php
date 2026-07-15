<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->constrained('users')
                ->cascadeOnUpdate()->restrictOnDelete();
            $table->string('name', 150);
            $table->string('slug', 180)->unique();
            $table->text('description')->nullable();
            $table->string('phone', 20);
            $table->string('email')->nullable();
            $table->string('address_line', 200);
            $table->string('city', 100);
            $table->string('state', 100);
            $table->string('postal_code', 10)->nullable();
            $table->string('country', 100)->default('México');
            $table->string('formatted_address', 255)->nullable();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('google_place_id')->nullable();
            $table->json('services')->nullable();
            $table->boolean('active')->default(true);
            $table->timestamps();
            $table->index(['city', 'state', 'active']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
