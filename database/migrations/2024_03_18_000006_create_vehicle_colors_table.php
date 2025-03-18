<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('vehicle_colors', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('hex_code');
            $table->text('description')->nullable();
            $table->boolean('is_metallic')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('vehicle_colors');
    }
}; 