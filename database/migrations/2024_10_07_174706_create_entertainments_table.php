<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('entertainments', function (Blueprint $table) {
            $table->id();
            $table->json('name');
            $table->json('description');
            $table->string('working_hours');
            $table->decimal('price_from', 10, 2)->nullable();
            $table->decimal('price_to', 10, 2)->nullable();
            $table->text('location_link');
            $table->json('contacts');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('entertainments');
    }
};
