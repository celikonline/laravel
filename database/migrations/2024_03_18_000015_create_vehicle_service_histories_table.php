<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vehicle_service_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vehicle_id')->constrained('customer_vehicles')->onDelete('cascade');
            $table->foreignId('service_id')->constrained('services')->onDelete('cascade');
            $table->foreignId('package_id')->nullable()->constrained('packages');
            $table->dateTime('service_date');
            $table->text('description')->nullable();
            $table->decimal('cost', 10, 2);
            $table->string('status');
            $table->string('technician');
            $table->text('notes')->nullable();
            $table->dateTime('completion_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vehicle_service_histories');
    }
}; 