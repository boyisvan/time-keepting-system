<?php
// Set page data
$pageTitle = 'Dashboard - Hệ thống chấm công';
$pageDescription = 'Trang tổng quan hệ thống chấm công';
$currentPage = 'dashboard';
$breadcrumbs = [
    ['title' => 'Dashboard']
];
$pageCSS = [];
$pageJS = ['ui-modals'];
$customJS = "";

// Calculate assets path
$assetsPath = '/assets/';

// Ensure user is logged in
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
if (!isset($_SESSION['user_id'])) {
    header('Location: /main/');
    exit;
}

// Mock user data for testing
$user = [
    'id' => $_SESSION['user_id'],
    'username' => $_SESSION['user']['username'] ?? 'admin',
    'first_name' => $_SESSION['user']['first_name'] ?? 'Admin',
    'last_name' => $_SESSION['user']['last_name'] ?? 'User',
    'role_name' => $_SESSION['user']['role_name'] ?? 'admin'
];

// Get statistics data
$stats = [
    'total_employees' => 50,
    'active_employees' => 48,
    'inactive_employees' => 2,
    'total_departments' => 5,
    'active_departments' => 5,
    'attendance_rate' => 95.2,
    'overtime_hours' => 245.8,
    'new_employees_this_month' => 5
];

// Get chart data
$attendanceData = [
    'daily_attendance' => [
        ['date' => '2025-01-01', 'present' => 45, 'absent' => 5, 'late' => 3],
        ['date' => '2025-01-02', 'present' => 48, 'absent' => 2, 'late' => 1],
        ['date' => '2025-01-03', 'present' => 46, 'absent' => 4, 'late' => 2],
        ['date' => '2025-01-04', 'present' => 49, 'absent' => 1, 'late' => 0],
        ['date' => '2025-01-05', 'present' => 47, 'absent' => 3, 'late' => 1],
        ['date' => '2025-01-06', 'present' => 44, 'absent' => 6, 'late' => 2],
        ['date' => '2025-01-07', 'present' => 48, 'absent' => 2, 'late' => 1],
    ],
    'department_attendance' => [
        ['department' => 'Phòng IT', 'attendance_rate' => 97.8],
        ['department' => 'Phòng Marketing', 'attendance_rate' => 93.1],
        ['department' => 'Phòng Nhân sự', 'attendance_rate' => 96.5],
        ['department' => 'Phòng Kế toán', 'attendance_rate' => 94.2],
    ]
];

$overtimeData = [
    'daily_overtime' => [
        ['date' => '2025-01-01', 'hours' => 12.5, 'employees' => 8],
        ['date' => '2025-01-02', 'hours' => 8.0, 'employees' => 5],
        ['date' => '2025-01-03', 'hours' => 15.2, 'employees' => 10],
        ['date' => '2025-01-04', 'hours' => 6.5, 'employees' => 4],
        ['date' => '2025-01-05', 'hours' => 9.8, 'employees' => 6],
        ['date' => '2025-01-06', 'hours' => 11.3, 'employees' => 7],
        ['date' => '2025-01-07', 'hours' => 7.5, 'employees' => 5],
    ],
    'department_overtime' => [
        ['department' => 'Phòng IT', 'hours' => 89.5, 'cost' => 895000],
        ['department' => 'Phòng Marketing', 'hours' => 67.2, 'cost' => 672000],
        ['department' => 'Phòng Nhân sự', 'hours' => 45.8, 'cost' => 458000],
        ['department' => 'Phòng Kế toán', 'hours' => 43.3, 'cost' => 433000],
    ]
];

$employeeStats = [
    'departments' => [
        ['name' => 'Phòng IT', 'count' => 15, 'percentage' => 30],
        ['name' => 'Phòng Marketing', 'count' => 12, 'percentage' => 24],
        ['name' => 'Phòng Nhân sự', 'count' => 10, 'percentage' => 20],
        ['name' => 'Phòng Kế toán', 'count' => 8, 'percentage' => 16],
        ['name' => 'Phòng Kinh doanh', 'count' => 5, 'percentage' => 10],
    ],
    'positions' => [
        ['name' => 'Nhân viên', 'count' => 35, 'percentage' => 70],
        ['name' => 'Trưởng phòng', 'count' => 8, 'percentage' => 16],
        ['name' => 'Phó giám đốc', 'count' => 4, 'percentage' => 8],
        ['name' => 'Giám đốc', 'count' => 2, 'percentage' => 4],
        ['name' => 'Thực tập sinh', 'count' => 1, 'percentage' => 2],
    ]
];

// Start output buffering to capture content
ob_start();
?>

