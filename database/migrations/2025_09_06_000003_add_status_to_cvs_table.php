<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('cvs') && ! Schema::hasColumn('cvs', 'status')) {
            Schema::table('cvs', function (Blueprint $table) {
                $table->string('status')->default('draft')->after('title');
            });
        }
    }

    public function down(): void
    {
        if (Schema::hasTable('cvs') && Schema::hasColumn('cvs', 'status')) {
            Schema::table('cvs', function (Blueprint $table) {
                $table->dropColumn('status');
            });
        }
    }
};
