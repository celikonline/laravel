<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::statement("ALTER TABLE packages MODIFY COLUMN status ENUM('pending_payment', 'active', 'expired', 'cancelled', 'inactive') DEFAULT 'pending_payment'");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::statement("ALTER TABLE packages MODIFY COLUMN status ENUM('pending_payment', 'active', 'expired', 'cancelled') DEFAULT 'pending_payment'");
    }
};
