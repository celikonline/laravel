<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('packages', function (Blueprint $table) {
            $table->id();
            $table->string('package_number', 50)->unique();
            $table->decimal('amount', 10, 2);
            $table->string('status', 50);
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->decimal('discount', 10, 2)->nullable();
            $table->dateTime('policy_date')->nullable();
            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->string('identity_number', 20)->nullable();
            $table->string('tax_office', 100)->nullable();
            $table->string('phone', 20)->nullable();
            $table->string('email', 200)->nullable();
            $table->foreignId('vehicle_brand_id')->nullable()->constrained('vehicle_brands');
            $table->foreignId('vehicle_model_id')->nullable()->constrained('vehicle_models');
            $table->integer('vehicle_model_year')->nullable();
            $table->string('plate_city', 10)->nullable();
            $table->string('plate_letters', 10)->nullable();
            $table->string('plate_numbers', 10)->nullable();
            $table->foreignId('city_id')->nullable()->constrained('cities');
            $table->foreignId('district_id')->nullable()->constrained('districts');
            $table->string('company_name', 200)->nullable();
            $table->foreignId('plate_type_id')->nullable()->constrained('plate_types');
            $table->boolean('is_individual')->default(true);
            $table->string('plate', 20)->nullable();
            $table->string('credit_card_number', 200)->nullable();
            $table->string('credit_card_owner', 200)->nullable();
            $table->string('three_d_collection', 200)->nullable();
            $table->integer('duration')->nullable();
            $table->string('package_name', 200)->nullable();
            $table->decimal('kdv_rate', 5, 2)->default(18.00);
            $table->decimal('kdv', 10, 2);
            $table->decimal('net_amount', 10, 2);
            $table->foreignId('package_type_id')->constrained('package_types');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('packages');
    }
}; 