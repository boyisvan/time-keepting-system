<?php
// Set page data
$pageTitle = 'Chấm vào - Hệ thống chấm công';
$pageDescription = 'Chấm công vào ca làm việc';
$currentPage = 'check-in';
$breadcrumbs = [
    ['title' => 'Dashboard', 'url' => '/main/dashboard'],
    ['title' => 'Chấm công', 'url' => '/main/attendance/check-in'],
    ['title' => 'Chấm vào']
];
$customJS = "
$(document).ready(function() {
    // Cập nhật thời gian hiện tại
    function updateCurrentTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('vi-VN', { 
            hour: '2-digit', 
            minute: '2-digit',
            second: '2-digit',
            hour12: false 
        });
        const dateString = now.toLocaleDateString('vi-VN');
        
        $('#currentTime').text(timeString);
        $('#currentDate').text(dateString);
    }
    
    updateCurrentTime();
    setInterval(updateCurrentTime, 1000);

    // Xử lý form chấm vào
    $('#checkInForm').on('submit', function(e) {
        e.preventDefault();
        
        const submitBtn = $('#checkInBtn');
        const originalText = submitBtn.html();
        
        // Disable button và hiển thị loading
        submitBtn.prop('disabled', true).html('<span class=\"spinner-border spinner-border-sm me-2\" role=\"status\"></span>Đang chấm công...');
        
        const formData = new FormData(this);
        
        $.ajax({
            url: '/main/api/attendance/check-in',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                if (response.success) {
                    showAlert('success', response.message);
                    $('#status').text('Đã chấm vào').removeClass('text-success').addClass('text-primary');
                    $('#checkInBtn').prop('disabled', true).html('<i class=\"bx bx-check me-2\"></i>Đã chấm vào');
                } else {
                    showAlert('danger', response.message);
                }
            },
            error: function(xhr) {
                const response = xhr.responseJSON;
                showAlert('danger', response ? response.message : 'Có lỗi xảy ra');
            },
            complete: function() {
                // Enable button sau 2 giây
                setTimeout(function() {
                    submitBtn.prop('disabled', false).html(originalText);
                }, 2000);
            }
        });
    });
});

function showAlert(type, message) {
    const alertHtml = `
        <div class=\"alert alert-\${type} alert-dismissible fade show\" role=\"alert\">
            \${message}
            <button type=\"button\" class=\"btn-close\" data-bs-dismiss=\"alert\" aria-label=\"Close\"></button>
        </div>
    `;
    
    // Xóa alert cũ
    $('#alertContainer').html(alertHtml);
    
    // Auto hide sau 5 giây
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 5000);
}
";
?>

<!-- Page content -->
<div class="row">
    <div class="col-lg-8 mx-auto">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title mb-0">
                    <i class="bx bx-log-in me-2"></i>
                    Chấm vào ca làm việc
                </h4>
                <p class="text-muted mb-0">Vui lòng chấm công để bắt đầu ca làm việc</p>
            </div>
            <div class="card-body">
                <!-- Hiển thị thông báo -->
                <div id="alertContainer"></div>

                <!-- Thông tin hiện tại -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Thời gian hiện tại</h5>
                                <h3 class="text-primary" id="currentTime">--:--</h3>
                                <p class="text-muted mb-0" id="currentDate">--/--/----</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card text-center">
                            <div class="card-body">
                                <h5 class="card-title">Trạng thái</h5>
                                <h3 class="text-success" id="status">Chưa chấm</h3>
                                <p class="text-muted mb-0">Sẵn sàng chấm vào</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Form chấm công -->
                <form id="checkInForm">
                    <div class="mb-4">
                        <label for="notes" class="form-label">Ghi chú (tùy chọn)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3" placeholder="Nhập ghi chú nếu có..."></textarea>
                    </div>
                    
                    <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                        <a href="/main/dashboard" class="btn btn-outline-secondary me-md-2">
                            <i class="bx bx-arrow-back me-2"></i>
                            Quay lại
                        </a>
                        <button class="btn btn-primary" type="submit" id="checkInBtn">
                            <i class="bx bx-log-in me-2"></i>
                            Chấm vào
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