<div class="container-xxl flex-grow-1 container-p-y">
    <!-- Header -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Dashboard Tổng Quan</h4>
                    <p class="card-subtitle text-muted">Thống kê chi tiết hệ thống chấm công</p>
                </div>
            </div>
        </div>
    </div>
<br>
    <!-- Summary Cards -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <div>
                            <h6 class="card-title">Tổng nhân viên</h6>
                            <h2><?php echo $stats['total_employees']; ?></h2>
                            <small class="text-white-50">+<?php echo $stats['new_employees_this_month']; ?> tháng này</small>
                        </div>
                        <div class="align-self-center">
                            <i class="bx bx-user text-white" style="font-size: 3rem;"></i>
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
                            <h2><?php echo $stats['attendance_rate']; ?>%</h2>
                            <small class="text-white-50">Trung bình tháng</small>
                        </div>
                        <div class="align-self-center">
                            <i class="bx bx-check-circle text-white" style="font-size: 3rem;"></i>
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
                            <h6 class="card-title">Giờ làm thêm</h6>
                            <h2><?php echo $stats['overtime_hours']; ?>h</h2>
                            <small class="text-white-50">Tháng này</small>
                        </div>
                        <div class="align-self-center">
                            <i class="bx bx-time text-white" style="font-size: 3rem;"></i>
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
                            <h6 class="card-title">Phòng ban</h6>
                            <h2><?php echo $stats['total_departments']; ?></h2>
                            <small class="text-white-50">Đang hoạt động</small>
                        </div>
                        <div class="align-self-center">
                            <i class="bx bx-buildings text-white" style="font-size: 3rem;"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 1 -->
    <div class="row mb-4">
        <!-- Daily Attendance Chart -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">Biểu đồ chấm công theo ngày</h6>
                </div>
                <div class="card-body">
                    <div id="dailyAttendanceChart" style="height: 350px;"></div>
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
                    <div id="departmentAttendanceChart" style="height: 350px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 2 -->
    <div class="row mb-4">
        <!-- Overtime Chart -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">Biểu đồ làm thêm giờ</h6>
                </div>
                <div class="card-body">
                    <div id="overtimeChart" style="height: 300px;"></div>
                </div>
            </div>
        </div>
        
        <!-- Employee Distribution Chart -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">Phân bố nhân viên theo phòng ban</h6>
                </div>
                <div class="card-body">
                    <div id="employeeDistributionChart" style="height: 300px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row 3 -->
    <div class="row mb-4">
        <!-- Position Distribution Chart -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">Phân bố nhân viên theo chức vụ</h6>
                </div>
                <div class="card-body">
                    <div id="positionDistributionChart" style="height: 300px;"></div>
                </div>
            </div>
        </div>
        
        <!-- Department Overtime Chart -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">Làm thêm giờ theo phòng ban</h6>
                </div>
                <div class="card-body">
                    <div id="departmentOvertimeChart" style="height: 300px;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Tables -->
    <div class="row">
        <!-- Department Stats Table -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">Thống kê phòng ban</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Phòng ban</th>
                                    <th>Nhân viên</th>
                                    <th>Chấm công</th>
                                    <th>Làm thêm</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($attendanceData['department_attendance'] as $index => $dept): ?>
                                    <tr>
                                        <td><?php echo htmlspecialchars($dept['department']); ?></td>
                                        <td>
                                            <span class="badge bg-primary"><?php echo $employeeStats['departments'][$index]['count']; ?></span>
                                        </td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="progress me-3" style="width: 80px; height: 8px;">
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
                                            <span class="badge bg-warning"><?php echo $overtimeData['department_overtime'][$index]['hours']; ?>h</span>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Recent Activity -->
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h6 class="card-title">Hoạt động gần đây</h6>
                </div>
                <div class="card-body">
                    <div class="timeline">
                        <div class="timeline-item">
                            <div class="timeline-marker bg-primary"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Nhân viên mới</h6>
                                <p class="timeline-text">Nguyễn Văn A đã được thêm vào hệ thống</p>
                                <small class="text-muted">2 giờ trước</small>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-success"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Chấm công</h6>
                                <p class="timeline-text">45 nhân viên đã chấm công hôm nay</p>
                                <small class="text-muted">4 giờ trước</small>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-warning"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Làm thêm giờ</h6>
                                <p class="timeline-text">8 nhân viên đăng ký làm thêm giờ</p>
                                <small class="text-muted">6 giờ trước</small>
                            </div>
                        </div>
                        <div class="timeline-item">
                            <div class="timeline-marker bg-info"></div>
                            <div class="timeline-content">
                                <h6 class="timeline-title">Cập nhật hệ thống</h6>
                                <p class="timeline-text">Hệ thống đã được cập nhật phiên bản mới</p>
                                <small class="text-muted">1 ngày trước</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.timeline {
    position: relative;
    padding-left: 30px;
}

