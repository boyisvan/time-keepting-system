<!doctype html>
<html lang="vi" class="layout-wide customizer-hide" data-assets-path="../assets/" data-template="vertical-menu-template-free">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0" />
    <title><?php echo $pageTitle ?? 'Hệ thống chấm công'; ?></title>
    <meta name="description" content="<?php echo $pageDescription ?? 'Hệ thống quản lý chấm công nhân viên'; ?>" />
    
    <!-- Favicon -->
    <link rel="icon" type="image/x-icon" href="<?php echo $assetsPath; ?>img/favicon/favicon.ico" />
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com" />
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
    <link href="https://fonts.googleapis.com/css2?family=Public+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;1,300;1,400;1,500;1,600;1,700&display=swap" rel="stylesheet" />
    
    <link rel="stylesheet" href="<?php echo $assetsPath; ?>vendor/fonts/iconify-icons.css" />
    
    <!-- Core CSS -->
    <link rel="stylesheet" href="<?php echo $assetsPath; ?>vendor/css/core.css" />
    <link rel="stylesheet" href="<?php echo $assetsPath; ?>css/demo.css" />
    
    <!-- Vendors CSS -->
    <link rel="stylesheet" href="<?php echo $assetsPath; ?>vendor/libs/perfect-scrollbar/perfect-scrollbar.css" />
    
    <!-- Page CSS -->
    <?php if (isset($pageCSS)): ?>
        <?php foreach ($pageCSS as $css): ?>
            <?php if ($css === 'apex-charts'): ?>
                <link rel="stylesheet" href="<?php echo $assetsPath; ?>vendor/libs/apex-charts/apex-charts.css" />
            <?php elseif ($css === 'ui-modals'): ?>
                <!-- ui-modals.css không tồn tại, bỏ qua -->
            <?php else: ?>
                <link rel="stylesheet" href="<?php echo $assetsPath; ?>vendor/css/pages/<?php echo $css; ?>.css" />
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <!-- Helpers -->
    <script src="<?php echo $assetsPath; ?>vendor/js/helpers.js"></script>
    <script src="<?php echo $assetsPath; ?>js/config.js"></script>
    
    <!-- SweetAlert2 -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</head>

