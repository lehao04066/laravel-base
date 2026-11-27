<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('venues', function (Blueprint $table) {
            $table->id();
            $table->foreignId('owner_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('address');
            $table->string('district');
            $table->string('city')->default('Hà Nội');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->string('phone', 20);
            $table->string('email')->nullable();
            $table->string('featured_image')->nullable();
            $table->json('gallery')->nullable();
            $table->text('description')->nullable();
            $table->time('opening_time')->default('06:00:00');
            $table->time('closing_time')->default('23:00:00');
            $table->boolean('is_active')->default(true)->index();
            $table->decimal('rating_avg', 3, 2)->default(5.00);
            $table->unsignedInteger('reviews_count')->default(0);
            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('court_types', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Tiêu chuẩn BWF, Sân VIP, Sân Trung tâm thi đấu
            $table->string('description')->nullable();
            $table->boolean('is_premium')->default(false);
            $table->timestamps();
        });

        Schema::create('courts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venue_id')->constrained()->cascadeOnDelete();
            $table->unsignedSmallInteger('court_number');
            $table->string('name');
            $table->foreignId('court_type_id')->nullable()->constrained()->nullOnDelete();
            $table->string('surface_type')->default('Yonex BWF Standard');
            $table->boolean('is_active')->default(true)->index();
            $table->unsignedTinyInteger('iot_relay_channel')->nullable();
            $table->string('camera_stream_url')->nullable();
            $table->timestamps();
            $table->softDeletes();

            $table->unique(['venue_id', 'court_number']);
        });

        Schema::create('amenities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('icon'); // ac_unit, local_parking, shower, wifi, sports_bar
            $table->timestamps();
        });

        Schema::create('venue_amenities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venue_id')->constrained()->cascadeOnDelete();
            $table->foreignId('amenity_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['venue_id', 'amenity_id']);
        });

        Schema::create('operating_hours', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venue_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('day_of_week'); // 0 = Sunday, 1 = Monday, ... 6 = Saturday
            $table->time('open_time')->default('06:00:00');
            $table->time('close_time')->default('23:00:00');
            $table->boolean('is_closed')->default(false);
            $table->timestamps();
        });

        Schema::create('court_pricing_rules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('venue_id')->constrained()->cascadeOnDelete();
            $table->foreignId('court_type_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name'); // Giờ thường sáng/chiều, Giờ vàng tối, Cuối tuần
            $table->string('day_type')->default('all'); // all, weekday, weekend
            $table->time('start_time');
            $table->time('end_time');
            $table->decimal('price_per_hour', 12, 2);
            $table->boolean('is_peak')->default(false);
            $table->timestamps();

            $table->index(['venue_id', 'start_time', 'end_time']);
        });

        Schema::create('court_maintenance', function (Blueprint $table) {
            $table->id();
            $table->foreignId('court_id')->constrained()->cascadeOnDelete();
            $table->string('title');
            $table->text('reason')->nullable();
            $table->dateTime('start_datetime');
            $table->dateTime('end_datetime');
            $table->string('status')->default('scheduled'); // scheduled, in_progress, completed, cancelled
            $table->timestamps();

            $table->index(['court_id', 'start_datetime', 'end_datetime']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('court_maintenance');
        Schema::dropIfExists('court_pricing_rules');
        Schema::dropIfExists('operating_hours');
        Schema::dropIfExists('venue_amenities');
        Schema::dropIfExists('amenities');
        Schema::dropIfExists('courts');
        Schema::dropIfExists('court_types');
        Schema::dropIfExists('venues');
    }
};
