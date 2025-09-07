<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('templates') && ! Schema::hasColumn('templates', 'styling')) {
            Schema::table('templates', function (Blueprint $table) {
                $table->json('styling')->nullable()->after('description');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('templates') && Schema::hasColumn('templates', 'styling')) {
            Schema::table('templates', function (Blueprint $table) {
                $table->dropColumn('styling');
            });
        }
    }
};
