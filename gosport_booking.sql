-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 09, 2026 lúc 02:28 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `gosport_booking`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `amenities`
--

CREATE TABLE `amenities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `amenities`
--

INSERT INTO `amenities` (`id`, `name`, `icon`, `created_at`, `updated_at`) VALUES
(1, 'Sân điều hòa mát mẻ', 'ac_unit', '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
(2, 'Có bãi đỗ ô tô miễn phí', 'directions_car', '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
(3, 'Mở cửa đêm 24/7', 'bedtime', '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
(4, 'Phòng tắm nóng lạnh VIP', 'shower', '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
(5, 'Wifi tốc độ cao miễn phí', 'wifi', '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
(6, 'Quầy đồ uống & Phụ kiện F&B', 'local_cafe', '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
(7, 'Dịch vụ căng vợt điện tử', 'build_circle', '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
(8, 'Camera AI bắt lỗi vạch', 'videocam', '2026-11-09 03:07:17', '2026-11-09 03:07:17');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `bookings`
--

CREATE TABLE `bookings` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_code` varchar(32) NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `venue_id` bigint(20) UNSIGNED NOT NULL,
  `booking_date` date NOT NULL,
  `total_amount` decimal(12,2) NOT NULL,
  `discount_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `final_amount` decimal(12,2) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending_payment',
  `payment_status` varchar(255) NOT NULL DEFAULT 'unpaid',
  `payment_method` varchar(255) DEFAULT NULL,
  `check_in_token` varchar(64) NOT NULL,
  `notes` text DEFAULT NULL,
  `created_by_staff_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `bookings`
--

