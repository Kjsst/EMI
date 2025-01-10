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
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('imei1')->nullable();
            $table->string('imei2')->nullable();
            $table->string('model')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('mobile_one')->nullable();
            $table->string('mobile_one_network')->nullable();
            $table->string('mobile_two')->nullable();
            $table->string('mobile_two_network')->nullable();
            $table->datetime('sync_at')->nullable();
            $table->string('notification_type')->nullable();
            $table->string('zt_status')->nullable()->comment('1: IMEI not register with ZT,2: IMEI successfully register with ZT,3: IMEI already register with another ZT');
            $table->string('action_status')->nullable();
            $table->string('type')->nullable()->comment('1:QR Code Enrollment,2: Old phone enrollment with APK');
            $table->string('status')->nullable()->comment('1:Not Active Yet,2:Active and App Installed,3:Lock Stage,4:Uninstalled,5:Deactivating,6:In-Locking Stage');
            $table->tinyInteger('device_status')->comment('0:locked,1:unlocked');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('devices');
    }
};
