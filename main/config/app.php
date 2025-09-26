<?php
/**
 * Cấu hình ứng dụng
 */

return [
    'name' => 'Hệ thống chấm công',
    'version' => '1.0.0',
    'timezone' => 'Asia/Ho_Chi_Minh',
    'debug' => $_ENV['APP_DEBUG'] ?? false,
    'url' => $_ENV['APP_URL'] ?? 'http://localhost',
    
    // Cấu hình session
    'session' => [
        'lifetime' => 1440, // 24 phút
        'secure' => false,
        'httponly' => true,
        'samesite' => 'Lax'
    ],
    
    // Cấu hình upload
    'upload' => [
        'max_size' => 5 * 1024 * 1024, // 5MB
        'allowed_types' => ['jpg', 'jpeg', 'png', 'pdf', 'doc', 'docx'],
        'path' => 'uploads/'
    ],
    
    // Cấu hình email
    'mail' => [
        'host' => $_ENV['MAIL_HOST'] ?? 'smtp.gmail.com',
        'port' => $_ENV['MAIL_PORT'] ?? 587,
        'username' => $_ENV['MAIL_USERNAME'] ?? '',
        'password' => $_ENV['MAIL_PASSWORD'] ?? '',
        'encryption' => $_ENV['MAIL_ENCRYPTION'] ?? 'tls',
        'from_address' => $_ENV['MAIL_FROM_ADDRESS'] ?? 'noreply@company.com',
        'from_name' => $_ENV['MAIL_FROM_NAME'] ?? 'Hệ thống chấm công'
    ],
    
    // Cấu hình chấm công
    'attendance' => [
        'tolerance_minutes' => 15,
        'max_overtime_per_day' => 4,
        'auto_approve_overtime' => false,
        'ip_whitelist' => [
            '192.168.1.0/24',
            '10.0.0.0/8',
            '127.0.0.1'
        ]
    ],
    
    // Cấu hình phân trang
    'pagination' => [
        'per_page' => 20,
        'max_per_page' => 100
    ]
];
