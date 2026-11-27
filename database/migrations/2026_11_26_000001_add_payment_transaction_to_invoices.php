<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->foreignId('payment_transaction_id')->nullable()->unique()->after('booking_id')
                ->constrained('payment_transactions')->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropForeign(['payment_transaction_id']);
            $table->dropUnique(['payment_transaction_id']);
            $table->dropColumn('payment_transaction_id');
        });
    }
};
