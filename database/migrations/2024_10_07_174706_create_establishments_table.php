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
        Schema::create('establishments', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->json('description');
            $table->string('working_hours');
            $table->decimal('price_from', 10, 2);
            $table->decimal('price_to', 10, 2);
            $table->foreignId('category_id')->constrained('categories')->cascadeOnDelete();
            $table->text('location_link');
            $table->string('contacts');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('establishments');
    }
};
