<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('districts', function (Blueprint $table) {
            $table->id();
            $table->string('name', 100);
            $table->foreignId('city_id')->constrained('cities')->onDelete('cascade');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->boolean('is_deleted')->default(false);
        });
    }

    public function down()
    {
        Schema::dropIfExists('districts');
    }
}; 