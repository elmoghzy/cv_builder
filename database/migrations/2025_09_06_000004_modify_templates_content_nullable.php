<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasTable('templates')) {
            try {
                DB::statement('ALTER TABLE `templates` MODIFY `content` JSON NULL');
            } catch (\Throwable $e) {
                // ignore if DB engine doesn't support JSON alter; factory will provide a value
            }
        }
    }

    public function down(): void
    {
        // No-op: reverting to NOT NULL may fail if rows contain NULL; safe to skip
    }
};
