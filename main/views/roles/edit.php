<?php
// Set page data
$pageTitle = 'Chỉnh sửa vai trò - Hệ thống chấm công';
$pageDescription = 'Chỉnh sửa thông tin vai trò';
$currentPage = 'roles';
$breadcrumbs = [
    ['title' => 'Dashboard', 'url' => '/main/dashboard'],
    ['title' => 'Quản lý vai trò', 'url' => '/main/roles'],
    ['title' => 'Chỉnh sửa vai trò']
];
$pageCSS = ['ui-modals'];
$pageJS = ['ui-modals'];
$customJS = "";

// Calculate assets path
$assetsPath = '/assets/';

// Start output buffering to capture content
ob_start();
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="card-title mb-0">Chỉnh sửa vai trò</h5>
                </div>
                
                <div class="card-body">
                    <?php if (isset($role) && $role): ?>
                        <form method="POST" action="/main/roles/<?php echo $role['id']; ?>">
                            <input type="hidden" name="_method" value="PUT">
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Tên vai trò <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name" 
                                               value="<?php echo htmlspecialchars($role['name']); ?>" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Mô tả <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($role['description'] ?? ''); ?></textarea>
                            </div>
                            
                            <div class="mb-4">
                                <label class="form-label">Quyền hạn <span class="text-danger">*</span></label>
                                <div class="row">
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="permission_attendance" name="permissions[attendance]" value="1"
                                                   <?php echo (isset($role['permissions']['attendance']) && $role['permissions']['attendance']) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="permission_attendance">Chấm công</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="permission_employee" name="permissions[employee]" value="1"
                                                   <?php echo (isset($role['permissions']['employee']) && $role['permissions']['employee']) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="permission_employee">Quản lý nhân viên</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="permission_department" name="permissions[department]" value="1"
                                                   <?php echo (isset($role['permissions']['department']) && $role['permissions']['department']) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="permission_department">Quản lý phòng ban</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="permission_role" name="permissions[role]" value="1"
                                                   <?php echo (isset($role['permissions']['role']) && $role['permissions']['role']) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="permission_role">Quản lý vai trò</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="permission_reports" name="permissions[reports]" value="1"
                                                   <?php echo (isset($role['permissions']['reports']) && $role['permissions']['reports']) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="permission_reports">Báo cáo</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="permission_settings" name="permissions[settings]" value="1"
                                                   <?php echo (isset($role['permissions']['settings']) && $role['permissions']['settings']) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="permission_settings">Cài đặt</label>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="permission_profile" name="permissions[profile]" value="1"
                                                   <?php echo (isset($role['permissions']['profile']) && $role['permissions']['profile']) ? 'checked' : ''; ?>>
                                            <label class="form-check-label" for="permission_profile">Hồ sơ cá nhân</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-end gap-2">
                                <a href="/main/roles" class="btn btn-secondary">Hủy</a>
                                <button type="submit" class="btn btn-primary">Cập nhật</button>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-danger">
                            <h6>Không tìm thấy vai trò</h6>
                            <p>Vai trò bạn đang tìm kiếm không tồn tại hoặc đã bị xóa.</p>
                            <a href="/main/roles" class="btn btn-primary">Quay lại danh sách</a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
// Get content and include layout
$content = ob_get_clean();
include __DIR__ . '/../layouts/app.php';
?>
