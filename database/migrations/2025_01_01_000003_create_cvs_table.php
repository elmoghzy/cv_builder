<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasTable('cvs')) {
            Schema::create('cvs', function (Blueprint $table) {
                $table->id();
                $table->unsignedBigInteger('user_id');
                $table->unsignedBigInteger('template_id');
                $table->string('title');
                $table->json('content');
                $table->boolean('is_paid')->default(false);
                $table->datetime('paid_at')->nullable();
                $table->string('pdf_path')->nullable();
                $table->integer('download_count')->default(0);
                $table->timestamps();

                $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
                $table->foreign('template_id')->references('id')->on('templates')->onDelete('cascade');
                $table->index(['user_id', 'is_paid']);
            });
        }
    }

    public function down(): void
    {
        Schema::dropIfExists('cvs');
    }
};
