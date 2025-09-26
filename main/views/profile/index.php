<?php
// Set page data
$pageTitle = 'Thông tin cá nhân - Hệ thống chấm công';
$pageDescription = 'Quản lý thông tin cá nhân';
$currentPage = 'profile';
$breadcrumbs = [
    ['title' => 'Dashboard', 'url' => '/main/dashboard'],
    ['title' => 'Thông tin cá nhân']
];
$customJS = "
$(document).ready(function() {
    // Update profile
    $('#updateProfileForm').on('submit', function(e) {
        e.preventDefault();
        updateProfile();
    });
    
    // Change password
    $('#changePasswordForm').on('submit', function(e) {
        e.preventDefault();
        changePassword();
    });
});

function updateProfile() {
    var formData = new FormData($('#updateProfileForm')[0]);
    
    $.ajax({
        url: '/main/profile/update',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    title: 'Thành công!',
                    text: response.message,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    location.reload();
                });
            } else {
                Swal.fire({
                    title: 'Lỗi!',
                    text: response.message,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        },
        error: function() {
            Swal.fire({
                title: 'Lỗi!',
                text: 'Có lỗi xảy ra khi cập nhật thông tin',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
}

function changePassword() {
    var formData = new FormData($('#changePasswordForm')[0]);
    
    $.ajax({
        url: '/main/profile/change-password',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            if (response.success) {
                Swal.fire({
                    title: 'Thành công!',
                    text: response.message,
                    icon: 'success',
                    confirmButtonText: 'OK'
                }).then(() => {
                    $('#changePasswordForm')[0].reset();
                });
            } else {
                Swal.fire({
                    title: 'Lỗi!',
                    text: response.message,
                    icon: 'error',
                    confirmButtonText: 'OK'
                });
            }
        },
        error: function() {
            Swal.fire({
                title: 'Lỗi!',
                text: 'Có lỗi xảy ra khi đổi mật khẩu',
                icon: 'error',
                confirmButtonText: 'OK'
            });
        }
    });
}
";
?>

<!-- Page content -->
<div class="row">
    <div class="col-md-4">
        <!-- Profile Card -->
        <div class="card">
            <div class="card-body text-center">
                <div class="avatar avatar-xl mb-3">
                    <img src="<?php echo $assetsPath; ?>img/avatars/1.png" alt="Avatar" class="rounded-circle">
                </div>
                <h5 class="card-title"><?php echo $user['first_name'] . ' ' . $user['last_name']; ?></h5>
                <p class="text-muted"><?php echo $user['position'] ?? 'Nhân viên'; ?></p>
                <p class="text-muted"><?php echo $user['department_name'] ?? 'Chưa phân công'; ?></p>
                
                <div class="d-flex justify-content-center gap-2">
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#changePasswordModal">
                        <i class="bx bx-key me-1"></i>
                        Đổi mật khẩu
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-8">
        <!-- Profile Information -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bx bx-user me-2"></i>
                    Thông tin cá nhân
                </h5>
            </div>
            <div class="card-body">
                <form id="updateProfileForm">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mã nhân viên</label>
                            <input type="text" class="form-control" value="<?php echo $user['employee_id']; ?>" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tên đăng nhập</label>
                            <input type="text" class="form-control" value="<?php echo $user['username']; ?>" readonly>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" value="<?php echo $user['email']; ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Số điện thoại</label>
                            <input type="text" class="form-control" name="phone" value="<?php echo $user['phone'] ?? ''; ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Họ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="first_name" value="<?php echo $user['first_name']; ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="last_name" value="<?php echo $user['last_name']; ?>" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Chức vụ</label>
                            <input type="text" class="form-control" name="position" value="<?php echo $user['position'] ?? ''; ?>">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Ngày vào làm</label>
                            <input type="text" class="form-control" value="<?php echo $user['hire_date'] ?? 'Chưa cập nhật'; ?>" readonly>
                        </div>
                    </div>
                    
                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">
                            <i class="bx bx-save me-2"></i>
                            Cập nhật thông tin
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Change Password Modal -->
<div class="modal fade" id="changePasswordModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Đổi mật khẩu</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="changePasswordForm">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Mật khẩu hiện tại <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="current_password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Mật khẩu mới <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="new_password" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Xác nhận mật khẩu mới <span class="text-danger">*</span></label>
                        <input type="password" class="form-control" name="confirm_password" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Đổi mật khẩu</button>
                </div>
            </form>
        </div>
    </div>
</div>
