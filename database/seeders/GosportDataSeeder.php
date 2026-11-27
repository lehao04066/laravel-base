<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class GosportDataSeeder extends Seeder
{
    public function run(): void
    {
        $statements = [
            <<<'SQL'
            INSERT IGNORE INTO `amenities` (`id`, `name`, `icon`, `created_at`, `updated_at`) VALUES
            (1, 'Sân điều hòa mát mẻ', 'ac_unit', '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
            (2, 'Có bãi đỗ ô tô miễn phí', 'directions_car', '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
            (3, 'Mở cửa đêm 24/7', 'bedtime', '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
            (4, 'Phòng tắm nóng lạnh VIP', 'shower', '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
            (5, 'Wifi tốc độ cao miễn phí', 'wifi', '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
            (6, 'Quầy đồ uống & Phụ kiện F&B', 'local_cafe', '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
            (7, 'Dịch vụ căng vợt điện tử', 'build_circle', '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
            (8, 'Camera AI bắt lỗi vạch', 'videocam', '2026-11-09 03:07:17', '2026-11-09 03:07:17')
            SQL,
            <<<'SQL'
            INSERT IGNORE INTO `bookings` (`id`, `booking_code`, `user_id`, `venue_id`, `booking_date`, `total_amount`, `discount_amount`, `final_amount`, `status`, `payment_status`, `payment_method`, `check_in_token`, `notes`, `created_by_staff_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
            (1, 'GS-20261109-001', 6, 1, '2026-11-09', 400000.00, 50000.00, 350000.00, 'checked_in', 'paid', 'vnpay', 'QR-H30MU2RYVKJQHJTT', 'Khách VIP - đặt sân cố định hàng tuần', NULL, '2026-11-09 03:07:17', '2026-11-09 03:40:11', NULL),
            (2, 'GS-20261109-V7XRHL', 7, 1, '2026-11-10', 200000.00, 0.00, 200000.00, 'confirmed', 'unpaid', 'cash', 'GSCK-c1c6fc0b-c44f-4585-aa90-99c1b101b9b6', NULL, NULL, '2026-11-09 04:14:27', '2026-11-09 04:14:40', NULL)
            SQL,
            <<<'SQL'
            INSERT IGNORE INTO `booking_items` (`id`, `booking_id`, `court_id`, `start_time`, `end_time`, `duration_minutes`, `hourly_rate`, `amount`, `created_at`, `updated_at`) VALUES
            (1, 1, 1, '18:00:00', '20:00:00', 120, 200000.00, 400000.00, '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
            (2, 2, 1, '17:00:00', '18:00:00', 60, 200000.00, 200000.00, '2026-11-09 04:14:27', '2026-11-09 04:14:27')
            SQL,
            <<<'SQL'
            INSERT IGNORE INTO `booking_status_histories` (`id`, `booking_id`, `old_status`, `new_status`, `changed_by_user_id`, `reason`, `created_at`, `updated_at`) VALUES
            (1, 1, 'confirmed', 'checked_in', 6, 'Check-in thành công qua kiosk_barrier', '2026-11-09 03:40:11', '2026-11-09 03:40:11'),
            (2, 2, NULL, 'pending_payment', 7, 'Khởi tạo đơn đặt sân trực tuyến', '2026-11-09 04:14:27', '2026-11-09 04:14:27')
            SQL,
            <<<'SQL'
            INSERT IGNORE INTO `check_ins` (`id`, `booking_id`, `court_id`, `check_in_time`, `check_out_time`, `method`, `verified_by_staff_id`, `gate_id`, `created_at`, `updated_at`) VALUES
            (1, 1, 1, '2026-11-09 03:40:11', NULL, 'kiosk_barrier', NULL, 'BAR-CG-GATE-01', '2026-11-09 03:40:11', '2026-11-09 03:40:11')
            SQL,
            <<<'SQL'
            INSERT IGNORE INTO `coaches` (`id`, `user_id`, `full_name`, `title`, `bio`, `hourly_rate`, `avatar`, `rating_avg`, `students_count`, `created_at`, `updated_at`) VALUES
            (1, 5, 'Nguyễn Văn Hùng', 'HLV Kiện tướng Quốc gia - Cựu VĐV ĐTQG', 'Hơn 12 năm kinh nghiệm huấn luyện chuyên sâu từ cơ bản đến nâng cao. Chuyên gia phân tích kỹ thuật Smash, xoay chuyển bộ pháp và chiến thuật thi đấu đánh đôi.', 350000.00, 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400&auto=format&fit=crop&q=80', 4.95, 64, '2026-11-09 03:07:17', '2026-11-09 03:07:17')
            SQL,
            <<<'SQL'
            INSERT IGNORE INTO `courses` (`id`, `coach_id`, `title`, `slug`, `level`, `price`, `max_students`, `description`, `created_at`, `updated_at`) VALUES
            (1, 1, 'Khóa Học Nâng Cao: Bí Quyết Smash Cầu Lửa 240km/h', 'smash-mastery-pro', 'smash_mastery', 2400000.00, 6, 'Khóa học 8 buổi tập trung tối ưu hóa điểm tiếp xúc cầu, lực cổ tay, xoay lườn và bật nhảy smash uy lực chính xác.', '2026-11-09 03:07:17', '2026-11-09 03:07:17')
            SQL,
            <<<'SQL'
            INSERT IGNORE INTO `course_schedules` (`id`, `course_id`, `venue_id`, `day_of_week`, `start_time`, `end_time`, `created_at`, `updated_at`) VALUES
            (1, 1, 1, 2, '19:00:00', '21:00:00', '2026-11-09 03:07:17', '2026-11-09 03:07:17')
            SQL,
            <<<'SQL'
            INSERT IGNORE INTO `courts` (`id`, `venue_id`, `court_number`, `name`, `court_type_id`, `surface_type`, `is_active`, `iot_relay_channel`, `camera_stream_url`, `created_at`, `updated_at`, `deleted_at`) VALUES
            (1, 1, 1, 'Sân 1 - Yonex Pro Court', 1, 'Yonex BWF Standard', 1, 1, NULL, '2026-11-09 03:07:17', '2026-11-09 03:07:17', NULL),
            (2, 1, 2, 'Sân 2 - Victor Club', 1, 'Victor Pro Mat', 1, 2, NULL, '2026-11-09 03:07:17', '2026-11-09 03:07:17', NULL),
            (3, 1, 3, 'Sân 3 - VIP Private Lounge', 2, 'Yonex Pro Cushion Mat', 1, 3, NULL, '2026-11-09 03:07:17', '2026-11-09 03:07:17', NULL),
            (4, 1, 4, 'Sân 4 - Li-Ning Speed Court', 1, 'Li-Ning Standard', 1, 4, NULL, '2026-11-09 03:07:17', '2026-11-09 03:07:17', NULL),
            (5, 1, 5, 'Sân 5 - GoSport Arena Center', 1, 'Yonex BWF Standard', 1, 5, NULL, '2026-11-09 03:07:17', '2026-11-09 03:07:17', NULL),
            (6, 1, 6, 'Sân 6 - Victor Champion', 1, 'Victor Pro Mat', 1, 6, NULL, '2026-11-09 03:07:17', '2026-11-09 03:07:17', NULL),
            (7, 1, 7, 'Sân 7 - Smash Master', 1, 'Yonex BWF Standard', 1, 7, NULL, '2026-11-09 03:07:17', '2026-11-09 03:07:17', NULL),
            (8, 1, 8, 'Sân 8 - Training Academy', 1, 'Yonex BWF Standard', 1, 8, NULL, '2026-11-09 03:07:17', '2026-11-09 03:07:17', NULL),
            (9, 2, 1, 'Sân 1 - Li-Ning Super Series', 1, 'Li-Ning Standard Mat', 1, 1, NULL, '2026-11-09 04:22:56', '2026-11-09 04:22:56', NULL),
            (10, 2, 2, 'Sân 2 - Yonex All England', 1, 'Yonex BWF Standard', 1, 2, NULL, '2026-11-09 04:22:56', '2026-11-09 04:22:56', NULL),
            (11, 2, 3, 'Sân 3 - Victor Master Court', 1, 'Victor Pro Mat', 1, 3, NULL, '2026-11-09 04:22:56', '2026-11-09 04:22:56', NULL),
            (12, 2, 4, 'Sân 4 - VIP Olympic Suite', 2, 'Yonex Pro Cushion Mat', 1, 4, NULL, '2026-11-09 04:22:56', '2026-11-09 04:22:56', NULL),
            (13, 2, 5, 'Sân 5 - GoSport Center Court', 1, 'Yonex BWF Standard', 1, 5, NULL, '2026-11-09 04:22:56', '2026-11-09 04:22:56', NULL),
            (14, 2, 6, 'Sân 6 - Training Court', 1, 'Victor Standard', 1, 6, NULL, '2026-11-09 04:22:56', '2026-11-09 04:22:56', NULL),
            (15, 3, 1, 'Sân 1 - Star Yonex Pro', 1, 'Yonex BWF Standard', 1, 1, NULL, '2026-11-09 04:22:56', '2026-11-09 04:22:56', NULL),
            (16, 3, 2, 'Sân 2 - Victor Fast Court', 1, 'Victor Pro Mat', 1, 2, NULL, '2026-11-09 04:22:56', '2026-11-09 04:22:56', NULL),
            (17, 3, 3, 'Sân 3 - VIP Red Court', 2, 'Yonex Pro Cushion Mat', 1, 3, NULL, '2026-11-09 04:22:56', '2026-11-09 04:22:56', NULL),
            (18, 3, 4, 'Sân 4 - Li-Ning Speed', 1, 'Li-Ning Standard', 1, 4, NULL, '2026-11-09 04:22:56', '2026-11-09 04:22:56', NULL),
            (19, 3, 5, 'Sân 5 - Youth Academy', 1, 'Yonex Standard', 1, 5, NULL, '2026-11-09 04:22:56', '2026-11-09 04:22:56', NULL),
            (20, 3, 6, 'Sân 6 - Practice Court', 1, 'Victor Standard', 1, 6, NULL, '2026-11-09 04:22:56', '2026-11-09 04:22:56', NULL)
            SQL,
            <<<'SQL'
            INSERT IGNORE INTO `court_pricing_rules` (`id`, `venue_id`, `court_type_id`, `name`, `day_type`, `start_time`, `end_time`, `price_per_hour`, `is_peak`, `created_at`, `updated_at`) VALUES
            (1, 1, NULL, 'Khung giờ thường sáng/chiều', 'weekday', '06:00:00', '17:00:00', 120000.00, 0, '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
            (2, 1, NULL, 'Khung giờ vàng tối (Peak Slot)', 'weekday', '17:00:00', '21:00:00', 200000.00, 1, '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
            (3, 1, NULL, 'Khung đêm muộn', 'weekday', '21:00:00', '23:30:00', 150000.00, 0, '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
            (4, 1, NULL, 'Khung cuối tuần (Thứ 7 - CN)', 'weekend', '06:00:00', '23:30:00', 190000.00, 1, '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
            (5, 2, NULL, 'Khung giờ thường sáng/chiều', 'weekday', '06:00:00', '17:00:00', 130000.00, 0, '2026-11-09 04:22:56', '2026-11-09 04:22:56'),
            (6, 2, NULL, 'Khung giờ vàng tối (Peak Slot)', 'weekday', '17:00:00', '21:00:00', 210000.00, 1, '2026-11-09 04:22:56', '2026-11-09 04:22:56'),
            (7, 2, NULL, 'Khung đêm muộn', 'weekday', '21:00:00', '23:00:00', 160000.00, 0, '2026-11-09 04:22:56', '2026-11-09 04:22:56'),
            (8, 2, NULL, 'Khung cuối tuần (Thứ 7 - CN)', 'weekend', '06:00:00', '23:00:00', 200000.00, 1, '2026-11-09 04:22:56', '2026-11-09 04:22:56'),
            (9, 3, NULL, 'Khung giờ thường sáng/chiều', 'weekday', '06:00:00', '17:00:00', 110000.00, 0, '2026-11-09 04:22:56', '2026-11-09 04:22:56'),
            (10, 3, NULL, 'Khung giờ vàng tối (Peak Slot)', 'weekday', '17:00:00', '21:00:00', 180000.00, 1, '2026-11-09 04:22:56', '2026-11-09 04:22:56'),
            (11, 3, NULL, 'Khung đêm muộn', 'weekday', '21:00:00', '23:00:00', 140000.00, 0, '2026-11-09 04:22:56', '2026-11-09 04:22:56'),
            (12, 3, NULL, 'Khung cuối tuần (Thứ 7 - CN)', 'weekend', '06:00:00', '23:00:00', 170000.00, 1, '2026-11-09 04:22:56', '2026-11-09 04:22:56')
            SQL,
            <<<'SQL'
            INSERT IGNORE INTO `court_types` (`id`, `name`, `description`, `is_premium`, `created_at`, `updated_at`) VALUES
            (1, 'Sân Tiêu Chuẩn BWF', 'Thảm cao su chuẩn thi đấu quốc tế, độ bám cao, giảm chấn đầu gối', 0, '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
            (2, 'Sân VIP Lounge Chuyên Nghiệp', 'Không gian riêng tư, ghế sofa da cao cấp, hệ thống đèn chống lóa Lumileds', 1, '2026-11-09 03:07:17', '2026-11-09 03:07:17')
            SQL,
            <<<'SQL'
            INSERT IGNORE INTO `iot_devices` (`id`, `venue_id`, `court_id`, `device_type`, `device_name`, `serial_number`, `ip_address`, `status`, `last_heartbeat`, `created_at`, `updated_at`) VALUES
            (1, 1, NULL, 'gateway', 'GoSport Smart Gateway Cầu Giấy #01', 'GTW-CG-01', '192.168.1.100', 'online', '2026-11-09 03:07:17', '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
            (2, 1, NULL, 'barrier_gate', 'Barrier Phân Làn Cửa Sân #01 (Kiosk Auto Check-in)', 'BAR-CG-GATE-01', '192.168.1.105', 'online', '2026-11-09 03:07:17', '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
            (3, 1, 1, 'led_scoreboard', 'Bảng Điểm Điện Tử LED Sân 1', 'LED-CRT-01', '192.168.1.111', 'online', '2026-11-09 03:07:17', '2026-11-09 03:07:17', '2026-11-09 03:07:17')
            SQL,
            <<<'SQL'
            INSERT IGNORE INTO `match_posts` (`id`, `user_id`, `booking_id`, `venue_id`, `title`, `play_datetime`, `duration_hours`, `target_level`, `players_needed`, `players_joined`, `fee_per_player`, `description`, `status`, `created_at`, `updated_at`) VALUES
            (1, 6, 1, 1, 'Tìm 2 tay vợt Nam/Nữ ghép kèo tối nay (Trình độ TB+)', '2026-11-09 18:00:00', 2.0, 'intermediate_plus', 4, 2, 87500.00, 'Sân 1 đã book sẵn, có nước mát, cần tìm 2 bạn nam hoặc nữ trình độ TB+ đập cầu đều tay giao lưu vui vẻ!', 'open', '2026-11-09 03:07:17', '2026-11-09 03:07:17')
            SQL,
            <<<'SQL'
            INSERT IGNORE INTO `membership_tiers` (`id`, `name`, `slug`, `min_spending`, `discount_percentage`, `priority_booking_days`, `perk_description`, `created_at`, `updated_at`) VALUES
            (1, 'Hội Viên Chuẩn', 'standard', 0.00, 0, 3, 'Tích điểm GoPoints 1% chi tiêu.', '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
            (2, 'Hội Viên Vàng (Gold)', 'gold', 3000000.00, 5, 7, 'Giảm 5% giờ thường, đặt trước 7 ngày, miễn phí mượn vợt.', '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
            (3, 'Thẻ Hội Viên VIP Kim Cương', 'diamond_vip', 10000000.00, 10, 14, 'Giảm 10% toàn bộ khung giờ, đặt trước 14 ngày, ưu tiên giờ vàng, tặng áo đấu GoSport Pro.', '2026-11-09 03:07:17', '2026-11-09 03:07:17')
            SQL,
            <<<'SQL'
            INSERT IGNORE INTO `operating_hours` (`id`, `venue_id`, `day_of_week`, `open_time`, `close_time`, `is_closed`, `created_at`, `updated_at`) VALUES
            (1, 1, 0, '06:00:00', '23:30:00', 0, '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
            (2, 2, 0, '06:00:00', '23:00:00', 0, '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
            (3, 3, 0, '06:00:00', '23:00:00', 0, '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
            (4, 1, 1, '06:00:00', '23:30:00', 0, '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
            (5, 2, 1, '06:00:00', '23:00:00', 0, '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
            (6, 3, 1, '06:00:00', '23:00:00', 0, '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
            (7, 1, 2, '06:00:00', '23:30:00', 0, '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
            (8, 2, 2, '06:00:00', '23:00:00', 0, '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
            (9, 3, 2, '06:00:00', '23:00:00', 0, '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
            (10, 1, 3, '06:00:00', '23:30:00', 0, '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
            (11, 2, 3, '06:00:00', '23:00:00', 0, '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
            (12, 3, 3, '06:00:00', '23:00:00', 0, '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
            (13, 1, 4, '06:00:00', '23:30:00', 0, '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
            (14, 2, 4, '06:00:00', '23:00:00', 0, '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
            (15, 3, 4, '06:00:00', '23:00:00', 0, '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
            (16, 1, 5, '06:00:00', '23:30:00', 0, '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
            (17, 2, 5, '06:00:00', '23:00:00', 0, '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
            (18, 3, 5, '06:00:00', '23:00:00', 0, '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
            (19, 1, 6, '06:00:00', '23:30:00', 0, '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
            (20, 2, 6, '06:00:00', '23:00:00', 0, '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
            (21, 3, 6, '06:00:00', '23:00:00', 0, '2026-11-09 03:07:17', '2026-11-09 03:07:17')
            SQL,
            <<<'SQL'
            INSERT IGNORE INTO `payment_transactions` (`id`, `transaction_code`, `booking_id`, `user_id`, `gateway`, `amount`, `currency`, `status`, `gateway_transaction_id`, `gateway_response`, `paid_at`, `created_at`, `updated_at`) VALUES
            (1, 'TXN-VNPAY-GS-20261109-001', 1, 6, 'vnpay', 350000.00, 'VND', 'succeeded', 'VNP14298765', NULL, '2026-11-09 01:07:17', '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
            (2, 'TXN-GS-20261109-001-1794220994', 1, 6, 'vnpay', 350000.00, 'VND', 'pending', NULL, NULL, NULL, '2026-11-09 03:43:14', '2026-11-09 03:43:14'),
            (3, 'TXN-GS-20261109-001-1794221382', 1, 6, 'vnpay', 350000.00, 'VND', 'pending', NULL, NULL, NULL, '2026-11-09 03:49:42', '2026-11-09 03:49:42'),
            (4, 'TXN-GS-20261109-001-1794221421', 1, 6, 'vnpay', 350000.00, 'VND', 'pending', NULL, NULL, NULL, '2026-11-09 03:50:21', '2026-11-09 03:50:21'),
            (5, 'TXN-GS-20261109-001-1794221616', 1, 6, 'vnpay', 350000.00, 'VND', 'pending', NULL, NULL, NULL, '2026-11-09 03:53:36', '2026-11-09 03:53:36'),
            (6, 'TXN-GS-20261109-001-1794221630', 1, 6, 'vnpay', 350000.00, 'VND', 'pending', NULL, NULL, NULL, '2026-11-09 03:53:50', '2026-11-09 03:53:50'),
            (7, 'TXN-GS-20261109-001-1794221861', 1, 6, 'vnpay', 350000.00, 'VND', 'pending', NULL, NULL, NULL, '2026-11-09 03:57:41', '2026-11-09 03:57:41'),
            (8, 'TXN-GS-20261109-001-1794222134', 1, 6, 'vnpay', 350000.00, 'VND', 'pending', NULL, NULL, NULL, '2026-11-09 04:02:14', '2026-11-09 04:02:14'),
            (9, 'TXN-GS-20261109-001-1794222358', 1, 6, 'vnpay', 350000.00, 'VND', 'pending', NULL, NULL, NULL, '2026-11-09 04:05:58', '2026-11-09 04:05:58'),
            (10, 'TXN-GS-20261109-V7XRHL-1794222874', 2, 7, 'vnpay', 200000.00, 'VND', 'pending', NULL, NULL, NULL, '2026-11-09 04:14:34', '2026-11-09 04:14:34'),
            (11, 'TXN-GS-20261109-001-1794223040', 1, 6, 'vnpay', 350000.00, 'VND', 'pending', NULL, NULL, NULL, '2026-11-09 04:17:20', '2026-11-09 04:17:20'),
            (12, 'TXN-GS-20261109-001-1794223454', 1, 6, 'vnpay', 350000.00, 'VND', 'pending', NULL, NULL, NULL, '2026-11-09 04:24:14', '2026-11-09 04:24:14')
            SQL,
            <<<'SQL'
            INSERT IGNORE INTO `posts` (`id`, `title`, `slug`, `post_category_id`, `summary`, `content`, `thumbnail`, `is_published`, `published_at`, `created_at`, `updated_at`) VALUES
            (1, 'Bí quyết nâng tốc độ Smash từ 180km/h lên 240km/h cho lông thủ phong trào', 'bi-quyet-smash-240kmh', 1, 'Phân tích cơ sinh học về cách phát lực, gập cổ tay và chọn thời điểm tiếp xúc cầu tối ưu nhất.', 'Trong cầu lông hiện đại, một cú đập cầu uy lực không chỉ phụ thuộc vào cơ bắp cánh tay mà là sự đồng bộ chuyển động của toàn bộ cơ thể...', 'https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?w=800&auto=format&fit=crop&q=80', 1, '2026-11-06 03:07:17', '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
            (2, 'So sánh chi tiết: Yonex Astrox 100ZZ vs Victor Thruster Ryuga II - Vua smash nào đỉnh hơn?', 'so-sanh-astrox-100zz-vs-ryuga-2', 2, 'Đánh giá thực tế độ nặng đầu, độ cứng đũa và khả năng phản tạt của hai cây vợt tấn công hàng đầu thế giới.', 'Hai siêu phẩm tấn công đại diện cho hai trường phái: Yonex sắc bén đầm tay và Victor uy lực bạo kích...', 'https://images.unsplash.com/photo-1546519638-68e109498ffc?w=800&auto=format&fit=crop&q=80', 1, '2026-11-03 03:07:17', '2026-11-09 03:07:17', '2026-11-09 03:07:17')
            SQL,
            <<<'SQL'
            INSERT IGNORE INTO `post_categories` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
            (1, 'Cẩm nang kỹ thuật & Smash', 'technique', '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
            (2, 'Đánh giá vợt & Thiết bị', 'gear-reviews', '2026-11-09 03:07:17', '2026-11-09 03:07:17')
            SQL,
            <<<'SQL'
            INSERT IGNORE INTO `pos_orders` (`id`, `order_code`, `venue_id`, `staff_id`, `customer_id`, `customer_name`, `customer_phone`, `total_amount`, `payment_method`, `status`, `created_at`, `updated_at`) VALUES
            (1, 'POS-20261109-FKIOP', 1, 4, NULL, 'Nguyễn Kiểm Thử POS', '0988777666', 15000.00, 'cash', 'paid', '2026-11-09 03:42:59', '2026-11-09 03:42:59'),
            (2, 'POS-20261109-LTQPG', 1, 4, NULL, 'Nguyễn Kiểm Thử POS', '0988777666', 15000.00, 'cash', 'paid', '2026-11-09 03:43:14', '2026-11-09 03:43:14'),
            (3, 'POS-20261109-XU5WH', 1, 4, NULL, 'Nguyễn Kiểm Thử POS', '0988777666', 15000.00, 'cash', 'paid', '2026-11-09 03:49:42', '2026-11-09 03:49:42'),
            (4, 'POS-20261109-ZQOJB', 1, 4, NULL, 'Nguyễn Kiểm Thử POS', '0988777666', 15000.00, 'cash', 'paid', '2026-11-09 03:50:21', '2026-11-09 03:50:21'),
            (5, 'POS-20261109-U35FJ', 1, 4, NULL, 'Nguyễn Kiểm Thử POS', '0988777666', 15000.00, 'cash', 'paid', '2026-11-09 03:53:36', '2026-11-09 03:53:36'),
            (6, 'POS-20261109-TT7ZS', 1, 4, NULL, 'Nguyễn Kiểm Thử POS', '0988777666', 15000.00, 'cash', 'paid', '2026-11-09 03:53:50', '2026-11-09 03:53:50'),
            (7, 'POS-20261109-QDL8K', 1, 4, NULL, 'Nguyễn Kiểm Thử POS', '0988777666', 15000.00, 'cash', 'paid', '2026-11-09 03:57:41', '2026-11-09 03:57:41'),
            (8, 'POS-20261109-9I66K', 1, 4, NULL, 'Nguyễn Kiểm Thử POS', '0988777666', 15000.00, 'cash', 'paid', '2026-11-09 04:02:14', '2026-11-09 04:02:14'),
            (9, 'POS-20261109-LAMW0', 1, 4, NULL, 'Nguyễn Kiểm Thử POS', '0988777666', 15000.00, 'cash', 'paid', '2026-11-09 04:05:58', '2026-11-09 04:05:58'),
            (10, 'POS-20261109-CHIRZ', 1, 4, NULL, 'Nguyễn Kiểm Thử POS', '0988777666', 15000.00, 'cash', 'paid', '2026-11-09 04:17:20', '2026-11-09 04:17:20'),
            (11, 'POS-20261109-7BOVV', 1, 4, NULL, 'Nguyễn Kiểm Thử POS', '0988777666', 15000.00, 'cash', 'paid', '2026-11-09 04:24:14', '2026-11-09 04:24:14')
            SQL,
            <<<'SQL'
            INSERT IGNORE INTO `pos_order_items` (`id`, `pos_order_id`, `product_id`, `quantity`, `unit_price`, `total_price`, `created_at`, `updated_at`) VALUES
            (1, 1, 1, 1, 15000.00, 15000.00, '2026-11-09 03:42:59', '2026-11-09 03:42:59'),
            (2, 2, 1, 1, 15000.00, 15000.00, '2026-11-09 03:43:14', '2026-11-09 03:43:14'),
            (3, 3, 1, 1, 15000.00, 15000.00, '2026-11-09 03:49:42', '2026-11-09 03:49:42'),
            (4, 4, 1, 1, 15000.00, 15000.00, '2026-11-09 03:50:21', '2026-11-09 03:50:21'),
            (5, 5, 1, 1, 15000.00, 15000.00, '2026-11-09 03:53:36', '2026-11-09 03:53:36'),
            (6, 6, 1, 1, 15000.00, 15000.00, '2026-11-09 03:53:50', '2026-11-09 03:53:50'),
            (7, 7, 1, 1, 15000.00, 15000.00, '2026-11-09 03:57:41', '2026-11-09 03:57:41'),
            (8, 8, 1, 1, 15000.00, 15000.00, '2026-11-09 04:02:14', '2026-11-09 04:02:14'),
            (9, 9, 1, 1, 15000.00, 15000.00, '2026-11-09 04:05:58', '2026-11-09 04:05:58'),
            (10, 10, 1, 1, 15000.00, 15000.00, '2026-11-09 04:17:20', '2026-11-09 04:17:20'),
            (11, 11, 1, 1, 15000.00, 15000.00, '2026-11-09 04:24:14', '2026-11-09 04:24:14')
            SQL,
            <<<'SQL'
            INSERT IGNORE INTO `products` (`id`, `product_category_id`, `name`, `sku`, `barcode`, `selling_price`, `cost_price`, `stock_quantity`, `low_stock_threshold`, `image`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
            (1, 1, 'Nước uống Revive bù điện giải', 'FB-REVIVE', '893456789001', 15000.00, 8000.00, 109, 10, NULL, 1, '2026-11-09 03:07:17', '2026-11-09 04:24:14', NULL),
            (2, 1, 'Nước khoáng thiên nhiên Lavie 500ml', 'FB-LAVIE', '893456789002', 10000.00, 5000.00, 200, 10, NULL, 1, '2026-11-09 03:07:17', '2026-11-09 03:07:17', NULL),
            (3, 1, 'Nước Pocari Sweat 500ml', 'FB-POCARI', '893456789003', 22000.00, 14000.00, 85, 10, NULL, 1, '2026-11-09 03:07:17', '2026-11-09 03:07:17', NULL),
            (4, 2, 'Ống cầu Ba Sao Pro 12 quả', 'SHU-BASAO', '893456789004', 240000.00, 195000.00, 45, 10, NULL, 1, '2026-11-09 03:07:17', '2026-11-09 03:07:17', NULL),
            (5, 2, 'Ống cầu Yonex AS40 Quốc tế', 'SHU-YONEX40', '893456789005', 480000.00, 390000.00, 18, 10, NULL, 1, '2026-11-09 03:07:17', '2026-11-09 03:07:17', NULL),
            (6, 3, 'Thuê vợt Yonex Astrox Pro / trận', 'REN-ASTROX', '893456789006', 50000.00, 0.00, 20, 10, NULL, 1, '2026-11-09 03:07:17', '2026-11-09 03:07:17', NULL),
            (7, 4, 'Quấn cán vợt Yonex AC102EX', 'ACC-AC102', '893456789007', 35000.00, 18000.00, 150, 10, NULL, 1, '2026-11-09 03:07:17', '2026-11-09 03:07:17', NULL),
            (8, 5, 'Cước đan Yonex BG65 Ti', 'STR-BG65TI', '893456789008', 140000.00, 85000.00, 35, 10, NULL, 1, '2026-11-09 03:07:17', '2026-11-09 03:07:17', NULL),
            (9, 5, 'Công căng vợt điện tử 4 nút', 'STR-LABOR', '893456789009', 50000.00, 0.00, 999, 10, NULL, 1, '2026-11-09 03:07:17', '2026-11-09 03:07:17', NULL)
            SQL,
            <<<'SQL'
            INSERT IGNORE INTO `product_categories` (`id`, `name`, `slug`, `icon`, `created_at`, `updated_at`) VALUES
            (1, 'Đồ uống F&B', 'drinks', 'local_drink', '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
            (2, 'Ống cầu thi đấu', 'shuttlecocks', 'sports_tennis', '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
            (3, 'Cho thuê vợt', 'racket-rental', 'sports_handball', '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
            (4, 'Phụ kiện & Cuốn cán', 'accessories', 'build_circle', '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
            (5, 'Căng vợt điện tử', 'stringing-services', 'precision_manufacturing', '2026-11-09 03:07:17', '2026-11-09 03:07:17')
            SQL,
            <<<'SQL'
            INSERT IGNORE INTO `reviews` (`id`, `venue_id`, `user_id`, `booking_id`, `rating`, `comment`, `is_approved`, `created_at`, `updated_at`) VALUES
            (1, 1, 6, 1, 5, 'Sân Cầu Giấy thảm cực êm, độ nảy tốt, đèn không bị chói mắt. Nhân viên nhiệt tình hỗ trợ check-in rất nhanh bằng QR!', 1, '2026-11-09 03:07:17', '2026-11-09 03:07:17')
            SQL,
            <<<'SQL'
            INSERT IGNORE INTO `roles` (`id`, `name`, `display_name`, `description`, `created_at`, `updated_at`) VALUES
            (1, 'super_admin', 'Quản trị viên tối cao', 'Toàn quyền hệ thống', '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
            (2, 'admin', 'Quản lý cơ sở', 'Quản trị cụm sân và vận hành', '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
            (3, 'owner', 'Chủ sân (Owner)', 'Xem doanh thu, tài chính, rút tiền', '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
            (4, 'staff', 'Nhân viên lễ tân / Thu ngân', 'Điều phối sân, check-in, POS', '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
            (5, 'coach', 'Huấn luyện viên', 'Dạy học viên Academy', '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
            (6, 'customer', 'Khách hàng', 'Đặt sân, chơi cầu lông, ghép kèo', '2026-11-09 03:07:17', '2026-11-09 03:07:17')
            SQL,
            <<<'SQL'
            INSERT IGNORE INTO `staff_profiles` (`id`, `user_id`, `venue_id`, `employee_code`, `position`, `salary_type`, `base_salary`, `hire_date`, `created_at`, `updated_at`) VALUES
            (1, 4, 1, 'EMP-001', 'Lễ tân & Điều phối sân', 'monthly', 8500000.00, '2026-05-09', '2026-11-09 03:07:17', '2026-11-09 03:07:17')
            SQL,
            <<<'SQL'
            INSERT IGNORE INTO `staff_shifts` (`id`, `user_id`, `venue_id`, `shift_date`, `shift_type`, `start_time`, `end_time`, `clock_in_time`, `clock_out_time`, `status`, `created_at`, `updated_at`) VALUES
            (1, 4, 1, '2026-11-09', 'Ca chiều (14:00 - 22:00)', '14:00:00', '22:00:00', '2026-11-09 06:50:00', NULL, 'present', '2026-11-09 03:07:17', '2026-11-09 03:07:17')
            SQL,
            <<<'SQL'
            INSERT IGNORE INTO `tournaments` (`id`, `venue_id`, `title`, `slug`, `banner_image`, `start_date`, `end_date`, `prize_pool`, `format`, `status`, `created_at`, `updated_at`) VALUES
            (1, 1, 'Giải Vô Địch Cầu Lông GoSport Open 2026', 'gosport-open-2026', 'https://images.unsplash.com/photo-1546519638-68e109498ffc?w=1200&auto=format&fit=crop&q=80', '2026-11-24', '2026-11-26', 50000000.00, 'knockout', 'upcoming', '2026-11-09 03:07:17', '2026-11-09 03:07:17')
            SQL,
            <<<'SQL'
            INSERT IGNORE INTO `tournament_matches` (`id`, `tournament_id`, `court_id`, `round_name`, `team_a_name`, `team_b_name`, `score_set_1`, `score_set_2`, `score_set_3`, `winner_team`, `status`, `match_time`, `created_at`, `updated_at`) VALUES
            (1, 1, 1, 'Chung kết Đôi Nam', 'Nguyễn Tuấn / Trần Khải', 'Lê Hoàng / Phạm Minh', '21-19', '18-21', '21-17', 'Nguyễn Tuấn / Trần Khải', 'finished', '2026-11-08 10:07:17', '2026-11-09 03:07:17', '2026-11-09 03:07:17')
            SQL,
            <<<'SQL'
            INSERT IGNORE INTO `users` (`id`, `name`, `email`, `phone`, `email_verified_at`, `password`, `role`, `avatar`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
            (1, 'GoSport Super Admin', 'admin@gosport.vn', '0901000001', NULL, '$2y$12$EWp3ub6Q.6tTPuwdZdKHD.loqfvxaMlfAdUUzcbMXws9DMvPAVO0a', 'super_admin', NULL, NULL, '2026-11-09 03:07:17', '2026-11-09 03:07:17', NULL),
            (2, 'Trần Quang Khải (Chủ chuỗi sân)', 'owner@gosport.vn', '0902000002', NULL, '$2y$12$EWp3ub6Q.6tTPuwdZdKHD.loqfvxaMlfAdUUzcbMXws9DMvPAVO0a', 'owner', NULL, NULL, '2026-11-09 03:07:17', '2026-11-09 03:07:17', NULL),
            (3, 'Lê Hoàng Nam (Quản lý Cầu Giấy)', 'manager@gosport.vn', '0903000003', NULL, '$2y$12$EWp3ub6Q.6tTPuwdZdKHD.loqfvxaMlfAdUUzcbMXws9DMvPAVO0a', 'admin', NULL, NULL, '2026-11-09 03:07:17', '2026-11-09 03:07:17', NULL),
            (4, 'Đỗ Thu Thảo (Lễ tân quầy)', 'staff@gosport.vn', '0904000004', NULL, '$2y$12$EWp3ub6Q.6tTPuwdZdKHD.loqfvxaMlfAdUUzcbMXws9DMvPAVO0a', 'staff', NULL, NULL, '2026-11-09 03:07:17', '2026-11-09 03:07:17', NULL),
            (5, 'Nguyễn Văn Hùng (HLV Kiện tướng QG)', 'coach@gosport.vn', '0905000005', NULL, '$2y$12$EWp3ub6Q.6tTPuwdZdKHD.loqfvxaMlfAdUUzcbMXws9DMvPAVO0a', 'coach', NULL, NULL, '2026-11-09 03:07:17', '2026-11-09 03:07:17', NULL),
            (6, 'Nguyễn Văn Tuấn', 'customer@gosport.vn', '0988889999', NULL, '$2y$04$vDq9YFm6B.vgyqtSevW7cuiDPCJRBoyZZ.VUvl71QkQ2WZWBtICvO', 'customer', NULL, NULL, '2026-11-09 03:07:17', '2026-11-09 03:40:11', NULL),
            (7, 'aa aa', 'lehao99nd@gmail.com', '0862225508', NULL, '$2y$12$nDyp51uGeiNxBqLYJU74lO.DFYXbZ0mO5gk/HtAd016pfsGe2v6Hm', 'customer', NULL, NULL, '2026-11-09 03:46:05', '2026-11-09 03:46:05', NULL)
            SQL,
            <<<'SQL'
            INSERT IGNORE INTO `user_memberships` (`id`, `user_id`, `membership_tier_id`, `start_date`, `end_date`, `is_active`, `created_at`, `updated_at`) VALUES
            (1, 6, 3, '2026-09-09', '2027-09-09', 1, '2026-11-09 03:07:17', '2026-11-09 03:07:17')
            SQL,
            <<<'SQL'
            INSERT IGNORE INTO `user_profiles` (`id`, `user_id`, `skill_level`, `play_style`, `preferred_hand`, `racket_brand`, `smash_speed_max`, `matches_played`, `win_rate`, `bio`, `created_at`, `updated_at`) VALUES
            (1, 6, 'intermediate_plus', 'Tấn công Smash', 'right', 'Yonex Astrox 100ZZ', 240, 48, 68.50, 'Đam mê smash cầu lửa, thích giao lưu học hỏi mỗi tối thứ 4 & CN hàng tuần.', '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
            (2, 7, 'intermediate', 'Tấn công Smash', 'right', NULL, 180, 0, 50.00, NULL, '2026-11-09 03:46:05', '2026-11-09 03:46:05')
            SQL,
            <<<'SQL'
            INSERT IGNORE INTO `venues` (`id`, `owner_id`, `name`, `slug`, `address`, `district`, `city`, `latitude`, `longitude`, `phone`, `email`, `featured_image`, `gallery`, `description`, `opening_time`, `closing_time`, `is_active`, `rating_avg`, `reviews_count`, `created_at`, `updated_at`, `deleted_at`) VALUES
            (1, 2, 'GoSport Arena Cầu Giấy', 'gosport-arena-cau-giay', 'Số 26 Phạm Văn Đồng, Mai Dịch, Cầu Giấy', 'Cầu Giấy', 'Hà Nội', 21.03680000, 105.78250000, '024 3888 6666', 'caugiay@gosport.vn', 'https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?w=1200&auto=format&fit=crop&q=80', '[\"https:\\/\\/images.unsplash.com\\/photo-1626224583764-f87db24ac4ea?w=800&auto=format&fit=crop&q=80\",\"https:\\/\\/images.unsplash.com\\/photo-1546519638-68e109498ffc?w=800&auto=format&fit=crop&q=80\",\"https:\\/\\/images.unsplash.com\\/photo-1511067007772-9da29974ce44?w=800&auto=format&fit=crop&q=80\"]', 'Tổ hợp 12 sân cầu lông trong nhà lớn nhất khu vực Cầu Giấy. Thảm Yonex BWF cao cấp, máy lạnh trung tâm, hệ thống ánh sáng chống chói mắt tiêu chuẩn quốc tế.', '06:00:00', '23:30:00', 1, 4.90, 128, '2026-11-09 03:07:17', '2026-11-09 03:07:17', NULL),
            (2, 2, 'GoSport Premium Mỹ Đình', 'gosport-premium-my-dinh', 'Khu liên hợp Thể Thao Quốc Gia, Mỹ Đình, Nam Từ Liêm', 'Nam Từ Liêm', 'Hà Nội', 21.02050000, 105.76450000, '024 3999 8888', 'mydinh@gosport.vn', 'https://images.unsplash.com/photo-1546519638-68e109498ffc?w=1200&auto=format&fit=crop&q=80', '[\"https:\\/\\/images.unsplash.com\\/photo-1546519638-68e109498ffc?w=800&auto=format&fit=crop&q=80\"]', 'Cụm 8 sân tiêu chuẩn thi đấu nằm tại trung tâm Mỹ Đình với bãi đỗ xe ô tô rộng rãi, phòng tắm nóng lạnh miễn phí.', '06:00:00', '23:00:00', 1, 4.85, 96, '2026-11-09 03:07:17', '2026-11-09 03:07:17', NULL),
            (3, 2, 'GoSport Star Thanh Xuân', 'gosport-star-thanh-xuan', 'Số 12 Khuất Duy Tiến, Thanh Xuân Bắc, Thanh Xuân', 'Thanh Xuân', 'Hà Nội', 20.99800000, 105.79900000, '024 3777 5555', 'thanhxuan@gosport.vn', 'https://images.unsplash.com/photo-1511067007772-9da29974ce44?w=1200&auto=format&fit=crop&q=80', NULL, 'Cụm 6 sân hiện đại trung tâm Thanh Xuân, giao thông thuận tiện, phục vụ cộng đồng sinh viên và dân văn phòng.', '06:00:00', '23:00:00', 1, 4.80, 74, '2026-11-09 03:07:17', '2026-11-09 03:07:17', NULL)
            SQL,
            <<<'SQL'
            INSERT IGNORE INTO `venue_amenities` (`id`, `venue_id`, `amenity_id`, `created_at`, `updated_at`) VALUES
            (1, 1, 1, NULL, NULL),
            (2, 1, 2, NULL, NULL),
            (3, 1, 3, NULL, NULL),
            (4, 1, 4, NULL, NULL),
            (5, 1, 5, NULL, NULL),
            (6, 1, 6, NULL, NULL),
            (7, 1, 7, NULL, NULL),
            (8, 1, 8, NULL, NULL),
            (9, 2, 1, NULL, NULL),
            (10, 2, 2, NULL, NULL),
            (11, 2, 3, NULL, NULL),
            (12, 2, 4, NULL, NULL),
            (13, 2, 5, NULL, NULL),
            (14, 3, 1, NULL, NULL),
            (15, 3, 2, NULL, NULL),
            (16, 3, 3, NULL, NULL),
            (17, 3, 4, NULL, NULL)
            SQL,
            <<<'SQL'
            INSERT IGNORE INTO `vouchers` (`id`, `code`, `title`, `discount_type`, `discount_value`, `min_order_amount`, `max_discount_amount`, `usage_limit`, `used_count`, `start_date`, `end_date`, `is_active`, `created_at`, `updated_at`) VALUES
            (1, 'GOSPORT20', 'Giảm 20% cho đơn đặt sân tối nay', 'percent', 20.00, 150000.00, 80000.00, 500, 38, '2026-11-04', '2026-12-09', 1, '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
            (2, 'GIOVANG50', 'Giảm ngay 50.000đ khung giờ vàng', 'fixed', 50000.00, 200000.00, NULL, 200, 15, '2026-11-07', '2026-11-24', 1, '2026-11-09 03:07:17', '2026-11-09 03:07:17')
            SQL,
            <<<'SQL'
            INSERT IGNORE INTO `wallet_accounts` (`id`, `user_id`, `balance`, `frozen_balance`, `currency`, `status`, `created_at`, `updated_at`) VALUES
            (1, 6, 1500000.00, 0.00, 'VND', 'active', '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
            (2, 7, 0.00, 0.00, 'VND', 'active', '2026-11-09 03:46:05', '2026-11-09 03:46:05')
            SQL,
            <<<'SQL'
            INSERT IGNORE INTO `wallet_ledger_entries` (`id`, `wallet_account_id`, `type`, `amount`, `balance_after`, `description`, `reference_id`, `created_at`, `updated_at`) VALUES
            (1, 1, 'deposit', 1500000.00, 1500000.00, 'Nạp tiền thẻ thể thao GoSport Khuyến mãi +15%', 'DEP-LRPKYJUP', '2026-11-09 03:07:17', '2026-11-09 03:07:17')
            SQL,
        ];

        Schema::disableForeignKeyConstraints();

        try {
            foreach ($statements as $statement) {
                DB::unprepared($statement);
            }
        } finally {
            Schema::enableForeignKeyConstraints();
        }
    }
}
