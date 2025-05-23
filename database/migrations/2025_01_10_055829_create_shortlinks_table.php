<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShortlinksTable extends Migration
{
    public function up()
    {
        Schema::create('shortlinks', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('title')->nullable()->index();
            $table->string('short_code')->unique()->index();
            $table->string('target_url')->index();
            $table->string('short_url')->unique()->index();
            $table->string('final_url')->nullable()->index();
            $table->string('hash')->unique()->index();
            $table->unsignedBigInteger('total_clicks')->default(0)->index();
            $table->unsignedBigInteger('unique_clicks')->default(0)->index();
            $table->unsignedBigInteger('qr_scans')->default(0)->index();
            $table->boolean('is_active')->default(false)->index();
            $table->boolean('is_premium')->default(false)->index();
            $table->timestamp('expires_at')->nullable()->index();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('shortlinks');
    }
}
