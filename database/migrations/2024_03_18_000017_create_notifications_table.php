<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('message');
            $table->string('type');
            $table->boolean('is_read')->default(false);
            $table->foreignId('customer_id')->nullable()->constrained('customers');
            $table->foreignId('package_id')->nullable()->constrained('packages');
            $table->foreignId('vehicle_id')->nullable()->constrained('customer_vehicles');
            $table->unsignedBigInteger('user_id')->nullable();
            $table->string('status');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}; 