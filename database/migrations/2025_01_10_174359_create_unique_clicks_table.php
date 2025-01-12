<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUniqueClicksTable extends Migration
{
    public function up()
    {
        Schema::create('unique_clicks', function (Blueprint $table) {
            $table->id();
            $table->uuid('shortlink_id'); // Define as UUID to match the `id` type in `shortlinks` table
            $table->foreign('shortlink_id')
                ->references('id')
                ->on('shortlinks')
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->string('ip_address')->index();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('unique_clicks');
    }
}
