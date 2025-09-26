<?php
require_once __DIR__ . '/../core/BaseController.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Role.php';
require_once __DIR__ . '/../core/Session.php';

/**
 * Controller xử lý xác thực
 */
class AuthController extends BaseController
{
    private $userModel;
    private $roleModel;
    protected $session;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
        $this->roleModel = new Role();
        $this->session = Session::getInstance();
    }

    /**
     * Hiển thị trang đăng nhập
     */
    public function login()
    {
        if ($this->session->isLoggedIn()) {
            Response::redirect('/dashboard');
        }

        $this->view('auth/login');
    }

    /**
     * Xử lý đăng nhập
     */
    public function doLogin()
    {
        $request = Request::getInstance();
        
        // Validate dữ liệu
        $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        $username = $request->post('username');
        $password = $request->post('password');
        $remember = $request->post('remember') ? true : false;

        try {
            // Tìm user
            $user = $this->userModel->findByUsernameOrEmail($username);
            
            if (!$user) {
                Response::error('Tên đăng nhập hoặc mật khẩu không đúng');
            }

            // Kiểm tra mật khẩu
            if (!$this->userModel->verifyPassword($password, $user['password_hash'])) {
                Response::error('Tên đăng nhập hoặc mật khẩu không đúng');
            }

            // Kiểm tra trạng thái tài khoản
            if ($user['status'] !== 'active') {
                Response::error('Tài khoản đã bị khóa hoặc chưa được kích hoạt');
            }

            // Cập nhật lần đăng nhập cuối
            $this->userModel->updateLastLogin($user['id']);

            // Lưu thông tin user vào session
            $this->session->setUser($user);

            // Lưu remember me nếu có
            if ($remember) {
                $this->setRememberToken($user['id']);
            }

            Response::success(['redirect' => '/main/dashboard'], 'Đăng nhập thành công');

        } catch (Exception $e) {
            // Log lỗi để debug
            error_log('Login error: ' . $e->getMessage());
            error_log('Stack trace: ' . $e->getTraceAsString());
            Response::error('Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Đăng xuất
     */
    public function logout()
    {
        $this->session->logout();
        Response::redirect('/login');
    }

    /**
     * Hiển thị trang đổi mật khẩu
     */
    public function changePassword()
    {
        $this->checkAuth();
        $this->view('auth/change-password');
    }

    /**
     * Xử lý đổi mật khẩu
     */
    public function doChangePassword()
    {
        $this->checkAuth();
        $request = Request::getInstance();
        
        // Validate dữ liệu
        $request->validate([
            'current_password' => 'required',
            'new_password' => 'required|min:8',
            'confirm_password' => 'required'
        ]);

        $currentPassword = $request->post('current_password');
        $newPassword = $request->post('new_password');
        $confirmPassword = $request->post('confirm_password');

        // Kiểm tra mật khẩu mới và xác nhận
        if ($newPassword !== $confirmPassword) {
            Response::error('Mật khẩu mới và xác nhận mật khẩu không khớp');
        }

        try {
            $userId = $this->session->getUserId();
            $user = $this->userModel->find($userId);

            // Kiểm tra mật khẩu hiện tại
            if (!$this->userModel->verifyPassword($currentPassword, $user['password_hash'])) {
                Response::error('Mật khẩu hiện tại không đúng');
            }

            // Cập nhật mật khẩu mới
            $this->userModel->updatePassword($userId, $newPassword);

            Response::success(null, 'Đổi mật khẩu thành công');

        } catch (Exception $e) {
            Response::error('Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Hiển thị trang quên mật khẩu
     */
    public function forgotPassword()
    {
        $this->view('auth/forgot-password');
    }

    /**
     * Xử lý quên mật khẩu
     */
    public function doForgotPassword()
    {
        $request = Request::getInstance();
        
        $request->validate([
            'email' => 'required|email'
        ]);

        $email = $request->post('email');

        try {
            $user = $this->userModel->findBy('email', $email);
            
            if (!$user) {
                Response::error('Email không tồn tại trong hệ thống');
            }

            // Tạo token reset password
            $token = $this->generateResetToken();
            $this->saveResetToken($user['id'], $token);

            // Gửi email reset password (cần implement)
            // $this->sendResetPasswordEmail($user, $token);

            Response::success(null, 'Hướng dẫn đặt lại mật khẩu đã được gửi đến email của bạn');

        } catch (Exception $e) {
            Response::error('Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Kiểm tra quyền truy cập
     */
    public function checkPermission($permission)
    {
        if (!$this->session->isLoggedIn()) {
            Response::unauthorized('Vui lòng đăng nhập');
        }

        if (!$this->session->hasPermission($permission)) {
            Response::forbidden('Bạn không có quyền truy cập chức năng này');
        }
    }

    /**
     * Kiểm tra role
     */
    public function checkRole($role)
    {
        if (!$this->session->isLoggedIn()) {
            Response::unauthorized('Vui lòng đăng nhập');
        }

        if (!$this->session->hasRole($role)) {
            Response::forbidden('Bạn không có quyền truy cập chức năng này');
        }
    }

    /**
     * Tạo remember token
     */
    private function setRememberToken($userId)
    {
        $token = bin2hex(random_bytes(32));
        $expires = date('Y-m-d H:i:s', strtotime('+30 days'));
        
        // Lưu token vào database hoặc cookie
        setcookie('remember_token', $token, strtotime('+30 days'), '/', '', false, true);
    }

    /**
     * Tạo reset token
     */
    private function generateResetToken()
    {
        return bin2hex(random_bytes(32));
    }

    /**
     * Lưu reset token
     */
    private function saveResetToken($userId, $token)
    {
        $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));
        
        $sql = "INSERT INTO password_resets (user_id, token, expires_at) 
                VALUES (:user_id, :token, :expires_at) 
                ON DUPLICATE KEY UPDATE token = :token, expires_at = :expires_at";
        
        $this->db->query($sql, [
            'user_id' => $userId,
            'token' => $token,
            'expires_at' => $expires
        ]);
    }
}
