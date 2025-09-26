-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th9 26, 2025 lúc 02:37 PM
-- Phiên bản máy phục vụ: 10.4.32-MariaDB
-- Phiên bản PHP: 8.0.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `timekeeping_system`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `attendance`
--

CREATE TABLE `attendance` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `attendance_date` date NOT NULL,
  `shift_type_id` int(11) NOT NULL,
  `clock_in_time` timestamp NULL DEFAULT NULL,
  `clock_out_time` timestamp NULL DEFAULT NULL,
  `clock_in_location` varchar(255) DEFAULT NULL,
  `clock_out_location` varchar(255) DEFAULT NULL,
  `clock_in_ip` varchar(45) DEFAULT NULL,
  `clock_out_ip` varchar(45) DEFAULT NULL,
  `clock_in_source` enum('web','kiosk','mobile') DEFAULT 'web',
  `clock_out_source` enum('web','kiosk','mobile') DEFAULT 'web',
  `total_hours` decimal(4,2) DEFAULT 0.00,
  `overtime_hours` decimal(4,2) DEFAULT 0.00,
  `late_minutes` int(11) DEFAULT 0,
  `early_leave_minutes` int(11) DEFAULT 0,
  `status` enum('present','absent','late','early_leave','half_day') DEFAULT 'present',
  `notes` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `audit_logs`
--

CREATE TABLE `audit_logs` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `action` varchar(100) NOT NULL,
  `table_name` varchar(50) NOT NULL,
  `record_id` int(11) NOT NULL,
  `old_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`old_values`)),
  `new_values` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`new_values`)),
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `audit_logs`
--

INSERT INTO `audit_logs` (`id`, `user_id`, `action`, `table_name`, `record_id`, `old_values`, `new_values`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, NULL, 'create', 'users', 4, NULL, '{\"employee_id\":\"NV001\",\"username\":\"testuser\",\"email\":\"test@example.com\",\"first_name\":\"Test\",\"last_name\":\"User\",\"phone\":\"0123456789\",\"position_id\":\"1\",\"role_id\":\"1\",\"department_id\":\"1\",\"password_hash\":\"$2y$10$tWSmvKCZQKexgbs3R2LH..d3nH8w5r.ClsLJLxwTjSHdlrZ2lO04C\"}', '0.0.0.0', '', '2025-09-23 13:26:27');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `departments`
--

CREATE TABLE `departments` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(20) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `manager_id` int(11) DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `departments`
--

INSERT INTO `departments` (`id`, `name`, `code`, `description`, `manager_id`, `parent_id`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Phòng Nhân sự ', 'HR_UPD', 'Quản lý nhân sự và tuyển dụng ', NULL, NULL, 'active', '2025-09-23 11:10:28', '2025-09-23 11:30:03'),
(2, 'Phòng Công nghệ thông tin', 'IT', 'Phát triển và bảo trì hệ thống', 3, NULL, 'active', '2025-09-23 11:10:28', '2025-09-23 11:10:28'),
(3, 'Phòng Kế toán', 'ACC', 'Quản lý tài chính và kế toán', NULL, NULL, 'active', '2025-09-23 11:10:28', '2025-09-23 11:10:28'),
(4, 'Phòng Kinh doanh', 'SALES', 'Bán hàng và phát triển thị trường', NULL, NULL, 'active', '2025-09-23 11:10:28', '2025-09-23 11:10:28'),
(5, 'Phòng Hành chính', 'ADMIN', 'Quản lý hành chính tổng hợp', NULL, NULL, 'active', '2025-09-23 11:10:28', '2025-09-23 11:10:28');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `holidays`
--

CREATE TABLE `holidays` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `holiday_date` date NOT NULL,
  `is_recurring` tinyint(1) DEFAULT 0,
  `recurring_type` enum('yearly','monthly','weekly') DEFAULT NULL,
  `is_working_day` tinyint(1) DEFAULT 0,
  `description` text DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `holidays`
--

INSERT INTO `holidays` (`id`, `name`, `holiday_date`, `is_recurring`, `recurring_type`, `is_working_day`, `description`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Tết Dương lịch', '2024-01-01', 1, 'yearly', 0, NULL, 'active', '2025-09-23 11:10:28', '2025-09-23 11:10:28'),
(2, 'Giỗ Tổ Hùng Vương', '2024-04-18', 0, NULL, 0, NULL, 'active', '2025-09-23 11:10:28', '2025-09-23 11:10:28'),
(3, 'Ngày Giải phóng miền Nam', '2024-04-30', 1, 'yearly', 0, NULL, 'active', '2025-09-23 11:10:28', '2025-09-23 11:10:28'),
(4, 'Ngày Quốc tế Lao động', '2024-05-01', 1, 'yearly', 0, NULL, 'active', '2025-09-23 11:10:28', '2025-09-23 11:10:28'),
(5, 'Quốc khánh', '2024-09-02', 1, 'yearly', 0, NULL, 'active', '2025-09-23 11:10:28', '2025-09-23 11:10:28');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `leave_requests`
--

CREATE TABLE `leave_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `leave_type_id` int(11) NOT NULL,
  `start_date` date NOT NULL,
  `end_date` date NOT NULL,
  `total_days` decimal(4,2) NOT NULL,
  `reason` text NOT NULL,
  `status` enum('pending','approved','rejected','cancelled') DEFAULT 'pending',
  `approved_by` int(11) DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `leave_types`
