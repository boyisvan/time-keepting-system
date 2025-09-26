<?php
require_once __DIR__ . '/Database.php';
require_once __DIR__ . '/Request.php';
require_once __DIR__ . '/Response.php';
require_once __DIR__ . '/Session.php';

/**
 * Lớp controller cơ sở
 */
abstract class BaseController
{
    protected $db;
    protected $request;
    protected $response;
    protected $session;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->request = Request::getInstance();
        $this->response = Response::class;
        $this->session = Session::getInstance();
    }

    /**
     * Kiểm tra đăng nhập
     */
    protected function checkAuth()
    {
        if (!$this->session->isLoggedIn()) {
            if ($this->request->isApi()) {
                Response::unauthorized('Vui lòng đăng nhập');
            } else {
                Response::redirect('/login');
            }
        }
    }

    /**
     * Kiểm tra quyền
     */
    protected function checkPermission($permission)
    {
        $this->checkAuth();
        
        if (!$this->session->hasPermission($permission)) {
            if ($this->request->isApi()) {
                Response::forbidden('Bạn không có quyền truy cập chức năng này');
            } else {
                Response::redirect('/403');
            }
        }
    }

    /**
     * Kiểm tra role
     */
    protected function checkRole($role)
    {
        $this->checkAuth();
        
        if (!$this->session->hasRole($role)) {
            if ($this->request->isApi()) {
                Response::forbidden('Bạn không có quyền truy cập chức năng này');
            } else {
                Response::redirect('/403');
            }
        }
    }

    /**
     * Lấy user hiện tại
     */
    protected function getCurrentUser()
    {
        return $this->session->getUser();
    }

    /**
     * Lấy user ID hiện tại
     */
    protected function getCurrentUserId()
    {
        return $this->session->getUserId();
    }

    /**
     * Render view
     */
    protected function view($view, $data = [])
    {
        // Thêm dữ liệu chung cho tất cả view
        $data['currentUser'] = $this->getCurrentUser();
        $data['messages'] = $this->session->getMessages();
        
        Response::view($view, $data);
    }

    /**
     * Render JSON response
     */
    protected function json($data = null, $status = 200, $message = '')
    {
        Response::json($data, $status, $message);
    }

    /**
     * Redirect
     */
    protected function redirect($url, $status = 302)
    {
        Response::redirect($url, $status);
    }

    /**
     * Lưu thông báo
     */
    protected function setMessage($type, $message)
    {
        $this->session->setMessage($type, $message);
        return $this;
    }

    /**
     * Lưu thông báo thành công
     */
    protected function success($message)
    {
        return $this->setMessage('success', $message);
    }

    /**
     * Lưu thông báo lỗi
     */
    protected function error($message)
    {
        return $this->setMessage('error', $message);
    }

    /**
     * Lưu thông báo cảnh báo
     */
    protected function warning($message)
    {
        return $this->setMessage('warning', $message);
    }

    /**
     * Lưu thông báo thông tin
     */
    protected function info($message)
    {
        return $this->setMessage('info', $message);
    }

    /**
     * Validate request
     */
    protected function validate($rules)
    {
        $this->request->validate($rules);
    }

    /**
     * Lấy dữ liệu từ request
     */
    protected function input($key = null, $default = null)
    {
        if ($key === null) {
            return $this->request->all();
        }
        return $this->request->get($key, $default);
    }

    /**
     * Lấy dữ liệu POST
     */
    protected function post($key = null, $default = null)
    {
        return $this->request->post($key, $default);
    }

    /**
     * Lấy dữ liệu GET
     */
    protected function query($key = null, $default = null)
    {
        return $this->request->query($key, $default);
    }

    /**
     * Lấy file upload
     */
    protected function file($key)
    {
        return $this->request->file($key);
    }

    /**
     * Kiểm tra có phải POST request
     */
    protected function isPost()
    {
        return $this->request->isPost();
    }

    /**
     * Kiểm tra có phải AJAX request
     */
    protected function isAjax()
    {
        return $this->request->isAjax();
    }

    /**
     * Kiểm tra có phải API request
     */
    protected function isApi()
    {
        return $this->request->isApi();
    }

    /**
     * Lấy IP address
     */
    protected function getClientIp()
    {
        return $this->request->ip();
    }

    /**
     * Lấy User Agent
     */
    protected function getUserAgent()
    {
        return $this->request->userAgent();
    }

    /**
     * Upload file
     */
    protected function uploadFile($file, $path = 'uploads/')
    {
        if (!$file || $file['error'] !== UPLOAD_ERR_OK) {
            throw new Exception('Lỗi upload file');
        }

        $config = require __DIR__ . '/../config/app.php';
        $uploadConfig = $config['upload'];

        // Kiểm tra kích thước file
        if ($file['size'] > $uploadConfig['max_size']) {
            throw new Exception('File quá lớn');
        }

        // Kiểm tra loại file
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $uploadConfig['allowed_types'])) {
            throw new Exception('Loại file không được phép');
        }

        // Tạo tên file unique
        $filename = uniqid() . '_' . time() . '.' . $extension;
        $fullPath = $uploadConfig['path'] . $path . $filename;

        // Tạo thư mục nếu chưa có
        $dir = dirname($fullPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        // Di chuyển file
        if (!move_uploaded_file($file['tmp_name'], $fullPath)) {
            throw new Exception('Không thể lưu file');
        }

        return $fullPath;
    }

    /**
     * Ghi log audit
     */
    protected function logAudit($action, $table, $recordId, $oldValues = null, $newValues = null)
    {
        $sql = "INSERT INTO audit_logs (user_id, action, table_name, record_id, old_values, new_values, ip_address, user_agent) 
                VALUES (:user_id, :action, :table_name, :record_id, :old_values, :new_values, :ip_address, :user_agent)";
        
        $this->db->query($sql, [
            'user_id' => $this->getCurrentUserId(),
            'action' => $action,
            'table_name' => $table,
            'record_id' => $recordId,
            'old_values' => $oldValues ? json_encode($oldValues) : null,
            'new_values' => $newValues ? json_encode($newValues) : null,
            'ip_address' => $this->getClientIp(),
            'user_agent' => $this->getUserAgent()
        ]);
    }
}
