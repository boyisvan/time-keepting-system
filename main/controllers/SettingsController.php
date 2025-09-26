<?php
require_once __DIR__ . '/../core/BaseController.php';
require_once __DIR__ . '/../core/Session.php';

/**
 * Controller xử lý cài đặt hệ thống
 */
class SettingsController extends BaseController
{
    private $settingsFile;
    protected $session;

    public function __construct()
    {
        parent::__construct();
        $this->session = Session::getInstance();
        $this->settingsFile = __DIR__ . '/../config/settings.json';
    }

    /**
     * Hiển thị trang cài đặt
     */
    public function index()
    {
        // $this->checkAuth(); // Tạm thời comment để test
        
        $settings = $this->getSettings();
        
        // Pass settings to view
        $GLOBALS['settings'] = $settings;
        
        // Dashboard mới sử dụng layout trực tiếp
        include __DIR__ . '/../views/settings/index.php';
    }

    /**
     * Lưu cài đặt
     */
    public function save()
    {
        // $this->checkAuth(); // Tạm thời comment để test
        
        if ($this->request->method() !== 'POST') {
            Response::error('Method not allowed');
        }

        $settings = [
            'company_name' => $this->request->get('company_name'),
            'timezone' => $this->request->get('timezone'),
            'tolerance_minutes' => (int)$this->request->get('tolerance_minutes'),
            'standard_hours' => (int)$this->request->get('standard_hours'),
            'max_overtime_hours' => (int)$this->request->get('max_overtime_hours'),
            'auto_approve_overtime' => $this->request->get('auto_approve_overtime') ? true : false,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => $this->session->getUserId()
        ];

        // Validate
        $errors = [];
        if (empty($settings['company_name'])) {
            $errors['company_name'] = 'Tên công ty là bắt buộc';
        }
        if ($settings['tolerance_minutes'] < 0 || $settings['tolerance_minutes'] > 60) {
            $errors['tolerance_minutes'] = 'Thời gian dung sai phải từ 0-60 phút';
        }
        if ($settings['standard_hours'] < 1 || $settings['standard_hours'] > 12) {
            $errors['standard_hours'] = 'Giờ làm việc chuẩn phải từ 1-12 giờ';
        }
        if ($settings['max_overtime_hours'] < 0 || $settings['max_overtime_hours'] > 8) {
            $errors['max_overtime_hours'] = 'Số giờ OT tối đa phải từ 0-8 giờ';
        }

        if (!empty($errors)) {
            Response::validationError('Dữ liệu không hợp lệ', $errors);
        }

        // Save to file
        if ($this->saveSettings($settings)) {
            if ($this->request->isApi()) {
                Response::success('Cài đặt đã được lưu thành công');
            } else {
                // Redirect with success message for form submission
                $_SESSION['success_message'] = 'Cài đặt đã được lưu thành công';
                header('Location: /main/settings');
                exit;
            }
        } else {
            if ($this->request->isApi()) {
                Response::error('Không thể lưu cài đặt');
            } else {
                $_SESSION['error_message'] = 'Không thể lưu cài đặt';
                header('Location: /main/settings');
                exit;
            }
        }
    }

    /**
     * Lấy cài đặt hiện tại
     */
    public function getSettings()
    {
        $defaultSettings = [
            'company_name' => 'Công ty TNHH ABC',
            'timezone' => 'Asia/Ho_Chi_Minh',
            'tolerance_minutes' => 15,
            'standard_hours' => 8,
            'max_overtime_hours' => 4,
            'auto_approve_overtime' => false,
            'updated_at' => date('Y-m-d H:i:s'),
            'updated_by' => 1
        ];

        if (file_exists($this->settingsFile)) {
            $content = file_get_contents($this->settingsFile);
            $settings = json_decode($content, true);
            if ($settings) {
                return array_merge($defaultSettings, $settings);
            }
        }

        return $defaultSettings;
    }

    /**
     * Lưu cài đặt vào file
     */
    private function saveSettings($settings)
    {
        // Ensure config directory exists
        $configDir = dirname($this->settingsFile);
        if (!is_dir($configDir)) {
            mkdir($configDir, 0755, true);
        }

        return file_put_contents($this->settingsFile, json_encode($settings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) !== false;
    }

    /**
     * API lấy cài đặt
     */
    public function get()
    {
        $this->checkAuth();
        
        $settings = $this->getSettings();
        Response::success($settings);
    }
}
