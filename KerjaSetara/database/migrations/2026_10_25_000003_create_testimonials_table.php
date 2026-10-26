<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('testimonials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('position');
            $table->string('company')->nullable();
            $table->enum('role_type', ['kandidat', 'hrd'])->default('kandidat');
            $table->text('content');
            $table->string('avatar_initials', 5)->nullable();
            $table->string('avatar_color', 20)->default('blue');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('testimonials');
    }
};
