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
        Schema::table('transfer_coins', function (Blueprint $table) {
            $table->integer('from_user_brahmastra_coin')->nullable()->after('coin_type');
            $table->integer('from_user_rambaan_coin')->nullable()->after('coin_type');
            $table->integer('to_user_brahmastra_coin')->nullable()->after('coin_type');
            $table->integer('to_user_rambaan_coin')->nullable()->after('coin_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('transfer_coins', function (Blueprint $table) {
            $table->dropColumn('from_user_brahmastra_coin');
            $table->dropColumn('from_user_rambaan_coin');
            $table->dropColumn('to_user_brahmastra_coin');
            $table->dropColumn('to_user_rambaan_coin');
        });
    }
};
