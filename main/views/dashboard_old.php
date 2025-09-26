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
                    <h4 class="card-title mb-0">📊 Dashboard Tổng Quan</h4>
                    <p class="card-subtitle text-muted">Thống kê chi tiết hệ thống chấm công</p>
                </div>
            </div>
        </div>
    </div>

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
            <!-- Menu -->
            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo">
                    <a href="/main/" class="app-brand-link">
                        <span class="app-brand-logo demo">
                            <span class="text-primary">
                                <svg width="25" viewBox="0 0 25 42" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <defs>
                                        <path d="M13.7918663,0.358365126 L3.39788168,7.44174259 C0.566865006,9.69408886 -0.379795268,12.4788597 0.557900856,15.7960551 C0.68998853,16.2305145 1.09562888,17.7872135 3.12357076,19.2293357 C3.8146334,19.7207684 5.32369333,20.3834223 7.65075054,21.2172976 L7.59773219,21.2525164 L2.63468769,24.5493413 C0.445452254,26.3002124 0.0884951797,28.5083815 1.56381646,31.1738486 C2.83770406,32.8170431 5.20850219,33.2640127 7.09180128,32.5391577 C8.347334,32.0559211 11.4559176,30.0011079 16.4175519,26.3747182 C18.0338572,24.4997857 18.6973423,22.4544883 18.4080071,20.2388261 C17.963753,17.5346866 16.1776345,15.5799961 13.0496516,14.3747546 L10.9194936,13.4715819 L18.6192054,7.984237 L13.7918663,0.358365126 Z" id="path-1"></path>
                                        <path d="M5.47320593,6.00457225 C4.05321814,8.216144 4.36334763,10.0722806 6.40359441,11.5729822 C8.61520715,12.571656 10.0999176,13.2171421 10.8577257,13.5094407 L15.5088241,14.433041 L18.6192054,7.984237 C15.5364148,3.11535317 13.9273018,0.573395879 13.7918663,0.358365126 C13.5790555,0.511491653 10.8061687,2.3935607 5.47320593,6.00457225 Z" id="path-3"></path>
                                        <path d="M7.50063644,21.2294429 L12.3234468,23.3159332 C14.1688022,24.7579751 14.397098,26.4880487 13.008334,28.506154 C11.6195701,30.5242593 10.3099883,31.790241 9.07958868,32.3040991 C5.78142938,33.4346997 4.13234973,34 4.13234973,34 C4.13234973,34 2.75489982,33.0538207 2.37032616e-14,31.1614621 C-0.55822714,27.8186216 -0.55822714,26.0572515 -4.05231404e-15,25.8773518 C0.83734071,25.6075023 2.77988457,22.8248993 3.3049379,22.52991 C3.65497346,22.3332504 5.05353963,21.8997614 7.50063644,21.2294429 Z" id="path-4"></path>
                                        <path d="M20.6,7.13333333 L25.6,13.8 C26.2627417,14.6836556 26.0836556,15.9372583 25.2,16.6 C24.8538077,16.8596443 24.4327404,17 24,17 L14,17 C12.8954305,17 12,16.1045695 12,15 C12,14.5672596 12.1403557,14.1461923 12.4,13.8 L17.4,7.13333333 C18.0627417,6.24967773 19.3163444,6.07059163 20.2,6.73333333 C20.3516113,6.84704183 20.4862915,6.981722 20.6,7.13333333 Z" id="path-5"></path>
                                    </defs>
                                    <g id="g-app-brand" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                        <g id="Brand-Logo" transform="translate(-27.000000, -15.000000)">
                                            <g id="Icon" transform="translate(27.000000, 15.000000)">
                                                <g id="Mask" transform="translate(0.000000, 8.000000)">
                                                    <mask id="mask-2" fill="white">
                                                        <use xlink:href="#path-1"></use>
                                                    </mask>
                                                    <use fill="currentColor" xlink:href="#path-1"></use>
                                                    <g id="Path-3" mask="url(#mask-2)">
                                                        <use fill="currentColor" xlink:href="#path-3"></use>
                                                        <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-3"></use>
                                                    </g>
                                                    <g id="Path-4" mask="url(#mask-2)">
                                                        <use fill="currentColor" xlink:href="#path-4"></use>
                                                        <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-4"></use>
                                                    </g>
                                                </g>
                                                <g id="Triangle" transform="translate(19.000000, 11.000000) rotate(-300.000000) translate(-19.000000, -11.000000)">
                                                    <use fill="currentColor" xlink:href="#path-5"></use>
                                                    <use fill-opacity="0.2" fill="#FFFFFF" xlink:href="#path-5"></use>
                                                </g>
                                            </g>
                                        </g>
                                    </g>
                                </svg>
                            </span>
                        </span>
                        <span class="app-brand-text demo text-heading fw-bold">Chấm công</span>
                    </a>
                </div>

                <div class="menu-inner-shadow"></div>

                <ul class="menu-inner py-1">
                    <!-- Dashboard -->
                    <li class="menu-item active">
                        <a href="/main/dashboard" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-home-circle"></i>
                            <div data-i18n="Dashboard">Dashboard</div>
                        </a>
                    </li>

                    <!-- Chấm công -->
                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-time"></i>
                            <div data-i18n="Chấm công">Chấm công</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="/main/attendance/check-in" class="menu-link">
                                    <div data-i18n="Chấm vào">Chấm vào</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="/main/attendance/check-out" class="menu-link">
                                    <div data-i18n="Chấm ra">Chấm ra</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="/main/attendance/history" class="menu-link">
                                    <div data-i18n="Lịch sử chấm công">Lịch sử chấm công</div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Quản lý -->
                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-user"></i>
                            <div data-i18n="Quản lý">Quản lý</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="/main/employees" class="menu-link">
                                    <div data-i18n="Nhân viên">Nhân viên</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="/main/departments" class="menu-link">
                                    <div data-i18n="Phòng ban">Phòng ban</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="/main/roles" class="menu-link">
                                    <div data-i18n="Vai trò">Vai trò</div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Báo cáo -->
                    <li class="menu-item">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-chart"></i>
                            <div data-i18n="Báo cáo">Báo cáo</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item">
                                <a href="/main/reports/attendance" class="menu-link">
                                    <div data-i18n="Báo cáo chấm công">Báo cáo chấm công</div>
                                </a>
                            </li>
                            <li class="menu-item">
                                <a href="/main/reports/overtime" class="menu-link">
                                    <div data-i18n="Báo cáo OT">Báo cáo OT</div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Cài đặt -->
                    <li class="menu-item">
                        <a href="/main/settings" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-cog"></i>
                            <div data-i18n="Cài đặt">Cài đặt</div>
                        </a>
                    </li>
                </ul>
            </aside>
            <!-- / Menu -->

            <!-- Layout container -->
            <div class="layout-page">
                <!-- Navbar -->
                <nav class="layout-navbar container-xxl navbar navbar-expand-xl navbar-detached" id="layout-navbar">
                    <div class="layout-menu-toggle navbar-nav align-items-xl-center me-3 me-xl-0 d-xl-none">
                        <a class="nav-item nav-link px-0 me-xl-4" href="javascript:void(0)">
                            <i class="bx bx-menu bx-sm"></i>
                        </a>
                    </div>

                    <div class="navbar-nav-right d-flex align-items-center" id="navbar-collapse">
                        <!-- Search -->
                        <div class="navbar-nav align-items-center">
                            <div class="nav-item d-flex align-items-center">
                                <i class="bx bx-search fs-4 lh-0"></i>
                                <input type="text" class="form-control border-0 shadow-none" placeholder="Tìm kiếm..." aria-label="Search..." />
                            </div>
                        </div>
                        <!-- /Search -->

                        <ul class="navbar-nav flex-row align-items-center ms-auto">
                            <!-- User -->
                            <li class="nav-item navbar-dropdown dropdown user-dropdown">
                                <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown">
                                    <div class="avatar avatar-online">
                                        <img src="<?php echo $assetsPath; ?>img/avatars/1.png" alt class="w-px-40 h-auto rounded-circle" />
                                    </div>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="/main/profile">
                                            <div class="d-flex">
                                                <div class="flex-shrink-0 me-3">
                                                    <div class="avatar avatar-online">
                                                        <img src="<?php echo $assetsPath; ?>img/avatars/1.png" alt class="w-px-40 h-auto rounded-circle" />
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-0"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></h6>
                                                    <small class="text-muted"><?php echo htmlspecialchars($user['role_name'] ?? 'Nhân viên'); ?></small>
                                                </div>
                                            </div>
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider" /></li>
                                    <li>
                                        <a class="dropdown-item" href="/main/profile">
                                            <i class="bx bx-user me-2"></i>
                                            <span class="align-middle">Hồ sơ cá nhân</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a class="dropdown-item" href="/main/settings">
                                            <i class="bx bx-cog me-2"></i>
                                            <span class="align-middle">Cài đặt</span>
                                        </a>
                                    </li>
                                    <li><hr class="dropdown-divider" /></li>
                                    <li>
                                        <a class="dropdown-item" href="/main/logout">
                                            <i class="bx bx-power-off me-2"></i>
                                            <span class="align-middle">Đăng xuất</span>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            <!--/ User -->
                        </ul>
                    </div>
                </nav>
                <!-- / Navbar -->

                <!-- Content wrapper -->
                <div class="content-wrapper">
                    <!-- Content -->
                    <div class="container-xxl flex-grow-1 container-p-y">
                        <div class="row">
                            <div class="col-lg-8 mb-4 order-0">
                                <div class="card">
                                    <div class="d-flex align-items-end row">
                                        <div class="col-sm-7">
                                            <div class="card-body">
                                                <h5 class="card-title text-primary">Chào mừng trở lại! 🎉</h5>
                                                <p class="mb-4">
                                                    Chào mừng <span class="fw-bold"><?php echo htmlspecialchars($user['first_name'] . ' ' . $user['last_name']); ?></span> đến với hệ thống chấm công.
                                                    Hôm nay là <span class="fw-bold"><?php echo date('d/m/Y'); ?></span>
                                                </p>
                                                <a href="/main/attendance/check-in" class="btn btn-sm btn-outline-primary">Chấm công ngay</a>
                                            </div>
                                        </div>
                                        <div class="col-sm-5 text-center text-sm-left">
                                            <div class="card-body pb-0 px-0 px-md-4">
                                                <img src="../assets/img/illustrations/man-with-laptop-light.png" height="140" alt="View Badge User" data-app-dark-img="illustrations/man-with-laptop-dark.png" data-app-light-img="illustrations/man-with-laptop-light.png" />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 order-1">
                                <div class="row">
                                    <div class="col-lg-6 col-md-12 col-6 mb-4">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="card-title d-flex align-items-start justify-content-between">
                                                    <div class="avatar flex-shrink-0">
                                                        <i class="bx bx-time text-primary"></i>
                                                    </div>
                                                </div>
                                                <span class="fw-semibold d-block mb-1">Giờ vào</span>
                                                <h3 class="card-title mb-2" id="checkInTime">--:--</h3>
                                                <small class="text-success fw-semibold">Chưa chấm vào</small>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-lg-6 col-md-12 col-6 mb-4">
                                        <div class="card">
                                            <div class="card-body">
                                                <div class="card-title d-flex align-items-start justify-content-between">
                                                    <div class="avatar flex-shrink-0">
                                                        <i class="bx bx-time-five text-warning"></i>
                                                    </div>
                                                </div>
                                                <span class="fw-semibold d-block mb-1">Giờ ra</span>
                                                <h3 class="card-title mb-2" id="checkOutTime">--:--</h3>
                                                <small class="text-muted fw-semibold">Chưa chấm ra</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <!-- Thống kê chấm công -->
                            <div class="col-md-6 col-lg-4 col-xl-4 order-0 mb-4">
                                <div class="card h-100">
                                    <div class="card-header d-flex align-items-center justify-content-between pb-0">
                                        <div class="card-title mb-0">
                                            <h5 class="m-0 me-2">Thống kê tuần này</h5>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="d-flex justify-content-between align-items-center mb-3">
                                            <div class="d-flex flex-column align-items-center gap-1">
                                                <h2 class="mb-2">5</h2>
                                                <span>Ngày làm việc</span>
                                            </div>
                                            <div id="orderStatisticsChart"></div>
                                        </div>
                                        <ul class="p-0 m-0">
                                            <li class="d-flex mb-4 pb-1">
                                                <div class="avatar flex-shrink-0 me-3">
                                                    <span class="avatar-initial rounded bg-label-primary">
                                                        <i class="bx bx-check text-primary"></i>
                                                    </span>
                                                </div>
                                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                    <div class="me-2">
                                                        <h6 class="mb-0">Đúng giờ</h6>
                                                        <small class="text-muted">4 ngày</small>
                                                    </div>
                                                    <div class="user-progress">
                                                        <small class="fw-semibold">80%</small>
                                                    </div>
                                                </div>
                                            </li>
                                            <li class="d-flex mb-4 pb-1">
                                                <div class="avatar flex-shrink-0 me-3">
                                                    <span class="avatar-initial rounded bg-label-warning">
                                                        <i class="bx bx-time text-warning"></i>
                                                    </span>
                                                </div>
                                                <div class="d-flex w-100 flex-wrap align-items-center justify-content-between gap-2">
                                                    <div class="me-2">
                                                        <h6 class="mb-0">Đi muộn</h6>
                                                        <small class="text-muted">1 ngày</small>
                                                    </div>
                                                    <div class="user-progress">
                                                        <small class="fw-semibold">20%</small>
                                                    </div>
                                                </div>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            <!-- Lịch chấm công -->
                            <div class="col-md-6 col-lg-8 order-1 mb-4">
                                <div class="card">
                                    <div class="row row-bordered g-0">
                                        <div class="col-md-8">
                                            <h5 class="card-header m-0 me-2 pb-3">Lịch chấm công</h5>
                                            <div id="totalRevenueChart" class="px-2"></div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="card-body">
                                                <div class="text-center">
                                                    <div class="dropdown">
                                                        <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" id="growthReportId" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                                            2024
                                                        </button>
                                                        <div class="dropdown-menu dropdown-menu-end" aria-labelledby="growthReportId">
                                                            <a class="dropdown-item" href="javascript:void(0);">2021</a>
                                                            <a class="dropdown-item" href="javascript:void(0);">2022</a>
                                                            <a class="dropdown-item" href="javascript:void(0);">2023</a>
                                                            <a class="dropdown-item" href="javascript:void(0);">2024</a>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div id="growthChart"></div>
                                            <div class="text-center fw-semibold pt-3 mb-2">62% Company Growth</div>

                                            <div class="d-flex px-xxl-4 px-lg-2 p-4 gap-xxl-3 gap-lg-1 gap-3 justify-content-between">
                                                <div class="d-flex">
                                                    <div class="me-2">
                                                        <span class="badge bg-label-primary p-2"><i class="bx bx-dollar text-primary"></i></span>
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <small>2022</small>
                                                        <h6 class="mb-0">$32.5k</h6>
                                                    </div>
                                                </div>
                                                <div class="d-flex">
                                                    <div class="me-2">
                                                        <span class="badge bg-label-info p-2"><i class="bx bx-dollar text-info"></i></span>
                                                    </div>
                                                    <div class="d-flex flex-column">
                                                        <small>2021</small>
                                                        <h6 class="mb-0">$41.2k</h6>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- / Content -->

                    <!-- Footer -->
                    <footer class="content-footer footer bg-footer-theme">
                        <div class="container-xxl d-flex flex-wrap justify-content-between py-2 flex-md-row flex-column">
                            <div class="mb-2 mb-md-0">
                                © <script>document.write(new Date().getFullYear())</script>, made with ❤️ by <a href="https://themeselection.com" target="_blank" class="footer-link fw-bolder">ThemeSelection</a>
                            </div>
                            <div>
                                <a href="https://themeselection.com/license/" class="footer-link me-4" target="_blank">License</a>
                                <a href="https://themeselection.com/" target="_blank" class="footer-link me-4">Documentation</a>
                                <a href="https://github.com/themeselection/sneat-html-admin-template-free/issues" target="_blank" class="footer-link d-none d-sm-inline-block">Support</a>
                            </div>
                        </div>
                    </footer>
                    <!-- / Footer -->

                    <div class="content-backdrop fade"></div>
                </div>
                <!-- Content wrapper -->
            </div>
            <!-- / Layout page -->
        </div>

        <!-- Overlay -->
        <div class="layout-overlay layout-menu-toggle"></div>
    </div>
    <!-- / Layout wrapper -->

    <!-- Core JS -->
    <script src="../assets/vendor/libs/jquery/jquery.js"></script>
    <script src="../assets/vendor/libs/popper/popper.js"></script>
    <script src="../assets/vendor/js/bootstrap.js"></script>
    <script src="../assets/vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="../assets/vendor/js/menu.js"></script>
    <script src="../assets/js/main.js"></script>

    <script>
        $(document).ready(function() {
            // Cập nhật thời gian hiện tại
            function updateCurrentTime() {
                const now = new Date();
                const timeString = now.toLocaleTimeString('vi-VN', { 
                    hour: '2-digit', 
                    minute: '2-digit',
                    hour12: false 
                });
                
                // Giả sử chưa chấm vào/ra
                $('#checkInTime').text('--:--');
                $('#checkOutTime').text('--:--');
            }
            
            updateCurrentTime();
            setInterval(updateCurrentTime, 1000);
        });
    </script>
</body>
</html>
