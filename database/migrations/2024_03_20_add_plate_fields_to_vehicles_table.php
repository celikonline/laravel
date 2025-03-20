<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->string('plate_city', 2)->after('plate_number');
            $table->string('plate_letters', 3)->after('plate_city');
            $table->string('plate_numbers', 4)->after('plate_letters');
        });
    }

    public function down()
    {
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropColumn(['plate_city', 'plate_letters', 'plate_numbers']);
        });
    }
}; 