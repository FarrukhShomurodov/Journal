<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bot_user_previous_choices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bot_user_id')->constrained('bot_users')->cascadeOnDelete();
            $table->foreignId('previous_specialization_id')->nullable()->constrained('specializations')->cascadeOnDelete();
            $table->foreignId('previous_disease_type_id')->nullable()->constrained('disease_types')->cascadeOnDelete();
            $table->foreignId('previous_clinic_id')->nullable()->constrained('clinics')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bot_user_previous_choices');
    }
};
