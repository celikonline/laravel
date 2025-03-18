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
            $table->string('package_number')->unique();
            $table->decimal('amount', 10, 2);
            $table->decimal('discount', 10, 2)->nullable();
            $table->string('status')->nullable();
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->dateTime('policy_date')->nullable();
            $table->string('first_name', 100)->nullable();
            $table->string('last_name', 100)->nullable();
            $table->string('identity_number', 20)->nullable();
            $table->string('tax_office', 100)->nullable();
            $table->string('phone', 20)->nullable();
            $table->foreignId('vehicle_brand')->constrained('vehicle_brands');
            $table->foreignId('vehicle_model')->constrained('vehicle_models');
            $table->integer('vehicle_model_year');
            $table->string('plate_city', 50)->nullable();
            $table->string('plate_letters', 10)->nullable();
            $table->string('plate_numbers', 10)->nullable();
            $table->foreignId('city_id')->nullable()->constrained('cities');
            $table->foreignId('district_id')->nullable()->constrained('districts');
            $table->string('company_name', 200)->nullable();
            $table->foreignId('plate_type')->constrained('plate_types');
            $table->boolean('is_individual')->default(true);
            $table->string('plate')->nullable();
            $table->foreignId('package_type_id')->constrained('package_types');
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->string('credit_card_number')->nullable();
            $table->string('credit_cart_owner')->nullable();
            $table->string('three_d_collection')->nullable();
            $table->string('email')->nullable();
            $table->decimal('net_amount', 10, 2);
            $table->decimal('kdv', 10, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('packages');
    }
}; 