--

CREATE TABLE `leave_types` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(20) DEFAULT NULL,
  `is_paid` tinyint(1) DEFAULT 1,
  `max_days_per_year` int(11) DEFAULT 0,
  `requires_approval` tinyint(1) DEFAULT 1,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `leave_types`
--

INSERT INTO `leave_types` (`id`, `name`, `code`, `is_paid`, `max_days_per_year`, `requires_approval`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Nghỉ phép năm', 'ANNUAL', 1, 12, 1, 'active', '2025-09-23 11:10:28', '2025-09-23 11:10:28'),
(2, 'Nghỉ ốm', 'SICK', 1, 30, 1, 'active', '2025-09-23 11:10:28', '2025-09-23 11:10:28'),
(3, 'Nghỉ không lương', 'UNPAID', 0, 0, 1, 'active', '2025-09-23 11:10:28', '2025-09-23 11:10:28'),
(4, 'Nghỉ thai sản', 'MATERNITY', 1, 180, 1, 'active', '2025-09-23 11:10:28', '2025-09-23 11:10:28'),
(5, 'Nghỉ cưới', 'WEDDING', 1, 3, 1, 'active', '2025-09-23 11:10:28', '2025-09-23 11:10:28'),
(6, 'Nghỉ tang', 'BEREAVEMENT', 1, 3, 1, 'active', '2025-09-23 11:10:28', '2025-09-23 11:10:28');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `overtime_requests`
--

CREATE TABLE `overtime_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `request_date` date NOT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `total_hours` decimal(4,2) NOT NULL,
  `reason` text NOT NULL,
  `status` enum('pending','approved','rejected','cancelled') DEFAULT 'pending',
  `approved_by` int(11) DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `positions`
--

CREATE TABLE `positions` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(20) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `level` int(11) DEFAULT 1,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `positions`
--

INSERT INTO `positions` (`id`, `name`, `code`, `description`, `level`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Giám đốc', 'GD', NULL, 1, 'active', '2025-09-23 13:17:34', '2025-09-23 13:17:34'),
(2, 'Phó giám đốc', 'PGD', NULL, 2, 'active', '2025-09-23 13:17:34', '2025-09-23 13:17:34'),
(3, 'Trưởng phòng', 'TP', NULL, 3, 'active', '2025-09-23 13:17:34', '2025-09-23 13:17:34'),
(4, 'Phó trưởng phòng', 'PTP', NULL, 4, 'active', '2025-09-23 13:17:34', '2025-09-23 13:17:34'),
(5, 'Trưởng nhóm', 'TN', NULL, 5, 'active', '2025-09-23 13:17:34', '2025-09-23 13:17:34'),
(6, 'Nhân viên chính', 'NVC', NULL, 6, 'active', '2025-09-23 13:17:34', '2025-09-23 13:17:34'),
(7, 'Nhân viên', 'NV', NULL, 7, 'active', '2025-09-23 13:17:34', '2025-09-23 13:17:34'),
(8, 'Thực tập sinh', 'TTS', NULL, 8, 'active', '2025-09-23 13:17:34', '2025-09-23 13:17:34');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `roles`
--

CREATE TABLE `roles` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `description` text DEFAULT NULL,
  `permissions` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL CHECK (json_valid(`permissions`)),
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `roles`
--

INSERT INTO `roles` (`id`, `name`, `description`, `permissions`, `created_at`, `updated_at`) VALUES
(1, 'admin', 'Quản trị viên hệ thống', '{\"attendance\":1,\"employee\":1,\"department\":1,\"role\":1,\"reports\":1,\"settings\":1,\"profile\":1}', '2025-09-23 11:10:28', '2025-09-23 12:09:53'),
(2, 'hr_manager', 'Quản lý nhân sự', '{\"attendance\":1,\"employee\":1,\"department\":0,\"role\":0,\"reports\":1,\"settings\":0,\"profile\":0}', '2025-09-23 11:10:28', '2025-09-23 11:48:57'),
(3, 'manager', 'Quản lý phòng ban', '{\"attendance\": true, \"reports\": true, \"approve\": true}', '2025-09-23 11:10:28', '2025-09-23 11:10:28'),
(4, 'employee', 'Nhân viên', '{\"attendance\": true, \"profile\": true}', '2025-09-23 11:10:28', '2025-09-23 11:10:28');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `schedule_details`
--

CREATE TABLE `schedule_details` (
  `id` int(11) NOT NULL,
  `schedule_id` int(11) NOT NULL,
  `day_of_week` tinyint(4) NOT NULL,
  `shift_type_id` int(11) NOT NULL,
  `is_working_day` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `shift_types`
--

CREATE TABLE `shift_types` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `code` varchar(20) DEFAULT NULL,
  `start_time` time NOT NULL,
  `end_time` time NOT NULL,
  `break_start` time DEFAULT NULL,
  `break_end` time DEFAULT NULL,
  `break_duration` int(11) DEFAULT 0,
  `late_tolerance` int(11) DEFAULT 0,
  `early_leave_tolerance` int(11) DEFAULT 0,
  `rounding_rule_in` int(11) DEFAULT 5,
  `rounding_rule_out` int(11) DEFAULT 5,
  `overtime_rate` decimal(5,2) DEFAULT 1.50,
  `standard_hours` decimal(4,2) DEFAULT 8.00,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `shift_types`
--

INSERT INTO `shift_types` (`id`, `name`, `code`, `start_time`, `end_time`, `break_start`, `break_end`, `break_duration`, `late_tolerance`, `early_leave_tolerance`, `rounding_rule_in`, `rounding_rule_out`, `overtime_rate`, `standard_hours`, `status`, `created_at`, `updated_at`) VALUES
(1, 'Ca sáng', 'MORNING', '08:00:00', '17:00:00', '12:00:00', '13:00:00', 60, 15, 15, 5, 5, 1.50, 8.00, 'active', '2025-09-23 11:10:28', '2025-09-23 11:10:28'),
(2, 'Ca chiều', 'AFTERNOON', '14:00:00', '23:00:00', '18:00:00', '19:00:00', 60, 15, 15, 5, 5, 1.50, 8.00, 'active', '2025-09-23 11:10:28', '2025-09-23 11:10:28'),
(3, 'Ca đêm', 'NIGHT', '22:00:00', '07:00:00', '02:00:00', '03:00:00', 60, 15, 15, 5, 5, 1.50, 8.00, 'active', '2025-09-23 11:10:28', '2025-09-23 11:10:28'),
(4, 'Ca hành chính', 'OFFICE', '09:00:00', '18:00:00', '12:00:00', '13:00:00', 60, 10, 10, 5, 5, 1.50, 8.00, 'active', '2025-09-23 11:10:28', '2025-09-23 11:10:28');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `system_settings`
--

CREATE TABLE `system_settings` (
  `id` int(11) NOT NULL,
  `setting_key` varchar(100) NOT NULL,
  `setting_value` text DEFAULT NULL,
  `setting_type` enum('string','number','boolean','json') DEFAULT 'string',
  `description` text DEFAULT NULL,
  `is_public` tinyint(1) DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `system_settings`
--

INSERT INTO `system_settings` (`id`, `setting_key`, `setting_value`, `setting_type`, `description`, `is_public`, `created_at`, `updated_at`) VALUES
(1, 'company_name', 'Công ty TNHH ABC', 'string', 'Tên công ty', 1, '2025-09-23 11:10:28', '2025-09-23 11:10:28'),
(2, 'timezone', 'Asia/Ho_Chi_Minh', 'string', 'Múi giờ hệ thống', 1, '2025-09-23 11:10:28', '2025-09-23 11:10:28'),
(3, 'attendance_tolerance', '15', 'number', 'Thời gian dung sai chấm công (phút)', 0, '2025-09-23 11:10:28', '2025-09-23 11:10:28'),
(4, 'max_overtime_per_day', '4', 'number', 'Số giờ OT tối đa mỗi ngày', 0, '2025-09-23 11:10:28', '2025-09-23 11:10:28'),
(5, 'auto_approve_overtime', 'false', 'boolean', 'Tự động duyệt OT', 0, '2025-09-23 11:10:28', '2025-09-23 11:10:28'),
(6, 'ip_whitelist', '[\"192.168.1.0/24\", \"10.0.0.0/8\"]', 'json', 'Danh sách IP được phép chấm công', 0, '2025-09-23 11:10:28', '2025-09-23 11:10:28');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `timesheet_requests`
--

CREATE TABLE `timesheet_requests` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `attendance_id` int(11) DEFAULT NULL,
  `request_type` enum('clock_in','clock_out','full_day','absence') NOT NULL,
  `request_date` date NOT NULL,
  `original_clock_in` time DEFAULT NULL,
  `original_clock_out` time DEFAULT NULL,
  `requested_clock_in` time DEFAULT NULL,
  `requested_clock_out` time DEFAULT NULL,
  `reason` text NOT NULL,
  `evidence_file` varchar(255) DEFAULT NULL,
  `status` enum('pending','approved','rejected') DEFAULT 'pending',
  `approved_by` int(11) DEFAULT NULL,
  `approved_at` timestamp NULL DEFAULT NULL,
  `rejection_reason` text DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `employee_id` varchar(20) DEFAULT NULL,
  `username` varchar(50) NOT NULL,
  `email` varchar(100) NOT NULL,
  `password_hash` varchar(255) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `last_name` varchar(50) NOT NULL,
  `phone` varchar(20) DEFAULT NULL,
  `avatar` varchar(255) DEFAULT NULL,
  `role_id` int(11) NOT NULL,
  `department_id` int(11) DEFAULT NULL,
  `position_id` int(11) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL,
  `hire_date` date DEFAULT NULL,
  `status` enum('active','inactive','suspended') DEFAULT 'active',
  `last_login` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `employee_id`, `username`, `email`, `password_hash`, `first_name`, `last_name`, `phone`, `avatar`, `role_id`, `department_id`, `position_id`, `position`, `hire_date`, `status`, `last_login`, `created_at`, `updated_at`) VALUES
(2, 'EMP002', 'dinhvu', 'dinhvu124@gmail.com', '$2y$10$DhXZ/C51M6T4dzeB3jZ1z.LGy0o2dXo8qUV4/sdFRk.jpzjA.1Fl2', 'Dinh', 'Vu', '0987654321', NULL, 2, 1, 1, 'Quản lý nhân sự', '2024-01-15', 'active', NULL, '2025-09-23 11:10:28', '2025-09-23 09:50:17'),
(3, 'EMP003', 'leduy', 'leduy_now2@hotmail.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Le', 'Duy', '0123456789', NULL, 3, 2, NULL, 'Quản lý phòng ban', '2024-02-01', 'active', NULL, '2025-09-23 11:10:28', '2025-09-23 11:10:28'),
(9, 'gmail.com', 'ducvan05102002@gmail.com', 'ducvan05102002@gmail.com', '$2y$10$mFlvv.HIyeuVRVHj5BVyE.eRnIZjqQqBpKyY0MXmvLDISoQPC29Qe', 'Hoàng Đức', 'Văn', '0587282880', NULL, 2, 2, 1, NULL, NULL, 'active', NULL, '2025-09-23 09:32:17', '2025-09-23 09:34:53');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `work_schedules`
--

CREATE TABLE `work_schedules` (
  `id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `description` text DEFAULT NULL,
  `schedule_type` enum('department','individual') NOT NULL,
  `department_id` int(11) DEFAULT NULL,
  `user_id` int(11) DEFAULT NULL,
  `is_template` tinyint(1) DEFAULT 0,
  `start_date` date DEFAULT NULL,
  `end_date` date DEFAULT NULL,
  `status` enum('active','inactive') DEFAULT 'active',
  `created_by` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `attendance`
--
ALTER TABLE `attendance`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_user_date` (`user_id`,`attendance_date`),
  ADD KEY `shift_type_id` (`shift_type_id`),
  ADD KEY `idx_attendance_date` (`attendance_date`),
  ADD KEY `idx_status` (`status`);

--
-- Chỉ mục cho bảng `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_user_id` (`user_id`),
  ADD KEY `idx_action` (`action`),
  ADD KEY `idx_table_name` (`table_name`),
  ADD KEY `idx_created_at` (`created_at`);

--
-- Chỉ mục cho bảng `departments`
--
ALTER TABLE `departments`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `manager_id` (`manager_id`),
  ADD KEY `parent_id` (`parent_id`),
  ADD KEY `idx_code` (`code`),
  ADD KEY `idx_status` (`status`);

--
-- Chỉ mục cho bảng `holidays`
--
ALTER TABLE `holidays`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_holiday_date` (`holiday_date`),
  ADD KEY `idx_holiday_date` (`holiday_date`),
  ADD KEY `idx_status` (`status`);

--
-- Chỉ mục cho bảng `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `leave_type_id` (`leave_type_id`),
  ADD KEY `approved_by` (`approved_by`),
  ADD KEY `idx_start_date` (`start_date`),
  ADD KEY `idx_status` (`status`);

--
-- Chỉ mục cho bảng `leave_types`
--
ALTER TABLE `leave_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `idx_code` (`code`),
  ADD KEY `idx_status` (`status`);

--
-- Chỉ mục cho bảng `overtime_requests`
--
ALTER TABLE `overtime_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `approved_by` (`approved_by`),
  ADD KEY `idx_request_date` (`request_date`),
  ADD KEY `idx_status` (`status`);

--
-- Chỉ mục cho bảng `positions`
--
ALTER TABLE `positions`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`);

