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
            $table->string('original_url');
            $table->string('short_code')->unique();
            $table->string('short_url')->unique();
            $table->unsignedBigInteger('total_clicks')->default(0);
            $table->unsignedBigInteger('unique_clicks')->default(0);
            $table->boolean('is_active')->default(false);
            $table->timestamps();

            $table->index('short_code');
            $table->index('short_url');
            $table->index('is_active');
        });
    }

    public function down()
    {
        Schema::dropIfExists('shortlinks');
    }
}
