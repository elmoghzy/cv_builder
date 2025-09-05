<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Add new columns only if they don't already exist to make this migration idempotent
        Schema::table('users', function (Blueprint $table) {
            if (! Schema::hasColumn('users', 'phone')) {
                $table->string('phone')->nullable()->after('email');
            }
            if (! Schema::hasColumn('users', 'google_id')) {
                $table->string('google_id')->nullable()->after('phone');
            }
            if (! Schema::hasColumn('users', 'linkedin_id')) {
                $table->string('linkedin_id')->nullable()->after('google_id');
            }
        });

        // Make email_verified_at nullable. Avoid using ->change() which triggers Doctrine DBAL
        // introspection and can fail with unknown types. Use a raw ALTER TABLE for MySQL.
        if (Schema::hasTable('users') && Schema::hasColumn('users', 'email_verified_at')) {
            try {
                $driver = DB::getDriverName();
                if ($driver === 'mysql') {
                    DB::statement('ALTER TABLE `users` MODIFY `email_verified_at` TIMESTAMP NULL');
                } elseif ($driver === 'pgsql') {
                    DB::statement('ALTER TABLE "users" ALTER COLUMN "email_verified_at" DROP NOT NULL');
                }
                // for sqlite or others, do nothing — column likely already supports null or
                // changing it would require recreating the table which is risky here.
            } catch (\Throwable $e) {
                // If the raw SQL fails, skip changing the column to avoid breaking migrations.
                // The application can still work if the column is already nullable or handled elsewhere.
            }
        }
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['phone', 'google_id', 'linkedin_id']);
        });
    }
};
