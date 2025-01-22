<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClicksOverTimeTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('clicks_over_times', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('shortlink_id');
            $table->string('ip_address');
            $table->string('referrer')->nullable();
            $table->timestamp('clicked_at')->useCurrent();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('shortlink_id')
                ->references('id')
                ->on('shortlinks')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('clicks_over_time');
    }
}
