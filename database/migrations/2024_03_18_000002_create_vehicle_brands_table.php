<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vehicle_brands', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
        });
    }

    public function down()
    {
        Schema::dropIfExists('vehicle_brands');
    }
}; 