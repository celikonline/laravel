<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('module');
            $table->index('action');
            $table->index('created_at');
        });
    }

    public function down()
    {
        Schema::table('audit_logs', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['module']);
            $table->dropIndex(['action']);
            $table->dropIndex(['created_at']);
        });
    }
}; 