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
            $table->foreignId('customer_id')->constrained();
            $table->foreignId('vehicle_id')->constrained();
            $table->foreignId('service_package_id')->constrained();
            $table->string('contract_number')->unique();
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('price', 10, 2);
            $table->decimal('commission', 10, 2)->default(0);
            $table->decimal('commission_rate', 5, 2)->default(0);
            $table->enum('status', ['pending_payment', 'active', 'expired', 'cancelled'])->default('pending_payment');
            $table->timestamp('payment_date')->nullable();
            $table->integer('duration');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('packages');
    }
}; 