--
-- Chỉ mục cho bảng `roles`
--
ALTER TABLE `roles`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `name` (`name`);

--
-- Chỉ mục cho bảng `schedule_details`
--
ALTER TABLE `schedule_details`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `unique_schedule_day` (`schedule_id`,`day_of_week`),
  ADD KEY `shift_type_id` (`shift_type_id`),
  ADD KEY `idx_day_of_week` (`day_of_week`);

--
-- Chỉ mục cho bảng `shift_types`
--
ALTER TABLE `shift_types`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `code` (`code`),
  ADD KEY `idx_code` (`code`),
  ADD KEY `idx_status` (`status`);

--
-- Chỉ mục cho bảng `system_settings`
--
ALTER TABLE `system_settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `setting_key` (`setting_key`),
  ADD KEY `idx_setting_key` (`setting_key`),
  ADD KEY `idx_is_public` (`is_public`);

--
-- Chỉ mục cho bảng `timesheet_requests`
--
ALTER TABLE `timesheet_requests`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `attendance_id` (`attendance_id`),
  ADD KEY `approved_by` (`approved_by`),
  ADD KEY `idx_request_date` (`request_date`),
  ADD KEY `idx_status` (`status`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD UNIQUE KEY `email` (`email`),
  ADD UNIQUE KEY `employee_id` (`employee_id`),
  ADD KEY `role_id` (`role_id`),
  ADD KEY `idx_employee_id` (`employee_id`),
  ADD KEY `idx_email` (`email`),
  ADD KEY `idx_status` (`status`),
  ADD KEY `department_id` (`department_id`),
  ADD KEY `position_id` (`position_id`);

--
-- Chỉ mục cho bảng `work_schedules`
--
ALTER TABLE `work_schedules`
  ADD PRIMARY KEY (`id`),
  ADD KEY `department_id` (`department_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `created_by` (`created_by`),
  ADD KEY `idx_schedule_type` (`schedule_type`),
  ADD KEY `idx_status` (`status`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `attendance`
--
ALTER TABLE `attendance`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `audit_logs`
--
ALTER TABLE `audit_logs`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `departments`
--
ALTER TABLE `departments`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `holidays`
--
ALTER TABLE `holidays`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT cho bảng `leave_requests`
--
ALTER TABLE `leave_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `leave_types`
--
ALTER TABLE `leave_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `overtime_requests`
--
ALTER TABLE `overtime_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `positions`
--
ALTER TABLE `positions`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT cho bảng `roles`
--
ALTER TABLE `roles`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `schedule_details`
--
ALTER TABLE `schedule_details`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `shift_types`
--
ALTER TABLE `shift_types`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `system_settings`
--
ALTER TABLE `system_settings`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT cho bảng `timesheet_requests`
--
ALTER TABLE `timesheet_requests`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT cho bảng `work_schedules`
--
ALTER TABLE `work_schedules`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `attendance`
--
ALTER TABLE `attendance`
  ADD CONSTRAINT `attendance_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `attendance_ibfk_2` FOREIGN KEY (`shift_type_id`) REFERENCES `shift_types` (`id`);

--
-- Các ràng buộc cho bảng `audit_logs`
--
ALTER TABLE `audit_logs`
  ADD CONSTRAINT `audit_logs_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `departments`
--
ALTER TABLE `departments`
  ADD CONSTRAINT `departments_ibfk_1` FOREIGN KEY (`manager_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `departments_ibfk_2` FOREIGN KEY (`parent_id`) REFERENCES `departments` (`id`);

--
-- Các ràng buộc cho bảng `leave_requests`
--
ALTER TABLE `leave_requests`
  ADD CONSTRAINT `leave_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `leave_requests_ibfk_2` FOREIGN KEY (`leave_type_id`) REFERENCES `leave_types` (`id`),
  ADD CONSTRAINT `leave_requests_ibfk_3` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `overtime_requests`
--
ALTER TABLE `overtime_requests`
  ADD CONSTRAINT `overtime_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `overtime_requests_ibfk_2` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `schedule_details`
--
ALTER TABLE `schedule_details`
  ADD CONSTRAINT `schedule_details_ibfk_1` FOREIGN KEY (`schedule_id`) REFERENCES `work_schedules` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `schedule_details_ibfk_2` FOREIGN KEY (`shift_type_id`) REFERENCES `shift_types` (`id`);

--
-- Các ràng buộc cho bảng `timesheet_requests`
--
ALTER TABLE `timesheet_requests`
  ADD CONSTRAINT `timesheet_requests_ibfk_1` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `timesheet_requests_ibfk_2` FOREIGN KEY (`attendance_id`) REFERENCES `attendance` (`id`),
  ADD CONSTRAINT `timesheet_requests_ibfk_3` FOREIGN KEY (`approved_by`) REFERENCES `users` (`id`);

--
-- Các ràng buộc cho bảng `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_ibfk_1` FOREIGN KEY (`role_id`) REFERENCES `roles` (`id`),
  ADD CONSTRAINT `users_ibfk_2` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`),
  ADD CONSTRAINT `users_ibfk_3` FOREIGN KEY (`position_id`) REFERENCES `positions` (`id`);

--
-- Các ràng buộc cho bảng `work_schedules`
--
ALTER TABLE `work_schedules`
  ADD CONSTRAINT `work_schedules_ibfk_1` FOREIGN KEY (`department_id`) REFERENCES `departments` (`id`),
  ADD CONSTRAINT `work_schedules_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `work_schedules_ibfk_3` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
