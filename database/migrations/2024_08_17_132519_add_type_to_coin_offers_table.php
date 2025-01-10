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
        Schema::table('coin_offers', function (Blueprint $table) {
             $table->string('coin_type')->nullable()->after('get_coin');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('coin_offers', function (Blueprint $table) {
            $table->dropColumn('coin_type');
        });
    }
};
