<?php
// Set page data
$pageTitle = 'Chỉnh sửa phòng ban - Hệ thống chấm công';
$pageDescription = 'Chỉnh sửa thông tin phòng ban';
$currentPage = 'departments';
$breadcrumbs = [
    ['title' => 'Dashboard', 'url' => '/main/dashboard'],
    ['title' => 'Quản lý phòng ban', 'url' => '/main/departments'],
    ['title' => 'Chỉnh sửa phòng ban']
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
                    <h5 class="card-title mb-0">Chỉnh sửa phòng ban</h5>
                </div>
                
                <div class="card-body">
                    <?php if (isset($department) && $department): ?>
                        <form method="POST" action="/main/departments/<?php echo $department['id']; ?>">
                            <input type="hidden" name="_method" value="PUT">
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="name" class="form-label">Tên phòng ban <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="name" name="name" 
                                               value="<?php echo htmlspecialchars($department['name']); ?>" required>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="code" class="form-label">Mã phòng ban <span class="text-danger">*</span></label>
                                        <input type="text" class="form-control" id="code" name="code" 
                                               value="<?php echo htmlspecialchars($department['code'] ?? ''); ?>" required>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-3">
                                <label for="description" class="form-label">Mô tả <span class="text-danger">*</span></label>
                                <textarea class="form-control" id="description" name="description" rows="4" required><?php echo htmlspecialchars($department['description'] ?? ''); ?></textarea>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="mb-3">
                                        <label for="status" class="form-label">Trạng thái <span class="text-danger">*</span></label>
                                        <select class="form-select" id="status" name="status" required>
                                            <option value="active" <?php echo ($department['status'] === 'active') ? 'selected' : ''; ?>>Hoạt động</option>
                                            <option value="inactive" <?php echo ($department['status'] === 'inactive') ? 'selected' : ''; ?>>Không hoạt động</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="d-flex justify-content-end gap-2">
                                <a href="/main/departments" class="btn btn-secondary">Hủy</a>
                                <button type="submit" class="btn btn-primary">Cập nhật</button>
                            </div>
                        </form>
                    <?php else: ?>
                        <div class="alert alert-danger">
                            <h6>Không tìm thấy phòng ban</h6>
                            <p>Phòng ban bạn đang tìm kiếm không tồn tại hoặc đã bị xóa.</p>
                            <a href="/main/departments" class="btn btn-primary">Quay lại danh sách</a>
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

