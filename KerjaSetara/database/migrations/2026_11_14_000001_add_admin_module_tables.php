<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // a) Add status & must_change_password to users table
        Schema::table('users', function (Blueprint $table) {
            $table->string('status')->default('active')->after('role');
            $table->boolean('must_change_password')->default(false)->after('status');
        });

        // b) Add verification columns to company_profiles table
        Schema::table('company_profiles', function (Blueprint $table) {
            $table->string('verification_status')->default('pending')->after('employee_count');
            $table->text('verification_notes')->nullable()->after('verification_status');
            $table->timestamp('verified_at')->nullable()->after('verification_notes');
            $table->foreignId('verified_by')->nullable()->constrained('users')->nullOnDelete()->after('verified_at');
            $table->text('rejection_reason')->nullable()->after('verified_by');
            $table->text('revision_reason')->nullable()->after('rejection_reason');
        });

        // c) Create facilities table
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('name')->unique();
            $table->text('description');
            $table->string('icon_path')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        // d) Create facility_job_vacancy pivot table
        Schema::create('facility_job_vacancy', function (Blueprint $table) {
            $table->foreignId('facility_id')->constrained('facilities')->cascadeOnDelete();
            $table->foreignId('job_vacancy_id')->constrained('job_vacancies')->cascadeOnDelete();
            $table->primary(['facility_id', 'job_vacancy_id']);
        });

        // e) Create activity_logs table
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('user_name')->nullable();
            $table->string('user_role')->nullable();
            $table->string('action_type');
            $table->text('description');
            $table->string('level')->default('info');
            $table->string('result')->default('success');
            $table->string('ip_address', 45)->nullable();
            $table->string('target_type')->nullable();
            $table->unsignedBigInteger('target_id')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
        Schema::dropIfExists('facility_job_vacancy');
        Schema::dropIfExists('facilities');

        Schema::table('company_profiles', function (Blueprint $table) {
            $table->dropForeign(['verified_by']);
            $table->dropColumn([
                'verification_status',
                'verification_notes',
                'verified_at',
                'verified_by',
                'rejection_reason',
                'revision_reason',
            ]);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['status', 'must_change_password']);
        });
    }
};
