<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('user_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('skill_level')->default('intermediate'); // beginner, intermediate, advanced, pro
            $table->string('play_style')->nullable(); // smash_attack, all_round, control_defense
            $table->string('preferred_hand')->default('right'); // right, left
            $table->string('racket_brand')->nullable(); // Yonex, Victor, Lining
            $table->unsignedInteger('smash_speed_max')->default(180); // km/h
            $table->unsignedInteger('matches_played')->default(0);
            $table->decimal('win_rate', 5, 2)->default(50.00); // percentage
            $table->text('bio')->nullable();
            $table->timestamps();
        });

        Schema::create('membership_tiers', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Thành viên Bạc, Vàng, Kim Cương VIP
            $table->string('slug')->unique();
            $table->decimal('min_spending', 12, 2)->default(0);
            $table->unsignedTinyInteger('discount_percentage')->default(0);
            $table->unsignedSmallInteger('priority_booking_days')->default(3);
            $table->text('perk_description')->nullable();
            $table->timestamps();
        });

        Schema::create('user_memberships', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('membership_tier_id')->constrained()->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });

        Schema::create('loyalty_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->integer('points_change');
            $table->integer('balance_after');
            $table->string('reason');
            $table->string('reference_id')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('loyalty_transactions');
        Schema::dropIfExists('user_memberships');
        Schema::dropIfExists('membership_tiers');
        Schema::dropIfExists('user_profiles');
    }
};
