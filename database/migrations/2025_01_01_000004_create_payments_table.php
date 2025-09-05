<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('payments')) {
            Schema::create('payments', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('cv_id')->nullable();
                $table->string('order_id')->unique();
                $table->string('transaction_id')->nullable();
                $table->decimal('amount', 8, 2);
                $table->string('currency', 3)->default('EGP');
                $table->enum('status', ['pending', 'success', 'failed', 'refunded'])->default('pending');
                $table->json('paymob_data')->nullable();
                $table->string('payment_method')->nullable();
                $table->datetime('paid_at')->nullable();
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('cv_id')->references('id')->on('cvs')->onDelete('set null');
                $table->index(['user_id', 'status']);
                $table->index('transaction_id');
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
