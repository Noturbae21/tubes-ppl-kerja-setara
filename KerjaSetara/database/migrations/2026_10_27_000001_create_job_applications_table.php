<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('job_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('job_vacancy_id')->constrained('job_vacancies')->onDelete('cascade');
            $table->string('resume_name');
            $table->string('portfolio_url')->nullable();
            $table->text('accessibility_notes')->nullable();
            $table->boolean('consent_agreement')->default(true);
            $table->string('status')->default('pending');
            $table->timestamps();

            $table->unique(['user_id', 'job_vacancy_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('job_applications');
    }
};
