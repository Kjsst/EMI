<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('merchant_id');
            $table->string('alter_mobile');
            $table->text('address');
            $table->string('imei1');
            $table->string('imei2');
            $table->tinyInteger('device_status')->comment('0:locked,1:unlocked');
            $table->tinyInteger('coin_type')->comment('1= v1,Ram Baan, 2= v2,Brahmastra');
            $table->string('aadhar_front')->nullable();
            $table->string('aadhar_back')->nullable();
            $table->string('pan_card')->nullable();
            $table->string('customer_photo')->nullable();
            $table->string('merchant_photo')->nullable();
            $table->string('customer_with_device')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('ifsc')->nullable();
            $table->string('blank_cheque')->nullable();
            $table->string('pin')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
