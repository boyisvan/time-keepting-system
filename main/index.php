<?php
/**
 * File chính của hệ thống chấm công
 */

// Load cấu hình
require_once __DIR__ . '/config/env.php';
require_once __DIR__ . '/config/constants.php';
require_once __DIR__ . '/core/Database.php';
require_once __DIR__ . '/core/BaseModel.php';
require_once __DIR__ . '/core/BaseController.php';
require_once __DIR__ . '/core/Request.php';
require_once __DIR__ . '/core/Response.php';
require_once __DIR__ . '/core/Session.php';

// Load models
require_once __DIR__ . '/models/User.php';
require_once __DIR__ . '/models/Role.php';
require_once __DIR__ . '/models/Department.php';
require_once __DIR__ . '/models/Position.php';

// Load controllers
require_once __DIR__ . '/controllers/AuthController.php';
require_once __DIR__ . '/controllers/DashboardController.php';
require_once __DIR__ . '/controllers/EmployeeController.php';
require_once __DIR__ . '/controllers/DepartmentController.php';
require_once __DIR__ . '/controllers/RoleController.php';
require_once __DIR__ . '/controllers/ReportsController.php';
require_once __DIR__ . '/controllers/SettingsController.php';

// Load helpers
require_once __DIR__ . '/helpers/ViewHelper.php';

// Khởi tạo session
session_start();

// Xử lý routing đơn giản
$request = Request::getInstance();
$uri = $request->uri();
$method = $request->method();

// Loại bỏ query string
$uri = strtok($uri, '?');

