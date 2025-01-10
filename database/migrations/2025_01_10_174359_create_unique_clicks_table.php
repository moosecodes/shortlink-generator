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
            $table->foreignId('shortlink_id')
                ->constrained()
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
