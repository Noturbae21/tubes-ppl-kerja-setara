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
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'headline')) {
                $table->string('headline')->nullable()->after('full_name');
            }
            if (!Schema::hasColumn('users', 'city')) {
                $table->string('city')->nullable()->after('phone');
            }
            if (!Schema::hasColumn('users', 'bio')) {
                $table->text('bio')->nullable()->after('city');
            }
            if (!Schema::hasColumn('users', 'accessibility_preferences')) {
                $table->json('accessibility_preferences')->nullable()->after('accessibility_category');
            }
            if (!Schema::hasColumn('users', 'accessibility_notes')) {
                $table->text('accessibility_notes')->nullable()->after('accessibility_preferences');
            }
            if (!Schema::hasColumn('users', 'resume_file')) {
                $table->string('resume_file')->nullable()->after('bio');
            }
            if (!Schema::hasColumn('users', 'portfolio_url')) {
                $table->string('portfolio_url')->nullable()->after('resume_file');
            }
            if (!Schema::hasColumn('users', 'linkedin_url')) {
                $table->string('linkedin_url')->nullable()->after('portfolio_url');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'headline',
                'city',
                'bio',
                'accessibility_preferences',
                'accessibility_notes',
                'resume_file',
                'portfolio_url',
                'linkedin_url',
            ]);
        });
    }
};

