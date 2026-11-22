<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('staff_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->unique()->constrained()->cascadeOnDelete();
            $table->foreignId('venue_id')->nullable()->constrained()->nullOnDelete();
            $table->string('employee_code', 30)->unique();
            $table->string('position'); // receptionist, cashier, court_manager, stringer, technician
            $table->string('salary_type')->default('monthly'); // monthly, hourly
            $table->decimal('base_salary', 12, 2)->default(0.00);
            $table->date('hire_date')->nullable();
            $table->timestamps();
        });

        Schema::create('staff_shifts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('venue_id')->constrained()->cascadeOnDelete();
            $table->date('shift_date');
            $table->string('shift_type'); // morning (06-14), afternoon (14-22), night (22-06)
            $table->time('start_time');
            $table->time('end_time');
            $table->timestamp('clock_in_time')->nullable();
            $table->timestamp('clock_out_time')->nullable();
            $table->string('status')->default('scheduled'); // scheduled, present, late, absent
            $table->timestamps();

            $table->index(['venue_id', 'shift_date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('staff_shifts');
        Schema::dropIfExists('staff_profiles');
    }
};
