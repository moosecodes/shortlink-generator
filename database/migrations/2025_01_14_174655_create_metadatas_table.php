<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMetadatasTable extends Migration
{
    public function up()
    {
        Schema::create('metadatas', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->uuid('shortlink_id');
            $table->string('meta_key')->nullable();
            $table->text('meta_value')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('shortlink_id')
                ->references('id')
                ->on('shortlinks')
                ->onDelete('cascade')
                ->onUpdate('cascade');

            // Prevent duplicate meta_key entries for the same shortlink
            $table->unique(['shortlink_id', 'meta_key']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('metadatas');
    }
}
