<?php
// Set page data
$pageTitle = 'Quản lý vai trò - Hệ thống chấm công';
$pageDescription = 'Quản lý các vai trò trong hệ thống';
$currentPage = 'roles';
$breadcrumbs = [
    ['title' => 'Dashboard', 'url' => '/main/dashboard'],
    ['title' => 'Quản lý vai trò']
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
    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Danh sách vai trò</h5>
                    <a href="/main/roles/create" class="btn btn-primary">
                        <i class="bx bx-plus me-2"></i>Thêm vai trò mới
                    </a>
                </div>
                
                <div class="card-body">
                    <!-- Search and Filter -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <form method="GET" action="/main/roles" class="d-flex gap-2">
                                <input type="text" class="form-control" name="search" 
                                       placeholder="Tìm kiếm vai trò..." 
                                       value="<?php echo htmlspecialchars($_GET['search'] ?? ''); ?>">
                                <button type="submit" class="btn btn-outline-primary">
                                    <i class="bx bx-search"></i>
                                </button>
                            </form>
                        </div>
                    </div>

                    <!-- Success/Error Messages -->
                    <?php if (isset($_SESSION['success_message'])): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <?php echo htmlspecialchars($_SESSION['success_message']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION['success_message']); ?>
                    <?php endif; ?>
                    
                    <?php if (isset($_SESSION['error_message'])): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?php echo htmlspecialchars($_SESSION['error_message']); ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                        <?php unset($_SESSION['error_message']); ?>
                    <?php endif; ?>

                    <!-- Roles Table -->
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Tên vai trò</th>
                                    <th>Mô tả</th>
                                    <th>Quyền hạn</th>
                                    <th>Ngày tạo</th>
                                    <th>Thao tác</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (isset($roles) && !empty($roles)): ?>
                                    <?php foreach ($roles as $role): ?>
                                        <tr>
                                            <td><?php echo $role['id']; ?></td>
                                            <td>
                                                <span class="badge bg-primary"><?php echo htmlspecialchars($role['name']); ?></span>
                                            </td>
                                            <td><?php echo htmlspecialchars($role['description'] ?? ''); ?></td>
                                            <td>
                                                <?php if (isset($role['permissions']) && is_array($role['permissions'])): ?>
                                                    <?php foreach ($role['permissions'] as $permission => $value): ?>
                                                        <?php if ($value): ?>
                                                            <span class="badge bg-success me-1"><?php echo ucfirst($permission); ?></span>
                                                        <?php endif; ?>
                                                    <?php endforeach; ?>
                                                <?php endif; ?>
                                            </td>
                                            <td><?php echo date('d/m/Y H:i', strtotime($role['created_at'])); ?></td>
                                            <td>
                                                <div class="dropdown">
                                                    <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                        <i class="bx bx-dots-vertical-rounded"></i>
                                                    </button>
                                                    <ul class="dropdown-menu">
                                                        <li>
                                                            <a class="dropdown-item" href="/main/roles/<?php echo $role['id']; ?>">
                                                                <i class="bx bx-show me-2"></i>Xem chi tiết
                                                            </a>
                                                        </li>
                                                        <li>
                                                            <a class="dropdown-item" href="/main/roles/<?php echo $role['id']; ?>/edit">
                                                                <i class="bx bx-edit me-2"></i>Chỉnh sửa
                                                            </a>
                                                        </li>
                                                        <li><hr class="dropdown-divider"></li>
                                                        <li>
                                                            <a class="dropdown-item text-danger" href="javascript:void(0);" 
                                                               onclick="deleteRole(<?php echo $role['id']; ?>)">
                                                                <i class="bx bx-trash me-2"></i>Xóa
                                                            </a>
                                                        </li>
                                                    </ul>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center text-muted py-4">
                                            <i class="bx bx-info-circle me-2"></i>Không có vai trò nào
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <?php if (isset($pagination) && $pagination['total_pages'] > 1): ?>
                        <nav aria-label="Page navigation">
                            <ul class="pagination justify-content-center">
                                <?php if ($pagination['current_page'] > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?page=<?php echo $pagination['current_page'] - 1; ?>&search=<?php echo urlencode($_GET['search'] ?? ''); ?>">Trước</a>
                                    </li>
                                <?php endif; ?>
                                
                                <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                                    <li class="page-item <?php echo $i == $pagination['current_page'] ? 'active' : ''; ?>">
                                        <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($_GET['search'] ?? ''); ?>"><?php echo $i; ?></a>
                                    </li>
                                <?php endfor; ?>
                                
                                <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?page=<?php echo $pagination['current_page'] + 1; ?>&search=<?php echo urlencode($_GET['search'] ?? ''); ?>">Sau</a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
function deleteRole(id) {
    if (confirm('Bạn có chắc chắn muốn xóa vai trò này?')) {
        var form = document.createElement('form');
        form.method = 'POST';
        form.action = '/main/roles/' + id;
        
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

<?php
// Get content and include layout
$content = ob_get_clean();
include __DIR__ . '/../layouts/app.php';
?>
