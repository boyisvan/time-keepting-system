<?php
$pageTitle = 'Chi tiết phòng ban - ' . htmlspecialchars($department['name']);
$pageDescription = 'Thông tin chi tiết về phòng ban ' . htmlspecialchars($department['name']);
$currentPage = 'departments';
$breadcrumbs = [
    ['name' => 'Trang chủ', 'url' => '/main/dashboard'],
    ['name' => 'Quản lý phòng ban', 'url' => '/main/departments'],
    ['name' => 'Chi tiết phòng ban', 'url' => '']
];

$customJS = "
<script>
function editDepartment(id) {
    window.location.href = '/main/departments?edit=' + id;
}

function deleteDepartment(id) {
    Swal.fire({
        title: 'Xác nhận xóa',
        text: 'Bạn có chắc chắn muốn xóa phòng ban này?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Xóa',
        cancelButtonText: 'Hủy'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                url: '/main/departments/' + id,
                type: 'POST',
                data: { _method: 'DELETE' },
                success: function(response) {
                    if (response.success) {
                        Swal.fire({
                            title: 'Thành công!',
                            text: response.message,
                            icon: 'success',
                            confirmButtonText: 'OK'
                        }).then(() => {
                            window.location.href = '/main/departments';
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
                error: function(xhr) {
                    var message = 'Có lỗi xảy ra khi xóa phòng ban';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        message = xhr.responseJSON.message;
                    }
                    Swal.fire({
                        title: 'Lỗi!',
                        text: message,
                        icon: 'error',
                        confirmButtonText: 'OK'
                    });
                }
            });
        }
    });
}
</script>
";
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">Thông tin phòng ban</h5>
                    <div>
                        <button type="button" class="btn btn-primary me-2" onclick="editDepartment(<?php echo $department['id']; ?>)">
                            <i class="bx bx-edit me-1"></i> Chỉnh sửa
                        </button>
                        <button type="button" class="btn btn-outline-danger" onclick="deleteDepartment(<?php echo $department['id']; ?>)">
                            <i class="bx bx-trash me-1"></i> Xóa
                        </button>
                    </div>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Tên phòng ban:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <?php echo htmlspecialchars($department['name']); ?>
                                </div>
                            </div>
                            
                            <?php if (!empty($department['code'])): ?>
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Mã phòng ban:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <span class="badge bg-label-primary"><?php echo htmlspecialchars($department['code']); ?></span>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Mô tả:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <?php echo nl2br(htmlspecialchars($department['description'])); ?>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Trạng thái:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <?php
                                    $statusClass = '';
                                    $statusText = '';
                                    switch ($department['status']) {
                                        case 'active':
                                            $statusClass = 'bg-label-success';
                                            $statusText = 'Hoạt động';
                                            break;
                                        case 'inactive':
                                            $statusClass = 'bg-label-danger';
                                            $statusText = 'Không hoạt động';
                                            break;
                                        default:
                                            $statusClass = 'bg-label-secondary';
                                            $statusText = ucfirst($department['status']);
                                    }
                                    ?>
                                    <span class="badge <?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Số nhân viên:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <span class="badge bg-label-info"><?php echo $department['employee_count'] ?? 0; ?> nhân viên</span>
                                </div>
                            </div>
                            
                            <?php if (!empty($department['manager_first_name'])): ?>
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Trưởng phòng:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <?php echo htmlspecialchars($department['manager_first_name'] . ' ' . $department['manager_last_name']); ?>
                                    <?php if (!empty($department['manager_email'])): ?>
                                        <br><small class="text-muted"><?php echo htmlspecialchars($department['manager_email']); ?></small>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <?php endif; ?>
                            
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Ngày tạo:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <?php echo date('d/m/Y H:i', strtotime($department['created_at'])); ?>
                                </div>
                            </div>
                            
                            <div class="row mb-3">
                                <div class="col-sm-3">
                                    <strong>Cập nhật lần cuối:</strong>
                                </div>
                                <div class="col-sm-9">
                                    <?php echo date('d/m/Y H:i', strtotime($department['updated_at'])); ?>
                                </div>
                            </div>
                        </div>
                        
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title mb-0">Thống kê</h6>
                                </div>
                                <div class="card-body">
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span>Tổng nhân viên:</span>
                                        <span class="badge bg-label-primary"><?php echo $department['employee_count'] ?? 0; ?></span>
                                    </div>
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <span>Trạng thái:</span>
                                        <span class="badge <?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                                    </div>
                                    <?php if (!empty($department['manager_first_name'])): ?>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span>Trưởng phòng:</span>
                                        <span class="text-truncate" style="max-width: 100px;" title="<?php echo htmlspecialchars($department['manager_first_name'] . ' ' . $department['manager_last_name']); ?>">
                                            <?php echo htmlspecialchars($department['manager_first_name'] . ' ' . $department['manager_last_name']); ?>
                                        </span>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
