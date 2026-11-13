<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('match_posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('booking_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('venue_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->dateTime('play_datetime');
            $table->decimal('duration_hours', 3, 1)->default(2.0);
            $table->string('target_level')->default('all'); // beginner, intermediate, advanced, all
            $table->unsignedTinyInteger('players_needed')->default(4);
            $table->unsignedTinyInteger('players_joined')->default(1);
            $table->decimal('fee_per_player', 12, 2)->default(0.00);
            $table->text('description')->nullable();
            $table->string('status')->default('open')->index(); // open, full, completed, cancelled
            $table->timestamps();

            $table->index(['venue_id', 'play_datetime']);
        });

        Schema::create('match_participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_post_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('status')->default('joined'); // requested, approved, joined, cancelled
            $table->string('deposit_status')->default('unpaid'); // unpaid, paid, refunded
            $table->timestamps();

            $table->unique(['match_post_id', 'user_id']);
        });

        Schema::create('tournaments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venue_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->string('slug')->unique();
            $table->string('banner_image')->nullable();
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('prize_pool', 12, 2)->default(0.00);
            $table->string('format')->default('knockout'); // knockout, round_robin, group_stage
            $table->string('status')->default('upcoming'); // upcoming, ongoing, completed
            $table->timestamps();
        });

        Schema::create('tournament_matches', function (Blueprint $table) {
            $table->id();
            $table->foreignId('tournament_id')->constrained()->cascadeOnDelete();
            $table->foreignId('court_id')->nullable()->constrained()->nullOnDelete();
            $table->string('round_name'); // Vòng 1/16, Tứ kết, Bán kết, Chung kết
            $table->string('team_a_name');
            $table->string('team_b_name');
            $table->string('score_set_1')->nullable(); // vd: 21-19
            $table->string('score_set_2')->nullable(); // vd: 18-21
            $table->string('score_set_3')->nullable(); // vd: 21-17
            $table->string('winner_team')->nullable();
            $table->string('status')->default('scheduled'); // scheduled, live, finished
            $table->dateTime('match_time')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tournament_matches');
        Schema::dropIfExists('tournaments');
        Schema::dropIfExists('match_participants');
        Schema::dropIfExists('match_posts');
    }
};
