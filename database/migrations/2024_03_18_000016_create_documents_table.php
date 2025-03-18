<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('file_path');
            $table->string('file_type');
            $table->bigInteger('file_size');
            $table->foreignId('customer_id')->nullable()->constrained('customers');
            $table->foreignId('package_id')->nullable()->constrained('packages');
            $table->foreignId('vehicle_id')->nullable()->constrained('customer_vehicles');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('documents');
    }
}; 