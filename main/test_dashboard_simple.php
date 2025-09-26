<?php
// Simple test for dashboard
session_start();

// Mock user session
$_SESSION['user_id'] = 1;
$_SESSION['user'] = [
    'id' => 1,
    'username' => 'admin',
    'first_name' => 'Admin',
    'last_name' => 'User',
    'role_name' => 'admin'
];

echo "Testing dashboard...\n";

// Test if dashboard loads
ob_start();
try {
    include 'views/dashboard.php';
    $output = ob_get_clean();
    
    if (strlen($output) > 100) {
        echo "SUCCESS: Dashboard loaded successfully\n";
        echo "Output length: " . strlen($output) . " characters\n";
    } else {
        echo "ERROR: Dashboard output too short\n";
        echo "Output: " . $output . "\n";
    }
} catch (Exception $e) {
    echo "ERROR: Exception: " . $e->getMessage() . "\n";
}

echo "Test completed\n";
?>

