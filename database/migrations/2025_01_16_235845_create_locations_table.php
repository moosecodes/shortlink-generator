<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('locations', function (Blueprint $table) {
            $table->uuid('id')->primary();
            $table->string('ip_address')->index(); // Ensure it matches the column in unique_clicks
            $table->string('driver')->nullable();
            $table->string('country_name')->nullable()->index();
            $table->string('country_code')->nullable()->index();
            $table->string('region_code')->nullable();
            $table->string('region_name')->nullable();
            $table->string('city_name')->nullable()->index();
            $table->string('zip_code')->nullable()->index();
            $table->string('iso_code')->nullable()->index();
            $table->string('postal_code')->nullable()->index();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->string('metro_code')->nullable();
            $table->string('area_code')->nullable()->index();
            $table->string('timezone')->nullable()->index();
            $table->timestamps();

            // Add foreign key constraint
            $table->foreign('ip_address')
                ->references('ip_address')
                ->on('unique_clicks')
                ->onDelete('cascade')
                ->onUpdate('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('locations', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign(['ip_address']);
        });

        Schema::dropIfExists('locations');
    }
};
