<?php
// Set page data
$pageTitle = 'Báo cáo chấm công - Hệ thống chấm công';
$pageDescription = 'Báo cáo thống kê chấm công nhân viên';
$currentPage = 'reports';
$breadcrumbs = [
    ['title' => 'Dashboard', 'url' => '/main/dashboard'],
    ['title' => 'Báo cáo', 'url' => '/main/reports'],
    ['title' => 'Báo cáo chấm công']
];
$pageCSS = [];
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
                <div class="card-header">
                    <h5 class="card-title mb-0">Báo cáo chấm công</h5>
                </div>
                
                <div class="card-body">
                    <!-- Date Range Filter -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <form method="GET" action="/main/reports/attendance" class="d-flex gap-2">
                                <div class="mb-3">
                                    <label for="start_date" class="form-label">Từ ngày</label>
                                    <input type="date" class="form-control" id="start_date" name="start_date" 
                                           value="<?php echo htmlspecialchars($startDate ?? ''); ?>">
                                </div>
                                <div class="mb-3">
                                    <label for="end_date" class="form-label">Đến ngày</label>
                                    <input type="date" class="form-control" id="end_date" name="end_date" 
                                           value="<?php echo htmlspecialchars($endDate ?? ''); ?>">
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">&nbsp;</label>
                                    <button type="submit" class="btn btn-primary d-block">
                                        <i class="bx bx-search"></i> Lọc
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title">Tổng ngày làm việc</h6>
                                            <h3><?php echo $attendanceData['monthly_summary']['total_days']; ?></h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="bx bx-calendar text-white" style="font-size: 2rem;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-success text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title">Tỷ lệ chấm công</h6>
                                            <h3><?php echo $attendanceData['monthly_summary']['average_attendance']; ?>%</h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="bx bx-check-circle text-white" style="font-size: 2rem;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-info text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title">Có mặt</h6>
                                            <h3><?php echo $attendanceData['monthly_summary']['total_present']; ?></h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="bx bx-user-check text-white" style="font-size: 2rem;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card bg-warning text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title">Vắng mặt</h6>
                                            <h3><?php echo $attendanceData['monthly_summary']['total_absent']; ?></h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="bx bx-user-x text-white" style="font-size: 2rem;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts -->
                    <div class="row">
                        <!-- Daily Attendance Chart -->
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title">Biểu đồ chấm công theo ngày</h6>
                                </div>
                                <div class="card-body">
                                    <div id="dailyAttendanceChart" style="height: 300px;"></div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Department Attendance Chart -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title">Chấm công theo phòng ban</h6>
                                </div>
                                <div class="card-body">
                                    <div id="departmentAttendanceChart" style="height: 300px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Department Attendance Table -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title">Chi tiết chấm công theo phòng ban</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Phòng ban</th>
                                                    <th>Tỷ lệ chấm công</th>
                                                    <th>Trạng thái</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($attendanceData['department_attendance'] as $dept): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($dept['department']); ?></td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="progress me-3" style="width: 100px; height: 8px;">
                                                                    <div class="progress-bar" role="progressbar" 
                                                                         style="width: <?php echo $dept['attendance_rate']; ?>%"
                                                                         aria-valuenow="<?php echo $dept['attendance_rate']; ?>" 
                                                                         aria-valuemin="0" aria-valuemax="100">
                                                                    </div>
                                                                </div>
                                                                <span><?php echo $dept['attendance_rate']; ?>%</span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <?php if ($dept['attendance_rate'] >= 95): ?>
                                                                <span class="badge bg-success">Tốt</span>
                                                            <?php elseif ($dept['attendance_rate'] >= 90): ?>
                                                                <span class="badge bg-warning">Khá</span>
                                                            <?php else: ?>
                                                                <span class="badge bg-danger">Cần cải thiện</span>
                                                            <?php endif; ?>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
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
// Wait for ApexCharts to load
document.addEventListener('DOMContentLoaded', function() {
    if (typeof ApexCharts === 'undefined') {
        console.error('ApexCharts not loaded');
        return;
    }
    
    // Daily Attendance Chart
    var dailyAttendanceData = <?php echo json_encode($attendanceData['daily_attendance']); ?>;
    var dailyAttendanceOptions = {
    series: [{
        name: 'Có mặt',
        data: dailyAttendanceData.map(function(item) { return item.present; })
    }, {
        name: 'Vắng mặt',
        data: dailyAttendanceData.map(function(item) { return item.absent; })
    }, {
        name: 'Đi muộn',
        data: dailyAttendanceData.map(function(item) { return item.late; })
    }],
    chart: {
        type: 'line',
        height: 300,
        toolbar: {
            show: true
        }
    },
    xaxis: {
        categories: dailyAttendanceData.map(function(item) { return item.date; })
    },
    yaxis: {
        title: {
            text: 'Số lượng nhân viên'
        }
    },
    colors: ['#28a745', '#dc3545', '#ffc107'],
    legend: {
        position: 'top'
    }
};

    var dailyAttendanceChart = new ApexCharts(document.querySelector("#dailyAttendanceChart"), dailyAttendanceOptions);
    dailyAttendanceChart.render();

    // Department Attendance Chart
    var departmentAttendanceData = <?php echo json_encode($attendanceData['department_attendance']); ?>;
    var departmentAttendanceOptions = {
    series: departmentAttendanceData.map(function(item) { return item.attendance_rate; }),
    chart: {
        type: 'donut',
        height: 300
    },
    labels: departmentAttendanceData.map(function(item) { return item.department; }),
    colors: ['#007bff', '#28a745', '#ffc107', '#dc3545'],
    legend: {
        position: 'bottom'
    },
    plotOptions: {
        pie: {
            donut: {
                size: '70%'
            }
        }
    }
};

    var departmentAttendanceChart = new ApexCharts(document.querySelector("#departmentAttendanceChart"), departmentAttendanceOptions);
    departmentAttendanceChart.render();
});
</script>

<?php
// Get content and include layout
$content = ob_get_clean();
include __DIR__ . '/../layouts/app.php';
?>
