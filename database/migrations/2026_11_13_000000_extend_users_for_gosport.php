<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if ($this->isLegacyImportedSchema()) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $table->string('phone')->nullable()->unique();
            $table->string('role')->default('customer')->index();
            $table->string('avatar')->nullable();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        if ($this->isLegacyImportedSchema()) {
            return;
        }

        Schema::table('users', function (Blueprint $table) {
            $table->dropUnique('users_phone_unique');
            $table->dropIndex('users_role_index');
            $table->dropColumn(['phone', 'role', 'avatar', 'deleted_at']);
        });
    }

    private function isLegacyImportedSchema(): bool
    {
        return DB::table('migrations')
            ->where('migration', '2026_01_01_000001_update_users_and_create_roles_table')
            ->exists();
    }
};