.timeline::before {
    content: '';
    position: absolute;
    left: 15px;
    top: 0;
    bottom: 0;
    width: 2px;
    background: #e9ecef;
}

.timeline-item {
    position: relative;
    margin-bottom: 20px;
}

.timeline-marker {
    position: absolute;
    left: -22px;
    top: 5px;
    width: 12px;
    height: 12px;
    border-radius: 50%;
    border: 2px solid #fff;
    box-shadow: 0 0 0 2px #e9ecef;
}

.timeline-content {
    background: #f8f9fa;
    padding: 15px;
    border-radius: 8px;
    border-left: 3px solid #007bff;
}

.timeline-title {
    margin-bottom: 5px;
    font-size: 14px;
    font-weight: 600;
}

.timeline-text {
    margin-bottom: 5px;
    font-size: 13px;
    color: #6c757d;
}
</style>

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
            height: 350,
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
        },
        stroke: {
            curve: 'smooth',
            width: 3
        },
        markers: {
            size: 6
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
            height: 350
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

    // Overtime Chart
    var dailyOvertimeData = <?php echo json_encode($overtimeData['daily_overtime']); ?>;
    var overtimeOptions = {
        series: [{
            name: 'Giờ làm thêm',
            data: dailyOvertimeData.map(function(item) { return item.hours; })
        }],
        chart: {
            type: 'area',
            height: 300,
            toolbar: {
                show: true
            }
        },
        xaxis: {
            categories: dailyOvertimeData.map(function(item) { return item.date; })
        },
        yaxis: {
            title: {
                text: 'Giờ làm thêm'
            }
        },
        colors: ['#ffc107'],
        fill: {
            type: 'gradient',
            gradient: {
                shadeIntensity: 1,
                opacityFrom: 0.7,
                opacityTo: 0.3,
            }
        },
        dataLabels: {
            enabled: false
        }
    };
    var overtimeChart = new ApexCharts(document.querySelector("#overtimeChart"), overtimeOptions);
    overtimeChart.render();

    // Employee Distribution Chart
    var departmentData = <?php echo json_encode($employeeStats['departments']); ?>;
    var employeeDistributionOptions = {
        series: departmentData.map(function(item) { return item.count; }),
        chart: {
            type: 'donut',
            height: 300
        },
        labels: departmentData.map(function(item) { return item.name; }),
        colors: ['#007bff', '#28a745', '#ffc107', '#dc3545', '#6f42c1'],
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
    var employeeDistributionChart = new ApexCharts(document.querySelector("#employeeDistributionChart"), employeeDistributionOptions);
    employeeDistributionChart.render();

    // Position Distribution Chart
    var positionData = <?php echo json_encode($employeeStats['positions']); ?>;
    var positionDistributionOptions = {
        series: [{
            name: 'Số lượng',
            data: positionData.map(function(item) { return item.count; })
        }],
        chart: {
            type: 'bar',
            height: 300
        },
        xaxis: {
            categories: positionData.map(function(item) { return item.name; })
        },
        yaxis: {
            title: {
                text: 'Số lượng nhân viên'
            }
        },
        colors: ['#28a745', '#007bff', '#ffc107', '#dc3545', '#6f42c1'],
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '55%',
            }
        }
    };
    var positionDistributionChart = new ApexCharts(document.querySelector("#positionDistributionChart"), positionDistributionOptions);
    positionDistributionChart.render();

    // Department Overtime Chart
    var departmentOvertimeData = <?php echo json_encode($overtimeData['department_overtime']); ?>;
    var departmentOvertimeOptions = {
        series: [{
            name: 'Giờ làm thêm',
            data: departmentOvertimeData.map(function(item) { return item.hours; })
        }],
        chart: {
            type: 'bar',
            height: 300
        },
        xaxis: {
            categories: departmentOvertimeData.map(function(item) { return item.department; })
        },
        yaxis: {
            title: {
                text: 'Giờ làm thêm'
            }
        },
        colors: ['#28a745', '#007bff', '#ffc107', '#dc3545'],
        plotOptions: {
            bar: {
                horizontal: false,
                columnWidth: '55%',
            }
        }
    };
    var departmentOvertimeChart = new ApexCharts(document.querySelector("#departmentOvertimeChart"), departmentOvertimeOptions);
    departmentOvertimeChart.render();
});
</script>

<?php
// Get content and include layout
$content = ob_get_clean();
include __DIR__ . '/layouts/app.php';
?>
