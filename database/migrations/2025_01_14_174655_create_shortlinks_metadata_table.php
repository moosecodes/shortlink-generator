<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShortlinksMetadataTable extends Migration
{
    public function up()
    {
        Schema::create('shortlinks_metadata', function (Blueprint $table) {
            $table->id();
            $table->uuid('shortlink_id'); // Foreign key to shortlinks table
            $table->string('meta_key')->nullable(); // Parameter key (e.g., "utm_source")
            $table->text('meta_value')->nullable(); // Parameter value (e.g., "google")
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
        Schema::dropIfExists('shortlinks_metadata');
    }
}
