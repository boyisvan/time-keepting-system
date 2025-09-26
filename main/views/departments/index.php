<?php
$pageTitle = 'Quản lý phòng ban';
$pageDescription = 'Quản lý thông tin phòng ban trong hệ thống';
$currentPage = 'departments';
$breadcrumbs = [
    ['name' => 'Trang chủ', 'url' => '/main/dashboard'],
    ['name' => 'Quản lý phòng ban', 'url' => '']
];
$customJS = "";
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Danh sách phòng ban</h5>
                    <a href="/main/departments/create" class="btn btn-primary">
                        <i class="bx bx-plus me-1"></i>
                        Thêm phòng ban
                    </a>
                </div>
                
                <div class="card-body">
                    <!-- Success Message -->
                    <?php if (isset($_SESSION['success_message'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo htmlspecialchars($_SESSION['success_message']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION['success_message']); ?>
                    <?php endif; ?>
                    
                    <!-- Error Message -->
                    <?php if (isset($_SESSION['error_message'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo htmlspecialchars($_SESSION['error_message']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION['error_message']); ?>
                    <?php endif; ?>
                    
                    <!-- Search and Filter Form -->
                    <form method="GET" class="row g-3 mb-4">
                        <div class="col-md-4">
                            <label for="search" class="form-label">Tìm kiếm</label>
                            <input type="text" class="form-control" id="search" name="search" 
                                   value="<?php echo htmlspecialchars($filters['search'] ?? ''); ?>" 
                                   placeholder="Tên phòng ban, mã phòng ban...">
                        </div>
                        <div class="col-md-3">
                            <label for="status" class="form-label">Trạng thái</label>
                            <select class="form-select" id="status" name="status">
                                <option value="">Tất cả</option>
                                <option value="active" <?php echo (($filters['status'] ?? '') === 'active') ? 'selected' : ''; ?>>Hoạt động</option>
                                <option value="inactive" <?php echo (($filters['status'] ?? '') === 'inactive') ? 'selected' : ''; ?>>Không hoạt động</option>
                            </select>
                        </div>
                        <div class="col-md-3 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bx bx-search me-1"></i>
                                Tìm kiếm
                            </button>
                            <a href="/main/departments" class="btn btn-secondary">
                                <i class="bx bx-x me-1"></i>
                                Xóa
                            </a>
                        </div>
                    </form>

                    <!-- Departments Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Tên phòng ban</th>
                                    <th>Mã phòng ban</th>
                                    <th>Mô tả</th>
                                    <th>Trưởng phòng</th>
                                    <th>Trạng thái</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody id="departmentsTableBody">
                                <?php if (isset($departments) && !empty($departments)): ?>
                                    <?php foreach ($departments as $dept): ?>
                                        <tr>
                                            <td><?php echo htmlspecialchars($dept['name']); ?></td>
                                            <td><?php echo htmlspecialchars($dept['code'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($dept['description'] ?? ''); ?></td>
                                            <td><?php echo htmlspecialchars($dept['manager_name'] ?? 'Chưa có'); ?></td>
                                            <td>
                                                <?php if ($dept['status'] === 'active'): ?>
                                                    <span class="badge bg-success">Hoạt động</span>
                                                <?php else: ?>
                                                    <span class="badge bg-secondary">Không hoạt động</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="dropdown">
                                                    <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                                        <i class="bx bx-dots-vertical-rounded"></i>
                                                    </button>
                                                    <div class="dropdown-menu">
                                                        <a class="dropdown-item" href="/main/departments/<?php echo $dept['id']; ?>">
                                                            <i class="bx bx-show me-1"></i> Xem chi tiết
                                                        </a>
                                                        <a class="dropdown-item" href="/main/departments/<?php echo $dept['id']; ?>/edit">
                                                            <i class="bx bx-edit me-1"></i> Chỉnh sửa
                                                        </a>
                                                        <a class="dropdown-item text-danger" href="javascript:void(0);" onclick="deleteDepartment(<?php echo $dept['id']; ?>)">
                                                            <i class="bx bx-trash me-1"></i> Xóa
                                                        </a>
                                                    </div>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center">Không có dữ liệu</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>



<!-- Simple JavaScript for delete confirmation only -->
<script>
function deleteDepartment(id) {
    if (confirm('Bạn có chắc chắn muốn xóa phòng ban này?')) {
        // Create a form to submit DELETE request
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = '/main/departments/' + id;
        
        var methodInput = document.createElement('input');
        methodInput.type = 'hidden';
        methodInput.name = '_method';
        methodInput.value = 'DELETE';
        
        form.appendChild(methodInput);
        document.body.appendChild(form);
        form.submit();
    }
}
</script>
