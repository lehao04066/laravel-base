<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $user = DB::table('users')->where('email', 'customer@gosport.vn')->first();
        if (! $user || DB::table('match_results')->where('user_id', $user->id)->exists()) {
            return;
        }

        $venues = DB::table('venues')->whereNull('deleted_at')->orderBy('id')->pluck('id')->values();
        $now = now();
        DB::table('match_results')->insert([
            ['user_id' => $user->id, 'venue_id' => $venues->get(0), 'opponent_name' => 'Trần Minh Đức', 'score' => '21-18, 21-16', 'result' => 'won', 'played_at' => $now->copy()->subDays(14), 'notes' => 'Trận giao lưu buổi tối', 'created_at' => $now, 'updated_at' => $now],
            ['user_id' => $user->id, 'venue_id' => $venues->get(0), 'opponent_name' => 'Lê Hoàng Nam', 'score' => '19-21, 21-17, 18-21', 'result' => 'lost', 'played_at' => $now->copy()->subDays(9), 'notes' => 'Đánh đôi cuối tuần', 'created_at' => $now, 'updated_at' => $now],
            ['user_id' => $user->id, 'venue_id' => $venues->get(1), 'opponent_name' => 'Phạm Tuấn Anh', 'score' => '21-15, 21-19', 'result' => 'won', 'played_at' => $now->copy()->subDays(3), 'notes' => 'Trận luyện tập', 'created_at' => $now, 'updated_at' => $now],
        ]);

        DB::table('user_profiles')->where('user_id', $user->id)->update(['matches_played' => 3, 'win_rate' => 66.67]);
    }

    public function down(): void
    {
        $user = DB::table('users')->where('email', 'customer@gosport.vn')->first();
        if ($user) {
            DB::table('match_results')->where('user_id', $user->id)->delete();
            DB::table('user_profiles')->where('user_id', $user->id)->update(['matches_played' => 0, 'win_rate' => 50]);
        }
    }
};
