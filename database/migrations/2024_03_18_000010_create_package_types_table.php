<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('package_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('description')->nullable();
            $table->integer('duration_in_days');
            $table->decimal('base_price', 10, 2);
            $table->boolean('is_recurring')->default(false);
            $table->integer('recurring_period')->nullable();
            $table->decimal('discount', 10, 2)->nullable();
            $table->decimal('kdv_rate', 5, 2)->default(18.00);
            $table->decimal('kdv', 10, 2);
            $table->decimal('net_amount', 10, 2);
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('package_types');
    }
}; 