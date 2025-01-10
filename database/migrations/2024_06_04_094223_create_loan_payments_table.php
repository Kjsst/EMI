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
        Schema::create('loan_payments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("user_id");
            $table->integer("loan_id");
            $table->decimal("amount",15,2);
            $table->date('due_date')->nullable();
            $table->string("payment_mode")->comment('online,offline')->nullable();
            $table->date('paid_date')->nullable();
            $table->date('paid_by_user')->nullable();
            $table->decimal('paid_amount',15,2)->nullable();
            $table->string('remarks')->nullable();
            $table->string("status")->comment('0:upcoming,1:success,2:failed,3:inProgress,4:due')->default('upcoming');
            $table->tinyInteger("message")->comment('0:Upcoming,1:EMI Paid,2:Payment Failed,3:Request In Progress,4:Due EMI')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('loan_payments');
    }
};
