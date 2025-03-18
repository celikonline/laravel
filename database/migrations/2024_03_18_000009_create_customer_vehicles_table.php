<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('customer_vehicles', function (Blueprint $table) {
            $table->id();
            $table->string('plate_number', 20);
            $table->string('chassis_number', 17)->nullable();
            $table->foreignId('brand_id')->constrained('vehicle_brands');
            $table->foreignId('model_id')->constrained('vehicle_models');
            $table->integer('year');
            $table->foreignId('color_id')->nullable()->constrained('vehicle_colors');
            $table->foreignId('vehicle_type_id')->constrained('vehicle_types');
            $table->foreignId('customer_id')->constrained('customers');
            $table->text('notes')->nullable();
            $table->dateTime('last_service_date')->nullable();
            $table->dateTime('next_service_date')->nullable();
            $table->string('insurance_number')->nullable();
            $table->dateTime('insurance_expiry_date')->nullable();
            $table->decimal('mileage', 10, 2)->nullable();
            $table->string('engine_number', 20)->nullable();
            $table->string('fuel_type', 20);
            $table->string('transmission', 20);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('customer_vehicles');
    }
}; 