<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('packages', function (Blueprint $table) {
            // Remove vehicle_id column
            $table->dropForeign(['vehicle_id']);
            $table->dropColumn('vehicle_id');

            // Add vehicle fields
            $table->string('plate_number');
            $table->string('plate_city', 2);
            $table->string('plate_letters', 3);
            $table->string('plate_numbers', 4);
            $table->foreignId('plate_type')->constrained('plate_types');
            $table->foreignId('brand_id')->constrained('vehicle_brands');
            $table->foreignId('model_id')->constrained('vehicle_models');
            $table->integer('model_year');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('packages', function (Blueprint $table) {
            // Remove vehicle fields
            $table->dropForeign(['plate_type']);
            $table->dropForeign(['brand_id']);
            $table->dropForeign(['model_id']);
            $table->dropColumn([
                'plate_number',
                'plate_city',
                'plate_letters',
                'plate_numbers',
                'plate_type',
                'brand_id',
                'model_id',
                'model_year'
            ]);

            // Add back vehicle_id column
            $table->foreignId('vehicle_id')->constrained('vehicles');
        });
    }
};
