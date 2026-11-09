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
        Schema::create('user_accessibility_preferences', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('category')->nullable();

            // Explicit user accessibility needs
            $table->boolean('wheelchair_access')->default(false);
            $table->boolean('sign_language')->default(false);
            $table->boolean('screen_reader')->default(false);
            $table->boolean('quiet_room')->default(false);
            $table->boolean('accessible_restroom')->default(false);
            $table->boolean('flexible_hours')->default(false);
            $table->boolean('adjustable_desk')->default(false);
            $table->boolean('accessible_parking')->default(false);
            $table->boolean('braille')->default(false);
            $table->boolean('high_contrast')->default(false);

            // Additional preferences and accommodations
            $table->json('additional_preferences')->nullable();
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_accessibility_preferences');
    }
};
