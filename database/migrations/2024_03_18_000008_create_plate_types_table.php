<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('plate_types', function (Blueprint $table) {
            $table->id();
            $table->string('name', 50);
            $table->string('description', 200)->nullable();
            $table->timestamps();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
        });
    }

    public function down()
    {
        Schema::dropIfExists('plate_types');
    }
}; 