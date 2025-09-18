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
            // Only add avatar field since google_id and linkedin_id already exist
            if (!Schema::hasColumn('users', 'avatar')) {
                $table->string('avatar')->nullable()->after('linkedin_id');
            }
            
            // Add indexes if they don't exist
            $sm = Schema::getConnection()->getDoctrineSchemaManager();
            $indexesFound = $sm->listTableIndexes('users');
            
            if (!isset($indexesFound['users_google_id_linkedin_id_index'])) {
                $table->index(['google_id', 'linkedin_id']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['google_id', 'linkedin_id']);
            $table->dropColumn(['google_id', 'linkedin_id', 'avatar']);
        });
    }
};
