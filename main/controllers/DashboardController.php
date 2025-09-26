<?php
require_once __DIR__ . '/../core/BaseController.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../core/Session.php';

/**
 * Controller xử lý dashboard
 */
class DashboardController extends BaseController
{
    private $userModel;
    protected $session;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
        $this->session = Session::getInstance();
    }

    /**
     * Hiển thị trang dashboard
     */
    public function index()
    {
        $this->checkAuth();
        
        // Lấy thông tin user hiện tại
        $userId = $this->session->getUserId();
        $user = $this->userModel->findWithDetails($userId);
        
        if (!$user) {
            Response::error('Không tìm thấy thông tin người dùng');
        }

        // Dashboard mới sử dụng layout trực tiếp
        include __DIR__ . '/../views/dashboard.php';
    }

    /**
     * Lấy thông tin chấm công hôm nay
     */
    private function getTodayAttendance($userId)
    {
        // Tạm thời trả về dữ liệu mẫu
        return [
            'check_in' => null,
            'check_out' => null,
            'status' => 'not_checked',
            'total_hours' => 0,
            'overtime_hours' => 0
        ];
    }

    /**
     * Lấy thống kê tuần
     */
    private function getWeekStats($userId)
    {
        // Tạm thời trả về dữ liệu mẫu
        return [
            'working_days' => 5,
            'present_days' => 4,
            'late_days' => 1,
            'absent_days' => 0,
            'total_hours' => 32,
            'overtime_hours' => 4
        ];
    }

    /**
     * Lấy thống kê tháng
     */
    private function getMonthStats($userId)
    {
        // Tạm thời trả về dữ liệu mẫu
        return [
            'working_days' => 22,
            'present_days' => 20,
            'late_days' => 2,
            'absent_days' => 0,
            'total_hours' => 160,
            'overtime_hours' => 16
        ];
    }

    /**
     * API lấy dữ liệu dashboard
     */
    public function getData()
    {
        $this->checkAuth();
        
        $userId = $this->session->getUserId();
        
        $data = [
            'user' => $this->userModel->findWithDetails($userId),
            'today_attendance' => $this->getTodayAttendance($userId),
            'week_stats' => $this->getWeekStats($userId),
            'month_stats' => $this->getMonthStats($userId)
        ];

        Response::success($data);
    }
}
