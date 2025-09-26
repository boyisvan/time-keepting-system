<?php

require_once __DIR__ . '/../core/BaseController.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Department.php';

class ReportsController extends BaseController
{
    private $userModel;
    private $departmentModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
        $this->departmentModel = new Department();
    }

    public function index()
    {
        $this->checkPermission(PERMISSION_EMPLOYEE);
        
        // Get basic stats
        $stats = [
            'total_employees' => $this->userModel->count(),
            'active_employees' => $this->userModel->count('', 'active'),
            'total_departments' => $this->departmentModel->count(),
            'active_departments' => $this->departmentModel->count('', 'active')
        ];
        
        include __DIR__ . '/../views/reports/index.php';
    }

    public function attendance()
    {
        $this->checkPermission(PERMISSION_EMPLOYEE);
        
        // Get date range
        $startDate = $_GET['start_date'] ?? date('Y-m-01'); // First day of current month
        $endDate = $_GET['end_date'] ?? date('Y-m-t'); // Last day of current month
        
        // Mock attendance data for demonstration
        $attendanceData = $this->getAttendanceData($startDate, $endDate);
        
        include __DIR__ . '/../views/reports/attendance.php';
    }

    public function overtime()
    {
        $this->checkPermission(PERMISSION_EMPLOYEE);
        
        // Get date range
        $startDate = $_GET['start_date'] ?? date('Y-m-01');
        $endDate = $_GET['end_date'] ?? date('Y-m-t');
        
        // Mock overtime data for demonstration
        $overtimeData = $this->getOvertimeData($startDate, $endDate);
        
        include __DIR__ . '/../views/reports/overtime.php';
    }

    public function employee()
    {
        $this->checkPermission(PERMISSION_EMPLOYEE);
        
        // Get employee statistics
        $employeeStats = $this->getEmployeeStats();
        
        include __DIR__ . '/../views/reports/employee.php';
    }

    public function department()
    {
        $this->checkPermission(PERMISSION_EMPLOYEE);
        
        // Get department statistics
        $departmentStats = $this->getDepartmentStats();
        
        include __DIR__ . '/../views/reports/department.php';
    }

    private function getAttendanceData($startDate, $endDate)
    {
        // Mock data - in real application, this would come from attendance table
        $data = [
            'daily_attendance' => [
                ['date' => '2025-01-01', 'present' => 45, 'absent' => 5, 'late' => 3],
                ['date' => '2025-01-02', 'present' => 48, 'absent' => 2, 'late' => 1],
                ['date' => '2025-01-03', 'present' => 46, 'absent' => 4, 'late' => 2],
                ['date' => '2025-01-04', 'present' => 49, 'absent' => 1, 'late' => 0],
                ['date' => '2025-01-05', 'present' => 47, 'absent' => 3, 'late' => 1],
                ['date' => '2025-01-06', 'present' => 44, 'absent' => 6, 'late' => 2],
                ['date' => '2025-01-07', 'present' => 48, 'absent' => 2, 'late' => 1],
            ],
            'monthly_summary' => [
                'total_days' => 30,
                'average_attendance' => 95.2,
                'total_present' => 1425,
                'total_absent' => 72,
                'total_late' => 18
            ],
            'department_attendance' => [
                ['department' => 'Phòng Nhân sự', 'attendance_rate' => 96.5],
                ['department' => 'Phòng Kế toán', 'attendance_rate' => 94.2],
                ['department' => 'Phòng IT', 'attendance_rate' => 97.8],
                ['department' => 'Phòng Marketing', 'attendance_rate' => 93.1],
            ]
        ];
        
        return $data;
    }

    private function getOvertimeData($startDate, $endDate)
    {
        // Mock data - in real application, this would come from overtime table
        $data = [
            'daily_overtime' => [
                ['date' => '2025-01-01', 'hours' => 12.5, 'employees' => 8],
                ['date' => '2025-01-02', 'hours' => 8.0, 'employees' => 5],
                ['date' => '2025-01-03', 'hours' => 15.2, 'employees' => 10],
                ['date' => '2025-01-04', 'hours' => 6.5, 'employees' => 4],
                ['date' => '2025-01-05', 'hours' => 9.8, 'employees' => 6],
                ['date' => '2025-01-06', 'hours' => 11.3, 'employees' => 7],
                ['date' => '2025-01-07', 'hours' => 7.5, 'employees' => 5],
            ],
            'monthly_summary' => [
                'total_hours' => 245.8,
                'average_hours_per_day' => 8.2,
                'total_employees' => 50,
                'overtime_cost' => 2458000 // VND
            ],
            'department_overtime' => [
                ['department' => 'Phòng IT', 'hours' => 89.5, 'cost' => 895000],
                ['department' => 'Phòng Marketing', 'hours' => 67.2, 'cost' => 672000],
                ['department' => 'Phòng Nhân sự', 'hours' => 45.8, 'cost' => 458000],
                ['department' => 'Phòng Kế toán', 'hours' => 43.3, 'cost' => 433000],
            ]
        ];
        
        return $data;
    }

    private function getEmployeeStats()
    {
        // Mock data - in real application, this would come from database
        $data = [
            'total_employees' => 50,
            'active_employees' => 48,
            'inactive_employees' => 2,
            'new_employees_this_month' => 5,
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
        
        return $data;
    }

    private function getDepartmentStats()
    {
        // Mock data - in real application, this would come from database
        $data = [
            'total_departments' => 5,
            'active_departments' => 5,
            'departments' => [
                [
                    'name' => 'Phòng IT',
                    'employee_count' => 15,
                    'attendance_rate' => 97.8,
                    'overtime_hours' => 89.5,
                    'budget_used' => 85.2
                ],
                [
                    'name' => 'Phòng Marketing',
                    'employee_count' => 12,
                    'attendance_rate' => 93.1,
                    'overtime_hours' => 67.2,
                    'budget_used' => 92.5
                ],
                [
                    'name' => 'Phòng Nhân sự',
                    'employee_count' => 10,
                    'attendance_rate' => 96.5,
                    'overtime_hours' => 45.8,
                    'budget_used' => 78.9
                ],
                [
                    'name' => 'Phòng Kế toán',
                    'employee_count' => 8,
                    'attendance_rate' => 94.2,
                    'overtime_hours' => 43.3,
                    'budget_used' => 88.1
                ],
                [
                    'name' => 'Phòng Kinh doanh',
                    'employee_count' => 5,
                    'attendance_rate' => 95.6,
                    'overtime_hours' => 38.8,
                    'budget_used' => 91.3
                ]
            ]
        ];
        
        return $data;
    }
}
