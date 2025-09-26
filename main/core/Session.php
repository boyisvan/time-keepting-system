<?php
/**
 * Lớp quản lý session
 */

class Session
{
    private static $instance = null;

    private function __construct()
    {
        // Don't start session here, let it be handled by the main app
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Lấy giá trị session
     */
    public function get($key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }

    /**
     * Lưu giá trị session
     */
    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
        return $this;
    }

    /**
     * Xóa giá trị session
     */
    public function remove($key)
    {
        unset($_SESSION[$key]);
        return $this;
    }

    /**
     * Kiểm tra tồn tại key
     */
    public function has($key)
    {
        return isset($_SESSION[$key]);
    }

    /**
     * Lấy tất cả session
     */
    public function all()
    {
        return $_SESSION;
    }

    /**
     * Xóa tất cả session
     */
    public function flush()
    {
        $_SESSION = [];
        return $this;
    }

    /**
     * Hủy session
     */
    public function destroy()
    {
        session_destroy();
        return $this;
    }

    /**
     * Lưu flash message
     */
    public function flash($key, $value = null)
    {
        if ($value === null) {
            $value = $this->get($key);
            $this->remove($key);
            return $value;
        }
        
        $this->set($key, $value);
        return $this;
    }

    /**
     * Lưu thông tin user
     */
    public function setUser($user)
    {
        $this->set('user', $user);
        $this->set('user_id', $user['id']);
        $this->set('user_role', $user['role_name'] ?? $user['role'] ?? '');
        return $this;
    }

    /**
     * Lấy thông tin user
     */
    public function getUser()
    {
        return $this->get('user');
    }

    /**
     * Lấy user ID
     */
    public function getUserId()
    {
        return $this->get('user_id');
    }

    /**
     * Lấy role user
     */
    public function getUserRole()
    {
        return $this->get('user_role');
    }

    /**
     * Kiểm tra đăng nhập
     */
    public function isLoggedIn()
    {
        return $this->has('user_id');
    }

    /**
     * Đăng xuất
     */
    public function logout()
    {
        $this->remove('user');
        $this->remove('user_id');
        $this->remove('user_role');
        return $this;
    }

    /**
     * Kiểm tra quyền
     */
    public function hasPermission($permission)
    {
        $user = $this->getUser();
        if (!$user) {
            return false;
        }

        $permissions = $user['permissions'] ?? [];
        
        // Admin có tất cả quyền
        if (in_array('all', $permissions)) {
            return true;
        }

        return in_array($permission, $permissions);
    }

    /**
     * Kiểm tra role
     */
    public function hasRole($role)
    {
        $userRole = $this->getUserRole();
        return $userRole === $role;
    }

    /**
     * Lưu thông báo
     */
    public function setMessage($type, $message)
    {
        $messages = $this->get('messages', []);
        $messages[] = [
            'type' => $type,
            'message' => $message,
            'timestamp' => time()
        ];
        $this->set('messages', $messages);
        return $this;
    }

    /**
     * Lấy thông báo
     */
    public function getMessages()
    {
        $messages = $this->get('messages', []);
        $this->remove('messages');
        return $messages;
    }

    /**
     * Lưu thông báo thành công
     */
    public function success($message)
    {
        return $this->setMessage('success', $message);
    }

    /**
     * Lưu thông báo lỗi
     */
    public function error($message)
    {
        return $this->setMessage('error', $message);
    }

    /**
     * Lưu thông báo cảnh báo
     */
    public function warning($message)
    {
        return $this->setMessage('warning', $message);
    }

    /**
     * Lưu thông báo thông tin
     */
    public function info($message)
    {
        return $this->setMessage('info', $message);
    }
}
