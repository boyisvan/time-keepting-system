<?php
// Set page data
$pageTitle = 'Quản lý nhân viên - Hệ thống chấm công';
$pageDescription = 'Quản lý thông tin nhân viên';
$currentPage = 'employees';
$breadcrumbs = [
    ['title' => 'Dashboard', 'url' => '/main/dashboard'],
    ['title' => 'Quản lý nhân viên']
];
$pageCSS = ['ui-modals'];
$pageJS = ['ui-modals'];
$customJS = "
$(document).ready(function() {
    // Search functionality
    $('#searchForm').on('submit', function(e) {
        e.preventDefault();
        var formData = $(this).serialize();
        window.location.href = '/main/employees?' + formData;
    });
    
    // Clear search
    $('#clearSearch').on('click', function() {
        $('#search').val('');
        $('#department_id').val('');
        $('#role_id').val('');
        $('#status').val('active');
        window.location.href = '/main/employees';
    });
    
    // Add employee modal
    $('#addEmployeeBtn').on('click', function() {
        $('#addEmployeeAlert').hide();
        $('#addEmployeeForm')[0].reset();
        $('#addEmployeeModal').modal('show');
    });
    
    // Edit employee modal
    $('.edit-employee').on('click', function() {
        var id = $(this).data('id');
        $('#editEmployeeAlert').hide();
        loadEmployeeData(id);
        $('#editEmployeeModal').modal('show');
    });
    
    // Delete employee
    $('.delete-employee').on('click', function() {
        var id = $(this).data('id');
        var name = $(this).data('name');
        
        Swal.fire({
            title: 'Xác nhận xóa',
            text: 'Bạn có chắc chắn muốn xóa nhân viên \"' + name + '\"?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Xóa',
            cancelButtonText: 'Hủy'
        }).then((result) => {
            if (result.isConfirmed) {
                deleteEmployee(id);
            }
        });
    });
    
    // Form submissions
    $('#addEmployeeForm').on('submit', function(e) {
        e.preventDefault();
        addEmployee();
    });
    
    $('#editEmployeeForm').on('submit', function(e) {
        e.preventDefault();
        updateEmployee();
    });
    
    // Check if edit parameter is in URL
    const urlParams = new URLSearchParams(window.location.search);
    const editId = urlParams.get('edit');
    if (editId) {
        // Load employee data and show edit modal
        loadEmployeeData(editId);
        $('#editEmployeeModal').modal('show');
        
        // Clean URL
        window.history.replaceState({}, document.title, window.location.pathname);
    }
});

function loadEmployeeData(id) {
    $.ajax({
        url: '/main/employees/' + id,
        type: 'GET',
        success: function(response) {
            console.log('Load employee response:', response); // Debug log
            if (response.success) {
                var emp = response.data || response; // Handle both formats
                console.log('Employee data:', emp); // Debug log
                $('#edit_id').val(emp.id);
                $('#edit_employee_id').val(emp.employee_id);
                $('#edit_username').val(emp.username);
                $('#edit_email').val(emp.email);
                $('#edit_first_name').val(emp.first_name);
                $('#edit_last_name').val(emp.last_name);
                $('#edit_phone').val(emp.phone);
                $('#edit_position_id').val(emp.position_id);
                $('#edit_role_id').val(emp.role_id);
                $('#edit_department_id').val(emp.department_id);
                $('#edit_status').val(emp.status);
            } else {
                alert('Không thể tải dữ liệu nhân viên: ' + (response.message || 'Lỗi không xác định'));
            }
        }
    });
}