INSERT INTO `bookings` (`id`, `booking_code`, `user_id`, `venue_id`, `booking_date`, `total_amount`, `discount_amount`, `final_amount`, `status`, `payment_status`, `payment_method`, `check_in_token`, `notes`, `created_by_staff_id`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'GS-20261109-001', 6, 1, '2026-11-09', 400000.00, 50000.00, 350000.00, 'checked_in', 'paid', 'vnpay', 'QR-H30MU2RYVKJQHJTT', 'Khách VIP - đặt sân cố định hàng tuần', NULL, '2026-11-09 03:07:17', '2026-11-09 03:40:11', NULL),
(2, 'GS-20261109-V7XRHL', 7, 1, '2026-11-10', 200000.00, 0.00, 200000.00, 'confirmed', 'unpaid', 'cash', 'GSCK-c1c6fc0b-c44f-4585-aa90-99c1b101b9b6', NULL, NULL, '2026-11-09 04:14:27', '2026-11-09 04:14:40', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `booking_holds`
--

CREATE TABLE `booking_holds` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `court_id` bigint(20) UNSIGNED NOT NULL,
  `booking_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `session_id` varchar(100) NOT NULL,
  `expires_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `booking_items`
--

CREATE TABLE `booking_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `court_id` bigint(20) UNSIGNED NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `duration_minutes` smallint(5) UNSIGNED NOT NULL DEFAULT 60,
  `hourly_rate` decimal(12,2) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `booking_items`
--

INSERT INTO `booking_items` (`id`, `booking_id`, `court_id`, `start_time`, `end_time`, `duration_minutes`, `hourly_rate`, `amount`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '18:00:00', '20:00:00', 120, 200000.00, 400000.00, '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
(2, 2, 1, '17:00:00', '18:00:00', 60, 200000.00, 200000.00, '2026-11-09 04:14:27', '2026-11-09 04:14:27');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `booking_status_histories`
--

CREATE TABLE `booking_status_histories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `old_status` varchar(255) DEFAULT NULL,
  `new_status` varchar(255) NOT NULL,
  `changed_by_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `reason` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `booking_status_histories`
--

INSERT INTO `booking_status_histories` (`id`, `booking_id`, `old_status`, `new_status`, `changed_by_user_id`, `reason`, `created_at`, `updated_at`) VALUES
(1, 1, 'confirmed', 'checked_in', 6, 'Check-in thành công qua kiosk_barrier', '2026-11-09 03:40:11', '2026-11-09 03:40:11'),
(2, 2, NULL, 'pending_payment', 7, 'Khởi tạo đơn đặt sân trực tuyến', '2026-11-09 04:14:27', '2026-11-09 04:14:27');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `cache_locks`
--

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `check_ins`
--

CREATE TABLE `check_ins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `court_id` bigint(20) UNSIGNED NOT NULL,
  `check_in_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `check_out_time` timestamp NULL DEFAULT NULL,
  `method` varchar(255) NOT NULL DEFAULT 'qr_scan',
  `verified_by_staff_id` bigint(20) UNSIGNED DEFAULT NULL,
  `gate_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `check_ins`
--

INSERT INTO `check_ins` (`id`, `booking_id`, `court_id`, `check_in_time`, `check_out_time`, `method`, `verified_by_staff_id`, `gate_id`, `created_at`, `updated_at`) VALUES
(1, 1, 1, '2026-11-09 03:40:11', NULL, 'kiosk_barrier', NULL, 'BAR-CG-GATE-01', '2026-11-09 03:40:11', '2026-11-09 03:40:11');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `coaches`
--

CREATE TABLE `coaches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `full_name` varchar(255) NOT NULL,
  `title` varchar(255) NOT NULL,
  `bio` text DEFAULT NULL,
  `hourly_rate` decimal(12,2) NOT NULL DEFAULT 300000.00,
  `avatar` varchar(255) DEFAULT NULL,
  `rating_avg` decimal(3,2) NOT NULL DEFAULT 5.00,
  `students_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `coaches`
--

INSERT INTO `coaches` (`id`, `user_id`, `full_name`, `title`, `bio`, `hourly_rate`, `avatar`, `rating_avg`, `students_count`, `created_at`, `updated_at`) VALUES
(1, 5, 'Nguyễn Văn Hùng', 'HLV Kiện tướng Quốc gia - Cựu VĐV ĐTQG', 'Hơn 12 năm kinh nghiệm huấn luyện chuyên sâu từ cơ bản đến nâng cao. Chuyên gia phân tích kỹ thuật Smash, xoay chuyển bộ pháp và chiến thuật thi đấu đánh đôi.', 350000.00, 'https://images.unsplash.com/photo-1534528741775-53994a69daeb?w=400&auto=format&fit=crop&q=80', 4.95, 64, '2026-11-09 03:07:17', '2026-11-09 03:07:17');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `courses`
--

CREATE TABLE `courses` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `coach_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `level` varchar(255) NOT NULL DEFAULT 'all',
  `price` decimal(12,2) NOT NULL,
  `max_students` tinyint(3) UNSIGNED NOT NULL DEFAULT 8,
  `description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `courses`
--

INSERT INTO `courses` (`id`, `coach_id`, `title`, `slug`, `level`, `price`, `max_students`, `description`, `created_at`, `updated_at`) VALUES
(1, 1, 'Khóa Học Nâng Cao: Bí Quyết Smash Cầu Lửa 240km/h', 'smash-mastery-pro', 'smash_mastery', 2400000.00, 6, 'Khóa học 8 buổi tập trung tối ưu hóa điểm tiếp xúc cầu, lực cổ tay, xoay lườn và bật nhảy smash uy lực chính xác.', '2026-11-09 03:07:17', '2026-11-09 03:07:17');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `course_enrollments`
--

CREATE TABLE `course_enrollments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `payment_status` varchar(255) NOT NULL DEFAULT 'paid',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `course_schedules`
--

CREATE TABLE `course_schedules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `course_id` bigint(20) UNSIGNED NOT NULL,
  `venue_id` bigint(20) UNSIGNED NOT NULL,
  `day_of_week` tinyint(3) UNSIGNED NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `course_schedules`
--

INSERT INTO `course_schedules` (`id`, `course_id`, `venue_id`, `day_of_week`, `start_time`, `end_time`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 2, '19:00:00', '21:00:00', '2026-11-09 03:07:17', '2026-11-09 03:07:17');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `courts`
--

CREATE TABLE `courts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `venue_id` bigint(20) UNSIGNED NOT NULL,
  `court_number` smallint(5) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `court_type_id` bigint(20) UNSIGNED DEFAULT NULL,
  `surface_type` varchar(255) NOT NULL DEFAULT 'Yonex BWF Standard',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `iot_relay_channel` tinyint(3) UNSIGNED DEFAULT NULL,
  `camera_stream_url` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `courts`
--

INSERT INTO `courts` (`id`, `venue_id`, `court_number`, `name`, `court_type_id`, `surface_type`, `is_active`, `iot_relay_channel`, `camera_stream_url`, `created_at`, `updated_at`, `deleted_at`) VALUES
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
(20, 3, 6, 'Sân 6 - Practice Court', 1, 'Victor Standard', 1, 6, NULL, '2026-11-09 04:22:56', '2026-11-09 04:22:56', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `court_maintenance`
--

CREATE TABLE `court_maintenance` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `court_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `reason` text DEFAULT NULL,
  `start_datetime` datetime NOT NULL,
  `end_datetime` datetime NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'scheduled',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `court_pricing_rules`
--

CREATE TABLE `court_pricing_rules` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `venue_id` bigint(20) UNSIGNED NOT NULL,
  `court_type_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `day_type` varchar(255) NOT NULL DEFAULT 'all',
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `price_per_hour` decimal(12,2) NOT NULL,
  `is_peak` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `court_pricing_rules`
--

INSERT INTO `court_pricing_rules` (`id`, `venue_id`, `court_type_id`, `name`, `day_type`, `start_time`, `end_time`, `price_per_hour`, `is_peak`, `created_at`, `updated_at`) VALUES
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
(12, 3, NULL, 'Khung cuối tuần (Thứ 7 - CN)', 'weekend', '06:00:00', '23:00:00', 170000.00, 1, '2026-11-09 04:22:56', '2026-11-09 04:22:56');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `court_types`
--

CREATE TABLE `court_types` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `is_premium` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `court_types`
--

INSERT INTO `court_types` (`id`, `name`, `description`, `is_premium`, `created_at`, `updated_at`) VALUES
(1, 'Sân Tiêu Chuẩn BWF', 'Thảm cao su chuẩn thi đấu quốc tế, độ bám cao, giảm chấn đầu gối', 0, '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
(2, 'Sân VIP Lounge Chuyên Nghiệp', 'Không gian riêng tư, ghế sofa da cao cấp, hệ thống đèn chống lóa Lumileds', 1, '2026-11-09 03:07:17', '2026-11-09 03:07:17');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `device_configurations`
--

CREATE TABLE `device_configurations` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `iot_device_id` bigint(20) UNSIGNED NOT NULL,
  `config_key` varchar(255) NOT NULL,
  `config_value` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `device_events`
--

CREATE TABLE `device_events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `iot_device_id` bigint(20) UNSIGNED NOT NULL,
  `event_type` varchar(255) NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payload`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `inventory_movements`
--

CREATE TABLE `inventory_movements` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `quantity` int(11) NOT NULL,
  `reference_id` varchar(255) DEFAULT NULL,
  `note` text DEFAULT NULL,
  `created_by_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `invoices`
--

CREATE TABLE `invoices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `invoice_code` varchar(32) NOT NULL,
  `booking_id` bigint(20) UNSIGNED DEFAULT NULL,
  `pos_order_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `customer_phone` varchar(255) DEFAULT NULL,
  `subtotal` decimal(12,2) NOT NULL,
  `tax_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `total` decimal(12,2) NOT NULL,
  `payment_method` varchar(255) NOT NULL DEFAULT 'cash',
  `status` varchar(255) NOT NULL DEFAULT 'paid',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `iot_devices`
--

CREATE TABLE `iot_devices` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `venue_id` bigint(20) UNSIGNED NOT NULL,
  `court_id` bigint(20) UNSIGNED DEFAULT NULL,
  `device_type` varchar(255) NOT NULL,
  `device_name` varchar(255) NOT NULL,
  `serial_number` varchar(100) NOT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'online',
  `last_heartbeat` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `iot_devices`
--

INSERT INTO `iot_devices` (`id`, `venue_id`, `court_id`, `device_type`, `device_name`, `serial_number`, `ip_address`, `status`, `last_heartbeat`, `created_at`, `updated_at`) VALUES
(1, 1, NULL, 'gateway', 'GoSport Smart Gateway Cầu Giấy #01', 'GTW-CG-01', '192.168.1.100', 'online', '2026-11-09 03:07:17', '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
(2, 1, NULL, 'barrier_gate', 'Barrier Phân Làn Cửa Sân #01 (Kiosk Auto Check-in)', 'BAR-CG-GATE-01', '192.168.1.105', 'online', '2026-11-09 03:07:17', '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
(3, 1, 1, 'led_scoreboard', 'Bảng Điểm Điện Tử LED Sân 1', 'LED-CRT-01', '192.168.1.111', 'online', '2026-11-09 03:07:17', '2026-11-09 03:07:17', '2026-11-09 03:07:17');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `jobs`
--

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `job_batches`
--

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `loyalty_transactions`
--

CREATE TABLE `loyalty_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `points_change` int(11) NOT NULL,
  `balance_after` int(11) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `reference_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `match_participants`
--

CREATE TABLE `match_participants` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `match_post_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'joined',
  `deposit_status` varchar(255) NOT NULL DEFAULT 'unpaid',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `match_posts`
--

CREATE TABLE `match_posts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED DEFAULT NULL,
  `venue_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `play_datetime` datetime NOT NULL,
  `duration_hours` decimal(3,1) NOT NULL DEFAULT 2.0,
  `target_level` varchar(255) NOT NULL DEFAULT 'all',
  `players_needed` tinyint(3) UNSIGNED NOT NULL DEFAULT 4,
  `players_joined` tinyint(3) UNSIGNED NOT NULL DEFAULT 1,
  `fee_per_player` decimal(12,2) NOT NULL DEFAULT 0.00,
  `description` text DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'open',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `match_posts`
--

INSERT INTO `match_posts` (`id`, `user_id`, `booking_id`, `venue_id`, `title`, `play_datetime`, `duration_hours`, `target_level`, `players_needed`, `players_joined`, `fee_per_player`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 6, 1, 1, 'Tìm 2 tay vợt Nam/Nữ ghép kèo tối nay (Trình độ TB+)', '2026-11-09 18:00:00', 2.0, 'intermediate_plus', 4, 2, 87500.00, 'Sân 1 đã book sẵn, có nước mát, cần tìm 2 bạn nam hoặc nữ trình độ TB+ đập cầu đều tay giao lưu vui vẻ!', 'open', '2026-11-09 03:07:17', '2026-11-09 03:07:17');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `membership_tiers`
--

CREATE TABLE `membership_tiers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `min_spending` decimal(12,2) NOT NULL DEFAULT 0.00,
  `discount_percentage` tinyint(3) UNSIGNED NOT NULL DEFAULT 0,
  `priority_booking_days` smallint(5) UNSIGNED NOT NULL DEFAULT 3,
  `perk_description` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `membership_tiers`
--

INSERT INTO `membership_tiers` (`id`, `name`, `slug`, `min_spending`, `discount_percentage`, `priority_booking_days`, `perk_description`, `created_at`, `updated_at`) VALUES
(1, 'Hội Viên Chuẩn', 'standard', 0.00, 0, 3, 'Tích điểm GoPoints 1% chi tiêu.', '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
(2, 'Hội Viên Vàng (Gold)', 'gold', 3000000.00, 5, 7, 'Giảm 5% giờ thường, đặt trước 7 ngày, miễn phí mượn vợt.', '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
(3, 'Thẻ Hội Viên VIP Kim Cương', 'diamond_vip', 10000000.00, 10, 14, 'Giảm 10% toàn bộ khung giờ, đặt trước 14 ngày, ưu tiên giờ vàng, tặng áo đấu GoSport Pro.', '2026-11-09 03:07:17', '2026-11-09 03:07:17');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '0001_01_01_000000_create_users_table', 1),
(2, '0001_01_01_000001_create_cache_table', 1),
(3, '0001_01_01_000002_create_jobs_table', 1),
(4, '2026_01_01_000001_update_users_and_create_roles_table', 2),
(5, '2026_01_01_000002_create_user_profiles_and_memberships_tables', 2),
(6, '2026_01_01_000003_create_venues_courts_and_pricing_tables', 2),
(7, '2026_01_01_000004_create_bookings_items_and_holds_tables', 2),
(8, '2026_01_01_000005_create_payment_transactions_and_invoices_tables', 2),
(9, '2026_01_01_000006_create_vouchers_and_wallets_tables', 2),
(10, '2026_01_01_000007_create_products_inventory_and_pos_tables', 2),
(11, '2026_01_01_000008_create_staff_shifts_and_roster_tables', 2),
(12, '2026_01_01_000009_create_matchmaking_and_tournaments_tables', 2),
(13, '2026_01_01_000010_create_academy_and_reviews_tables', 2),
(14, '2026_01_01_000011_create_iot_devices_and_kiosk_tables', 2);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `operating_hours`
--

CREATE TABLE `operating_hours` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `venue_id` bigint(20) UNSIGNED NOT NULL,
  `day_of_week` tinyint(3) UNSIGNED NOT NULL,
  `open_time` time NOT NULL DEFAULT '06:00:00',
  `close_time` time NOT NULL DEFAULT '23:00:00',
  `is_closed` tinyint(1) NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `operating_hours`
--

INSERT INTO `operating_hours` (`id`, `venue_id`, `day_of_week`, `open_time`, `close_time`, `is_closed`, `created_at`, `updated_at`) VALUES
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
(21, 3, 6, '06:00:00', '23:00:00', 0, '2026-11-09 03:07:17', '2026-11-09 03:07:17');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `password_reset_tokens`
--

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `payment_events`
--

CREATE TABLE `payment_events` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `payment_transaction_id` bigint(20) UNSIGNED NOT NULL,
  `event_type` varchar(255) NOT NULL,
  `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payload`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `payment_transactions`
--

CREATE TABLE `payment_transactions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `transaction_code` varchar(64) NOT NULL,
  `booking_id` bigint(20) UNSIGNED DEFAULT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `gateway` varchar(255) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `currency` varchar(10) NOT NULL DEFAULT 'VND',
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `gateway_transaction_id` varchar(100) DEFAULT NULL,
  `gateway_response` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gateway_response`)),
  `paid_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `payment_transactions`
--

INSERT INTO `payment_transactions` (`id`, `transaction_code`, `booking_id`, `user_id`, `gateway`, `amount`, `currency`, `status`, `gateway_transaction_id`, `gateway_response`, `paid_at`, `created_at`, `updated_at`) VALUES
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
(12, 'TXN-GS-20261109-001-1794223454', 1, 6, 'vnpay', 350000.00, 'VND', 'pending', NULL, NULL, NULL, '2026-11-09 04:24:14', '2026-11-09 04:24:14');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `posts`
--

CREATE TABLE `posts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `post_category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `summary` text DEFAULT NULL,
  `content` longtext NOT NULL,
  `thumbnail` varchar(255) DEFAULT NULL,
  `is_published` tinyint(1) NOT NULL DEFAULT 1,
  `published_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `posts`
--

INSERT INTO `posts` (`id`, `title`, `slug`, `post_category_id`, `summary`, `content`, `thumbnail`, `is_published`, `published_at`, `created_at`, `updated_at`) VALUES
(1, 'Bí quyết nâng tốc độ Smash từ 180km/h lên 240km/h cho lông thủ phong trào', 'bi-quyet-smash-240kmh', 1, 'Phân tích cơ sinh học về cách phát lực, gập cổ tay và chọn thời điểm tiếp xúc cầu tối ưu nhất.', 'Trong cầu lông hiện đại, một cú đập cầu uy lực không chỉ phụ thuộc vào cơ bắp cánh tay mà là sự đồng bộ chuyển động của toàn bộ cơ thể...', 'https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?w=800&auto=format&fit=crop&q=80', 1, '2026-11-06 03:07:17', '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
(2, 'So sánh chi tiết: Yonex Astrox 100ZZ vs Victor Thruster Ryuga II - Vua smash nào đỉnh hơn?', 'so-sanh-astrox-100zz-vs-ryuga-2', 2, 'Đánh giá thực tế độ nặng đầu, độ cứng đũa và khả năng phản tạt của hai cây vợt tấn công hàng đầu thế giới.', 'Hai siêu phẩm tấn công đại diện cho hai trường phái: Yonex sắc bén đầm tay và Victor uy lực bạo kích...', 'https://images.unsplash.com/photo-1546519638-68e109498ffc?w=800&auto=format&fit=crop&q=80', 1, '2026-11-03 03:07:17', '2026-11-09 03:07:17', '2026-11-09 03:07:17');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `post_categories`
--

CREATE TABLE `post_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `post_categories`
--

INSERT INTO `post_categories` (`id`, `name`, `slug`, `created_at`, `updated_at`) VALUES
(1, 'Cẩm nang kỹ thuật & Smash', 'technique', '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
(2, 'Đánh giá vợt & Thiết bị', 'gear-reviews', '2026-11-09 03:07:17', '2026-11-09 03:07:17');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `pos_orders`
--

CREATE TABLE `pos_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_code` varchar(32) NOT NULL,
  `venue_id` bigint(20) UNSIGNED NOT NULL,
  `staff_id` bigint(20) UNSIGNED NOT NULL,
  `customer_id` bigint(20) UNSIGNED DEFAULT NULL,
  `customer_name` varchar(255) DEFAULT NULL,
  `customer_phone` varchar(255) DEFAULT NULL,
  `total_amount` decimal(12,2) NOT NULL,
  `payment_method` varchar(255) NOT NULL DEFAULT 'cash',
  `status` varchar(255) NOT NULL DEFAULT 'paid',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `pos_orders`
--

INSERT INTO `pos_orders` (`id`, `order_code`, `venue_id`, `staff_id`, `customer_id`, `customer_name`, `customer_phone`, `total_amount`, `payment_method`, `status`, `created_at`, `updated_at`) VALUES
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
(11, 'POS-20261109-7BOVV', 1, 4, NULL, 'Nguyễn Kiểm Thử POS', '0988777666', 15000.00, 'cash', 'paid', '2026-11-09 04:24:14', '2026-11-09 04:24:14');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `pos_order_items`
--

CREATE TABLE `pos_order_items` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `pos_order_id` bigint(20) UNSIGNED NOT NULL,
  `product_id` bigint(20) UNSIGNED NOT NULL,
  `quantity` int(10) UNSIGNED NOT NULL DEFAULT 1,
  `unit_price` decimal(12,2) NOT NULL,
  `total_price` decimal(12,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `pos_order_items`
--

INSERT INTO `pos_order_items` (`id`, `pos_order_id`, `product_id`, `quantity`, `unit_price`, `total_price`, `created_at`, `updated_at`) VALUES
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
(11, 11, 1, 1, 15000.00, 15000.00, '2026-11-09 04:24:14', '2026-11-09 04:24:14');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `products`
--

CREATE TABLE `products` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `product_category_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `sku` varchar(50) NOT NULL,
  `barcode` varchar(50) DEFAULT NULL,
  `selling_price` decimal(12,2) NOT NULL,
  `cost_price` decimal(12,2) NOT NULL DEFAULT 0.00,
  `stock_quantity` int(11) NOT NULL DEFAULT 0,
  `low_stock_threshold` smallint(5) UNSIGNED NOT NULL DEFAULT 5,
  `image` varchar(255) DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `products`
--

INSERT INTO `products` (`id`, `product_category_id`, `name`, `sku`, `barcode`, `selling_price`, `cost_price`, `stock_quantity`, `low_stock_threshold`, `image`, `is_active`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Nước uống Revive bù điện giải', 'FB-REVIVE', '893456789001', 15000.00, 8000.00, 109, 10, NULL, 1, '2026-11-09 03:07:17', '2026-11-09 04:24:14', NULL),
(2, 1, 'Nước khoáng thiên nhiên Lavie 500ml', 'FB-LAVIE', '893456789002', 10000.00, 5000.00, 200, 10, NULL, 1, '2026-11-09 03:07:17', '2026-11-09 03:07:17', NULL),
(3, 1, 'Nước Pocari Sweat 500ml', 'FB-POCARI', '893456789003', 22000.00, 14000.00, 85, 10, NULL, 1, '2026-11-09 03:07:17', '2026-11-09 03:07:17', NULL),
(4, 2, 'Ống cầu Ba Sao Pro 12 quả', 'SHU-BASAO', '893456789004', 240000.00, 195000.00, 45, 10, NULL, 1, '2026-11-09 03:07:17', '2026-11-09 03:07:17', NULL),
(5, 2, 'Ống cầu Yonex AS40 Quốc tế', 'SHU-YONEX40', '893456789005', 480000.00, 390000.00, 18, 10, NULL, 1, '2026-11-09 03:07:17', '2026-11-09 03:07:17', NULL),
(6, 3, 'Thuê vợt Yonex Astrox Pro / trận', 'REN-ASTROX', '893456789006', 50000.00, 0.00, 20, 10, NULL, 1, '2026-11-09 03:07:17', '2026-11-09 03:07:17', NULL),
(7, 4, 'Quấn cán vợt Yonex AC102EX', 'ACC-AC102', '893456789007', 35000.00, 18000.00, 150, 10, NULL, 1, '2026-11-09 03:07:17', '2026-11-09 03:07:17', NULL),
(8, 5, 'Cước đan Yonex BG65 Ti', 'STR-BG65TI', '893456789008', 140000.00, 85000.00, 35, 10, NULL, 1, '2026-11-09 03:07:17', '2026-11-09 03:07:17', NULL),
(9, 5, 'Công căng vợt điện tử 4 nút', 'STR-LABOR', '893456789009', 50000.00, 0.00, 999, 10, NULL, 1, '2026-11-09 03:07:17', '2026-11-09 03:07:17', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `product_categories`
--

CREATE TABLE `product_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `icon` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `product_categories`
--

INSERT INTO `product_categories` (`id`, `name`, `slug`, `icon`, `created_at`, `updated_at`) VALUES
(1, 'Đồ uống F&B', 'drinks', 'local_drink', '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
(2, 'Ống cầu thi đấu', 'shuttlecocks', 'sports_tennis', '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
(3, 'Cho thuê vợt', 'racket-rental', 'sports_handball', '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
(4, 'Phụ kiện & Cuốn cán', 'accessories', 'build_circle', '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
(5, 'Căng vợt điện tử', 'stringing-services', 'precision_manufacturing', '2026-11-09 03:07:17', '2026-11-09 03:07:17');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `refunds`
--

CREATE TABLE `refunds` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED NOT NULL,
  `payment_transaction_id` bigint(20) UNSIGNED DEFAULT NULL,
  `amount` decimal(12,2) NOT NULL,
  `reason` varchar(255) NOT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `approved_by_user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `reviews`
--

CREATE TABLE `reviews` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `venue_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED DEFAULT NULL,
  `rating` tinyint(3) UNSIGNED NOT NULL DEFAULT 5,
  `comment` text NOT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `reviews`
--

INSERT INTO `reviews` (`id`, `venue_id`, `user_id`, `booking_id`, `rating`, `comment`, `is_approved`, `created_at`, `updated_at`) VALUES
(1, 1, 6, 1, 5, 'Sân Cầu Giấy thảm cực êm, độ nảy tốt, đèn không bị chói mắt. Nhân viên nhiệt tình hỗ trợ check-in rất nhanh bằng QR!', 1, '2026-11-09 03:07:17', '2026-11-09 03:07:17');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `review_replies`
--

CREATE TABLE `review_replies` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `review_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `reply_text` text NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `roles`
--

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `display_name` varchar(255) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `roles`
--

INSERT INTO `roles` (`id`, `name`, `display_name`, `description`, `created_at`, `updated_at`) VALUES
(1, 'super_admin', 'Quản trị viên tối cao', 'Toàn quyền hệ thống', '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
(2, 'admin', 'Quản lý cơ sở', 'Quản trị cụm sân và vận hành', '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
(3, 'owner', 'Chủ sân (Owner)', 'Xem doanh thu, tài chính, rút tiền', '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
(4, 'staff', 'Nhân viên lễ tân / Thu ngân', 'Điều phối sân, check-in, POS', '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
(5, 'coach', 'Huấn luyện viên', 'Dạy học viên Academy', '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
(6, 'customer', 'Khách hàng', 'Đặt sân, chơi cầu lông, ghép kèo', '2026-11-09 03:07:17', '2026-11-09 03:07:17');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `role_user`
--

CREATE TABLE `role_user` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `role_id` bigint(20) UNSIGNED NOT NULL,
  `venue_id` bigint(20) UNSIGNED DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `service_orders`
--

CREATE TABLE `service_orders` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `order_code` varchar(32) NOT NULL,
  `venue_id` bigint(20) UNSIGNED NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_phone` varchar(255) NOT NULL,
  `service_type` varchar(255) NOT NULL DEFAULT 'stringing',
  `string_type` varchar(255) DEFAULT NULL,
  `tension_kg` decimal(4,1) DEFAULT NULL,
  `method_knots` varchar(255) NOT NULL DEFAULT '4_knots',
  `technician_id` bigint(20) UNSIGNED DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'pending',
  `price` decimal(12,2) NOT NULL,
  `notes` text DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('1jgygzMopspkKWG0BrO1EauyobZW8GxvXbELwnVP', NULL, '127.0.0.1', 'curl/8.13.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiazNNRE80dWN6dzlYUUxBY2U4d1pwazFFTk1xU1NGRUNveDhzTHBuSiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zdGFmZi9kZXNrIjtzOjU6InJvdXRlIjtzOjEwOiJzdGFmZi5kZXNrIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1794221065),
('528HGmZCPbCgaqC8oVqhb3qjsWBtdK6y3N33LFqY', NULL, '127.0.0.1', 'curl/8.13.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiYlFCVGh3aEQ4VWdqRUM4QW9NZ3dVVmtxdmZEc2tuTm5COXBKTEhyRCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9leHBsb3JlIjtzOjU6InJvdXRlIjtzOjc6ImV4cGxvcmUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1794221056),
('5bEKAmxduFnB2UljjwmI6iK5qkTId4DNcgZNgKc7', NULL, '127.0.0.1', 'curl/8.13.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiTmMwbjNiZnRsVDFXUUEwcU96ck95TGVpWkF3MUU4UU9RWFgwWEVHaiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zY29yZWJvYXJkLzEiO3M6NToicm91dGUiO3M6MTY6InNjb3JlYm9hcmQuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1794221066),
('7erLwr6DuwRsy2OCbHb7Rm3IRYYGIsyEQpvwsq4O', NULL, '127.0.0.1', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiT2daMGp5TVphWkNveUk4RWM5M25PQmJpUjBKeDg1M3NVcGx0ZnRMVCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wb3MiO3M6NToicm91dGUiO3M6OToicG9zLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1794222387),
('a5joRc07FxpFLLhlqEUfBVMZvwRosVwARJEZssfC', NULL, '127.0.0.1', 'curl/8.13.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiQ01DSElrTFdkbEpKTVRGWERvcEVUUlB6OWtobVV3S3cyTTF5aGJDdCI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL215LWJvb2tpbmdzIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9teS1ib29raW5ncyI7czo1OiJyb3V0ZSI7czoxMToibXktYm9va2luZ3MiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1794221636),
('b8oLmDHVHh2Y3XVmtbiIwpOCZAKK2CVR3m02U1wr', NULL, '127.0.0.1', 'curl/8.13.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoib0t0anFkNTFlY05hc2FRa0JNRk1KenpsdzRKU1lHY3FaQzlyMEl5MCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9raW9zayI7czo1OiJyb3V0ZSI7czoxMToia2lvc2suaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1794221066),
('CvKKSeMxXRTehZ9UsqN9AOxkoDhnxhx5hKRSl2TM', NULL, '127.0.0.1', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiT2VPNUVYcVp5OVhxOFMzSjg2UUthbUN1d3pTdjQ2SjRpbjVOb3VqeSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9leHBsb3JlIjtzOjU6InJvdXRlIjtzOjc6ImV4cGxvcmUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1794222385),
('EOxrtljD1At7SUHtLfltAXUZMT949MECDdau62Vp', NULL, '127.0.0.1', 'curl/8.13.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUHZRSllpZzVqS1IzYjYxMU5Pald0R2k4U0pmbVZocVpKUnF5ZWduVyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9leHBsb3JlIjtzOjU6InJvdXRlIjtzOjc6ImV4cGxvcmUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1794221854),
('GDKPnaOpb5Xrzeu0GK2xu6Iycg4IBqpZaZFVbQGP', NULL, '127.0.0.1', 'curl/8.13.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiSklPUFBuUXFIOXYzTkY2MDFGT29pMlhXNVRhZEs5VmcwejhNSW5XdiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzY6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi90aW1lbGluZSI7czo1OiJyb3V0ZSI7czoxNDoiYWRtaW4udGltZWxpbmUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1794221065),
('hIjh19p2r63kR8NozIbTTMilrmjMV6kXdZF8jYrg', NULL, '127.0.0.1', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiN2Y3bzlIRDhWbGRXcjRLQ3hBZWFSeGFwQzQ3SUpuNlRyTUJNOHg0aiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9raW9zayI7czo1OiJyb3V0ZSI7czoxMToia2lvc2suaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1794222387),
('HOFHKqQj69uqyCvY5Ibpmosn6eqzyrfxHhjqxkrL', 1, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/157.0.0.0 Safari/537.36', 'YTo1OntzOjY6Il90b2tlbiI7czo0MDoiWTg2YXJudjRxZDdXNnJ4dDhlZVhwcUREdEpuQmFRZGVGWFhZTFk0bSI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJuZXciO2E6MDp7fXM6Mzoib2xkIjthOjA6e319czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hZG1pbi9pb3QtZGV2aWNlcyI7czo1OiJyb3V0ZSI7czoxNzoiYWRtaW4uaW90LWRldmljZXMiO31zOjUwOiJsb2dpbl93ZWJfNTliYTM2YWRkYzJiMmY5NDAxNTgwZjAxNGM3ZjU4ZWE0ZTMwOTg5ZCI7aToxO3M6MTc6InBhc3N3b3JkX2hhc2hfd2ViIjtzOjY0OiJhODZmMTczNGJlZTQ4ZGVkMTcyZDc3NTAzOWJkMDYwMzNmYjIxZDU1ZDdiYjIzNDU0MGFlNzZhYjRhMTRkZWIxIjt9', 1794223631),
('izJLrJI3IMXEYB7wCIB1NyMoZI6n9EzjOjH2uQQO', NULL, '127.0.0.1', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoibWtJUTVrM01jcHZTZjlJODBGYXVQRDhyVXNMbnI0STRoSTRjSFNxaiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzA6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9yZWdpc3RlciI7czo1OiJyb3V0ZSI7czo4OiJyZWdpc3RlciI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1794222388),
('JnlfqchwahSgozZh1BwWEahHimEoiVvICoQxLizO', NULL, '127.0.0.1', 'curl/8.13.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiY1JsQTYwbGQxR1llM216cFgxaEZRd3hzeDR5a3dpRzZaYll1RVZhdCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wb3MiO3M6NToicm91dGUiO3M6OToicG9zLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1794221065),
('kFMv7Vry0hEMAMIoNrjG4G9AToOklh6alAt6vviv', NULL, '127.0.0.1', 'curl/8.13.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRk9EZldOZ0ZyM1ltTzRsbTRNU1V6d0JqOFVUMkFOQWtwTFpLUEh5cyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9leHBsb3JlIjtzOjU6InJvdXRlIjtzOjc6ImV4cGxvcmUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1794221064),
('KOVKvIoDqSUleTWN3Ys0J4CWkCLnX0JyTFYT50eH', NULL, '127.0.0.1', 'curl/8.13.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUjNBblVsQW9Pb3JVbmZTMzNyOUZjamZpNmtOV3FrWE1SaDBmS1F2ciI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hY2FkZW15IjtzOjU6InJvdXRlIjtzOjEzOiJhY2FkZW15LmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1794221854),
('lFKyeRvZ83Yv04ibAwUKvZ7ZrFCC4lUEhClbr6UW', NULL, '127.0.0.1', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVEdBclo0REEzWXMwbGc5MUhoYU56ZGZkNE1NVFphZnZXR1NnMjZjYyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9leHBsb3JlL2dvc3BvcnQtcHJlbWl1bS1teS1kaW5oIjtzOjU6InJvdXRlIjtzOjExOiJ2ZW51ZXMuc2hvdyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1794223494),
('Lx0DbiG6KqhocYqNbHrG2fO5wy8gVOag0pZ7R6pf', NULL, '127.0.0.1', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiRVFWb1VsUU1kTEZtWUprU1RWMEdVQkViRkdKRzRJWlpEc3dZNGVwayI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tYXRjaG1ha2luZyI7czo1OiJyb3V0ZSI7czoxNzoibWF0Y2htYWtpbmcuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1794222387),
('LxTEw2Y18NinhQNCfxuOnjkQtm0zLuBRZZgtF3lo', NULL, '127.0.0.1', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiR0hkODEzRlpPM2sxVGx4ZWdOY29CZ0paNUVNczNYSjhBU0xIMVFiUSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9leHBsb3JlL2dvc3BvcnQtYXJlbmEtY2F1LWdpYXkiO3M6NToicm91dGUiO3M6MTE6InZlbnVlcy5zaG93Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1794222149),
('M42K3j8S654xEwtmjxFWFfONQDcs39EaEGjj1NQy', NULL, '127.0.0.1', 'curl/8.13.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiaEZhU2h5UmRrRWFuM3Q1aHg1MXVGeGN5SlM4R2dpaEN6elNLSGM4ZiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9odWIiO3M6NToicm91dGUiO3M6OToiaHViLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1794221855),
('nLug390Y44jtW4j2ECPqgUbTqTIGAXvflMFg0luh', NULL, '127.0.0.1', 'curl/8.13.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNUJJeWJWSGN3elVDTkNxMjM3MEp0VmROS1JzbUVRYkVZS0xYN2twWCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wcm9tb3Rpb25zIjtzOjU6InJvdXRlIjtzOjE2OiJwcm9tb3Rpb25zLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1794221854),
('oB4URJga7UpISltLGXQIsCyQYRg93cKe3rmRkUAF', NULL, '127.0.0.1', 'curl/8.13.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWEJxTkJySWEzc1ZlbzB6TWpWeHBaZ0xnNUNRVWVOdDBYMFh0bE5OVCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9leHBsb3JlIjtzOjU6InJvdXRlIjtzOjc6ImV4cGxvcmUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1794221668),
('odqjtUuZLNHxicuyt39Ijm38r1ZqosW97tig8vGn', NULL, '127.0.0.1', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiWHdyOThrdzRmUGNORVdxNmo3WEJvUFlMbGNvQTdweG5oV0MxanI5cSI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9leHBsb3JlL2dvc3BvcnQtYXJlbmEtY2F1LWdpYXkiO3M6NToicm91dGUiO3M6MTE6InZlbnVlcy5zaG93Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1794222386),
('PmC4wTBMqdqwDYcSHkO2QLipcMoSaO1i54oyu4Mt', NULL, '127.0.0.1', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiOGd2bDZVYlVlUGV1eFIzVkdud3FXbVAyY29NcVVvSUJKbnRzdTFUVCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9hY2FkZW15IjtzOjU6InJvdXRlIjtzOjEzOiJhY2FkZW15LmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1794222386),
('qmm1enGV835DSccgIqdzyOKDdkeTsR3zyd4WsiYN', NULL, '127.0.0.1', 'curl/8.13.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoicG80OWE1NmRiRmhBR0pPMjJYVnhYWFN5RzMxdEdRenJWUmlBSm1qWiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mzc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9vd25lci9kYXNoYm9hcmQiO3M6NToicm91dGUiO3M6MTU6Im93bmVyLmRhc2hib2FyZCI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1794221065),
('RFlCeNY3QigPfT31EdTYowrAktTAyoLKl1SvFLPo', NULL, '127.0.0.1', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiclZ1TmVFeU1vaE5pV2VtNEozaEp3a3I4TVNnczZyeEk3NUgwV2hlaCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6NTM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9leHBsb3JlL2dvc3BvcnQtc3Rhci10aGFuaC14dWFuIjtzOjU6InJvdXRlIjtzOjExOiJ2ZW51ZXMuc2hvdyI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1794223495),
('SBWQE1ZEEdgsdGVyRmfefTqFWYcrLrx12zWd0IW0', NULL, '127.0.0.1', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiNXBqV3NJSzJlMFhLaXVNVTQ5SHprNU5tZTQxNHV5S2lQQlpiZldQWiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjU6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9odWIiO3M6NToicm91dGUiO3M6OToiaHViLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1794222386),
('V7GslKdgEgCebtcJ6sP9y0hCOzhVKPYrkVGBOmrE', NULL, '127.0.0.1', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiVHBEdGJRWnpSYTBVcUhoVHBGTUxWbU9ySHV6c2Z1UmZGeVM3TmkwSCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzQ6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9zY29yZWJvYXJkLzEiO3M6NToicm91dGUiO3M6MTY6InNjb3JlYm9hcmQuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1794222387),
('vpKjoiMd4B9mRZk7Ah9UnuF5nsAn2bEFTf7YkX96', NULL, '127.0.0.1', 'curl/8.13.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiM245T1p5NjdGbHNXOU9PTXBIdTdIWDJSWjJoQVo1QTdHT0lDT1NhQiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9tYXRjaG1ha2luZyI7czo1OiJyb3V0ZSI7czoxNzoibWF0Y2htYWtpbmcuaW5kZXgiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1794221855),
('VZZUWNFCNs290qb5bfFFwrkZc5lxxsAhg6K0LfZ5', NULL, '127.0.0.1', 'curl/8.13.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiU0Nxb09IcElUbE9vRkpjTkxJSE5SWkJkbzBQRlhhSEw3bFBpenM4eCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjk6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9leHBsb3JlIjtzOjU6InJvdXRlIjtzOjc6ImV4cGxvcmUiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1794221425),
('x0ltSxTBI4M3c1RkKeBNinmupo1yWHA1voLJCKGI', NULL, '127.0.0.1', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiUjNhbGxVeERHZDdVaVkxSW5PMFVleWl4RnhodXNqUUdXbFpnMzdMZyI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6Mjc6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9sb2dpbiI7czo1OiJyb3V0ZSI7czo1OiJsb2dpbiI7fXM6NjoiX2ZsYXNoIjthOjI6e3M6Mzoib2xkIjthOjA6e31zOjM6Im5ldyI7YTowOnt9fX0=', 1794222388),
('XkOp9FmIUv9N9DjdYSnww1acFxFLKoqUkratcBOG', NULL, '127.0.0.1', 'curl/8.13.0', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiZ0VNR2Y1bnNDME1YWG9GQXlpUjNNOThXcEJUQTFKeWhMakVHTnFkMCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1794221642),
('z2MTShHX91X7CJ7GllIo65rW3T1g8FcBCPZY63m6', NULL, '127.0.0.1', 'curl/8.13.0', 'YTo0OntzOjY6Il90b2tlbiI7czo0MDoiZDNFVnZIM3NsOExQTDB4UElGeXg4cXlxYVl1ZVFudTlOUnVhbGhKVyI7czozOiJ1cmwiO2E6MTp7czo4OiJpbnRlbmRlZCI7czozMzoiaHR0cDovLzEyNy4wLjAuMTo4MDAwL215LWJvb2tpbmdzIjt9czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzM6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9teS1ib29raW5ncyI7czo1OiJyb3V0ZSI7czoxMToibXktYm9va2luZ3MiO31zOjY6Il9mbGFzaCI7YToyOntzOjM6Im9sZCI7YTowOnt9czozOiJuZXciO2E6MDp7fX19', 1794221854),
('zD0PslqEHR83FjI5a2GJnT3N4K3L78CWszJ8PVwG', NULL, '127.0.0.1', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoidndQVE81VUJ3bFR4NzJNUTA2SkhrVExuR29VaVJsU0tDQWpSSTFrcCI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MzI6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMC9wcm9tb3Rpb25zIjtzOjU6InJvdXRlIjtzOjE2OiJwcm9tb3Rpb25zLmluZGV4Ijt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1794222386),
('ZRmdnUbXDJLGuMzYLrANbjdbO8speqO7inWQ7MyC', NULL, '127.0.0.1', '', 'YTozOntzOjY6Il90b2tlbiI7czo0MDoiYzJQWmp1QmJDVHdySmhYdGg0N0xIMWtGU0Q4RDA1RWxUeHdFTUYxaiI7czo5OiJfcHJldmlvdXMiO2E6Mjp7czozOiJ1cmwiO3M6MjE6Imh0dHA6Ly8xMjcuMC4wLjE6ODAwMCI7czo1OiJyb3V0ZSI7czo0OiJob21lIjt9czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1794222385);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `staff_profiles`
--

CREATE TABLE `staff_profiles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `venue_id` bigint(20) UNSIGNED DEFAULT NULL,
  `employee_code` varchar(30) NOT NULL,
  `position` varchar(255) NOT NULL,
  `salary_type` varchar(255) NOT NULL DEFAULT 'monthly',
  `base_salary` decimal(12,2) NOT NULL DEFAULT 0.00,
  `hire_date` date DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `staff_profiles`
--

INSERT INTO `staff_profiles` (`id`, `user_id`, `venue_id`, `employee_code`, `position`, `salary_type`, `base_salary`, `hire_date`, `created_at`, `updated_at`) VALUES
(1, 4, 1, 'EMP-001', 'Lễ tân & Điều phối sân', 'monthly', 8500000.00, '2026-05-09', '2026-11-09 03:07:17', '2026-11-09 03:07:17');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `staff_shifts`
--

CREATE TABLE `staff_shifts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `venue_id` bigint(20) UNSIGNED NOT NULL,
  `shift_date` date NOT NULL,
  `shift_type` varchar(255) NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `clock_in_time` timestamp NULL DEFAULT NULL,
  `clock_out_time` timestamp NULL DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'scheduled',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `staff_shifts`
--

INSERT INTO `staff_shifts` (`id`, `user_id`, `venue_id`, `shift_date`, `shift_type`, `start_time`, `end_time`, `clock_in_time`, `clock_out_time`, `status`, `created_at`, `updated_at`) VALUES
(1, 4, 1, '2026-11-09', 'Ca chiều (14:00 - 22:00)', '14:00:00', '22:00:00', '2026-11-09 06:50:00', NULL, 'present', '2026-11-09 03:07:17', '2026-11-09 03:07:17');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tournaments`
--

CREATE TABLE `tournaments` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `venue_id` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `banner_image` varchar(255) DEFAULT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `prize_pool` decimal(12,2) NOT NULL DEFAULT 0.00,
  `format` varchar(255) NOT NULL DEFAULT 'knockout',
  `status` varchar(255) NOT NULL DEFAULT 'upcoming',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tournaments`
--

INSERT INTO `tournaments` (`id`, `venue_id`, `title`, `slug`, `banner_image`, `start_date`, `end_date`, `prize_pool`, `format`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'Giải Vô Địch Cầu Lông GoSport Open 2026', 'gosport-open-2026', 'https://images.unsplash.com/photo-1546519638-68e109498ffc?w=1200&auto=format&fit=crop&q=80', '2026-11-24', '2026-11-26', 50000000.00, 'knockout', 'upcoming', '2026-11-09 03:07:17', '2026-11-09 03:07:17');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `tournament_matches`
--

CREATE TABLE `tournament_matches` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tournament_id` bigint(20) UNSIGNED NOT NULL,
  `court_id` bigint(20) UNSIGNED DEFAULT NULL,
  `round_name` varchar(255) NOT NULL,
  `team_a_name` varchar(255) NOT NULL,
  `team_b_name` varchar(255) NOT NULL,
  `score_set_1` varchar(255) DEFAULT NULL,
  `score_set_2` varchar(255) DEFAULT NULL,
  `score_set_3` varchar(255) DEFAULT NULL,
  `winner_team` varchar(255) DEFAULT NULL,
  `status` varchar(255) NOT NULL DEFAULT 'scheduled',
  `match_time` datetime DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `tournament_matches`
--

INSERT INTO `tournament_matches` (`id`, `tournament_id`, `court_id`, `round_name`, `team_a_name`, `team_b_name`, `score_set_1`, `score_set_2`, `score_set_3`, `winner_team`, `status`, `match_time`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 'Chung kết Đôi Nam', 'Nguyễn Tuấn / Trần Khải', 'Lê Hoàng / Phạm Minh', '21-19', '18-21', '21-17', 'Nguyễn Tuấn / Trần Khải', 'finished', '2026-11-08 10:07:17', '2026-11-09 03:07:17', '2026-11-09 03:07:17');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `phone` varchar(255) DEFAULT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(255) NOT NULL DEFAULT 'customer',
  `avatar` varchar(255) DEFAULT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `phone`, `email_verified_at`, `password`, `role`, `avatar`, `remember_token`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 'GoSport Super Admin', 'admin@gosport.vn', '0901000001', NULL, '$2y$12$EWp3ub6Q.6tTPuwdZdKHD.loqfvxaMlfAdUUzcbMXws9DMvPAVO0a', 'super_admin', NULL, NULL, '2026-11-09 03:07:17', '2026-11-09 03:07:17', NULL),
(2, 'Trần Quang Khải (Chủ chuỗi sân)', 'owner@gosport.vn', '0902000002', NULL, '$2y$12$EWp3ub6Q.6tTPuwdZdKHD.loqfvxaMlfAdUUzcbMXws9DMvPAVO0a', 'owner', NULL, NULL, '2026-11-09 03:07:17', '2026-11-09 03:07:17', NULL),
(3, 'Lê Hoàng Nam (Quản lý Cầu Giấy)', 'manager@gosport.vn', '0903000003', NULL, '$2y$12$EWp3ub6Q.6tTPuwdZdKHD.loqfvxaMlfAdUUzcbMXws9DMvPAVO0a', 'admin', NULL, NULL, '2026-11-09 03:07:17', '2026-11-09 03:07:17', NULL),
(4, 'Đỗ Thu Thảo (Lễ tân quầy)', 'staff@gosport.vn', '0904000004', NULL, '$2y$12$EWp3ub6Q.6tTPuwdZdKHD.loqfvxaMlfAdUUzcbMXws9DMvPAVO0a', 'staff', NULL, NULL, '2026-11-09 03:07:17', '2026-11-09 03:07:17', NULL),
(5, 'Nguyễn Văn Hùng (HLV Kiện tướng QG)', 'coach@gosport.vn', '0905000005', NULL, '$2y$12$EWp3ub6Q.6tTPuwdZdKHD.loqfvxaMlfAdUUzcbMXws9DMvPAVO0a', 'coach', NULL, NULL, '2026-11-09 03:07:17', '2026-11-09 03:07:17', NULL),
(6, 'Nguyễn Văn Tuấn', 'customer@gosport.vn', '0988889999', NULL, '$2y$04$vDq9YFm6B.vgyqtSevW7cuiDPCJRBoyZZ.VUvl71QkQ2WZWBtICvO', 'customer', NULL, NULL, '2026-11-09 03:07:17', '2026-11-09 03:40:11', NULL),
(7, 'aa aa', 'lehao99nd@gmail.com', '0862225508', NULL, '$2y$12$nDyp51uGeiNxBqLYJU74lO.DFYXbZ0mO5gk/HtAd016pfsGe2v6Hm', 'customer', NULL, NULL, '2026-11-09 03:46:05', '2026-11-09 03:46:05', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_memberships`
--

CREATE TABLE `user_memberships` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `membership_tier_id` bigint(20) UNSIGNED NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date DEFAULT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `user_memberships`
--

INSERT INTO `user_memberships` (`id`, `user_id`, `membership_tier_id`, `start_date`, `end_date`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 6, 3, '2026-09-09', '2027-09-09', 1, '2026-11-09 03:07:17', '2026-11-09 03:07:17');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `user_profiles`
--

CREATE TABLE `user_profiles` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `skill_level` varchar(255) NOT NULL DEFAULT 'intermediate',
  `play_style` varchar(255) DEFAULT NULL,
  `preferred_hand` varchar(255) NOT NULL DEFAULT 'right',
  `racket_brand` varchar(255) DEFAULT NULL,
  `smash_speed_max` int(10) UNSIGNED NOT NULL DEFAULT 180,
  `matches_played` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `win_rate` decimal(5,2) NOT NULL DEFAULT 50.00,
  `bio` text DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `user_profiles`
--

INSERT INTO `user_profiles` (`id`, `user_id`, `skill_level`, `play_style`, `preferred_hand`, `racket_brand`, `smash_speed_max`, `matches_played`, `win_rate`, `bio`, `created_at`, `updated_at`) VALUES
(1, 6, 'intermediate_plus', 'Tấn công Smash', 'right', 'Yonex Astrox 100ZZ', 240, 48, 68.50, 'Đam mê smash cầu lửa, thích giao lưu học hỏi mỗi tối thứ 4 & CN hàng tuần.', '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
(2, 7, 'intermediate', 'Tấn công Smash', 'right', NULL, 180, 0, 50.00, NULL, '2026-11-09 03:46:05', '2026-11-09 03:46:05');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `venues`
--

CREATE TABLE `venues` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `owner_id` bigint(20) UNSIGNED DEFAULT NULL,
  `name` varchar(255) NOT NULL,
  `slug` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `district` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL DEFAULT 'Hà Nội',
  `latitude` decimal(10,8) DEFAULT NULL,
  `longitude` decimal(11,8) DEFAULT NULL,
  `phone` varchar(20) NOT NULL,
  `email` varchar(255) DEFAULT NULL,
  `featured_image` varchar(255) DEFAULT NULL,
  `gallery` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`gallery`)),
  `description` text DEFAULT NULL,
  `opening_time` time NOT NULL DEFAULT '06:00:00',
  `closing_time` time NOT NULL DEFAULT '23:00:00',
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `rating_avg` decimal(3,2) NOT NULL DEFAULT 5.00,
  `reviews_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `venues`
--

INSERT INTO `venues` (`id`, `owner_id`, `name`, `slug`, `address`, `district`, `city`, `latitude`, `longitude`, `phone`, `email`, `featured_image`, `gallery`, `description`, `opening_time`, `closing_time`, `is_active`, `rating_avg`, `reviews_count`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 2, 'GoSport Arena Cầu Giấy', 'gosport-arena-cau-giay', 'Số 26 Phạm Văn Đồng, Mai Dịch, Cầu Giấy', 'Cầu Giấy', 'Hà Nội', 21.03680000, 105.78250000, '024 3888 6666', 'caugiay@gosport.vn', 'https://images.unsplash.com/photo-1626224583764-f87db24ac4ea?w=1200&auto=format&fit=crop&q=80', '[\"https:\\/\\/images.unsplash.com\\/photo-1626224583764-f87db24ac4ea?w=800&auto=format&fit=crop&q=80\",\"https:\\/\\/images.unsplash.com\\/photo-1546519638-68e109498ffc?w=800&auto=format&fit=crop&q=80\",\"https:\\/\\/images.unsplash.com\\/photo-1511067007772-9da29974ce44?w=800&auto=format&fit=crop&q=80\"]', 'Tổ hợp 12 sân cầu lông trong nhà lớn nhất khu vực Cầu Giấy. Thảm Yonex BWF cao cấp, máy lạnh trung tâm, hệ thống ánh sáng chống chói mắt tiêu chuẩn quốc tế.', '06:00:00', '23:30:00', 1, 4.90, 128, '2026-11-09 03:07:17', '2026-11-09 03:07:17', NULL),
(2, 2, 'GoSport Premium Mỹ Đình', 'gosport-premium-my-dinh', 'Khu liên hợp Thể Thao Quốc Gia, Mỹ Đình, Nam Từ Liêm', 'Nam Từ Liêm', 'Hà Nội', 21.02050000, 105.76450000, '024 3999 8888', 'mydinh@gosport.vn', 'https://images.unsplash.com/photo-1546519638-68e109498ffc?w=1200&auto=format&fit=crop&q=80', '[\"https:\\/\\/images.unsplash.com\\/photo-1546519638-68e109498ffc?w=800&auto=format&fit=crop&q=80\"]', 'Cụm 8 sân tiêu chuẩn thi đấu nằm tại trung tâm Mỹ Đình với bãi đỗ xe ô tô rộng rãi, phòng tắm nóng lạnh miễn phí.', '06:00:00', '23:00:00', 1, 4.85, 96, '2026-11-09 03:07:17', '2026-11-09 03:07:17', NULL),
(3, 2, 'GoSport Star Thanh Xuân', 'gosport-star-thanh-xuan', 'Số 12 Khuất Duy Tiến, Thanh Xuân Bắc, Thanh Xuân', 'Thanh Xuân', 'Hà Nội', 20.99800000, 105.79900000, '024 3777 5555', 'thanhxuan@gosport.vn', 'https://images.unsplash.com/photo-1511067007772-9da29974ce44?w=1200&auto=format&fit=crop&q=80', NULL, 'Cụm 6 sân hiện đại trung tâm Thanh Xuân, giao thông thuận tiện, phục vụ cộng đồng sinh viên và dân văn phòng.', '06:00:00', '23:00:00', 1, 4.80, 74, '2026-11-09 03:07:17', '2026-11-09 03:07:17', NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `venue_amenities`
--

CREATE TABLE `venue_amenities` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `venue_id` bigint(20) UNSIGNED NOT NULL,
  `amenity_id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `venue_amenities`
--

INSERT INTO `venue_amenities` (`id`, `venue_id`, `amenity_id`, `created_at`, `updated_at`) VALUES
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
(17, 3, 4, NULL, NULL);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `vouchers`
--

CREATE TABLE `vouchers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `code` varchar(50) NOT NULL,
  `title` varchar(255) NOT NULL,
  `discount_type` varchar(255) NOT NULL DEFAULT 'fixed',
  `discount_value` decimal(12,2) NOT NULL,
  `min_order_amount` decimal(12,2) NOT NULL DEFAULT 0.00,
  `max_discount_amount` decimal(12,2) DEFAULT NULL,
  `usage_limit` int(10) UNSIGNED NOT NULL DEFAULT 100,
  `used_count` int(10) UNSIGNED NOT NULL DEFAULT 0,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT 1,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `vouchers`
--

INSERT INTO `vouchers` (`id`, `code`, `title`, `discount_type`, `discount_value`, `min_order_amount`, `max_discount_amount`, `usage_limit`, `used_count`, `start_date`, `end_date`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'GOSPORT20', 'Giảm 20% cho đơn đặt sân tối nay', 'percent', 20.00, 150000.00, 80000.00, 500, 38, '2026-11-04', '2026-12-09', 1, '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
(2, 'GIOVANG50', 'Giảm ngay 50.000đ khung giờ vàng', 'fixed', 50000.00, 200000.00, NULL, 200, 15, '2026-11-07', '2026-11-24', 1, '2026-11-09 03:07:17', '2026-11-09 03:07:17');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `voucher_redemptions`
--

CREATE TABLE `voucher_redemptions` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `voucher_id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `booking_id` bigint(20) UNSIGNED DEFAULT NULL,
  `discount_applied` decimal(12,2) NOT NULL,
  `redeemed_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `wallet_accounts`
--

CREATE TABLE `wallet_accounts` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` bigint(20) UNSIGNED NOT NULL,
  `balance` decimal(12,2) NOT NULL DEFAULT 0.00,
  `frozen_balance` decimal(12,2) NOT NULL DEFAULT 0.00,
  `currency` varchar(10) NOT NULL DEFAULT 'VND',
  `status` varchar(255) NOT NULL DEFAULT 'active',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `wallet_accounts`
--

INSERT INTO `wallet_accounts` (`id`, `user_id`, `balance`, `frozen_balance`, `currency`, `status`, `created_at`, `updated_at`) VALUES
(1, 6, 1500000.00, 0.00, 'VND', 'active', '2026-11-09 03:07:17', '2026-11-09 03:07:17'),
(2, 7, 0.00, 0.00, 'VND', 'active', '2026-11-09 03:46:05', '2026-11-09 03:46:05');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `wallet_ledger_entries`
--

CREATE TABLE `wallet_ledger_entries` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `wallet_account_id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) NOT NULL,
  `amount` decimal(12,2) NOT NULL,
  `balance_after` decimal(12,2) NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `reference_id` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `wallet_ledger_entries`
--

INSERT INTO `wallet_ledger_entries` (`id`, `wallet_account_id`, `type`, `amount`, `balance_after`, `description`, `reference_id`, `created_at`, `updated_at`) VALUES
(1, 1, 'deposit', 1500000.00, 1500000.00, 'Nạp tiền thẻ thể thao GoSport Khuyến mãi +15%', 'DEP-LRPKYJUP', '2026-11-09 03:07:17', '2026-11-09 03:07:17');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `amenities`
--
ALTER TABLE `amenities`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `bookings`
--
ALTER TABLE `bookings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `bookings_booking_code_unique` (`booking_code`),
  ADD UNIQUE KEY `bookings_check_in_token_unique` (`check_in_token`),
  ADD KEY `bookings_created_by_staff_id_foreign` (`created_by_staff_id`),
  ADD KEY `bookings_user_id_status_index` (`user_id`,`status`),
  ADD KEY `bookings_venue_id_booking_date_index` (`venue_id`,`booking_date`),
  ADD KEY `bookings_booking_date_index` (`booking_date`),
  ADD KEY `bookings_status_index` (`status`),
  ADD KEY `bookings_payment_status_index` (`payment_status`);

--
-- Chỉ mục cho bảng `booking_holds`
--
ALTER TABLE `booking_holds`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_holds_user_id_foreign` (`user_id`),
  ADD KEY `idx_hold_slot` (`court_id`,`booking_date`,`start_time`,`end_time`),
  ADD KEY `booking_holds_expires_at_index` (`expires_at`);

--
-- Chỉ mục cho bảng `booking_items`
--
ALTER TABLE `booking_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_items_booking_id_foreign` (`booking_id`),
  ADD KEY `booking_items_court_id_start_time_end_time_index` (`court_id`,`start_time`,`end_time`);

--
-- Chỉ mục cho bảng `booking_status_histories`
--
ALTER TABLE `booking_status_histories`
  ADD PRIMARY KEY (`id`),
  ADD KEY `booking_status_histories_booking_id_foreign` (`booking_id`),
  ADD KEY `booking_status_histories_changed_by_user_id_foreign` (`changed_by_user_id`);

--
-- Chỉ mục cho bảng `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_expiration_index` (`expiration`);

--
-- Chỉ mục cho bảng `cache_locks`
--
ALTER TABLE `cache_locks`
  ADD PRIMARY KEY (`key`),
  ADD KEY `cache_locks_expiration_index` (`expiration`);

--
-- Chỉ mục cho bảng `check_ins`
--
ALTER TABLE `check_ins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `check_ins_court_id_foreign` (`court_id`),
  ADD KEY `check_ins_verified_by_staff_id_foreign` (`verified_by_staff_id`),
  ADD KEY `check_ins_booking_id_court_id_index` (`booking_id`,`court_id`);

--
-- Chỉ mục cho bảng `coaches`
--
ALTER TABLE `coaches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `coaches_user_id_foreign` (`user_id`);

--
-- Chỉ mục cho bảng `courses`
--
ALTER TABLE `courses`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `courses_slug_unique` (`slug`),
  ADD KEY `courses_coach_id_foreign` (`coach_id`);

--
-- Chỉ mục cho bảng `course_enrollments`
--
ALTER TABLE `course_enrollments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `course_enrollments_course_id_user_id_unique` (`course_id`,`user_id`),
  ADD KEY `course_enrollments_user_id_foreign` (`user_id`);

--
-- Chỉ mục cho bảng `course_schedules`
--
ALTER TABLE `course_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `course_schedules_course_id_foreign` (`course_id`),
  ADD KEY `course_schedules_venue_id_foreign` (`venue_id`);

--
-- Chỉ mục cho bảng `courts`
--
ALTER TABLE `courts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `courts_venue_id_court_number_unique` (`venue_id`,`court_number`),
  ADD KEY `courts_court_type_id_foreign` (`court_type_id`),
  ADD KEY `courts_is_active_index` (`is_active`);

--
-- Chỉ mục cho bảng `court_maintenance`
--
ALTER TABLE `court_maintenance`
  ADD PRIMARY KEY (`id`),
  ADD KEY `court_maintenance_court_id_start_datetime_end_datetime_index` (`court_id`,`start_datetime`,`end_datetime`);

--
-- Chỉ mục cho bảng `court_pricing_rules`
--
ALTER TABLE `court_pricing_rules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `court_pricing_rules_court_type_id_foreign` (`court_type_id`),
  ADD KEY `court_pricing_rules_venue_id_start_time_end_time_index` (`venue_id`,`start_time`,`end_time`);

--
-- Chỉ mục cho bảng `court_types`
--
ALTER TABLE `court_types`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `device_configurations`
--
ALTER TABLE `device_configurations`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `device_configurations_iot_device_id_config_key_unique` (`iot_device_id`,`config_key`);

--
-- Chỉ mục cho bảng `device_events`
--
ALTER TABLE `device_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `device_events_iot_device_id_foreign` (`iot_device_id`);

--
-- Chỉ mục cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Chỉ mục cho bảng `inventory_movements`
--
ALTER TABLE `inventory_movements`
  ADD PRIMARY KEY (`id`),
  ADD KEY `inventory_movements_product_id_foreign` (`product_id`),
  ADD KEY `inventory_movements_created_by_user_id_foreign` (`created_by_user_id`);

--
-- Chỉ mục cho bảng `invoices`
--
ALTER TABLE `invoices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `invoices_invoice_code_unique` (`invoice_code`),
  ADD KEY `invoices_booking_id_foreign` (`booking_id`),
  ADD KEY `invoices_pos_order_id_index` (`pos_order_id`);

--
-- Chỉ mục cho bảng `iot_devices`
--
ALTER TABLE `iot_devices`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `iot_devices_serial_number_unique` (`serial_number`),
  ADD KEY `iot_devices_venue_id_foreign` (`venue_id`),
  ADD KEY `iot_devices_court_id_foreign` (`court_id`),
  ADD KEY `iot_devices_status_index` (`status`);

--
-- Chỉ mục cho bảng `jobs`
--
ALTER TABLE `jobs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `jobs_queue_index` (`queue`);

--
-- Chỉ mục cho bảng `job_batches`
--
ALTER TABLE `job_batches`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `loyalty_transactions`
--
ALTER TABLE `loyalty_transactions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `loyalty_transactions_user_id_foreign` (`user_id`);

--
-- Chỉ mục cho bảng `match_participants`
--
ALTER TABLE `match_participants`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `match_participants_match_post_id_user_id_unique` (`match_post_id`,`user_id`),
  ADD KEY `match_participants_user_id_foreign` (`user_id`);

--
-- Chỉ mục cho bảng `match_posts`
--
ALTER TABLE `match_posts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `match_posts_user_id_foreign` (`user_id`),
  ADD KEY `match_posts_booking_id_foreign` (`booking_id`),
  ADD KEY `match_posts_venue_id_play_datetime_index` (`venue_id`,`play_datetime`),
  ADD KEY `match_posts_status_index` (`status`);

--
-- Chỉ mục cho bảng `membership_tiers`
--
ALTER TABLE `membership_tiers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `membership_tiers_slug_unique` (`slug`);

--
-- Chỉ mục cho bảng `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `operating_hours`
--
ALTER TABLE `operating_hours`
  ADD PRIMARY KEY (`id`),
  ADD KEY `operating_hours_venue_id_foreign` (`venue_id`);

--
-- Chỉ mục cho bảng `password_reset_tokens`
--
ALTER TABLE `password_reset_tokens`
  ADD PRIMARY KEY (`email`);

--
-- Chỉ mục cho bảng `payment_events`
--
ALTER TABLE `payment_events`
  ADD PRIMARY KEY (`id`),
  ADD KEY `payment_events_payment_transaction_id_foreign` (`payment_transaction_id`);

--
-- Chỉ mục cho bảng `payment_transactions`
--
ALTER TABLE `payment_transactions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `payment_transactions_transaction_code_unique` (`transaction_code`),
  ADD KEY `payment_transactions_user_id_foreign` (`user_id`),
  ADD KEY `payment_transactions_booking_id_status_index` (`booking_id`,`status`),
  ADD KEY `payment_transactions_status_index` (`status`),
  ADD KEY `payment_transactions_gateway_transaction_id_index` (`gateway_transaction_id`);

--
-- Chỉ mục cho bảng `posts`
--
ALTER TABLE `posts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `posts_slug_unique` (`slug`),
  ADD KEY `posts_post_category_id_foreign` (`post_category_id`),
  ADD KEY `posts_is_published_index` (`is_published`);

--
-- Chỉ mục cho bảng `post_categories`
--
ALTER TABLE `post_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `post_categories_slug_unique` (`slug`);

--
-- Chỉ mục cho bảng `pos_orders`
--
ALTER TABLE `pos_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `pos_orders_order_code_unique` (`order_code`),
  ADD KEY `pos_orders_venue_id_foreign` (`venue_id`),
  ADD KEY `pos_orders_staff_id_foreign` (`staff_id`),
  ADD KEY `pos_orders_customer_id_foreign` (`customer_id`);

--
-- Chỉ mục cho bảng `pos_order_items`
--
ALTER TABLE `pos_order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `pos_order_items_pos_order_id_foreign` (`pos_order_id`),
  ADD KEY `pos_order_items_product_id_foreign` (`product_id`);

--
-- Chỉ mục cho bảng `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `products_sku_unique` (`sku`),
  ADD UNIQUE KEY `products_barcode_unique` (`barcode`),
  ADD KEY `products_product_category_id_foreign` (`product_category_id`),
  ADD KEY `products_is_active_index` (`is_active`);

--
-- Chỉ mục cho bảng `product_categories`
--
ALTER TABLE `product_categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `product_categories_slug_unique` (`slug`);

--
-- Chỉ mục cho bảng `refunds`
--
ALTER TABLE `refunds`
  ADD PRIMARY KEY (`id`),
  ADD KEY `refunds_booking_id_foreign` (`booking_id`),
  ADD KEY `refunds_payment_transaction_id_foreign` (`payment_transaction_id`),
  ADD KEY `refunds_approved_by_user_id_foreign` (`approved_by_user_id`);

--
-- Chỉ mục cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `reviews_venue_id_foreign` (`venue_id`),
  ADD KEY `reviews_user_id_foreign` (`user_id`),
  ADD KEY `reviews_booking_id_foreign` (`booking_id`),
  ADD KEY `reviews_is_approved_index` (`is_approved`);

--
-- Chỉ mục cho bảng `review_replies`
--
ALTER TABLE `review_replies`
  ADD PRIMARY KEY (`id`),
  ADD KEY `review_replies_review_id_foreign` (`review_id`),
  ADD KEY `review_replies_user_id_foreign` (`user_id`);

--
-- Chỉ mục cho bảng `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `roles_name_unique` (`name`);

--
-- Chỉ mục cho bảng `role_user`
--
ALTER TABLE `role_user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `role_user_user_id_role_id_venue_id_unique` (`user_id`,`role_id`,`venue_id`),
  ADD KEY `role_user_role_id_foreign` (`role_id`),
  ADD KEY `role_user_venue_id_index` (`venue_id`);

--
-- Chỉ mục cho bảng `service_orders`
--
ALTER TABLE `service_orders`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `service_orders_order_code_unique` (`order_code`),
  ADD KEY `service_orders_venue_id_foreign` (`venue_id`),
  ADD KEY `service_orders_technician_id_foreign` (`technician_id`);

--
-- Chỉ mục cho bảng `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Chỉ mục cho bảng `staff_profiles`
--
ALTER TABLE `staff_profiles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `staff_profiles_user_id_unique` (`user_id`),
  ADD UNIQUE KEY `staff_profiles_employee_code_unique` (`employee_code`),
  ADD KEY `staff_profiles_venue_id_foreign` (`venue_id`);

--
-- Chỉ mục cho bảng `staff_shifts`
--
ALTER TABLE `staff_shifts`
  ADD PRIMARY KEY (`id`),
  ADD KEY `staff_shifts_user_id_foreign` (`user_id`),
  ADD KEY `staff_shifts_venue_id_shift_date_index` (`venue_id`,`shift_date`);

--
-- Chỉ mục cho bảng `tournaments`
--
ALTER TABLE `tournaments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `tournaments_slug_unique` (`slug`),
  ADD KEY `tournaments_venue_id_foreign` (`venue_id`);

--
-- Chỉ mục cho bảng `tournament_matches`
--
ALTER TABLE `tournament_matches`
  ADD PRIMARY KEY (`id`),
  ADD KEY `tournament_matches_tournament_id_foreign` (`tournament_id`),
  ADD KEY `tournament_matches_court_id_foreign` (`court_id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_phone_unique` (`phone`),
  ADD KEY `users_role_index` (`role`);

--
-- Chỉ mục cho bảng `user_memberships`
--
ALTER TABLE `user_memberships`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_memberships_user_id_foreign` (`user_id`),
  ADD KEY `user_memberships_membership_tier_id_foreign` (`membership_tier_id`);

--
-- Chỉ mục cho bảng `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_profiles_user_id_foreign` (`user_id`);

--
-- Chỉ mục cho bảng `venues`
--
ALTER TABLE `venues`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `venues_slug_unique` (`slug`),
  ADD KEY `venues_owner_id_foreign` (`owner_id`),
  ADD KEY `venues_is_active_index` (`is_active`);

--
-- Chỉ mục cho bảng `venue_amenities`
--
ALTER TABLE `venue_amenities`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `venue_amenities_venue_id_amenity_id_unique` (`venue_id`,`amenity_id`),
  ADD KEY `venue_amenities_amenity_id_foreign` (`amenity_id`);

--
-- Chỉ mục cho bảng `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `vouchers_code_unique` (`code`),
  ADD KEY `vouchers_is_active_index` (`is_active`);

--
-- Chỉ mục cho bảng `voucher_redemptions`
--
ALTER TABLE `voucher_redemptions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `voucher_redemptions_voucher_id_foreign` (`voucher_id`),
  ADD KEY `voucher_redemptions_user_id_foreign` (`user_id`),
  ADD KEY `voucher_redemptions_booking_id_foreign` (`booking_id`);

--
-- Chỉ mục cho bảng `wallet_accounts`
--
ALTER TABLE `wallet_accounts`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `wallet_accounts_user_id_unique` (`user_id`);

--
-- Chỉ mục cho bảng `wallet_ledger_entries`
--
ALTER TABLE `wallet_ledger_entries`
  ADD PRIMARY KEY (`id`),
  ADD KEY `wallet_ledger_entries_wallet_account_id_foreign` (`wallet_account_id`),
  ADD KEY `wallet_ledger_entries_reference_id_index` (`reference_id`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `amenities`
--
ALTER TABLE `amenities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `bookings`
--
ALTER TABLE `bookings`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `booking_holds`
--
ALTER TABLE `booking_holds`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `booking_items`
--
ALTER TABLE `booking_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `booking_status_histories`
--
ALTER TABLE `booking_status_histories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `check_ins`
--
ALTER TABLE `check_ins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `coaches`
--
ALTER TABLE `coaches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `courses`
--
ALTER TABLE `courses`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `course_enrollments`
--
ALTER TABLE `course_enrollments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `course_schedules`
--
ALTER TABLE `course_schedules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `courts`
--
ALTER TABLE `courts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT cho bảng `court_maintenance`
--
ALTER TABLE `court_maintenance`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `court_pricing_rules`
--
ALTER TABLE `court_pricing_rules`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `court_types`
--
ALTER TABLE `court_types`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `device_configurations`
--
ALTER TABLE `device_configurations`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `device_events`
--
ALTER TABLE `device_events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `inventory_movements`
--
ALTER TABLE `inventory_movements`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `invoices`
--
ALTER TABLE `invoices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `iot_devices`
--
ALTER TABLE `iot_devices`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `jobs`
--
ALTER TABLE `jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `loyalty_transactions`
--
ALTER TABLE `loyalty_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `match_participants`
--
ALTER TABLE `match_participants`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `match_posts`
--
ALTER TABLE `match_posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `membership_tiers`
--
ALTER TABLE `membership_tiers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT cho bảng `operating_hours`
--
ALTER TABLE `operating_hours`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22;

--
-- AUTO_INCREMENT cho bảng `payment_events`
--
ALTER TABLE `payment_events`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `payment_transactions`
--
ALTER TABLE `payment_transactions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13;

--
-- AUTO_INCREMENT cho bảng `posts`
--
ALTER TABLE `posts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `post_categories`
--
ALTER TABLE `post_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `pos_orders`
--
ALTER TABLE `pos_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `pos_order_items`
--
ALTER TABLE `pos_order_items`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT cho bảng `products`
--
ALTER TABLE `products`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10;

--
-- AUTO_INCREMENT cho bảng `product_categories`
--
ALTER TABLE `product_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `refunds`
--
ALTER TABLE `refunds`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `review_replies`
--
ALTER TABLE `review_replies`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `roles`
--
ALTER TABLE `roles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `role_user`
--
ALTER TABLE `role_user`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `service_orders`
--
ALTER TABLE `service_orders`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `staff_profiles`
--
ALTER TABLE `staff_profiles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `staff_shifts`
--
ALTER TABLE `staff_shifts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `tournaments`
--
ALTER TABLE `tournaments`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `tournament_matches`
--
ALTER TABLE `tournament_matches`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `user_memberships`
--
ALTER TABLE `user_memberships`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `user_profiles`
--
ALTER TABLE `user_profiles`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `venues`
--
ALTER TABLE `venues`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `venue_amenities`
--
ALTER TABLE `venue_amenities`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT cho bảng `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `voucher_redemptions`
--
ALTER TABLE `voucher_redemptions`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `wallet_accounts`
--
ALTER TABLE `wallet_accounts`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `wallet_ledger_entries`
--
ALTER TABLE `wallet_ledger_entries`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `bookings`
--
ALTER TABLE `bookings`
  ADD CONSTRAINT `bookings_created_by_staff_id_foreign` FOREIGN KEY (`created_by_staff_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `bookings_venue_id_foreign` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `booking_holds`
--
ALTER TABLE `booking_holds`
  ADD CONSTRAINT `booking_holds_court_id_foreign` FOREIGN KEY (`court_id`) REFERENCES `courts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_holds_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `booking_items`
--
ALTER TABLE `booking_items`
  ADD CONSTRAINT `booking_items_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_items_court_id_foreign` FOREIGN KEY (`court_id`) REFERENCES `courts` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `booking_status_histories`
--
ALTER TABLE `booking_status_histories`
  ADD CONSTRAINT `booking_status_histories_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `booking_status_histories_changed_by_user_id_foreign` FOREIGN KEY (`changed_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `check_ins`
--
ALTER TABLE `check_ins`
  ADD CONSTRAINT `check_ins_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `check_ins_court_id_foreign` FOREIGN KEY (`court_id`) REFERENCES `courts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `check_ins_verified_by_staff_id_foreign` FOREIGN KEY (`verified_by_staff_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `coaches`
--
ALTER TABLE `coaches`
  ADD CONSTRAINT `coaches_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `courses`
--
ALTER TABLE `courses`
  ADD CONSTRAINT `courses_coach_id_foreign` FOREIGN KEY (`coach_id`) REFERENCES `coaches` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `course_enrollments`
--
ALTER TABLE `course_enrollments`
  ADD CONSTRAINT `course_enrollments_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `course_enrollments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `course_schedules`
--
ALTER TABLE `course_schedules`
  ADD CONSTRAINT `course_schedules_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `course_schedules_venue_id_foreign` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `courts`
--
ALTER TABLE `courts`
  ADD CONSTRAINT `courts_court_type_id_foreign` FOREIGN KEY (`court_type_id`) REFERENCES `court_types` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `courts_venue_id_foreign` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `court_maintenance`
--
ALTER TABLE `court_maintenance`
  ADD CONSTRAINT `court_maintenance_court_id_foreign` FOREIGN KEY (`court_id`) REFERENCES `courts` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `court_pricing_rules`
--
ALTER TABLE `court_pricing_rules`
  ADD CONSTRAINT `court_pricing_rules_court_type_id_foreign` FOREIGN KEY (`court_type_id`) REFERENCES `court_types` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `court_pricing_rules_venue_id_foreign` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `device_configurations`
--
ALTER TABLE `device_configurations`
  ADD CONSTRAINT `device_configurations_iot_device_id_foreign` FOREIGN KEY (`iot_device_id`) REFERENCES `iot_devices` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `device_events`
--
ALTER TABLE `device_events`
  ADD CONSTRAINT `device_events_iot_device_id_foreign` FOREIGN KEY (`iot_device_id`) REFERENCES `iot_devices` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `inventory_movements`
--
ALTER TABLE `inventory_movements`
  ADD CONSTRAINT `inventory_movements_created_by_user_id_foreign` FOREIGN KEY (`created_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `inventory_movements_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `invoices`
--
ALTER TABLE `invoices`
  ADD CONSTRAINT `invoices_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `iot_devices`
--
ALTER TABLE `iot_devices`
  ADD CONSTRAINT `iot_devices_court_id_foreign` FOREIGN KEY (`court_id`) REFERENCES `courts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `iot_devices_venue_id_foreign` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `loyalty_transactions`
--
ALTER TABLE `loyalty_transactions`
  ADD CONSTRAINT `loyalty_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `match_participants`
--
ALTER TABLE `match_participants`
  ADD CONSTRAINT `match_participants_match_post_id_foreign` FOREIGN KEY (`match_post_id`) REFERENCES `match_posts` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `match_participants_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `match_posts`
--
ALTER TABLE `match_posts`
  ADD CONSTRAINT `match_posts_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `match_posts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `match_posts_venue_id_foreign` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `operating_hours`
--
ALTER TABLE `operating_hours`
  ADD CONSTRAINT `operating_hours_venue_id_foreign` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `payment_events`
--
ALTER TABLE `payment_events`
  ADD CONSTRAINT `payment_events_payment_transaction_id_foreign` FOREIGN KEY (`payment_transaction_id`) REFERENCES `payment_transactions` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `payment_transactions`
--
ALTER TABLE `payment_transactions`
  ADD CONSTRAINT `payment_transactions_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `payment_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `posts`
--
ALTER TABLE `posts`
  ADD CONSTRAINT `posts_post_category_id_foreign` FOREIGN KEY (`post_category_id`) REFERENCES `post_categories` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `pos_orders`
--
ALTER TABLE `pos_orders`
  ADD CONSTRAINT `pos_orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `pos_orders_staff_id_foreign` FOREIGN KEY (`staff_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pos_orders_venue_id_foreign` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `pos_order_items`
--
ALTER TABLE `pos_order_items`
  ADD CONSTRAINT `pos_order_items_pos_order_id_foreign` FOREIGN KEY (`pos_order_id`) REFERENCES `pos_orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `pos_order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `products`
--
ALTER TABLE `products`
  ADD CONSTRAINT `products_product_category_id_foreign` FOREIGN KEY (`product_category_id`) REFERENCES `product_categories` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `refunds`
--
ALTER TABLE `refunds`
  ADD CONSTRAINT `refunds_approved_by_user_id_foreign` FOREIGN KEY (`approved_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `refunds_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `refunds_payment_transaction_id_foreign` FOREIGN KEY (`payment_transaction_id`) REFERENCES `payment_transactions` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `reviews_venue_id_foreign` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `review_replies`
--
ALTER TABLE `review_replies`
  ADD CONSTRAINT `review_replies_review_id_foreign` FOREIGN KEY (`review_id`) REFERENCES `reviews` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `review_replies_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `role_user`
--
ALTER TABLE `role_user`
  ADD CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `service_orders`
--
ALTER TABLE `service_orders`
  ADD CONSTRAINT `service_orders_technician_id_foreign` FOREIGN KEY (`technician_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `service_orders_venue_id_foreign` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `staff_profiles`
--
ALTER TABLE `staff_profiles`
  ADD CONSTRAINT `staff_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `staff_profiles_venue_id_foreign` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `staff_shifts`
--
ALTER TABLE `staff_shifts`
  ADD CONSTRAINT `staff_shifts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `staff_shifts_venue_id_foreign` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `tournaments`
--
ALTER TABLE `tournaments`
  ADD CONSTRAINT `tournaments_venue_id_foreign` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `tournament_matches`
--
ALTER TABLE `tournament_matches`
  ADD CONSTRAINT `tournament_matches_court_id_foreign` FOREIGN KEY (`court_id`) REFERENCES `courts` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `tournament_matches_tournament_id_foreign` FOREIGN KEY (`tournament_id`) REFERENCES `tournaments` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `user_memberships`
--
ALTER TABLE `user_memberships`
  ADD CONSTRAINT `user_memberships_membership_tier_id_foreign` FOREIGN KEY (`membership_tier_id`) REFERENCES `membership_tiers` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `user_memberships_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `user_profiles`
--
ALTER TABLE `user_profiles`
  ADD CONSTRAINT `user_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `venues`
--
ALTER TABLE `venues`
  ADD CONSTRAINT `venues_owner_id_foreign` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Các ràng buộc cho bảng `venue_amenities`
--
ALTER TABLE `venue_amenities`
  ADD CONSTRAINT `venue_amenities_amenity_id_foreign` FOREIGN KEY (`amenity_id`) REFERENCES `amenities` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `venue_amenities_venue_id_foreign` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `voucher_redemptions`
--
ALTER TABLE `voucher_redemptions`
  ADD CONSTRAINT `voucher_redemptions_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE SET NULL,
  ADD CONSTRAINT `voucher_redemptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `voucher_redemptions_voucher_id_foreign` FOREIGN KEY (`voucher_id`) REFERENCES `vouchers` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `wallet_accounts`
--
ALTER TABLE `wallet_accounts`
  ADD CONSTRAINT `wallet_accounts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE;

--
-- Các ràng buộc cho bảng `wallet_ledger_entries`
--
ALTER TABLE `wallet_ledger_entries`
  ADD CONSTRAINT `wallet_ledger_entries_wallet_account_id_foreign` FOREIGN KEY (`wallet_account_id`) REFERENCES `wallet_accounts` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
