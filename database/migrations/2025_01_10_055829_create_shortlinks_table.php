<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShortlinksTable extends Migration
{
    public function up()
    {
        Schema::create('shortlinks', function (Blueprint $table) {
            $table->id();
            $table->string('original_url');
            $table->string('short_code')->unique();
            $table->string('utm_source')->nullable();
            $table->string('utm_medium')->nullable();
            $table->string('utm_campaign')->nullable();
            $table->string('utm_term')->nullable();
            $table->string('utm_content')->nullable();
            $table->unsignedBigInteger('total_clicks')->default(0);
            $table->unsignedBigInteger('unique_clicks')->default(0);
            $table->json('geo_data')->nullable();
            $table->json('device_data')->nullable();
            $table->boolean('is_active')->default(false);
            $table->timestamps();

            $table->index('short_code');
            $table->index('is_active');
        });
    }

    public function down()
    {
        Schema::dropIfExists('shortlinks');
    }
}
