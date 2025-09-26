<?php
// Set page data
$pageTitle = 'Cài đặt - Hệ thống chấm công';
$pageDescription = 'Cài đặt hệ thống';
$currentPage = 'settings';
$breadcrumbs = [
    ['title' => 'Dashboard', 'url' => '/main/dashboard'],
    ['title' => 'Cài đặt']
];
$pageCSS = [];
$pageJS = ['ui-modals'];
$customJS = "";

// Calculate assets path
$assetsPath = '/assets/';

// Ensure user is logged in
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header('Location: /main/');
    exit;
}

// Get settings data from controller
$settings = $GLOBALS['settings'] ?? [
    'company_name' => 'Công ty TNHH ABC',
    'timezone' => 'Asia/Ho_Chi_Minh',
    'tolerance_minutes' => 15,
    'standard_hours' => 8,
    'max_overtime_hours' => 4,
    'auto_approve_overtime' => false
];

// Mock user data for layout
$user = [
    'id' => $_SESSION['user_id'],
    'username' => $_SESSION['user']['username'] ?? 'admin',
    'first_name' => $_SESSION['user']['first_name'] ?? 'Admin',
    'last_name' => $_SESSION['user']['last_name'] ?? 'User',
    'role_name' => $_SESSION['user']['role_name'] ?? 'admin'
];

// Start output buffering to capture content
ob_start();
?>

<!-- Page content -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bx bx-cog me-2"></i>
                    Cài đặt hệ thống
                </h5>
                <p class="text-muted mb-0">Quản lý cài đặt chung của hệ thống</p>
            </div>
            <div class="card-body">
                <!-- Alert messages -->
                <div id="alertContainer">
                    <?php if (isset($_SESSION['success_message'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert" id="successAlert">
                            <i class="bx bx-check-circle me-2"></i>
                            <?php echo htmlspecialchars($_SESSION['success_message']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['success_message']); ?>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['error_message'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bx bx-error-circle me-2"></i>
                            <?php echo htmlspecialchars($_SESSION['error_message']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                        <?php unset($_SESSION['error_message']); ?>
                    <?php endif; ?>
                </div>
                
                <form id="settingsForm" method="POST" action="/main/settings">
                    <div class="row">
                        <div class="col-md-6">
                            <h6 class="mb-3">Cài đặt chung</h6>
                            <div class="mb-3">
                                <label class="form-label">Tên công ty <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" name="company_name" 
                                       value="<?php echo htmlspecialchars($settings['company_name']); ?>" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Múi giờ</label>
                                <select class="form-select" name="timezone">
                                    <option value="Asia/Ho_Chi_Minh" <?php echo $settings['timezone'] === 'Asia/Ho_Chi_Minh' ? 'selected' : ''; ?>>Asia/Ho_Chi_Minh</option>
                                    <option value="UTC" <?php echo $settings['timezone'] === 'UTC' ? 'selected' : ''; ?>>UTC</option>
                                </select>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Thời gian dung sai (phút)</label>
                                <input type="number" class="form-control" name="tolerance_minutes" 
                                       value="<?php echo $settings['tolerance_minutes']; ?>" min="0" max="60">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <h6 class="mb-3">Cài đặt chấm công</h6>
                            <div class="mb-3">
                                <label class="form-label">Giờ làm việc chuẩn</label>
                                <input type="number" class="form-control" name="standard_hours" 
                                       value="<?php echo $settings['standard_hours']; ?>" min="1" max="12">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Số giờ OT tối đa/ngày</label>
                                <input type="number" class="form-control" name="max_overtime_hours" 
                                       value="<?php echo $settings['max_overtime_hours']; ?>" min="0" max="8">
                            </div>
                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" name="auto_approve_overtime" 
                                       id="autoApprove" <?php echo $settings['auto_approve_overtime'] ? 'checked' : ''; ?>>
                                <label class="form-check-label" for="autoApprove">
                                    Tự động duyệt làm thêm giờ
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary" id="saveSettings">
                            <i class="bx bx-save me-2"></i>
                            Lưu cài đặt
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // Handle form submission
    $('#settingsForm').on('submit', function(e) {
        // Don't prevent default - let form submit normally
        // This will trigger the PHP redirect with session message
        
        // Show loading
        $('#saveSettings').prop('disabled', true).html('<i class="bx bx-loader-alt bx-spin me-2"></i>Đang lưu...');
    });
    
    // Auto-hide success alert after 5 seconds
    if ($('#successAlert').length > 0) {
        setTimeout(function() {
            $('#successAlert').fadeOut();
        }, 5000);
    }
    
    // Show alert function
    function showAlert(type, message) {
        var alertHtml = '<div class="alert alert-' + type + ' alert-dismissible fade show" role="alert">' +
                       '<i class="bx bx-' + (type === 'success' ? 'check-circle' : 'error-circle') + ' me-2"></i>' +
                       message +
                       '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                       '</div>';
        
        $('#alertContainer').html(alertHtml);
        
        // Auto hide after 5 seconds
        setTimeout(function() {
            $('.alert').fadeOut();
        }, 5000);
    }
});
</script>

<?php
// Get content and include layout
$content = ob_get_clean();
include __DIR__ . '/../layouts/app.php';
?>
