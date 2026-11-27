<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('iot_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venue_id')->constrained()->cascadeOnDelete();
            $table->foreignId('court_id')->nullable()->constrained()->nullOnDelete();
            $table->string('device_type'); // gateway, relay_controller, smart_meter, barrier_gate, led_scoreboard, kiosk_terminal
            $table->string('device_name');
            $table->string('serial_number', 100)->unique();
            $table->string('ip_address', 45)->nullable();
            $table->string('status')->default('online')->index(); // online, offline, error, maintenance
            $table->timestamp('last_heartbeat')->nullable();
            $table->timestamps();
        });

        Schema::create('device_events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('iot_device_id')->constrained()->cascadeOnDelete();
            $table->string('event_type'); // power_on, power_off, barrier_open, heartbeat, score_sync
            $table->json('payload')->nullable();
            $table->timestamp('created_at')->useCurrent();
        });

        Schema::create('device_configurations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('iot_device_id')->constrained()->cascadeOnDelete();
            $table->string('config_key');
            $table->text('config_value');
            $table->timestamps();

            $table->unique(['iot_device_id', 'config_key']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('device_configurations');
        Schema::dropIfExists('device_events');
        Schema::dropIfExists('iot_devices');
    }
};
