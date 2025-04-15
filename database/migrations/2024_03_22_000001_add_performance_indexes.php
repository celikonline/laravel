<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        // Customers table indexes
        Schema::table('customers', function (Blueprint $table) {
            $table->index('email');
            $table->index('phone');
            $table->index('created_at');
        });

        // Vehicles table indexes
        Schema::table('vehicles', function (Blueprint $table) {
            $table->index('plate_number');
            $table->index('customer_id');
            $table->index('created_at');
        });

        // Packages table indexes
        Schema::table('packages', function (Blueprint $table) {
            $table->index('customer_id');
            $table->index('vehicle_id');
            $table->index('status');
            $table->index('created_at');
        });

        // Service packages table indexes
        Schema::table('service_packages', function (Blueprint $table) {
            $table->index('package_id');
            $table->index('service_id');
            $table->index('status');
        });

        // Notifications table indexes
        Schema::table('notifications', function (Blueprint $table) {
            $table->index('user_id');
            $table->index('read_at');
            $table->index('created_at');
        });

        // Documents table indexes
        Schema::table('documents', function (Blueprint $table) {
            $table->index('customer_id');
            $table->index('vehicle_id');
            $table->index('type');
            $table->index('created_at');
        });
    }

    public function down()
    {
        // Customers table indexes
        Schema::table('customers', function (Blueprint $table) {
            $table->dropIndex(['email']);
            $table->dropIndex(['phone']);
            $table->dropIndex(['created_at']);
        });

        // Vehicles table indexes
        Schema::table('vehicles', function (Blueprint $table) {
            $table->dropIndex(['plate_number']);
            $table->dropIndex(['customer_id']);
            $table->dropIndex(['created_at']);
        });

        // Packages table indexes
        Schema::table('packages', function (Blueprint $table) {
            $table->dropIndex(['customer_id']);
            $table->dropIndex(['vehicle_id']);
            $table->dropIndex(['status']);
            $table->dropIndex(['created_at']);
        });

        // Service packages table indexes
        Schema::table('service_packages', function (Blueprint $table) {
            $table->dropIndex(['package_id']);
            $table->dropIndex(['service_id']);
            $table->dropIndex(['status']);
        });

        // Notifications table indexes
        Schema::table('notifications', function (Blueprint $table) {
            $table->dropIndex(['user_id']);
            $table->dropIndex(['read_at']);
            $table->dropIndex(['created_at']);
        });

        // Documents table indexes
        Schema::table('documents', function (Blueprint $table) {
            $table->dropIndex(['customer_id']);
            $table->dropIndex(['vehicle_id']);
            $table->dropIndex(['type']);
            $table->dropIndex(['created_at']);
        });
    }
}; 