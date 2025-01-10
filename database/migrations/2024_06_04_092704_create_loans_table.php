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
        Schema::create('loans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->unsignedBigInteger("merchant_id");
            $table->decimal("billing_amount",15,2);
            $table->decimal("file_charge",15,2);
            $table->decimal("down_payment",15,2);
            $table->decimal("loan_amount",15,2);
            $table->decimal("interest",15,2);
            $table->integer("month");
            $table->decimal("monthly_amount",15,2);
            $table->date("first_emi_date");
            $table->decimal("total_amount",15,2);
            $table->decimal("total_interest",15,2);
            $table->tinyInteger("status")->comment('1:in progress,2:completed');
            $table->integer("lock_type")->default('1')->comment('1:mannuel ,2:mannual with auto');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loans');
    }
};
