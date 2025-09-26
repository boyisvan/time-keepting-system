<?php
// Page data for layout
$pageTitle = 'Chi tiết nhân viên';
$pageDescription = 'Xem thông tin chi tiết nhân viên';
$currentPage = 'employees';
$breadcrumbs = [
    ['name' => 'Trang chủ', 'url' => '/main/dashboard'],
    ['name' => 'Quản lý nhân viên', 'url' => '/main/employees'],
    ['name' => 'Chi tiết nhân viên', 'url' => '']
];
$customJS = '';
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Thông tin chi tiết nhân viên</h5>
                    <div>
                        <a href="/main/employees" class="btn btn-outline-secondary">
                            <i class="bx bx-arrow-back me-1"></i> Quay lại
                        </a>
                        <button type="button" class="btn btn-primary" onclick="editEmployee(<?php echo $employee['id']; ?>)">
                            <i class="bx bx-edit me-1"></i> Chỉnh sửa
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="text-center mb-4">
                                <?php if (!empty($employee['avatar'])): ?>
                                    <img src="<?php echo htmlspecialchars($employee['avatar']); ?>" 
                                         alt="Avatar" 
                                         class="rounded-circle" 
                                         width="120" 
                                         height="120">
                                <?php else: ?>
                                    <div class="rounded-circle bg-primary d-flex align-items-center justify-content-center text-white" 
                                         style="width: 120px; height: 120px; margin: 0 auto; font-size: 48px;">
                                        <?php echo strtoupper(substr($employee['first_name'], 0, 1) . substr($employee['last_name'], 0, 1)); ?>
                                    </div>
                                <?php endif; ?>
                                <h4 class="mt-3 mb-1"><?php echo htmlspecialchars($employee['first_name'] . ' ' . $employee['last_name']); ?></h4>
                                <p class="text-muted"><?php echo htmlspecialchars($employee['role_name']); ?></p>
                            </div>
                        </div>
                        <div class="col-md-8">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Mã nhân viên</label>
                                    <p class="form-control-plaintext"><?php echo htmlspecialchars($employee['employee_id']); ?></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Tên đăng nhập</label>
                                    <p class="form-control-plaintext"><?php echo htmlspecialchars($employee['username']); ?></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Email</label>
                                    <p class="form-control-plaintext"><?php echo htmlspecialchars($employee['email']); ?></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Số điện thoại</label>
                                    <p class="form-control-plaintext"><?php echo htmlspecialchars($employee['phone']); ?></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Phòng ban</label>
                                    <p class="form-control-plaintext"><?php echo htmlspecialchars($employee['department_name']); ?></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Chức vụ</label>
                                    <p class="form-control-plaintext"><?php echo htmlspecialchars($employee['position'] ?? 'Chưa cập nhật'); ?></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Trạng thái</label>
                                    <p class="form-control-plaintext">
                                        <span class="badge bg-<?php echo $employee['status'] === 'active' ? 'success' : 'danger'; ?>">
                                            <?php echo $employee['status'] === 'active' ? 'Hoạt động' : 'Không hoạt động'; ?>
                                        </span>
                                    </p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Ngày tạo</label>
                                    <p class="form-control-plaintext"><?php echo date('d/m/Y H:i', strtotime($employee['created_at'])); ?></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Cập nhật lần cuối</label>
                                    <p class="form-control-plaintext"><?php echo date('d/m/Y H:i', strtotime($employee['updated_at'])); ?></p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label fw-bold">Đăng nhập lần cuối</label>
                                    <p class="form-control-plaintext">
                                        <?php echo $employee['last_login'] ? date('d/m/Y H:i', strtotime($employee['last_login'])) : 'Chưa đăng nhập'; ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function editEmployee(id) {
    // Redirect to employees page with edit modal
    window.location.href = '/main/employees?edit=' + id;
}
</script>
