<?php

namespace Database\Seeders;

use App\Models\Booking;
use App\Models\BookingItem;
use App\Models\Court;
use App\Models\CourtPricingRule;
use App\Models\CourtType;
use App\Models\OperatingHour;
use App\Models\User;
use App\Models\Venue;
use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password123');

        // 1. Admin User
        $admin = User::firstOrCreate(
            ['email' => 'admin@gosport.vn'],
            [
                'name' => 'Quản Trị Viên GoSport',
                'phone' => '0901000001',
                'password' => $password,
                'role' => 'admin',
            ]
        );

        // 2. Client User
        $client = User::firstOrCreate(
            ['email' => 'customer@gosport.vn'],
            [
                'name' => 'Nguyễn Văn Tuấn (Customer)',
                'phone' => '0988889999',
                'password' => $password,
                'role' => 'client',
            ]
        );

        // 3. Court Types
        $courtTypeStandard = CourtType::firstOrCreate(
            ['name' => 'Sân Tiêu Chuẩn BWF'],
            [
                'description' => 'Thảm cao su chuẩn thi đấu quốc tế, độ bám cao, giảm chấn đầu gối',
                'is_premium' => false,
            ]
        );

        $courtTypeVIP = CourtType::firstOrCreate(
            ['name' => 'Sân VIP Lounge Chuyên Nghiệp'],
            [
                'description' => 'Không gian riêng tư, ghế sofa da cao cấp, hệ thống đèn chống lóa',
                'is_premium' => true,
            ]
        );

        // 4. Venues
        $venue1 = Venue::firstOrCreate(
            ['slug' => 'gosport-arena-cau-giay'],
            [
                'owner_id' => $admin->id,
                'name' => 'GoSport Arena Cầu Giấy',
                'address' => 'Số 26 Phạm Văn Đồng, Mai Dịch, Cầu Giấy',
                'district' => 'Cầu Giấy',
                'city' => 'Hà Nội',
                'latitude' => 21.036800,
                'longitude' => 105.782500,
                'phone' => '024 3888 6666',
                'email' => 'caugiay@gosport.vn',
                'featured_image' => 'https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?w=1200&auto=format&fit=crop&q=80',
                'description' => 'Tổ hợp 8 sân cầu lông trong nhà lớn nhất khu vực Cầu Giấy. Thảm Yonex BWF cao cấp, máy lạnh trung tâm, hệ thống ánh sáng chống chói mắt tiêu chuẩn quốc tế.',
                'opening_time' => '06:00:00',
                'closing_time' => '23:00:00',
                'is_active' => true,
                'rating_avg' => 4.90,
                'reviews_count' => 128,
            ]
        );

        $venue2 = Venue::firstOrCreate(
            ['slug' => 'gosport-premium-my-dinh'],
            [
                'owner_id' => $admin->id,
                'name' => 'GoSport Premium Mỹ Đình',
                'address' => 'Khu liên hợp Thể Thao Quốc Gia, Mỹ Đình, Nam Từ Liêm',
                'district' => 'Nam Từ Liêm',
                'city' => 'Hà Nội',
                'latitude' => 21.020500,
                'longitude' => 105.764500,
                'phone' => '024 3999 8888',
                'email' => 'mydinh@gosport.vn',
                'featured_image' => 'https://images.unsplash.com/photo-1546519638-68e109498ffc?w=1200&auto=format&fit=crop&q=80',
                'description' => 'Cụm 6 sân tiêu chuẩn thi đấu nằm tại trung tâm Mỹ Đình với bãi đỗ xe ô tô rộng rãi, phòng tắm nóng lạnh miễn phí.',
                'opening_time' => '06:00:00',
                'closing_time' => '23:00:00',
                'is_active' => true,
                'rating_avg' => 4.85,
                'reviews_count' => 96,
            ]
        );

        $venue3 = Venue::firstOrCreate(
            ['slug' => 'gosport-star-thanh-xuan'],
            [
                'owner_id' => $admin->id,
                'name' => 'GoSport Star Thanh Xuân',
                'address' => 'Số 12 Khuất Duy Tiến, Thanh Xuân Bắc, Thanh Xuân',
                'district' => 'Thanh Xuân',
                'city' => 'Hà Nội',
                'latitude' => 20.998000,
                'longitude' => 105.799000,
                'phone' => '024 3777 5555',
                'email' => 'thanhxuan@gosport.vn',
                'featured_image' => 'https://images.unsplash.com/photo-1511067007772-9da29974ce44?w=1200&auto=format&fit=crop&q=80',
                'description' => 'Cụm 6 sân hiện đại trung tâm Thanh Xuân, giao thông thuận tiện, thảm đạt chuẩn thi đấu.',
                'opening_time' => '06:00:00',
                'closing_time' => '23:00:00',
                'is_active' => true,
                'rating_avg' => 4.80,
                'reviews_count' => 74,
            ]
        );

        // 5. Operating Hours
        $venues = [$venue1, $venue2, $venue3];
        foreach ($venues as $v) {
            for ($day = 0; $day <= 6; $day++) {
                OperatingHour::firstOrCreate(
                    ['venue_id' => $v->id, 'day_of_week' => $day],
                    ['open_time' => '06:00:00', 'close_time' => '23:00:00', 'is_closed' => false]
                );
            }
        }

        // 6. Pricing Rules
        $rules = [
            ['name' => 'Khung giờ thường ngày thường', 'day_type' => 'weekday', 'start_time' => '06:00:00', 'end_time' => '17:00:00', 'price_per_hour' => 120000, 'is_peak' => false],
            ['name' => 'Khung giờ vàng tối (Peak Slot)', 'day_type' => 'weekday', 'start_time' => '17:00:00', 'end_time' => '21:00:00', 'price_per_hour' => 200000, 'is_peak' => true],
            ['name' => 'Khung đêm muộn', 'day_type' => 'weekday', 'start_time' => '21:00:00', 'end_time' => '23:00:00', 'price_per_hour' => 150000, 'is_peak' => false],
            ['name' => 'Khung cuối tuần (Thứ 7 & CN)', 'day_type' => 'weekend', 'start_time' => '06:00:00', 'end_time' => '23:00:00', 'price_per_hour' => 190000, 'is_peak' => true],
        ];

        foreach ($venues as $v) {
            foreach ($rules as $r) {
                CourtPricingRule::firstOrCreate(
                    ['venue_id' => $v->id, 'name' => $r['name']],
                    $r
                );
            }
        }

        // 7. Courts
        // Venue 1 courts (8 courts)
        for ($i = 1; $i <= 8; $i++) {
            Court::firstOrCreate(
                ['venue_id' => $venue1->id, 'court_number' => $i],
                [
                    'name' => "Sân {$i} - " . ($i === 3 ? 'VIP Private Lounge' : 'Yonex Pro Court'),
                    'court_type_id' => ($i === 3 ? $courtTypeVIP->id : $courtTypeStandard->id),
                    'surface_type' => 'Yonex BWF Standard',
                    'is_active' => true,
                ]
            );
        }

        // Venue 2 courts (6 courts)
        for ($i = 1; $i <= 6; $i++) {
            Court::firstOrCreate(
                ['venue_id' => $venue2->id, 'court_number' => $i],
                [
                    'name' => "Sân {$i} - Mỹ Đình " . ($i === 1 ? 'Center Court' : 'Pro Standard'),
                    'court_type_id' => ($i === 1 ? $courtTypeVIP->id : $courtTypeStandard->id),
                    'surface_type' => 'Yonex BWF Standard',
                    'is_active' => true,
                ]
            );
        }

        // Venue 3 courts (6 courts)
        for ($i = 1; $i <= 6; $i++) {
            Court::firstOrCreate(
                ['venue_id' => $venue3->id, 'court_number' => $i],
                [
                    'name' => "Sân {$i} - Thanh Xuân Champion",
                    'court_type_id' => $courtTypeStandard->id,
                    'surface_type' => 'Victor Pro Mat',
                    'is_active' => true,
                ]
            );
        }

        // 8. Sample Booking
        $court1 = Court::where('venue_id', $venue1->id)->where('court_number', 1)->first();
        if ($court1) {
            $bookingDate = Carbon::tomorrow()->toDateString();
            $booking = Booking::firstOrCreate(
                ['booking_code' => 'GS-SAMPLE-001'],
                [
                    'user_id' => $client->id,
                    'venue_id' => $venue1->id,
                    'booking_date' => $bookingDate,
                    'total_amount' => 400000.00,
                    'discount_amount' => 0.00,
                    'final_amount' => 400000.00,
                    'status' => 'confirmed',
                    'payment_status' => 'unpaid',
                    'payment_method' => 'pay_at_venue',
                    'check_in_token' => 'GSCK-' . Str::uuid()->toString(),
                    'notes' => 'Đơn đặt mẫu thử nghiệm hệ thống',
                ]
            );

            BookingItem::firstOrCreate(
                [
                    'booking_id' => $booking->id,
                    'court_id' => $court1->id,
                    'start_time' => '18:00:00',
                    'end_time' => '20:00:00',
                ],
                [
                    'duration_minutes' => 120,
                    'hourly_rate' => 200000.00,
                    'amount' => 400000.00,
                ]
            );

            $booking->statusHistories()->firstOrCreate(
                ['new_status' => 'confirmed'],
                [
                    'old_status' => null,
                    'changed_by_user_id' => $client->id,
                    'reason' => 'Đặt sân thử nghiệm ban đầu',
                ]
            );
        }
    }
}
