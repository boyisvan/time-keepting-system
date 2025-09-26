<?php
// Set page data
$pageTitle = 'Báo cáo làm thêm giờ - Hệ thống chấm công';
$pageDescription = 'Báo cáo thống kê làm thêm giờ nhân viên';
$currentPage = 'reports';
$breadcrumbs = [
    ['title' => 'Dashboard', 'url' => '/main/dashboard'],
    ['title' => 'Báo cáo', 'url' => '/main/reports'],
    ['title' => 'Báo cáo làm thêm giờ']
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
                    <h5 class="card-title mb-0">Báo cáo làm thêm giờ</h5>
                </div>
                
                <div class="card-body">
                    <!-- Date Range Filter -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <form method="GET" action="/main/reports/overtime" class="d-flex gap-2">
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
                                            <h6 class="card-title">Tổng giờ làm thêm</h6>
                                            <h3><?php echo $overtimeData['monthly_summary']['total_hours']; ?></h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="bx bx-time text-white" style="font-size: 2rem;"></i>
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
                                            <h6 class="card-title">Trung bình/ngày</h6>
                                            <h3><?php echo $overtimeData['monthly_summary']['average_hours_per_day']; ?>h</h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="bx bx-trending-up text-white" style="font-size: 2rem;"></i>
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
                                            <h6 class="card-title">Nhân viên làm thêm</h6>
                                            <h3><?php echo $overtimeData['monthly_summary']['total_employees']; ?></h3>
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
                                            <h6 class="card-title">Chi phí làm thêm</h6>
                                            <h3><?php echo number_format($overtimeData['monthly_summary']['overtime_cost']); ?>đ</h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="bx bx-money text-white" style="font-size: 2rem;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts -->
                    <div class="row">
                        <!-- Daily Overtime Chart -->
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title">Biểu đồ làm thêm giờ theo ngày</h6>
                                </div>
                                <div class="card-body">
                                    <div id="dailyOvertimeChart" style="height: 300px;"></div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Department Overtime Chart -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title">Làm thêm theo phòng ban</h6>
                                </div>
                                <div class="card-body">
                                    <div id="departmentOvertimeChart" style="height: 300px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Department Overtime Table -->
                    <div class="row mt-4">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title">Chi tiết làm thêm giờ theo phòng ban</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Phòng ban</th>
                                                    <th>Giờ làm thêm</th>
                                                    <th>Chi phí</th>
                                                    <th>Trạng thái</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($overtimeData['department_overtime'] as $dept): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($dept['department']); ?></td>
                                                        <td>
                                                            <span class="badge bg-primary"><?php echo $dept['hours']; ?>h</span>
                                                        </td>
                                                        <td>
                                                            <span class="text-success fw-bold"><?php echo number_format($dept['cost']); ?>đ</span>
                                                        </td>
                                                        <td>
                                                            <?php if ($dept['hours'] >= 80): ?>
                                                                <span class="badge bg-danger">Cao</span>
                                                            <?php elseif ($dept['hours'] >= 50): ?>
                                                                <span class="badge bg-warning">Trung bình</span>
                                                            <?php else: ?>
                                                                <span class="badge bg-success">Thấp</span>
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
    
    // Daily Overtime Chart
    var dailyOvertimeData = <?php echo json_encode($overtimeData['daily_overtime']); ?>;
    var dailyOvertimeOptions = {
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
    colors: ['#007bff'],
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

    var dailyOvertimeChart = new ApexCharts(document.querySelector("#dailyOvertimeChart"), dailyOvertimeOptions);
    dailyOvertimeChart.render();

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
include __DIR__ . '/../layouts/app.php';
?>
