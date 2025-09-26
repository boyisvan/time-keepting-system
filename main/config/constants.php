<?php
/**
 * Các hằng số hệ thống
 */

// Vai trò người dùng
define('ROLE_ADMIN', 'admin');
define('ROLE_HR_MANAGER', 'hr_manager');
define('ROLE_MANAGER', 'manager');
define('ROLE_EMPLOYEE', 'employee');

// Trạng thái người dùng
define('USER_STATUS_ACTIVE', 'active');
define('USER_STATUS_INACTIVE', 'inactive');
define('USER_STATUS_SUSPENDED', 'suspended');

// Trạng thái chấm công
define('ATTENDANCE_PRESENT', 'present');
define('ATTENDANCE_ABSENT', 'absent');
define('ATTENDANCE_LATE', 'late');
define('ATTENDANCE_EARLY_LEAVE', 'early_leave');
define('ATTENDANCE_HALF_DAY', 'half_day');

// Nguồn chấm công
define('CLOCK_SOURCE_WEB', 'web');
define('CLOCK_SOURCE_KIOSK', 'kiosk');
define('CLOCK_SOURCE_MOBILE', 'mobile');

// Trạng thái yêu cầu
define('REQUEST_STATUS_PENDING', 'pending');
define('REQUEST_STATUS_APPROVED', 'approved');
define('REQUEST_STATUS_REJECTED', 'rejected');
define('REQUEST_STATUS_CANCELLED', 'cancelled');

// Loại yêu cầu chỉnh sửa công
define('TIMESHEET_REQUEST_CLOCK_IN', 'clock_in');
define('TIMESHEET_REQUEST_CLOCK_OUT', 'clock_out');
define('TIMESHEET_REQUEST_FULL_DAY', 'full_day');
define('TIMESHEET_REQUEST_ABSENCE', 'absence');

// Loại lịch làm việc
define('SCHEDULE_TYPE_DEPARTMENT', 'department');
define('SCHEDULE_TYPE_INDIVIDUAL', 'individual');

// Trạng thái chung
define('STATUS_ACTIVE', 'active');
define('STATUS_INACTIVE', 'inactive');

// Thông báo
define('MSG_SUCCESS', 'success');
define('MSG_ERROR', 'error');
define('MSG_WARNING', 'warning');
define('MSG_INFO', 'info');

// Phân quyền
define('PERMISSION_ALL', 'all');
define('PERMISSION_EMPLOYEE', 'employee');
define('PERMISSION_ATTENDANCE', 'attendance');
define('PERMISSION_REPORTS', 'reports');
define('PERMISSION_APPROVE', 'approve');
define('PERMISSION_PROFILE', 'profile');

// Giới hạn
define('MAX_LOGIN_ATTEMPTS', 5);
define('LOCKOUT_DURATION', 900); // 15 phút
define('PASSWORD_MIN_LENGTH', 8);
define('SESSION_TIMEOUT', 1440); // 24 phút

// Định dạng ngày tháng
define('DATE_FORMAT', 'Y-m-d');
define('DATETIME_FORMAT', 'Y-m-d H:i:s');
define('TIME_FORMAT', 'H:i:s');
define('DISPLAY_DATE_FORMAT', 'd/m/Y');
define('DISPLAY_DATETIME_FORMAT', 'd/m/Y H:i:s');

// Upload
define('UPLOAD_MAX_SIZE', 5 * 1024 * 1024); // 5MB
define('UPLOAD_PATH', 'uploads/');
define('UPLOAD_ALLOWED_TYPES', ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx']);

// API
define('API_VERSION', 'v1');
define('API_RATE_LIMIT', 100); // requests per minute
