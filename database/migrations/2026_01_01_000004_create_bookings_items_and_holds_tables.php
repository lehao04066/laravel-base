<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('booking_holds', function (Blueprint $table) {
            $table->id();
            $table->foreignId('court_id')->constrained()->cascadeOnDelete();
            $table->date('booking_date');
            $table->time('start_time');
            $table->time('end_time');
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('session_id', 100);
            $table->timestamp('expires_at');
            $table->timestamps();

            $table->index(['court_id', 'booking_date', 'start_time', 'end_time'], 'idx_hold_slot');
            $table->index('expires_at');
        });

        Schema::create('bookings', function (Blueprint $table) {
            $table->id();
            $table->string('booking_code', 32)->unique();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('venue_id')->constrained()->cascadeOnDelete();
            $table->date('booking_date')->index();
            $table->decimal('total_amount', 12, 2);
            $table->decimal('discount_amount', 12, 2)->default(0.00);
            $table->decimal('final_amount', 12, 2);
            $table->string('status')->default('pending_payment')->index(); // pending_payment, confirmed, checked_in, completed, cancelled, expired, no_show
            $table->string('payment_status')->default('unpaid')->index(); // unpaid, partially_paid, paid, refunded
            $table->string('payment_method')->nullable(); // vnpay, vietqr, wallet, cash, card
            $table->string('check_in_token', 64)->unique();
            $table->text('notes')->nullable();
            $table->foreignId('created_by_staff_id')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
            $table->softDeletes();

            $table->index(['user_id', 'status']);
            $table->index(['venue_id', 'booking_date']);
        });

        Schema::create('booking_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('court_id')->constrained()->cascadeOnDelete();
            $table->time('start_time');
            $table->time('end_time');
            $table->unsignedSmallInteger('duration_minutes')->default(60);
            $table->decimal('hourly_rate', 12, 2);
            $table->decimal('amount', 12, 2);
            $table->timestamps();

            $table->index(['court_id', 'start_time', 'end_time']);
        });

        Schema::create('booking_status_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->string('old_status')->nullable();
            $table->string('new_status');
            $table->foreignId('changed_by_user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('reason')->nullable();
            $table->timestamps();
        });

        Schema::create('check_ins', function (Blueprint $table) {
            $table->id();
            $table->foreignId('booking_id')->constrained()->cascadeOnDelete();
            $table->foreignId('court_id')->constrained()->cascadeOnDelete();
            $table->timestamp('check_in_time');
            $table->timestamp('check_out_time')->nullable();
            $table->string('method')->default('qr_scan'); // qr_scan, staff_manual, kiosk_barrier
            $table->foreignId('verified_by_staff_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('gate_id')->nullable();
            $table->timestamps();

            $table->index(['booking_id', 'court_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('check_ins');
        Schema::dropIfExists('booking_status_histories');
        Schema::dropIfExists('booking_items');
        Schema::dropIfExists('bookings');
        Schema::dropIfExists('booking_holds');
    }
};