<body>
    <!-- Layout wrapper -->
    <div class="layout-wrapper layout-content-navbar">
        <div class="layout-container">
            <!-- Menu -->
            <aside id="layout-menu" class="layout-menu menu-vertical menu bg-menu-theme">
                <div class="app-brand demo">
                    <a href="/main/dashboard" class="app-brand-link">
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
                    <li class="menu-item <?php echo ($currentPage === 'dashboard') ? 'active' : ''; ?>">
                        <a href="/main/dashboard" class="menu-link">
                            <i class="menu-icon tf-icons bx bx-home-circle"></i>
                            <div data-i18n="Dashboard">Dashboard</div>
                        </a>
                    </li>

                    <!-- Chấm công -->
                    <li class="menu-item <?php echo (in_array($currentPage, ['check-in', 'check-out', 'attendance-history'])) ? 'active open' : ''; ?>">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-time"></i>
                            <div data-i18n="Chấm công">Chấm công</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item <?php echo ($currentPage === 'check-in') ? 'active' : ''; ?>">
                                <a href="/main/attendance/check-in" class="menu-link">
                                    <div data-i18n="Chấm vào">Chấm vào</div>
                                </a>
                            </li>
                            <li class="menu-item <?php echo ($currentPage === 'check-out') ? 'active' : ''; ?>">
                                <a href="/main/attendance/check-out" class="menu-link">
                                    <div data-i18n="Chấm ra">Chấm ra</div>
                                </a>
                            </li>
                            <li class="menu-item <?php echo ($currentPage === 'attendance-history') ? 'active' : ''; ?>">
                                <a href="/main/attendance/history" class="menu-link">
                                    <div data-i18n="Lịch sử chấm công">Lịch sử chấm công</div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Quản lý -->
                    <li class="menu-item <?php echo (in_array($currentPage, ['employees', 'departments', 'roles'])) ? 'active open' : ''; ?>">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-user"></i>
                            <div data-i18n="Quản lý">Quản lý</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item <?php echo ($currentPage === 'employees') ? 'active' : ''; ?>">
                                <a href="/main/employees" class="menu-link">
                                    <div data-i18n="Nhân viên">Nhân viên</div>
                                </a>
                            </li>
                            <li class="menu-item <?php echo ($currentPage === 'departments') ? 'active' : ''; ?>">
                                <a href="/main/departments" class="menu-link">
                                    <div data-i18n="Phòng ban">Phòng ban</div>
                                </a>
                            </li>
                            <li class="menu-item <?php echo ($currentPage === 'roles') ? 'active' : ''; ?>">
                                <a href="/main/roles" class="menu-link">
                                    <div data-i18n="Vai trò">Vai trò</div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Báo cáo -->
                    <li class="menu-item <?php echo (in_array($currentPage, ['reports', 'reports-attendance', 'reports-overtime', 'reports-employee', 'reports-department'])) ? 'active open' : ''; ?>">
                        <a href="javascript:void(0);" class="menu-link menu-toggle">
                            <i class="menu-icon tf-icons bx bx-chart"></i>
                            <div data-i18n="Báo cáo">Báo cáo</div>
                        </a>
                        <ul class="menu-sub">
                            <li class="menu-item <?php echo ($currentPage === 'reports-attendance') ? 'active' : ''; ?>">
                                <a href="/main/reports/attendance" class="menu-link">
                                    <div data-i18n="Báo cáo chấm công">Báo cáo chấm công</div>
                                </a>
                            </li>
                            <li class="menu-item <?php echo ($currentPage === 'reports-overtime') ? 'active' : ''; ?>">
                                <a href="/main/reports/overtime" class="menu-link">
                                    <div data-i18n="Báo cáo OT">Báo cáo OT</div>
                                </a>
                            </li>
                            <li class="menu-item <?php echo ($currentPage === 'reports-employee') ? 'active' : ''; ?>">
                                <a href="/main/reports/employee" class="menu-link">
                                    <div data-i18n="Báo cáo nhân viên">Báo cáo nhân viên</div>
                                </a>
                            </li>
                            <li class="menu-item <?php echo ($currentPage === 'reports-department') ? 'active' : ''; ?>">
                                <a href="/main/reports/department" class="menu-link">
                                    <div data-i18n="Báo cáo phòng ban">Báo cáo phòng ban</div>
                                </a>
                            </li>
                        </ul>
                    </li>

                    <!-- Cài đặt -->
                    <li class="menu-item <?php echo ($currentPage === 'settings') ? 'active' : ''; ?>">
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
                        <?php if (isset($breadcrumbs)): ?>
                            <!-- Breadcrumb -->
                            <nav aria-label="breadcrumb" class="mb-4">
                                <ol class="breadcrumb">
                                    <?php foreach ($breadcrumbs as $index => $crumb): ?>
                                        <?php if ($index === count($breadcrumbs) - 1): ?>
                                            <li class="breadcrumb-item active" aria-current="page"><?php echo $crumb['title']; ?></li>
                                        <?php else: ?>
                                            <li class="breadcrumb-item">
                                                <a href="<?php echo $crumb['url']; ?>"><?php echo $crumb['title']; ?></a>
                                            </li>
                                        <?php endif; ?>
                                    <?php endforeach; ?>
                                </ol>
                            </nav>
                        <?php endif; ?>

                        <!-- Page content -->
                        <?php echo $content; ?>
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
    <script src="<?php echo $assetsPath; ?>vendor/libs/jquery/jquery.js"></script>
    <script src="<?php echo $assetsPath; ?>vendor/libs/popper/popper.js"></script>
    <script src="<?php echo $assetsPath; ?>vendor/js/bootstrap.js"></script>
    <script src="<?php echo $assetsPath; ?>vendor/libs/perfect-scrollbar/perfect-scrollbar.js"></script>
    <script src="<?php echo $assetsPath; ?>vendor/js/menu.js"></script>
    <script src="<?php echo $assetsPath; ?>js/main.js"></script>
    
    <!-- ApexCharts - Load before custom scripts -->
    <script src="<?php echo $assetsPath; ?>vendor/libs/apex-charts/apexcharts.js"></script>
    
    <!-- Page JS -->
    <?php if (isset($pageJS)): ?>
        <?php foreach ($pageJS as $js): ?>
            <?php if ($js === 'apex-charts'): ?>
                <!-- ApexCharts already loaded above -->
            <?php else: ?>
                <script src="<?php echo $assetsPath; ?>js/<?php echo $js; ?>.js"></script>
            <?php endif; ?>
        <?php endforeach; ?>
    <?php endif; ?>

    <?php if (isset($customJS)): ?>
        <script>
            // Wait for ApexCharts to load
            if (typeof ApexCharts === 'undefined') {
                console.error('ApexCharts not loaded');
            }
            <?php echo $customJS; ?>
        </script>
    <?php endif; ?>
</body>
</html>
