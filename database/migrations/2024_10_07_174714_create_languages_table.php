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
    public function up()
    {
        Schema::create('languages', function (Blueprint $table) {
            $table->id();
            $table->string('code', 10)->unique();
            $table->string('name');
            $table->timestamps();
        });

        DB::table('languages')->insert([
            ['code' => 'ru', 'name' => 'Русский', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'en', 'name' => 'English', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'uz', 'name' => 'O\'zbek', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'kz', 'name' => 'Қазақ', 'created_at' => now(), 'updated_at' => now()],
            ['code' => 'tj', 'name' => 'Тоҷикӣ', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('languages');
    }
};