// Routing
switch ($uri) {
    case '/':
    case '/main':
    case '/main/':
    case '/login':
        $controller = new AuthController();
        if ($method === 'POST') {
            $controller->doLogin();
        } else {
            $controller->login();
        }
        break;
        
    case '/logout':
        $controller = new AuthController();
        $controller->logout();
        break;
        
    case '/change-password':
        $controller = new AuthController();
        if ($method === 'POST') {
            $controller->doChangePassword();
        } else {
            $controller->changePassword();
        }
        break;
        
    case '/forgot-password':
        $controller = new AuthController();
        if ($method === 'POST') {
            $controller->doForgotPassword();
        } else {
            $controller->forgotPassword();
        }
        break;
        
    case '/dashboard':
    case '/main/dashboard':
        $controller = new DashboardController();
        $controller->index();
        break;
        
    case '/attendance/check-in':
        ViewHelper::render('attendance/check-in');
        break;
        
    case '/attendance/check-out':
        ViewHelper::render('attendance/check-out');
        break;
        
    case '/attendance/history':
        ViewHelper::render('attendance/history');
        break;
        
    case '/employees':
    case '/main/employees':
        $controller = new EmployeeController();
        if ($request->method() === 'POST') {
            $controller->store();
        } else {
            $controller->index();
        }
        break;
        
    case '/employees/create':
    case '/main/employees/create':
        $controller = new EmployeeController();
        $controller->create();
        break;
        
    case '/employees/store':
    case '/main/employees/store':
        if ($request->method() === 'POST') {
            $controller = new EmployeeController();
            $controller->store();
        }
        break;
        
    case (preg_match('/^\/employees\/(\d+)$/', $uri, $matches) ? true : false):
    case (preg_match('/^\/main\/employees\/(\d+)$/', $uri, $matches) ? true : false):
        $id = $matches[1];
        $controller = new EmployeeController();
        if ($request->method() === 'GET') {
            $controller->show($id);
        } elseif ($request->method() === 'PUT' || ($request->method() === 'POST' && $request->get('_method') === 'PUT')) {
            $controller->update($id);
        } elseif ($request->method() === 'DELETE' || ($request->method() === 'POST' && $request->get('_method') === 'DELETE')) {
            $controller->delete($id);
        }
        break;
        
    case '/employees/search':
    case '/main/employees/search':
        $controller = new EmployeeController();
        $controller->search();
        break;
        
    case '/employees/stats':
    case '/main/employees/stats':
        $controller = new EmployeeController();
        $controller->stats();
        break;
        
    case '/departments':
    case '/main/departments':
        $controller = new DepartmentController();
        if ($request->method() === 'POST') {
            $controller->store();
        } else {
            $controller->index();
        }
        break;
        
    case '/departments/create':
    case '/main/departments/create':
        $controller = new DepartmentController();
        $controller->create();
        break;
        
    case '/departments/store':
    case '/main/departments/store':
        if ($request->method() === 'POST') {
            $controller = new DepartmentController();
            $controller->store();
        }
        break;
        
    case (preg_match('/^\/departments\/(\d+)\/edit$/', $uri, $matches) ? true : false):
    case (preg_match('/^\/main\/departments\/(\d+)\/edit$/', $uri, $matches) ? true : false):
        $id = $matches[1];
        $controller = new DepartmentController();
        $controller->edit($id);
        break;
        
    // Role management routes
    case '/roles':
    case '/main/roles':
        $controller = new RoleController();
        $controller->index();
        break;
        
    case '/roles/create':
    case '/main/roles/create':
        $controller = new RoleController();
        $controller->create();
        break;
        
    case '/roles/store':
    case '/main/roles/store':
        $controller = new RoleController();
        $controller->store();
        break;
        
    case (preg_match('/^\/roles\/(\d+)$/', $uri, $matches) ? true : false):
    case (preg_match('/^\/main\/roles\/(\d+)$/', $uri, $matches) ? true : false):
        $id = $matches[1];
        $controller = new RoleController();
        if ($request->method() === 'GET') {
            $controller->show($id);
        } elseif ($request->method() === 'PUT' || ($request->method() === 'POST' && $request->get('_method') === 'PUT')) {
            $controller->update($id);
        } elseif ($request->method() === 'DELETE' || ($request->method() === 'POST' && $request->get('_method') === 'DELETE')) {
            $controller->delete($id);
        }
        break;
        
    case (preg_match('/^\/roles\/(\d+)\/edit$/', $uri, $matches) ? true : false):
    case (preg_match('/^\/main\/roles\/(\d+)\/edit$/', $uri, $matches) ? true : false):
        $id = $matches[1];
        $controller = new RoleController();
        $controller->edit($id);
        break;
        
    case '/roles/search':
    case '/main/roles/search':
        $controller = new RoleController();
        $controller->search();
        break;
        
    case '/roles/stats':
    case '/main/roles/stats':
        $controller = new RoleController();
        $controller->stats();
        break;
        
    // Reports routes - redirect to attendance
    case '/reports':
    case '/main/reports':
        header('Location: /main/reports/attendance');
        exit;
        break;
        
    case '/reports/attendance':
    case '/main/reports/attendance':
        $controller = new ReportsController();
        $controller->attendance();
        break;
        
    case '/reports/overtime':
    case '/main/reports/overtime':
        $controller = new ReportsController();
        $controller->overtime();
        break;
        
    case '/reports/employee':
    case '/main/reports/employee':
        $controller = new ReportsController();
        $controller->employee();
        break;
        
    case '/reports/department':
    case '/main/reports/department':
        $controller = new ReportsController();
        $controller->department();
        break;
        
    case (preg_match('/^\/departments\/(\d+)$/', $uri, $matches) ? true : false):
    case (preg_match('/^\/main\/departments\/(\d+)$/', $uri, $matches) ? true : false):
        $id = $matches[1];
        $controller = new DepartmentController();
        if ($request->method() === 'GET') {
            $controller->show($id);
        } elseif ($request->method() === 'PUT' || ($request->method() === 'POST' && $request->get('_method') === 'PUT')) {
            $controller->update($id);
        } elseif ($request->method() === 'DELETE' || ($request->method() === 'POST' && $request->get('_method') === 'DELETE')) {
            $controller->delete($id);
        }
        break;
        
    case '/departments/search':
    case '/main/departments/search':
        $controller = new DepartmentController();
        $controller->search();
        break;
        
    case '/departments/stats':
    case '/main/departments/stats':
        $controller = new DepartmentController();
        $controller->stats();
        break;
        
    case '/settings':
    case '/main/settings':
        $controller = new SettingsController();
        if ($request->method() === 'GET') {
            $controller->index();
        } elseif ($request->method() === 'POST') {
            $controller->save();
        }
        break;
        
    case '/profile':
        ViewHelper::render('profile/index');
        break;
        
    case '/403':
        http_response_code(403);
        echo '<h1>403 - Forbidden</h1>';
        echo '<p>Bạn không có quyền truy cập trang này.</p>';
        echo '<a href="/main/dashboard">Quay về trang chủ</a>';
        break;
        
    default:
        // Kiểm tra nếu là API request
        if (strpos($uri, '/api/') === 0) {
            Response::error('API endpoint không tồn tại', 404);
        } else {
            Response::error('Trang không tồn tại', 404);
        }
        break;
}
