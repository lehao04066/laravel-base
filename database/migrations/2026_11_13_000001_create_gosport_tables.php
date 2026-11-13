<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if ($this->isLegacyImportedSchema()) {
            return;
        }

        $statements = [
            <<<'SQL'
            CREATE TABLE `amenities` (
              `id` bigint(20) UNSIGNED NOT NULL,
              `name` varchar(255) NOT NULL,
              `icon` varchar(255) NOT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL,
            <<<'SQL'
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL,
            <<<'SQL'
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL,
            <<<'SQL'
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL,
            <<<'SQL'
            CREATE TABLE `booking_status_histories` (
              `id` bigint(20) UNSIGNED NOT NULL,
              `booking_id` bigint(20) UNSIGNED NOT NULL,
              `old_status` varchar(255) DEFAULT NULL,
              `new_status` varchar(255) NOT NULL,
              `changed_by_user_id` bigint(20) UNSIGNED DEFAULT NULL,
              `reason` varchar(255) DEFAULT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL,
            <<<'SQL'
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL,
            <<<'SQL'
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL,
            <<<'SQL'
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL,
            <<<'SQL'
            CREATE TABLE `course_enrollments` (
              `id` bigint(20) UNSIGNED NOT NULL,
              `course_id` bigint(20) UNSIGNED NOT NULL,
              `user_id` bigint(20) UNSIGNED NOT NULL,
              `status` varchar(255) NOT NULL DEFAULT 'active',
              `payment_status` varchar(255) NOT NULL DEFAULT 'paid',
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL,
            <<<'SQL'
            CREATE TABLE `course_schedules` (
              `id` bigint(20) UNSIGNED NOT NULL,
              `course_id` bigint(20) UNSIGNED NOT NULL,
              `venue_id` bigint(20) UNSIGNED NOT NULL,
              `day_of_week` tinyint(3) UNSIGNED NOT NULL,
              `start_time` time NOT NULL,
              `end_time` time NOT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL,
            <<<'SQL'
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL,
            <<<'SQL'
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL,
            <<<'SQL'
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL,
            <<<'SQL'
            CREATE TABLE `court_types` (
              `id` bigint(20) UNSIGNED NOT NULL,
              `name` varchar(255) NOT NULL,
              `description` varchar(255) DEFAULT NULL,
              `is_premium` tinyint(1) NOT NULL DEFAULT 0,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL,
            <<<'SQL'
            CREATE TABLE `device_configurations` (
              `id` bigint(20) UNSIGNED NOT NULL,
              `iot_device_id` bigint(20) UNSIGNED NOT NULL,
              `config_key` varchar(255) NOT NULL,
              `config_value` text NOT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL,
            <<<'SQL'
            CREATE TABLE `device_events` (
              `id` bigint(20) UNSIGNED NOT NULL,
              `iot_device_id` bigint(20) UNSIGNED NOT NULL,
              `event_type` varchar(255) NOT NULL,
              `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payload`)),
              `created_at` timestamp NOT NULL DEFAULT current_timestamp()
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL,
            <<<'SQL'
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL,
            <<<'SQL'
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL,
            <<<'SQL'
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL,
            <<<'SQL'
            CREATE TABLE `loyalty_transactions` (
              `id` bigint(20) UNSIGNED NOT NULL,
              `user_id` bigint(20) UNSIGNED NOT NULL,
              `points_change` int(11) NOT NULL,
              `balance_after` int(11) NOT NULL,
              `reason` varchar(255) NOT NULL,
              `reference_id` varchar(255) DEFAULT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL,
            <<<'SQL'
            CREATE TABLE `match_participants` (
              `id` bigint(20) UNSIGNED NOT NULL,
              `match_post_id` bigint(20) UNSIGNED NOT NULL,
              `user_id` bigint(20) UNSIGNED NOT NULL,
              `status` varchar(255) NOT NULL DEFAULT 'joined',
              `deposit_status` varchar(255) NOT NULL DEFAULT 'unpaid',
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL,
            <<<'SQL'
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL,
            <<<'SQL'
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL,
            <<<'SQL'
            CREATE TABLE `operating_hours` (
              `id` bigint(20) UNSIGNED NOT NULL,
              `venue_id` bigint(20) UNSIGNED NOT NULL,
              `day_of_week` tinyint(3) UNSIGNED NOT NULL,
              `open_time` time NOT NULL DEFAULT '06:00:00',
              `close_time` time NOT NULL DEFAULT '23:00:00',
              `is_closed` tinyint(1) NOT NULL DEFAULT 0,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL,
            <<<'SQL'
            CREATE TABLE `payment_events` (
              `id` bigint(20) UNSIGNED NOT NULL,
              `payment_transaction_id` bigint(20) UNSIGNED NOT NULL,
              `event_type` varchar(255) NOT NULL,
              `payload` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`payload`)),
              `ip_address` varchar(45) DEFAULT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL,
            <<<'SQL'
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL,
            <<<'SQL'
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL,
            <<<'SQL'
            CREATE TABLE `post_categories` (
              `id` bigint(20) UNSIGNED NOT NULL,
              `name` varchar(255) NOT NULL,
              `slug` varchar(255) NOT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL,
            <<<'SQL'
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL,
            <<<'SQL'
            CREATE TABLE `pos_order_items` (
              `id` bigint(20) UNSIGNED NOT NULL,
              `pos_order_id` bigint(20) UNSIGNED NOT NULL,
              `product_id` bigint(20) UNSIGNED NOT NULL,
              `quantity` int(10) UNSIGNED NOT NULL DEFAULT 1,
              `unit_price` decimal(12,2) NOT NULL,
              `total_price` decimal(12,2) NOT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL,
            <<<'SQL'
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL,
            <<<'SQL'
            CREATE TABLE `product_categories` (
              `id` bigint(20) UNSIGNED NOT NULL,
              `name` varchar(255) NOT NULL,
              `slug` varchar(255) NOT NULL,
              `icon` varchar(255) DEFAULT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL,
            <<<'SQL'
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL,
            <<<'SQL'
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL,
            <<<'SQL'
            CREATE TABLE `review_replies` (
              `id` bigint(20) UNSIGNED NOT NULL,
              `review_id` bigint(20) UNSIGNED NOT NULL,
              `user_id` bigint(20) UNSIGNED NOT NULL,
              `reply_text` text NOT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL,
            <<<'SQL'
            CREATE TABLE `roles` (
              `id` bigint(20) UNSIGNED NOT NULL,
              `name` varchar(255) NOT NULL,
              `display_name` varchar(255) NOT NULL,
              `description` varchar(255) DEFAULT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL,
            <<<'SQL'
            CREATE TABLE `role_user` (
              `id` bigint(20) UNSIGNED NOT NULL,
              `user_id` bigint(20) UNSIGNED NOT NULL,
              `role_id` bigint(20) UNSIGNED NOT NULL,
              `venue_id` bigint(20) UNSIGNED DEFAULT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL,
            <<<'SQL'
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL,
            <<<'SQL'
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL,
            <<<'SQL'
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL,
            <<<'SQL'
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL,
            <<<'SQL'
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL,
            <<<'SQL'
            CREATE TABLE `user_memberships` (
              `id` bigint(20) UNSIGNED NOT NULL,
              `user_id` bigint(20) UNSIGNED NOT NULL,
              `membership_tier_id` bigint(20) UNSIGNED NOT NULL,
              `start_date` date NOT NULL,
              `end_date` date DEFAULT NULL,
              `is_active` tinyint(1) NOT NULL DEFAULT 1,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL,
            <<<'SQL'
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL,
            <<<'SQL'
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL,
            <<<'SQL'
            CREATE TABLE `venue_amenities` (
              `id` bigint(20) UNSIGNED NOT NULL,
              `venue_id` bigint(20) UNSIGNED NOT NULL,
              `amenity_id` bigint(20) UNSIGNED NOT NULL,
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL,
            <<<'SQL'
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL,
            <<<'SQL'
            CREATE TABLE `voucher_redemptions` (
              `id` bigint(20) UNSIGNED NOT NULL,
              `voucher_id` bigint(20) UNSIGNED NOT NULL,
              `user_id` bigint(20) UNSIGNED NOT NULL,
              `booking_id` bigint(20) UNSIGNED DEFAULT NULL,
              `discount_applied` decimal(12,2) NOT NULL,
              `redeemed_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL,
            <<<'SQL'
            CREATE TABLE `wallet_accounts` (
              `id` bigint(20) UNSIGNED NOT NULL,
              `user_id` bigint(20) UNSIGNED NOT NULL,
              `balance` decimal(12,2) NOT NULL DEFAULT 0.00,
              `frozen_balance` decimal(12,2) NOT NULL DEFAULT 0.00,
              `currency` varchar(10) NOT NULL DEFAULT 'VND',
              `status` varchar(255) NOT NULL DEFAULT 'active',
              `created_at` timestamp NULL DEFAULT NULL,
              `updated_at` timestamp NULL DEFAULT NULL
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL,
            <<<'SQL'
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
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
            SQL,
            <<<'SQL'
            ALTER TABLE `amenities`
              ADD PRIMARY KEY (`id`)
            SQL,
            <<<'SQL'
            ALTER TABLE `bookings`
              ADD PRIMARY KEY (`id`),
              ADD UNIQUE KEY `bookings_booking_code_unique` (`booking_code`),
              ADD UNIQUE KEY `bookings_check_in_token_unique` (`check_in_token`),
              ADD KEY `bookings_created_by_staff_id_foreign` (`created_by_staff_id`),
              ADD KEY `bookings_user_id_status_index` (`user_id`,`status`),
              ADD KEY `bookings_venue_id_booking_date_index` (`venue_id`,`booking_date`),
              ADD KEY `bookings_booking_date_index` (`booking_date`),
              ADD KEY `bookings_status_index` (`status`),
              ADD KEY `bookings_payment_status_index` (`payment_status`)
            SQL,
            <<<'SQL'
            ALTER TABLE `booking_holds`
              ADD PRIMARY KEY (`id`),
              ADD KEY `booking_holds_user_id_foreign` (`user_id`),
              ADD KEY `idx_hold_slot` (`court_id`,`booking_date`,`start_time`,`end_time`),
              ADD KEY `booking_holds_expires_at_index` (`expires_at`)
            SQL,
            <<<'SQL'
            ALTER TABLE `booking_items`
              ADD PRIMARY KEY (`id`),
              ADD KEY `booking_items_booking_id_foreign` (`booking_id`),
              ADD KEY `booking_items_court_id_start_time_end_time_index` (`court_id`,`start_time`,`end_time`)
            SQL,
            <<<'SQL'
            ALTER TABLE `booking_status_histories`
              ADD PRIMARY KEY (`id`),
              ADD KEY `booking_status_histories_booking_id_foreign` (`booking_id`),
              ADD KEY `booking_status_histories_changed_by_user_id_foreign` (`changed_by_user_id`)
            SQL,
            <<<'SQL'
            ALTER TABLE `check_ins`
              ADD PRIMARY KEY (`id`),
              ADD KEY `check_ins_court_id_foreign` (`court_id`),
              ADD KEY `check_ins_verified_by_staff_id_foreign` (`verified_by_staff_id`),
              ADD KEY `check_ins_booking_id_court_id_index` (`booking_id`,`court_id`)
            SQL,
            <<<'SQL'
            ALTER TABLE `coaches`
              ADD PRIMARY KEY (`id`),
              ADD KEY `coaches_user_id_foreign` (`user_id`)
            SQL,
            <<<'SQL'
            ALTER TABLE `courses`
              ADD PRIMARY KEY (`id`),
              ADD UNIQUE KEY `courses_slug_unique` (`slug`),
              ADD KEY `courses_coach_id_foreign` (`coach_id`)
            SQL,
            <<<'SQL'
            ALTER TABLE `course_enrollments`
              ADD PRIMARY KEY (`id`),
              ADD UNIQUE KEY `course_enrollments_course_id_user_id_unique` (`course_id`,`user_id`),
              ADD KEY `course_enrollments_user_id_foreign` (`user_id`)
            SQL,
            <<<'SQL'
            ALTER TABLE `course_schedules`
              ADD PRIMARY KEY (`id`),
              ADD KEY `course_schedules_course_id_foreign` (`course_id`),
              ADD KEY `course_schedules_venue_id_foreign` (`venue_id`)
            SQL,
            <<<'SQL'
            ALTER TABLE `courts`
              ADD PRIMARY KEY (`id`),
              ADD UNIQUE KEY `courts_venue_id_court_number_unique` (`venue_id`,`court_number`),
              ADD KEY `courts_court_type_id_foreign` (`court_type_id`),
              ADD KEY `courts_is_active_index` (`is_active`)
            SQL,
            <<<'SQL'
            ALTER TABLE `court_maintenance`
              ADD PRIMARY KEY (`id`),
              ADD KEY `court_maintenance_court_id_start_datetime_end_datetime_index` (`court_id`,`start_datetime`,`end_datetime`)
            SQL,
            <<<'SQL'
            ALTER TABLE `court_pricing_rules`
              ADD PRIMARY KEY (`id`),
              ADD KEY `court_pricing_rules_court_type_id_foreign` (`court_type_id`),
              ADD KEY `court_pricing_rules_venue_id_start_time_end_time_index` (`venue_id`,`start_time`,`end_time`)
            SQL,
            <<<'SQL'
            ALTER TABLE `court_types`
              ADD PRIMARY KEY (`id`)
            SQL,
            <<<'SQL'
            ALTER TABLE `device_configurations`
              ADD PRIMARY KEY (`id`),
              ADD UNIQUE KEY `device_configurations_iot_device_id_config_key_unique` (`iot_device_id`,`config_key`)
            SQL,
            <<<'SQL'
            ALTER TABLE `device_events`
              ADD PRIMARY KEY (`id`),
              ADD KEY `device_events_iot_device_id_foreign` (`iot_device_id`)
            SQL,
            <<<'SQL'
            ALTER TABLE `inventory_movements`
              ADD PRIMARY KEY (`id`),
              ADD KEY `inventory_movements_product_id_foreign` (`product_id`),
              ADD KEY `inventory_movements_created_by_user_id_foreign` (`created_by_user_id`)
            SQL,
            <<<'SQL'
            ALTER TABLE `invoices`
              ADD PRIMARY KEY (`id`),
              ADD UNIQUE KEY `invoices_invoice_code_unique` (`invoice_code`),
              ADD KEY `invoices_booking_id_foreign` (`booking_id`),
              ADD KEY `invoices_pos_order_id_index` (`pos_order_id`)
            SQL,
            <<<'SQL'
            ALTER TABLE `iot_devices`
              ADD PRIMARY KEY (`id`),
              ADD UNIQUE KEY `iot_devices_serial_number_unique` (`serial_number`),
              ADD KEY `iot_devices_venue_id_foreign` (`venue_id`),
              ADD KEY `iot_devices_court_id_foreign` (`court_id`),
              ADD KEY `iot_devices_status_index` (`status`)
            SQL,
            <<<'SQL'
            ALTER TABLE `loyalty_transactions`
              ADD PRIMARY KEY (`id`),
              ADD KEY `loyalty_transactions_user_id_foreign` (`user_id`)
            SQL,
            <<<'SQL'
            ALTER TABLE `match_participants`
              ADD PRIMARY KEY (`id`),
              ADD UNIQUE KEY `match_participants_match_post_id_user_id_unique` (`match_post_id`,`user_id`),
              ADD KEY `match_participants_user_id_foreign` (`user_id`)
            SQL,
            <<<'SQL'
            ALTER TABLE `match_posts`
              ADD PRIMARY KEY (`id`),
              ADD KEY `match_posts_user_id_foreign` (`user_id`),
              ADD KEY `match_posts_booking_id_foreign` (`booking_id`),
              ADD KEY `match_posts_venue_id_play_datetime_index` (`venue_id`,`play_datetime`),
              ADD KEY `match_posts_status_index` (`status`)
            SQL,
            <<<'SQL'
            ALTER TABLE `membership_tiers`
              ADD PRIMARY KEY (`id`),
              ADD UNIQUE KEY `membership_tiers_slug_unique` (`slug`)
            SQL,
            <<<'SQL'
            ALTER TABLE `operating_hours`
              ADD PRIMARY KEY (`id`),
              ADD KEY `operating_hours_venue_id_foreign` (`venue_id`)
            SQL,
            <<<'SQL'
            ALTER TABLE `payment_events`
              ADD PRIMARY KEY (`id`),
              ADD KEY `payment_events_payment_transaction_id_foreign` (`payment_transaction_id`)
            SQL,
            <<<'SQL'
            ALTER TABLE `payment_transactions`
              ADD PRIMARY KEY (`id`),
              ADD UNIQUE KEY `payment_transactions_transaction_code_unique` (`transaction_code`),
              ADD KEY `payment_transactions_user_id_foreign` (`user_id`),
              ADD KEY `payment_transactions_booking_id_status_index` (`booking_id`,`status`),
              ADD KEY `payment_transactions_status_index` (`status`),
              ADD KEY `payment_transactions_gateway_transaction_id_index` (`gateway_transaction_id`)
            SQL,
            <<<'SQL'
            ALTER TABLE `posts`
              ADD PRIMARY KEY (`id`),
              ADD UNIQUE KEY `posts_slug_unique` (`slug`),
              ADD KEY `posts_post_category_id_foreign` (`post_category_id`),
              ADD KEY `posts_is_published_index` (`is_published`)
            SQL,
            <<<'SQL'
            ALTER TABLE `post_categories`
              ADD PRIMARY KEY (`id`),
              ADD UNIQUE KEY `post_categories_slug_unique` (`slug`)
            SQL,
            <<<'SQL'
            ALTER TABLE `pos_orders`
              ADD PRIMARY KEY (`id`),
              ADD UNIQUE KEY `pos_orders_order_code_unique` (`order_code`),
              ADD KEY `pos_orders_venue_id_foreign` (`venue_id`),
              ADD KEY `pos_orders_staff_id_foreign` (`staff_id`),
              ADD KEY `pos_orders_customer_id_foreign` (`customer_id`)
            SQL,
            <<<'SQL'
            ALTER TABLE `pos_order_items`
              ADD PRIMARY KEY (`id`),
              ADD KEY `pos_order_items_pos_order_id_foreign` (`pos_order_id`),
              ADD KEY `pos_order_items_product_id_foreign` (`product_id`)
            SQL,
            <<<'SQL'
            ALTER TABLE `products`
              ADD PRIMARY KEY (`id`),
              ADD UNIQUE KEY `products_sku_unique` (`sku`),
              ADD UNIQUE KEY `products_barcode_unique` (`barcode`),
              ADD KEY `products_product_category_id_foreign` (`product_category_id`),
              ADD KEY `products_is_active_index` (`is_active`)
            SQL,
            <<<'SQL'
            ALTER TABLE `product_categories`
              ADD PRIMARY KEY (`id`),
              ADD UNIQUE KEY `product_categories_slug_unique` (`slug`)
            SQL,
            <<<'SQL'
            ALTER TABLE `refunds`
              ADD PRIMARY KEY (`id`),
              ADD KEY `refunds_booking_id_foreign` (`booking_id`),
              ADD KEY `refunds_payment_transaction_id_foreign` (`payment_transaction_id`),
              ADD KEY `refunds_approved_by_user_id_foreign` (`approved_by_user_id`)
            SQL,
            <<<'SQL'
            ALTER TABLE `reviews`
              ADD PRIMARY KEY (`id`),
              ADD KEY `reviews_venue_id_foreign` (`venue_id`),
              ADD KEY `reviews_user_id_foreign` (`user_id`),
              ADD KEY `reviews_booking_id_foreign` (`booking_id`),
              ADD KEY `reviews_is_approved_index` (`is_approved`)
            SQL,
            <<<'SQL'
            ALTER TABLE `review_replies`
              ADD PRIMARY KEY (`id`),
              ADD KEY `review_replies_review_id_foreign` (`review_id`),
              ADD KEY `review_replies_user_id_foreign` (`user_id`)
            SQL,
            <<<'SQL'
            ALTER TABLE `roles`
              ADD PRIMARY KEY (`id`),
              ADD UNIQUE KEY `roles_name_unique` (`name`)
            SQL,
            <<<'SQL'
            ALTER TABLE `role_user`
              ADD PRIMARY KEY (`id`),
              ADD UNIQUE KEY `role_user_user_id_role_id_venue_id_unique` (`user_id`,`role_id`,`venue_id`),
              ADD KEY `role_user_role_id_foreign` (`role_id`),
              ADD KEY `role_user_venue_id_index` (`venue_id`)
            SQL,
            <<<'SQL'
            ALTER TABLE `service_orders`
              ADD PRIMARY KEY (`id`),
              ADD UNIQUE KEY `service_orders_order_code_unique` (`order_code`),
              ADD KEY `service_orders_venue_id_foreign` (`venue_id`),
              ADD KEY `service_orders_technician_id_foreign` (`technician_id`)
            SQL,
            <<<'SQL'
            ALTER TABLE `staff_profiles`
              ADD PRIMARY KEY (`id`),
              ADD UNIQUE KEY `staff_profiles_user_id_unique` (`user_id`),
              ADD UNIQUE KEY `staff_profiles_employee_code_unique` (`employee_code`),
              ADD KEY `staff_profiles_venue_id_foreign` (`venue_id`)
            SQL,
            <<<'SQL'
            ALTER TABLE `staff_shifts`
              ADD PRIMARY KEY (`id`),
              ADD KEY `staff_shifts_user_id_foreign` (`user_id`),
              ADD KEY `staff_shifts_venue_id_shift_date_index` (`venue_id`,`shift_date`)
            SQL,
            <<<'SQL'
            ALTER TABLE `tournaments`
              ADD PRIMARY KEY (`id`),
              ADD UNIQUE KEY `tournaments_slug_unique` (`slug`),
              ADD KEY `tournaments_venue_id_foreign` (`venue_id`)
            SQL,
            <<<'SQL'
            ALTER TABLE `tournament_matches`
              ADD PRIMARY KEY (`id`),
              ADD KEY `tournament_matches_tournament_id_foreign` (`tournament_id`),
              ADD KEY `tournament_matches_court_id_foreign` (`court_id`)
            SQL,
            <<<'SQL'
            ALTER TABLE `user_memberships`
              ADD PRIMARY KEY (`id`),
              ADD KEY `user_memberships_user_id_foreign` (`user_id`),
              ADD KEY `user_memberships_membership_tier_id_foreign` (`membership_tier_id`)
            SQL,
            <<<'SQL'
            ALTER TABLE `user_profiles`
              ADD PRIMARY KEY (`id`),
              ADD KEY `user_profiles_user_id_foreign` (`user_id`)
            SQL,
            <<<'SQL'
            ALTER TABLE `venues`
              ADD PRIMARY KEY (`id`),
              ADD UNIQUE KEY `venues_slug_unique` (`slug`),
              ADD KEY `venues_owner_id_foreign` (`owner_id`),
              ADD KEY `venues_is_active_index` (`is_active`)
            SQL,
            <<<'SQL'
            ALTER TABLE `venue_amenities`
              ADD PRIMARY KEY (`id`),
              ADD UNIQUE KEY `venue_amenities_venue_id_amenity_id_unique` (`venue_id`,`amenity_id`),
              ADD KEY `venue_amenities_amenity_id_foreign` (`amenity_id`)
            SQL,
            <<<'SQL'
            ALTER TABLE `vouchers`
              ADD PRIMARY KEY (`id`),
              ADD UNIQUE KEY `vouchers_code_unique` (`code`),
              ADD KEY `vouchers_is_active_index` (`is_active`)
            SQL,
            <<<'SQL'
            ALTER TABLE `voucher_redemptions`
              ADD PRIMARY KEY (`id`),
              ADD KEY `voucher_redemptions_voucher_id_foreign` (`voucher_id`),
              ADD KEY `voucher_redemptions_user_id_foreign` (`user_id`),
              ADD KEY `voucher_redemptions_booking_id_foreign` (`booking_id`)
            SQL,
            <<<'SQL'
            ALTER TABLE `wallet_accounts`
              ADD PRIMARY KEY (`id`),
              ADD UNIQUE KEY `wallet_accounts_user_id_unique` (`user_id`)
            SQL,
            <<<'SQL'
            ALTER TABLE `wallet_ledger_entries`
              ADD PRIMARY KEY (`id`),
              ADD KEY `wallet_ledger_entries_wallet_account_id_foreign` (`wallet_account_id`),
              ADD KEY `wallet_ledger_entries_reference_id_index` (`reference_id`)
            SQL,
            <<<'SQL'
            ALTER TABLE `amenities`
              MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9
            SQL,
            <<<'SQL'
            ALTER TABLE `bookings`
              MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3
            SQL,
            <<<'SQL'
            ALTER TABLE `booking_holds`
              MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT
            SQL,
            <<<'SQL'
            ALTER TABLE `booking_items`
              MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3
            SQL,
            <<<'SQL'
            ALTER TABLE `booking_status_histories`
              MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3
            SQL,
            <<<'SQL'
            ALTER TABLE `check_ins`
              MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2
            SQL,
            <<<'SQL'
            ALTER TABLE `coaches`
              MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2
            SQL,
            <<<'SQL'
            ALTER TABLE `courses`
              MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2
            SQL,
            <<<'SQL'
            ALTER TABLE `course_enrollments`
              MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT
            SQL,
            <<<'SQL'
            ALTER TABLE `course_schedules`
              MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2
            SQL,
            <<<'SQL'
            ALTER TABLE `courts`
              MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21
            SQL,
            <<<'SQL'
            ALTER TABLE `court_maintenance`
              MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT
            SQL,
            <<<'SQL'
            ALTER TABLE `court_pricing_rules`
              MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13
            SQL,
            <<<'SQL'
            ALTER TABLE `court_types`
              MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3
            SQL,
            <<<'SQL'
            ALTER TABLE `device_configurations`
              MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT
            SQL,
            <<<'SQL'
            ALTER TABLE `device_events`
              MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT
            SQL,
            <<<'SQL'
            ALTER TABLE `inventory_movements`
              MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT
            SQL,
            <<<'SQL'
            ALTER TABLE `invoices`
              MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT
            SQL,
            <<<'SQL'
            ALTER TABLE `iot_devices`
              MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4
            SQL,
            <<<'SQL'
            ALTER TABLE `loyalty_transactions`
              MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT
            SQL,
            <<<'SQL'
            ALTER TABLE `match_participants`
              MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT
            SQL,
            <<<'SQL'
            ALTER TABLE `match_posts`
              MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2
            SQL,
            <<<'SQL'
            ALTER TABLE `membership_tiers`
              MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4
            SQL,
            <<<'SQL'
            ALTER TABLE `operating_hours`
              MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=22
            SQL,
            <<<'SQL'
            ALTER TABLE `payment_events`
              MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT
            SQL,
            <<<'SQL'
            ALTER TABLE `payment_transactions`
              MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=13
            SQL,
            <<<'SQL'
            ALTER TABLE `posts`
              MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3
            SQL,
            <<<'SQL'
            ALTER TABLE `post_categories`
              MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3
            SQL,
            <<<'SQL'
            ALTER TABLE `pos_orders`
              MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12
            SQL,
            <<<'SQL'
            ALTER TABLE `pos_order_items`
              MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12
            SQL,
            <<<'SQL'
            ALTER TABLE `products`
              MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=10
            SQL,
            <<<'SQL'
            ALTER TABLE `product_categories`
              MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6
            SQL,
            <<<'SQL'
            ALTER TABLE `refunds`
              MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT
            SQL,
            <<<'SQL'
            ALTER TABLE `reviews`
              MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2
            SQL,
            <<<'SQL'
            ALTER TABLE `review_replies`
              MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT
            SQL,
            <<<'SQL'
            ALTER TABLE `roles`
              MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7
            SQL,
            <<<'SQL'
            ALTER TABLE `role_user`
              MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT
            SQL,
            <<<'SQL'
            ALTER TABLE `service_orders`
              MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT
            SQL,
            <<<'SQL'
            ALTER TABLE `staff_profiles`
              MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2
            SQL,
            <<<'SQL'
            ALTER TABLE `staff_shifts`
              MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2
            SQL,
            <<<'SQL'
            ALTER TABLE `tournaments`
              MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2
            SQL,
            <<<'SQL'
            ALTER TABLE `tournament_matches`
              MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2
            SQL,
            <<<'SQL'
            ALTER TABLE `user_memberships`
              MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2
            SQL,
            <<<'SQL'
            ALTER TABLE `user_profiles`
              MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3
            SQL,
            <<<'SQL'
            ALTER TABLE `venues`
              MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4
            SQL,
            <<<'SQL'
            ALTER TABLE `venue_amenities`
              MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18
            SQL,
            <<<'SQL'
            ALTER TABLE `vouchers`
              MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3
            SQL,
            <<<'SQL'
            ALTER TABLE `voucher_redemptions`
              MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT
            SQL,
            <<<'SQL'
            ALTER TABLE `wallet_accounts`
              MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3
            SQL,
            <<<'SQL'
            ALTER TABLE `wallet_ledger_entries`
              MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2
            SQL,
            <<<'SQL'
            ALTER TABLE `bookings`
              ADD CONSTRAINT `bookings_created_by_staff_id_foreign` FOREIGN KEY (`created_by_staff_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
              ADD CONSTRAINT `bookings_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
              ADD CONSTRAINT `bookings_venue_id_foreign` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE
            SQL,
            <<<'SQL'
            ALTER TABLE `booking_holds`
              ADD CONSTRAINT `booking_holds_court_id_foreign` FOREIGN KEY (`court_id`) REFERENCES `courts` (`id`) ON DELETE CASCADE,
              ADD CONSTRAINT `booking_holds_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
            SQL,
            <<<'SQL'
            ALTER TABLE `booking_items`
              ADD CONSTRAINT `booking_items_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
              ADD CONSTRAINT `booking_items_court_id_foreign` FOREIGN KEY (`court_id`) REFERENCES `courts` (`id`) ON DELETE CASCADE
            SQL,
            <<<'SQL'
            ALTER TABLE `booking_status_histories`
              ADD CONSTRAINT `booking_status_histories_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
              ADD CONSTRAINT `booking_status_histories_changed_by_user_id_foreign` FOREIGN KEY (`changed_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
            SQL,
            <<<'SQL'
            ALTER TABLE `check_ins`
              ADD CONSTRAINT `check_ins_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
              ADD CONSTRAINT `check_ins_court_id_foreign` FOREIGN KEY (`court_id`) REFERENCES `courts` (`id`) ON DELETE CASCADE,
              ADD CONSTRAINT `check_ins_verified_by_staff_id_foreign` FOREIGN KEY (`verified_by_staff_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
            SQL,
            <<<'SQL'
            ALTER TABLE `coaches`
              ADD CONSTRAINT `coaches_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
            SQL,
            <<<'SQL'
            ALTER TABLE `courses`
              ADD CONSTRAINT `courses_coach_id_foreign` FOREIGN KEY (`coach_id`) REFERENCES `coaches` (`id`) ON DELETE CASCADE
            SQL,
            <<<'SQL'
            ALTER TABLE `course_enrollments`
              ADD CONSTRAINT `course_enrollments_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
              ADD CONSTRAINT `course_enrollments_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
            SQL,
            <<<'SQL'
            ALTER TABLE `course_schedules`
              ADD CONSTRAINT `course_schedules_course_id_foreign` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
              ADD CONSTRAINT `course_schedules_venue_id_foreign` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE
            SQL,
            <<<'SQL'
            ALTER TABLE `courts`
              ADD CONSTRAINT `courts_court_type_id_foreign` FOREIGN KEY (`court_type_id`) REFERENCES `court_types` (`id`) ON DELETE SET NULL,
              ADD CONSTRAINT `courts_venue_id_foreign` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE
            SQL,
            <<<'SQL'
            ALTER TABLE `court_maintenance`
              ADD CONSTRAINT `court_maintenance_court_id_foreign` FOREIGN KEY (`court_id`) REFERENCES `courts` (`id`) ON DELETE CASCADE
            SQL,
            <<<'SQL'
            ALTER TABLE `court_pricing_rules`
              ADD CONSTRAINT `court_pricing_rules_court_type_id_foreign` FOREIGN KEY (`court_type_id`) REFERENCES `court_types` (`id`) ON DELETE SET NULL,
              ADD CONSTRAINT `court_pricing_rules_venue_id_foreign` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE
            SQL,
            <<<'SQL'
            ALTER TABLE `device_configurations`
              ADD CONSTRAINT `device_configurations_iot_device_id_foreign` FOREIGN KEY (`iot_device_id`) REFERENCES `iot_devices` (`id`) ON DELETE CASCADE
            SQL,
            <<<'SQL'
            ALTER TABLE `device_events`
              ADD CONSTRAINT `device_events_iot_device_id_foreign` FOREIGN KEY (`iot_device_id`) REFERENCES `iot_devices` (`id`) ON DELETE CASCADE
            SQL,
            <<<'SQL'
            ALTER TABLE `inventory_movements`
              ADD CONSTRAINT `inventory_movements_created_by_user_id_foreign` FOREIGN KEY (`created_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
              ADD CONSTRAINT `inventory_movements_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
            SQL,
            <<<'SQL'
            ALTER TABLE `invoices`
              ADD CONSTRAINT `invoices_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE SET NULL
            SQL,
            <<<'SQL'
            ALTER TABLE `iot_devices`
              ADD CONSTRAINT `iot_devices_court_id_foreign` FOREIGN KEY (`court_id`) REFERENCES `courts` (`id`) ON DELETE SET NULL,
              ADD CONSTRAINT `iot_devices_venue_id_foreign` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE
            SQL,
            <<<'SQL'
            ALTER TABLE `loyalty_transactions`
              ADD CONSTRAINT `loyalty_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
            SQL,
            <<<'SQL'
            ALTER TABLE `match_participants`
              ADD CONSTRAINT `match_participants_match_post_id_foreign` FOREIGN KEY (`match_post_id`) REFERENCES `match_posts` (`id`) ON DELETE CASCADE,
              ADD CONSTRAINT `match_participants_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
            SQL,
            <<<'SQL'
            ALTER TABLE `match_posts`
              ADD CONSTRAINT `match_posts_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE SET NULL,
              ADD CONSTRAINT `match_posts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
              ADD CONSTRAINT `match_posts_venue_id_foreign` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE
            SQL,
            <<<'SQL'
            ALTER TABLE `operating_hours`
              ADD CONSTRAINT `operating_hours_venue_id_foreign` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE
            SQL,
            <<<'SQL'
            ALTER TABLE `payment_events`
              ADD CONSTRAINT `payment_events_payment_transaction_id_foreign` FOREIGN KEY (`payment_transaction_id`) REFERENCES `payment_transactions` (`id`) ON DELETE CASCADE
            SQL,
            <<<'SQL'
            ALTER TABLE `payment_transactions`
              ADD CONSTRAINT `payment_transactions_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE SET NULL,
              ADD CONSTRAINT `payment_transactions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
            SQL,
            <<<'SQL'
            ALTER TABLE `posts`
              ADD CONSTRAINT `posts_post_category_id_foreign` FOREIGN KEY (`post_category_id`) REFERENCES `post_categories` (`id`) ON DELETE SET NULL
            SQL,
            <<<'SQL'
            ALTER TABLE `pos_orders`
              ADD CONSTRAINT `pos_orders_customer_id_foreign` FOREIGN KEY (`customer_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
              ADD CONSTRAINT `pos_orders_staff_id_foreign` FOREIGN KEY (`staff_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
              ADD CONSTRAINT `pos_orders_venue_id_foreign` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE
            SQL,
            <<<'SQL'
            ALTER TABLE `pos_order_items`
              ADD CONSTRAINT `pos_order_items_pos_order_id_foreign` FOREIGN KEY (`pos_order_id`) REFERENCES `pos_orders` (`id`) ON DELETE CASCADE,
              ADD CONSTRAINT `pos_order_items_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
            SQL,
            <<<'SQL'
            ALTER TABLE `products`
              ADD CONSTRAINT `products_product_category_id_foreign` FOREIGN KEY (`product_category_id`) REFERENCES `product_categories` (`id`) ON DELETE SET NULL
            SQL,
            <<<'SQL'
            ALTER TABLE `refunds`
              ADD CONSTRAINT `refunds_approved_by_user_id_foreign` FOREIGN KEY (`approved_by_user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
              ADD CONSTRAINT `refunds_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE CASCADE,
              ADD CONSTRAINT `refunds_payment_transaction_id_foreign` FOREIGN KEY (`payment_transaction_id`) REFERENCES `payment_transactions` (`id`) ON DELETE SET NULL
            SQL,
            <<<'SQL'
            ALTER TABLE `reviews`
              ADD CONSTRAINT `reviews_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE SET NULL,
              ADD CONSTRAINT `reviews_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
              ADD CONSTRAINT `reviews_venue_id_foreign` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE
            SQL,
            <<<'SQL'
            ALTER TABLE `review_replies`
              ADD CONSTRAINT `review_replies_review_id_foreign` FOREIGN KEY (`review_id`) REFERENCES `reviews` (`id`) ON DELETE CASCADE,
              ADD CONSTRAINT `review_replies_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
            SQL,
            <<<'SQL'
            ALTER TABLE `role_user`
              ADD CONSTRAINT `role_user_role_id_foreign` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`) ON DELETE CASCADE,
              ADD CONSTRAINT `role_user_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
            SQL,
            <<<'SQL'
            ALTER TABLE `service_orders`
              ADD CONSTRAINT `service_orders_technician_id_foreign` FOREIGN KEY (`technician_id`) REFERENCES `users` (`id`) ON DELETE SET NULL,
              ADD CONSTRAINT `service_orders_venue_id_foreign` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE
            SQL,
            <<<'SQL'
            ALTER TABLE `staff_profiles`
              ADD CONSTRAINT `staff_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
              ADD CONSTRAINT `staff_profiles_venue_id_foreign` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE SET NULL
            SQL,
            <<<'SQL'
            ALTER TABLE `staff_shifts`
              ADD CONSTRAINT `staff_shifts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
              ADD CONSTRAINT `staff_shifts_venue_id_foreign` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE
            SQL,
            <<<'SQL'
            ALTER TABLE `tournaments`
              ADD CONSTRAINT `tournaments_venue_id_foreign` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE
            SQL,
            <<<'SQL'
            ALTER TABLE `tournament_matches`
              ADD CONSTRAINT `tournament_matches_court_id_foreign` FOREIGN KEY (`court_id`) REFERENCES `courts` (`id`) ON DELETE SET NULL,
              ADD CONSTRAINT `tournament_matches_tournament_id_foreign` FOREIGN KEY (`tournament_id`) REFERENCES `tournaments` (`id`) ON DELETE CASCADE
            SQL,
            <<<'SQL'
            ALTER TABLE `user_memberships`
              ADD CONSTRAINT `user_memberships_membership_tier_id_foreign` FOREIGN KEY (`membership_tier_id`) REFERENCES `membership_tiers` (`id`) ON DELETE CASCADE,
              ADD CONSTRAINT `user_memberships_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
            SQL,
            <<<'SQL'
            ALTER TABLE `user_profiles`
              ADD CONSTRAINT `user_profiles_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
            SQL,
            <<<'SQL'
            ALTER TABLE `venues`
              ADD CONSTRAINT `venues_owner_id_foreign` FOREIGN KEY (`owner_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
            SQL,
            <<<'SQL'
            ALTER TABLE `venue_amenities`
              ADD CONSTRAINT `venue_amenities_amenity_id_foreign` FOREIGN KEY (`amenity_id`) REFERENCES `amenities` (`id`) ON DELETE CASCADE,
              ADD CONSTRAINT `venue_amenities_venue_id_foreign` FOREIGN KEY (`venue_id`) REFERENCES `venues` (`id`) ON DELETE CASCADE
            SQL,
            <<<'SQL'
            ALTER TABLE `voucher_redemptions`
              ADD CONSTRAINT `voucher_redemptions_booking_id_foreign` FOREIGN KEY (`booking_id`) REFERENCES `bookings` (`id`) ON DELETE SET NULL,
              ADD CONSTRAINT `voucher_redemptions_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
              ADD CONSTRAINT `voucher_redemptions_voucher_id_foreign` FOREIGN KEY (`voucher_id`) REFERENCES `vouchers` (`id`) ON DELETE CASCADE
            SQL,
            <<<'SQL'
            ALTER TABLE `wallet_accounts`
              ADD CONSTRAINT `wallet_accounts_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
            SQL,
            <<<'SQL'
            ALTER TABLE `wallet_ledger_entries`
              ADD CONSTRAINT `wallet_ledger_entries_wallet_account_id_foreign` FOREIGN KEY (`wallet_account_id`) REFERENCES `wallet_accounts` (`id`) ON DELETE CASCADE
            SQL,
        ];

        foreach ($statements as $statement) {
            DB::unprepared($statement);
        }
    }

    public function down(): void
    {
        if ($this->isLegacyImportedSchema()) {
            return;
        }

        Schema::disableForeignKeyConstraints();

        try {
            Schema::dropIfExists('wallet_ledger_entries');
            Schema::dropIfExists('wallet_accounts');
            Schema::dropIfExists('voucher_redemptions');
            Schema::dropIfExists('vouchers');
            Schema::dropIfExists('venue_amenities');
            Schema::dropIfExists('venues');
            Schema::dropIfExists('user_profiles');
            Schema::dropIfExists('user_memberships');
            Schema::dropIfExists('tournament_matches');
            Schema::dropIfExists('tournaments');
            Schema::dropIfExists('staff_shifts');
            Schema::dropIfExists('staff_profiles');
            Schema::dropIfExists('service_orders');
            Schema::dropIfExists('role_user');
            Schema::dropIfExists('roles');
            Schema::dropIfExists('review_replies');
            Schema::dropIfExists('reviews');
            Schema::dropIfExists('refunds');
            Schema::dropIfExists('product_categories');
            Schema::dropIfExists('products');
            Schema::dropIfExists('pos_order_items');
            Schema::dropIfExists('pos_orders');
            Schema::dropIfExists('post_categories');
            Schema::dropIfExists('posts');
            Schema::dropIfExists('payment_transactions');
            Schema::dropIfExists('payment_events');
            Schema::dropIfExists('operating_hours');
            Schema::dropIfExists('membership_tiers');
            Schema::dropIfExists('match_posts');
            Schema::dropIfExists('match_participants');
            Schema::dropIfExists('loyalty_transactions');
            Schema::dropIfExists('iot_devices');
            Schema::dropIfExists('invoices');
            Schema::dropIfExists('inventory_movements');
            Schema::dropIfExists('device_events');
            Schema::dropIfExists('device_configurations');
            Schema::dropIfExists('court_types');
            Schema::dropIfExists('court_pricing_rules');
            Schema::dropIfExists('court_maintenance');
            Schema::dropIfExists('courts');
            Schema::dropIfExists('course_schedules');
            Schema::dropIfExists('course_enrollments');
            Schema::dropIfExists('courses');
            Schema::dropIfExists('coaches');
            Schema::dropIfExists('check_ins');
            Schema::dropIfExists('booking_status_histories');
            Schema::dropIfExists('booking_items');
            Schema::dropIfExists('booking_holds');
            Schema::dropIfExists('bookings');
            Schema::dropIfExists('amenities');
        } finally {
            Schema::enableForeignKeyConstraints();
        }
    }

    private function isLegacyImportedSchema(): bool
    {
        return DB::table('migrations')
            ->where('migration', '2026_01_01_000001_update_users_and_create_roles_table')
            ->exists();
    }
};
