<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            // Interview schedule & assistive accommodation details
            $table->dateTime('interview_at')->nullable()->after('status');
            $table->string('interview_method')->nullable()->after('interview_at');
            $table->string('interview_link')->nullable()->after('interview_method');
            $table->text('interview_assistive_facilities')->nullable()->after('interview_link');

            // Job Offer details
            $table->string('offer_salary')->nullable()->after('interview_assistive_facilities');
            $table->text('offer_benefits')->nullable()->after('offer_salary');
            $table->date('offer_start_date')->nullable()->after('offer_benefits');
            $table->text('offer_facilities')->nullable()->after('offer_start_date');
            $table->text('offer_notes')->nullable()->after('offer_facilities');
            $table->string('offer_status')->nullable()->after('offer_notes');
            $table->string('offer_letter_url')->nullable()->after('offer_status');

            // Rejection & inclusive feedback
            $table->text('rejection_feedback')->nullable()->after('offer_letter_url');
            $table->text('rejection_recommendation')->nullable()->after('rejection_feedback');
            $table->date('rejection_date')->nullable()->after('rejection_recommendation');

            // Cancellation
            $table->dateTime('cancelled_at')->nullable()->after('rejection_date');
            $table->string('cancellation_reason')->nullable()->after('cancelled_at');
        });
    }

    public function down(): void
    {
        Schema::table('job_applications', function (Blueprint $table) {
            $table->dropColumn([
                'interview_at',
                'interview_method',
                'interview_link',
                'interview_assistive_facilities',
                'offer_salary',
                'offer_benefits',
                'offer_start_date',
                'offer_facilities',
                'offer_notes',
                'offer_status',
                'offer_letter_url',
                'rejection_feedback',
                'rejection_recommendation',
                'rejection_date',
                'cancelled_at',
                'cancellation_reason',
            ]);
        });
    }
};

