<?php
/**
 * Kiểm tra kết nối database
 */

echo "<h2>Kiểm tra kết nối Database</h2>";

// Test 1: Kiểm tra MySQL service
echo "<h3>1. Kiểm tra MySQL service:</h3>";

// Test kết nối trực tiếp
try {
    $pdo = new PDO("mysql:host=localhost;port=3306;charset=utf8mb4", 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    echo "<p style='color: green;'>✓ Kết nối MySQL thành công!</p>";
} catch (PDOException $e) {
    echo "<p style='color: red;'>✗ Lỗi kết nối MySQL: " . $e->getMessage() . "</p>";
    
    if (strpos($e->getMessage(), 'No connection could be made') !== false) {
        echo "<div style='background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
        echo "<h4>🔧 Hướng dẫn khắc phục:</h4>";
        echo "<ol>";
        echo "<li><strong>Khởi động XAMPP:</strong></li>";
        echo "<ul>";
        echo "<li>Mở XAMPP Control Panel</li>";
        echo "<li>Click 'Start' cho Apache</li>";
        echo "<li>Click 'Start' cho MySQL</li>";
        echo "<li>Đợi cả 2 service chạy (màu xanh)</li>";
        echo "</ul>";
        echo "<li><strong>Kiểm tra port:</strong> MySQL phải chạy trên port 3306</li>";
        echo "<li><strong>Tạo database:</strong> Mở phpMyAdmin và import file database.sql</li>";
        echo "</ol>";
        echo "</div>";
    }
    exit;
}

// Test 2: Kiểm tra database timekeeping_system
echo "<h3>2. Kiểm tra database timekeeping_system:</h3>";

try {
    $pdo = new PDO("mysql:host=localhost;port=3306;dbname=timekeeping_system;charset=utf8mb4", 'root', '', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);
    echo "<p style='color: green;'>✓ Kết nối database timekeeping_system thành công!</p>";
    
    // Kiểm tra bảng users
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM users");
    $count = $stmt->fetch();
    echo "<p>✓ Số lượng user trong database: " . $count['count'] . "</p>";
    
    // Kiểm tra user admin
    $stmt = $pdo->prepare("SELECT id, username, email, status FROM users WHERE username = ?");
    $stmt->execute(['admin']);
    $admin = $stmt->fetch();
    
    if ($admin) {
        echo "<p style='color: green;'>✓ Tìm thấy user admin!</p>";
        echo "<p>ID: " . $admin['id'] . ", Status: " . $admin['status'] . "</p>";
    } else {
        echo "<p style='color: red;'>✗ Không tìm thấy user admin!</p>";
        echo "<p>Hãy import file database.sql vào phpMyAdmin</p>";
    }
    
} catch (PDOException $e) {
    echo "<p style='color: red;'>✗ Lỗi database timekeeping_system: " . $e->getMessage() . "</p>";
    
    if (strpos($e->getMessage(), 'Unknown database') !== false) {
        echo "<div style='background: #f8d7da; border: 1px solid #f5c6cb; padding: 15px; border-radius: 5px; margin: 10px 0;'>";
        echo "<h4>📋 Cần tạo database:</h4>";
        echo "<ol>";
        echo "<li>Mở phpMyAdmin: <a href='http://localhost/phpmyadmin' target='_blank'>http://localhost/phpmyadmin</a></li>";
        echo "<li>Tạo database mới tên 'timekeeping_system'</li>";
        echo "<li>Import file database.sql từ thư mục main/database/</li>";
        echo "</ol>";
        echo "</div>";
    }
}

echo "<hr>";
echo "<h3>3. Kiểm tra hệ thống:</h3>";

// Test 3: Kiểm tra các file cần thiết
$requiredFiles = [
    'config/env.php',
    'config/database.php',
    'core/Database.php',
    'core/Request.php',
    'core/Response.php',
    'core/Session.php',
    'models/User.php',
    'controllers/AuthController.php',
    'views/auth/login.php'
];

echo "<p>Kiểm tra các file cần thiết:</p>";
foreach ($requiredFiles as $file) {
    if (file_exists(__DIR__ . '/' . $file)) {
        echo "<p style='color: green;'>✓ " . $file . "</p>";
    } else {
        echo "<p style='color: red;'>✗ " . $file . " (thiếu)</p>";
    }
}

echo "<hr>";
echo "<p><strong>Kết luận:</strong> Nếu tất cả đều OK, hãy truy cập <a href='http://localhost/main/'>http://localhost/main/</a></p>";
?>
