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
        Schema::table('payments', function (Blueprint $table) {
            if (!Schema::hasColumn('payments', 'paymob_order_id')) {
                $table->string('paymob_order_id')->nullable()->after('transaction_id');
            }
            if (!Schema::hasColumn('payments', 'payment_key')) {
                $table->string('payment_key')->nullable()->after('paymob_order_id');
            }
            if (!Schema::hasColumn('payments', 'is_paid')) {
                $table->boolean('is_paid')->default(false)->after('status');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('payments', function (Blueprint $table) {
            $table->dropColumn(['paymob_order_id', 'payment_key', 'is_paid']);
        });
    }
};
