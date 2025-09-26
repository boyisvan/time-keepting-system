<?php
// Set page data
$pageTitle = 'Lịch sử chấm công - Hệ thống chấm công';
$pageDescription = 'Xem lịch sử chấm công của bạn';
$currentPage = 'attendance-history';
$breadcrumbs = [
    ['title' => 'Dashboard', 'url' => '/main/dashboard'],
    ['title' => 'Chấm công', 'url' => '/main/attendance/check-in'],
    ['title' => 'Lịch sử chấm công']
];
$customJS = "
$(document).ready(function() {
    // Filter by date range
    $('#dateRange').on('change', function() {
        const range = $(this).val();
        filterByDateRange(range);
    });
    
    // Search functionality
    $('#searchInput').on('keyup', function() {
        const searchTerm = $(this).val().toLowerCase();
        $('#attendanceTable tbody tr').each(function() {
            const rowText = $(this).text().toLowerCase();
            if (rowText.includes(searchTerm)) {
                $(this).show();
            } else {
                $(this).hide();
            }
        });
    });
});

function filterByDateRange(range) {
    // Simulate filtering
    console.log('Filter by:', range);
    // In real implementation, this would make an AJAX call
}
";
?>

<!-- Page content -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="card-title mb-0">
                    <i class="bx bx-history me-2"></i>
                    Lịch sử chấm công
                </h5>
                <div class="d-flex gap-2">
                    <select class="form-select" id="dateRange" style="width: auto;">
                        <option value="today">Hôm nay</option>
                        <option value="week">Tuần này</option>
                        <option value="month" selected>Tháng này</option>
                        <option value="quarter">Quý này</option>
                        <option value="year">Năm nay</option>
                    </select>
                    <button class="btn btn-outline-primary">
                        <i class="bx bx-download me-2"></i>
                        Xuất Excel
                    </button>
                </div>
            </div>
            <div class="card-body">
                <!-- Search and Filter -->
                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="input-group">
                            <span class="input-group-text"><i class="bx bx-search"></i></span>
                            <input type="text" class="form-control" placeholder="Tìm kiếm..." id="searchInput">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex gap-2">
                            <input type="date" class="form-control" id="startDate">
                            <input type="date" class="form-control" id="endDate">
                            <button class="btn btn-primary">Lọc</button>
                        </div>
                    </div>
                </div>

                <!-- Statistics Cards -->
                <div class="row mb-4">
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h6 class="card-title">Tổng ngày làm</h6>
                                <h3 class="text-primary">22</h3>
                                <small class="text-muted">Tháng này</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h6 class="card-title">Đúng giờ</h6>
                                <h3 class="text-success">18</h3>
                                <small class="text-muted">81.8%</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h6 class="card-title">Đi muộn</h6>
                                <h3 class="text-warning">3</h3>
                                <small class="text-muted">13.6%</small>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card text-center">
                            <div class="card-body">
                                <h6 class="card-title">Tổng OT</h6>
                                <h3 class="text-info">12h</h3>
                                <small class="text-muted">Tháng này</small>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Attendance Table -->
                <div class="table-responsive">
                    <table class="table table-striped" id="attendanceTable">
                        <thead>
                            <tr>
                                <th>Ngày</th>
                                <th>Thứ</th>
                                <th>Giờ vào</th>
                                <th>Giờ ra</th>
                                <th>Tổng giờ</th>
                                <th>OT</th>
                                <th>Trạng thái</th>
                                <th>Ghi chú</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Sample data -->
                            <tr>
                                <td>23/09/2024</td>
                                <td>Thứ 2</td>
                                <td><span class="badge bg-success">08:00</span></td>
                                <td><span class="badge bg-primary">17:30</span></td>
                                <td>8h 30m</td>
                                <td>30m</td>
                                <td><span class="badge bg-success">Đúng giờ</span></td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>24/09/2024</td>
                                <td>Thứ 3</td>
                                <td><span class="badge bg-warning">08:15</span></td>
                                <td><span class="badge bg-primary">17:45</span></td>
                                <td>8h 30m</td>
                                <td>45m</td>
                                <td><span class="badge bg-warning">Muộn 15p</span></td>
                                <td>Kẹt xe</td>
                            </tr>
                            <tr>
                                <td>25/09/2024</td>
                                <td>Thứ 4</td>
                                <td><span class="badge bg-success">08:00</span></td>
                                <td><span class="badge bg-primary">17:00</span></td>
                                <td>8h 00m</td>
                                <td>0m</td>
                                <td><span class="badge bg-success">Đúng giờ</span></td>
                                <td>-</td>
                            </tr>
                            <tr>
                                <td>26/09/2024</td>
                                <td>Thứ 5</td>
                                <td><span class="badge bg-success">08:00</span></td>
                                <td><span class="badge bg-primary">18:00</span></td>
                                <td>9h 00m</td>
                                <td>1h</td>
                                <td><span class="badge bg-success">Đúng giờ</span></td>
                                <td>Làm thêm</td>
                            </tr>
                            <tr>
                                <td>27/09/2024</td>
                                <td>Thứ 6</td>
                                <td><span class="badge bg-warning">08:20</span></td>
                                <td><span class="badge bg-primary">17:30</span></td>
                                <td>8h 10m</td>
                                <td>10m</td>
                                <td><span class="badge bg-warning">Muộn 20p</span></td>
                                <td>Họp sáng</td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <nav aria-label="Page navigation" class="mt-4">
                    <ul class="pagination justify-content-center">
                        <li class="page-item disabled">
                            <a class="page-link" href="#" tabindex="-1">Trước</a>
                        </li>
                        <li class="page-item active"><a class="page-link" href="#">1</a></li>
                        <li class="page-item"><a class="page-link" href="#">2</a></li>
                        <li class="page-item"><a class="page-link" href="#">3</a></li>
                        <li class="page-item">
                            <a class="page-link" href="#">Sau</a>
                        </li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</div>
