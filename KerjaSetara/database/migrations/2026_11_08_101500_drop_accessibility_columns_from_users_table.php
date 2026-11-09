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
            $columnsToDrop = [];

            if (Schema::hasColumn('users', 'accessibility_preferences')) {
                $columnsToDrop[] = 'accessibility_preferences';
            }
            if (Schema::hasColumn('users', 'accessibility_notes')) {
                $columnsToDrop[] = 'accessibility_notes';
            }
            if (Schema::hasColumn('users', 'accessibility_category')) {
                $columnsToDrop[] = 'accessibility_category';
            }

            if (! empty($columnsToDrop)) {
                $table->dropColumn($columnsToDrop);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'accessibility_category')) {
                $table->string('accessibility_category')->nullable()->after('role');
            }
            if (! Schema::hasColumn('users', 'accessibility_preferences')) {
                $table->json('accessibility_preferences')->nullable()->after('accessibility_category');
            }
            if (! Schema::hasColumn('users', 'accessibility_notes')) {
                $table->text('accessibility_notes')->nullable()->after('accessibility_preferences');
            }
        });
    }
};
