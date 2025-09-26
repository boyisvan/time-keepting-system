<?php
// Set page data
$pageTitle = 'Báo cáo nhân viên - Hệ thống chấm công';
$pageDescription = 'Báo cáo thống kê nhân viên';
$currentPage = 'reports-employee';
$breadcrumbs = [
    ['title' => 'Dashboard', 'url' => '/main/dashboard'],
    ['title' => 'Báo cáo', 'url' => '/main/reports'],
    ['title' => 'Báo cáo nhân viên']
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
                    <h5 class="card-title mb-0">Báo cáo nhân viên</h5>
                </div>
                
                <div class="card-body">
                    <!-- Summary Cards -->
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <div class="card bg-primary text-white">
                                <div class="card-body">
                                    <div class="d-flex justify-content-between">
                                        <div>
                                            <h6 class="card-title">Tổng nhân viên</h6>
                                            <h3><?php echo $employeeStats['total_employees']; ?></h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="bx bx-user text-white" style="font-size: 2rem;"></i>
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
                                            <h3><?php echo $employeeStats['active_employees']; ?></h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="bx bx-user-check text-white" style="font-size: 2rem;"></i>
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
                                            <h6 class="card-title">Không hoạt động</h6>
                                            <h3><?php echo $employeeStats['inactive_employees']; ?></h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="bx bx-user-x text-white" style="font-size: 2rem;"></i>
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
                                            <h6 class="card-title">Nhân viên mới</h6>
                                            <h3><?php echo $employeeStats['new_employees_this_month']; ?></h3>
                                        </div>
                                        <div class="align-self-center">
                                            <i class="bx bx-user-plus text-white" style="font-size: 2rem;"></i>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Charts -->
                    <div class="row">
                        <!-- Department Distribution Chart -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title">Phân bố nhân viên theo phòng ban</h6>
                                </div>
                                <div class="card-body">
                                    <div id="departmentChart" style="height: 300px;"></div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Position Distribution Chart -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title">Phân bố nhân viên theo chức vụ</h6>
                                </div>
                                <div class="card-body">
                                    <div id="positionChart" style="height: 300px;"></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Department Table -->
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title">Chi tiết theo phòng ban</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Phòng ban</th>
                                                    <th>Số lượng</th>
                                                    <th>Tỷ lệ</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($employeeStats['departments'] as $dept): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($dept['name']); ?></td>
                                                        <td>
                                                            <span class="badge bg-primary"><?php echo $dept['count']; ?></span>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="progress me-3" style="width: 100px; height: 8px;">
                                                                    <div class="progress-bar" role="progressbar" 
                                                                         style="width: <?php echo $dept['percentage']; ?>%"
                                                                         aria-valuenow="<?php echo $dept['percentage']; ?>" 
                                                                         aria-valuemin="0" aria-valuemax="100">
                                                                    </div>
                                                                </div>
                                                                <span><?php echo $dept['percentage']; ?>%</span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Position Table -->
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h6 class="card-title">Chi tiết theo chức vụ</h6>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Chức vụ</th>
                                                    <th>Số lượng</th>
                                                    <th>Tỷ lệ</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php foreach ($employeeStats['positions'] as $pos): ?>
                                                    <tr>
                                                        <td><?php echo htmlspecialchars($pos['name']); ?></td>
                                                        <td>
                                                            <span class="badge bg-success"><?php echo $pos['count']; ?></span>
                                                        </td>
                                                        <td>
                                                            <div class="d-flex align-items-center">
                                                                <div class="progress me-3" style="width: 100px; height: 8px;">
                                                                    <div class="progress-bar bg-success" role="progressbar" 
                                                                         style="width: <?php echo $pos['percentage']; ?>%"
                                                                         aria-valuenow="<?php echo $pos['percentage']; ?>" 
                                                                         aria-valuemin="0" aria-valuemax="100">
                                                                    </div>
                                                                </div>
                                                                <span><?php echo $pos['percentage']; ?>%</span>
                                                            </div>
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
    
    // Department Distribution Chart
    var departmentData = <?php echo json_encode($employeeStats['departments']); ?>;
    var departmentOptions = {
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

    var departmentChart = new ApexCharts(document.querySelector("#departmentChart"), departmentOptions);
    departmentChart.render();

    // Position Distribution Chart
    var positionData = <?php echo json_encode($employeeStats['positions']); ?>;
    var positionOptions = {
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

    var positionChart = new ApexCharts(document.querySelector("#positionChart"), positionOptions);
    positionChart.render();
});
</script>

<?php
// Get content and include layout
$content = ob_get_clean();
include __DIR__ . '/../layouts/app.php';
?>
