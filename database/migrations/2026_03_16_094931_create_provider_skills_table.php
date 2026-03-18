<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('provider_skills', function (Blueprint $table) {
            $table->id();
            $table->foreignId('provider_id')
                ->constrained('provider_profiles')
                ->cascadeOnDelete();
            $table->string('skill_name');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('provider_skills');
    }
};
