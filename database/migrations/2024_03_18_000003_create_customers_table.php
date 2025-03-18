<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->string('first_name');
            $table->string('last_name');
            $table->string('identity_number')->nullable();
            $table->string('tax_number')->nullable();
            $table->string('email');
            $table->string('phone_number');
            $table->text('address');
            $table->string('city');
            $table->string('district');
            $table->string('postal_code')->nullable();
            $table->string('customer_type');
            $table->string('company_name')->nullable();
            $table->string('tax_office')->nullable();
            $table->text('notes')->nullable();
            $table->unsignedBigInteger('created_by');
            $table->unsignedBigInteger('creator_id');
            $table->timestamps();
            $table->boolean('is_active')->default(true);
            $table->boolean('is_deleted')->default(false);
        });
    }

    public function down()
    {
        Schema::dropIfExists('customers');
    }
}; 