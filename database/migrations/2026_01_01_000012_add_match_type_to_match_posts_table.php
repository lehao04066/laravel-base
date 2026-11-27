<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('match_posts', function (Blueprint $table) {
            $table->string('match_type')->default('doubles')->after('title');
        });
    }

    public function down(): void
    {
        Schema::table('match_posts', function (Blueprint $table) {
            $table->dropColumn('match_type');
        });
    }
};