function addEmployee() {
    var formData = new FormData($('#addEmployeeForm')[0]);
    
    // Clear previous alerts
    $('#addEmployeeAlert').hide().removeClass('alert-success alert-danger');
    
    $.ajax({
        url: '/main/employees',
        type: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            console.log('Success Response:', response); // Debug log
            
            if (response.success) {
                // Show success message in modal
                $('#addEmployeeAlert')
                    .removeClass('alert-danger')
                    .addClass('alert-success')
                    .html(\"<i class=\\\"bx bx-check-circle me-2\\\"></i>\" + response.message)
                    .show();
                
                // Close modal and reload after 2 seconds
                setTimeout(function() {
                    $('#addEmployeeModal').modal('hide');
                    location.reload();
                }, 2000);
            } else {
                // Show error message in modal
                var errorMessage = response.message || 'Có lỗi xảy ra';
                if (response.errors) {
                    var errorList = '<ul class=\"mb-0\">';
                    for (var field in response.errors) {
                        response.errors[field].forEach(function(error) {
                            errorList += '<li>' + error + '</li>';
                        });
                    }
                    errorList += '</ul>';
                    errorMessage = errorList;
                }
                
                $('#addEmployeeAlert')
                    .removeClass('alert-success')
                    .addClass('alert-danger')
                    .html(\"<i class=\\\"bx bx-x-circle me-2\\\"></i>\" + errorMessage)
                    .show();
            }
        },
        error: function(xhr) {
            console.log('Error Response:', xhr.responseJSON); // Debug log
            var message = 'Có lỗi xảy ra khi thêm nhân viên';
            
            // Handle 422 validation errors
            if (xhr.status === 422 && xhr.responseJSON) {
                if (xhr.responseJSON.errors) {
                    var errorList = '<ul class=\"mb-0\">';
                    for (var field in xhr.responseJSON.errors) {
                        xhr.responseJSON.errors[field].forEach(function(error) {
                            errorList += '<li>' + error + '</li>';
                        });
                    }
                    errorList += '</ul>';
                    message = errorList;
                } else if (xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }
            
            // Show error message in modal
            $('#addEmployeeAlert')
                .removeClass('alert-success')
                .addClass('alert-danger')
                .html(\"<i class=\\\"bx bx-x-circle me-2\\\"></i>\" + message)
                .show();
        }
    });
}

function updateEmployee() {
    var id = $('#edit_id').val();
    var formData = new FormData($('#editEmployeeForm')[0]);
    formData.append('_method', 'PUT'); // Add method override
    
    // Clear previous alerts
    $('#editEmployeeAlert').hide().removeClass('alert-success alert-danger');
    
    console.log('Update form data:', Object.fromEntries(formData)); // Debug log
    
    $.ajax({
        url: '/main/employees/' + id,
        type: 'POST', // Use POST with method override
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
            console.log('Update Response:', response); // Debug log
            
            if (response.success) {
                // Show success message in modal
                $('#editEmployeeAlert')
                    .removeClass('alert-danger')
                    .addClass('alert-success')
                    .html(\"<i class=\\\"bx bx-check-circle me-2\\\"></i>\" + response.message)
                    .show();
                
                // Close modal and reload after 2 seconds
                setTimeout(function() {
                    $('#editEmployeeModal').modal('hide');
                    location.reload();
                }, 2000);
            } else {
                // Show error message in modal
                var errorMessage = response.message || 'Có lỗi xảy ra';
                if (response.errors) {
                    var errorList = '<ul class=\"mb-0\">';
                    for (var field in response.errors) {
                        response.errors[field].forEach(function(error) {
                            errorList += '<li>' + error + '</li>';
                        });
                    }
                    errorList += '</ul>';
                    errorMessage = errorList;
                }
                
                $('#editEmployeeAlert')
                    .removeClass('alert-success')
                    .addClass('alert-danger')
                    .html(\"<i class=\\\"bx bx-x-circle me-2\\\"></i>\" + errorMessage)
                    .show();
            }
        },
        error: function(xhr) {
            console.log('Update Error Response:', xhr.responseJSON); // Debug log
            var message = 'Có lỗi xảy ra khi cập nhật nhân viên';
            
            // Handle 422 validation errors
            if (xhr.status === 422 && xhr.responseJSON) {
                if (xhr.responseJSON.errors) {
                    var errorList = '<ul class=\"mb-0\">';
                    for (var field in xhr.responseJSON.errors) {
                        xhr.responseJSON.errors[field].forEach(function(error) {
                            errorList += '<li>' + error + '</li>';
                        });
                    }
                    errorList += '</ul>';
                    message = errorList;
                } else if (xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
            } else if (xhr.responseJSON && xhr.responseJSON.message) {
                message = xhr.responseJSON.message;
            }
            
            // Show error message in modal
            $('#editEmployeeAlert')
                .removeClass('alert-success')
                .addClass('alert-danger')
                .html(\"<i class=\\\"bx bx-x-circle me-2\\\"></i>\" + message)
                .show();
        }
    });
}

function deleteEmployee(id) {
    $.ajax({
        url: '/main/employees/' + id,
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
        error: function(xhr) {
            console.log('Delete Error Response:', xhr.responseJSON); // Debug log
            var message = 'Có lỗi xảy ra khi xóa nhân viên';
            
            if (xhr.responseJSON) {
                if (xhr.responseJSON.message) {
                    message = xhr.responseJSON.message;
                }
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
";
?>

<!-- Page content -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h5 class="card-title mb-0">
                            <i class="bx bx-user me-2"></i>
                            Quản lý nhân viên
                        </h5>
                        <p class="text-muted mb-0">Danh sách và quản lý thông tin nhân viên</p>
                    </div>
                    <button class="btn btn-primary" id="addEmployeeBtn">
                        <i class="bx bx-plus me-2"></i>
                        Thêm nhân viên
                    </button>
                </div>
            </div>
            
            <!-- Search and Filter -->
            <div class="card-body border-bottom">
                <form id="searchForm" class="row g-3">
                    <div class="col-md-3">
                        <label class="form-label">Tìm kiếm</label>
                        <input type="text" class="form-control" id="search" name="search" 
                               placeholder="Tên, mã NV, email..." value="<?php echo $filters['search'] ?? ''; ?>">
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Phòng ban</label>
                        <select class="form-select" id="department_id" name="department_id">
                            <option value="">Tất cả</option>
                            <?php foreach ($departments as $dept): ?>
                                <option value="<?php echo $dept['id']; ?>" 
                                        <?php echo ($filters['department_id'] ?? '') == $dept['id'] ? 'selected' : ''; ?>>
                                    <?php echo $dept['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Vai trò</label>
                        <select class="form-select" id="role_id" name="role_id">
                            <option value="">Tất cả</option>
                            <?php foreach ($roles as $role): ?>
                                <option value="<?php echo $role['id']; ?>" 
                                        <?php echo ($filters['role_id'] ?? '') == $role['id'] ? 'selected' : ''; ?>>
                                    <?php echo $role['name']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="col-md-2">
                        <label class="form-label">Trạng thái</label>
                        <select class="form-select" id="status" name="status">
                            <option value="">Tất cả</option>
                            <option value="active" <?php echo ($filters['status'] ?? '') == 'active' ? 'selected' : ''; ?>>Hoạt động</option>
                            <option value="inactive" <?php echo ($filters['status'] ?? '') == 'inactive' ? 'selected' : ''; ?>>Không hoạt động</option>
                        </select>
                    </div>
                    <div class="col-md-3 d-flex align-items-end">
                        <button type="submit" class="btn btn-primary me-2">
                            <i class="bx bx-search me-1"></i>
                            Tìm kiếm
                        </button>
                        <button type="button" class="btn btn-outline-secondary" id="clearSearch">
                            <i class="bx bx-x me-1"></i>
                            Xóa bộ lọc
                        </button>
                    </div>
                </form>
            </div>

            <!-- Table -->
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Mã NV</th>
                                <th>Họ tên</th>
                                <th>Email</th>
                                <th>Phòng ban</th>
                                <th>Vai trò</th>
                                <th>Trạng thái</th>
                                <th>Thao tác</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (!empty($employees)): ?>
                                <?php foreach ($employees as $employee): ?>
                                    <tr>
                                        <td>
                                            <span class="fw-semibold"><?php echo $employee['employee_id']; ?></span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm me-3">
                                                    <img src="<?php echo $assetsPath; ?>img/avatars/1.png" alt="Avatar" class="rounded-circle">
                                                </div>
                                                <div>
                                                    <h6 class="mb-0"><?php echo $employee['first_name'] . ' ' . $employee['last_name']; ?></h6>
                                                    <small class="text-muted"><?php echo $employee['position_name'] ?? 'Chưa phân công'; ?></small>
                                                </div>
                                            </div>
                                        </td>
                                        <td><?php echo $employee['email']; ?></td>
                                        <td>
                                            <span class="badge bg-label-info"><?php echo $employee['department_name'] ?? 'Chưa phân công'; ?></span>
                                        </td>
                                        <td>
                                            <span class="badge bg-label-primary"><?php echo $employee['role_name'] ?? 'Nhân viên'; ?></span>
                                        </td>
                                        <td>
                                            <?php
                                            $statusClass = '';
                                            $statusText = '';
                                            switch ($employee['status']) {
                                                case 'active':
                                                    $statusClass = 'bg-label-success';
                                                    $statusText = 'Hoạt động';
                                                    break;
                                                case 'inactive':
                                                    $statusClass = 'bg-label-secondary';
                                                    $statusText = 'Không hoạt động';
                                                    break;
                                                case 'suspended':
                                                    $statusClass = 'bg-label-danger';
                                                    $statusText = 'Tạm khóa';
                                                    break;
                                            }
                                            ?>
                                            <span class="badge <?php echo $statusClass; ?>"><?php echo $statusText; ?></span>
                                        </td>
                                        <td>
                                            <div class="dropdown">
                                                <button class="btn p-0" type="button" data-bs-toggle="dropdown">
                                                    <i class="bx bx-dots-vertical-rounded"></i>
                                                </button>
                                                <ul class="dropdown-menu">
                                                    <li>
                                                        <a class="dropdown-item edit-employee" href="javascript:void(0);" 
                                                           data-id="<?php echo $employee['id']; ?>">
                                                            <i class="bx bx-edit-alt me-2"></i>
                                                            Chỉnh sửa
                                                        </a>
                                                    </li>
                                                    <li>
                                                        <a class="dropdown-item" href="/main/employees/<?php echo $employee['id']; ?>">
                                                            <i class="bx bx-show me-2"></i>
                                                            Xem chi tiết
                                                        </a>
                                                    </li>
                                                    <li><hr class="dropdown-divider"></li>
                                                    <li>
                                                        <a class="dropdown-item text-danger delete-employee" href="javascript:void(0);" 
                                                           data-id="<?php echo $employee['id']; ?>" 
                                                           data-name="<?php echo $employee['first_name'] . ' ' . $employee['last_name']; ?>">
                                                            <i class="bx bx-trash me-2"></i>
                                                            Xóa
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="7" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bx bx-user-x display-4"></i>
                                            <p class="mt-2">Không có nhân viên nào</p>
                                        </div>
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
                                    <a class="page-link" href="?page=<?php echo $pagination['current_page'] - 1; ?>&<?php echo http_build_query($filters); ?>">
                                        <i class="bx bx-chevron-left"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                            
                            <?php for ($i = 1; $i <= $pagination['total_pages']; $i++): ?>
                                <li class="page-item <?php echo $i == $pagination['current_page'] ? 'active' : ''; ?>">
                                    <a class="page-link" href="?page=<?php echo $i; ?>&<?php echo http_build_query($filters); ?>">
                                        <?php echo $i; ?>
                                    </a>
                                </li>
                            <?php endfor; ?>
                            
                            <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                                <li class="page-item">
                                    <a class="page-link" href="?page=<?php echo $pagination['current_page'] + 1; ?>&<?php echo http_build_query($filters); ?>">
                                        <i class="bx bx-chevron-right"></i>
                                    </a>
                                </li>
                            <?php endif; ?>
                        </ul>
                    </nav>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Add Employee Modal -->
<div class="modal fade" id="addEmployeeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Thêm nhân viên mới</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="addEmployeeForm">
                <div class="modal-body">
                    <!-- Alert container -->
                    <div id="addEmployeeAlert" class="alert" style="display: none;"></div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mã nhân viên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="employee_id" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tên đăng nhập <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="username" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" name="email" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mật khẩu <span class="text-danger">*</span></label>
                            <input type="password" class="form-control" name="password" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Họ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="first_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" name="last_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Số điện thoại</label>
                            <input type="text" class="form-control" name="phone">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Chức vụ</label>
                            <select class="form-select" name="position_id">
                                <option value="">Chọn chức vụ</option>
                                <?php foreach ($positions as $position): ?>
                                    <option value="<?php echo $position['id']; ?>"><?php echo $position['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phòng ban <span class="text-danger">*</span></label>
                            <select class="form-select" name="department_id" required>
                                <option value="">Chọn phòng ban</option>
                                <?php foreach ($departments as $dept): ?>
                                    <option value="<?php echo $dept['id']; ?>"><?php echo $dept['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Vai trò <span class="text-danger">*</span></label>
                            <select class="form-select" name="role_id" required>
                                <option value="">Chọn vai trò</option>
                                <?php foreach ($roles as $role): ?>
                                    <option value="<?php echo $role['id']; ?>"><?php echo $role['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Thêm nhân viên</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Edit Employee Modal -->
<div class="modal fade" id="editEmployeeModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Chỉnh sửa nhân viên</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editEmployeeForm">
                <input type="hidden" id="edit_id" name="id">
                <div class="modal-body">
                    <!-- Alert container -->
                    <div id="editEmployeeAlert" class="alert" style="display: none;"></div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Mã nhân viên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_employee_id" name="employee_id" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tên đăng nhập <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_username" name="username" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email <span class="text-danger">*</span></label>
                            <input type="email" class="form-control" id="edit_email" name="email" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Họ <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_first_name" name="first_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tên <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="edit_last_name" name="last_name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Số điện thoại</label>
                            <input type="text" class="form-control" id="edit_phone" name="phone">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Chức vụ</label>
                            <select class="form-select" id="edit_position_id" name="position_id">
                                <option value="">Chọn chức vụ</option>
                                <?php foreach ($positions as $position): ?>
                                    <option value="<?php echo $position['id']; ?>"><?php echo $position['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phòng ban <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit_department_id" name="department_id" required>
                                <option value="">Chọn phòng ban</option>
                                <?php foreach ($departments as $dept): ?>
                                    <option value="<?php echo $dept['id']; ?>"><?php echo $dept['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Vai trò <span class="text-danger">*</span></label>
                            <select class="form-select" id="edit_role_id" name="role_id" required>
                                <option value="">Chọn vai trò</option>
                                <?php foreach ($roles as $role): ?>
                                    <option value="<?php echo $role['id']; ?>"><?php echo $role['name']; ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Trạng thái</label>
                            <select class="form-select" id="edit_status" name="status">
                                <option value="active">Hoạt động</option>
                                <option value="inactive">Không hoạt động</option>
                                <option value="suspended">Tạm khóa</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-primary">Cập nhật</button>
                </div>
            </form>
        </div>
    </div>
</div>