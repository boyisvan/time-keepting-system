<?php
// Set page data
$pageTitle = 'Báo cáo phòng ban - Hệ thống chấm công';
$pageDescription = 'Báo cáo thống kê phòng ban';
$currentPage = 'reports-department';
$breadcrumbs = [
    ['title' => 'Dashboard', 'url' => '/main/dashboard'],
    ['title' => 'Báo cáo', 'url' => '/main/reports'],
    ['title' => 'Báo cáo phòng ban']
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
                    <h5 class="card-title mb-0">Báo cáo phòng ban</h5>
                </div>
                
                <div class="card-body">
                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title">Tổng phòng ban</h6>
                                            <h3><?php echo $departmentStats['total_departments']; ?></h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="bx bx-buildings text-white" style="font-size: 2rem;"></i>
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
                                            <h6 class="card-title">Đang hoạt động</h6>
                                            <h3><?php echo $departmentStats['active_departments']; ?></h3>
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
                                            <h6 class="card-title">Tổng nhân viên</h6>
                                            <h3><?php echo array_sum(array_column($departmentStats['departments'], 'employee_count')); ?></h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="bx bx-user text-white" style="font-size: 2rem;"></i>
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
                                            <h6 class="card-title">Tỷ lệ chấm công TB</h6>
                                            <h3><?php echo number_format(array_sum(array_column($departmentStats['departments'], 'attendance_rate')) / count($departmentStats['departments']), 1); ?>%</h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="bx bx-trending-up text-white" style="font-size: 2rem;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts -->
                    <div class="row">
                        <!-- Employee Count Chart -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title">Số lượng nhân viên theo phòng ban</h6>
                                </div>
                                <div class="card-body">
                                    <div id="employeeCountChart" style="height: 300px;"></div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Attendance Rate Chart -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title">Tỷ lệ chấm công theo phòng ban</h6>
                                </div>
                                <div class="card-body">
                                    <div id="attendanceRateChart" style="height: 300px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Overtime Chart -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title">Giờ làm thêm theo phòng ban</h6>
                                </div>
                                <div class="card-body">
                                    <div id="overtimeChart" style="height: 300px;"></div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Budget Usage Chart -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title">Sử dụng ngân sách theo phòng ban</h6>
                                </div>
                                <div class="card-body">
                                    <div id="budgetChart" style="height: 300px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Department Details Table -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title">Chi tiết phòng ban</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Phòng ban</th>
                                                    <th>Nhân viên</th>
                                                    <th>Chấm công</th>
                                                    <th>Làm thêm (h)</th>
                                                    <th>Ngân sách</th>
                                                    <th>Trạng thái</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($departmentStats['departments'] as $dept): ?>
                                                    <tr>
                                                        <td>
                                                            <strong><?php echo htmlspecialchars($dept['name']); ?></strong>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-primary"><?php echo $dept['employee_count']; ?></span>
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
                                                            <span class="badge bg-warning"><?php echo $dept['overtime_hours']; ?>h</span>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="progress me-3" style="width: 80px; height: 8px;">
                                                                    <div class="progress-bar bg-info" role="progressbar" 
                                                                         style="width: <?php echo $dept['budget_used']; ?>%"
                                                                         aria-valuenow="<?php echo $dept['budget_used']; ?>" 
                                                                         aria-valuemin="0" aria-valuemax="100">
                                                                    </div>
                                                                </div>
                                                                <span><?php echo $dept['budget_used']; ?>%</span>
                                                            </div>
                                                        </td>
                                                        <td>
                                                            <span class="badge bg-success">Hoạt động</span>
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
    
    // Employee Count Chart
    var departmentData = <?php echo json_encode($departmentStats['departments']); ?>;
    var employeeCountOptions = {
    series: [{
        name: 'Số lượng nhân viên',
        data: departmentData.map(function(item) { return item.employee_count; })
    }],
    chart: {
        type: 'bar',
        height: 300
    },
    xaxis: {
        categories: departmentData.map(function(item) { return item.name; })
    },
    yaxis: {
        title: {
            text: 'Số lượng nhân viên'
        }
    },
    colors: ['#007bff'],
    plotOptions: {
        bar: {
            horizontal: false,
            columnWidth: '55%',
        }
    }
};

    var employeeCountChart = new ApexCharts(document.querySelector("#employeeCountChart"), employeeCountOptions);
    employeeCountChart.render();

    // Attendance Rate Chart
    var attendanceRateOptions = {
    series: [{
        name: 'Tỷ lệ chấm công',
        data: departmentData.map(function(item) { return item.attendance_rate; })
    }],
    chart: {
        type: 'line',
        height: 300
    },
    xaxis: {
        categories: departmentData.map(function(item) { return item.name; })
    },
    yaxis: {
        title: {
            text: 'Tỷ lệ (%)'
        },
        min: 80,
        max: 100
    },
    colors: ['#28a745'],
    stroke: {
        curve: 'smooth',
        width: 3
    },
    markers: {
        size: 6
    }
};

    var attendanceRateChart = new ApexCharts(document.querySelector("#attendanceRateChart"), attendanceRateOptions);
    attendanceRateChart.render();

    // Overtime Chart
    var overtimeOptions = {
    series: [{
        name: 'Giờ làm thêm',
        data: departmentData.map(function(item) { return item.overtime_hours; })
    }],
    chart: {
        type: 'area',
        height: 300
    },
    xaxis: {
        categories: departmentData.map(function(item) { return item.name; })
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
    }
};

    var overtimeChart = new ApexCharts(document.querySelector("#overtimeChart"), overtimeOptions);
    overtimeChart.render();

    // Budget Chart
    var budgetOptions = {
    series: departmentData.map(function(item) { return item.budget_used; }),
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

    var budgetChart = new ApexCharts(document.querySelector("#budgetChart"), budgetOptions);
    budgetChart.render();
});
</script>

<?php
// Get content and include layout
$content = ob_get_clean();
include __DIR__ . '/../layouts/app.php';
?>
