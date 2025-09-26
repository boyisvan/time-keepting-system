<?php
/**
 * Lớp xử lý request
 */

class Request
{
    private static $instance = null;
    private $data = [];

    private function __construct()
    {
        $this->data = array_merge($_GET, $_POST, $_FILES);
    }

    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * Lấy tất cả dữ liệu
     */
    public function all()
    {
        return $this->data;
    }

    /**
     * Lấy giá trị theo key
     */
    public function get($key, $default = null)
    {
        return $this->data[$key] ?? $default;
    }

    /**
     * Lấy giá trị POST
     */
    public function post($key = null, $default = null)
    {
        if ($key === null) {
            return $_POST;
        }
        return $_POST[$key] ?? $default;
    }

    /**
     * Lấy giá trị GET
     */
    public function query($key = null, $default = null)
    {
        if ($key === null) {
            return $_GET;
        }
        return $_GET[$key] ?? $default;
    }

    /**
     * Lấy file upload
     */
    public function file($key)
    {
        return $_FILES[$key] ?? null;
    }

    /**
     * Kiểm tra có phải POST request
     */
    public function isPost()
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * Kiểm tra có phải GET request
     */
    public function isGet()
    {
        return $_SERVER['REQUEST_METHOD'] === 'GET';
    }

    /**
     * Kiểm tra có phải AJAX request
     */
    public function isAjax()
    {
        return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    /**
     * Kiểm tra có phải API request
     */
    public function isApi()
    {
        // Check if it's an AJAX request or API endpoint
        return strpos($_SERVER['REQUEST_URI'], '/api/') !== false || 
               (isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] === 'XMLHttpRequest');
    }

    /**
     * Lấy method
     */
    public function method()
    {
        return $_SERVER['REQUEST_METHOD'] ?? 'GET';
    }

    /**
     * Lấy URI
     */
    public function uri()
    {
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        
        // Loại bỏ base path nếu có
        $scriptName = dirname($_SERVER['SCRIPT_NAME'] ?? '/');
        if ($scriptName !== '/' && strpos($uri, $scriptName) === 0) {
            $uri = substr($uri, strlen($scriptName));
        }
        
        // Đảm bảo URI bắt đầu bằng /
        if (empty($uri) || $uri[0] !== '/') {
            $uri = '/' . $uri;
        }
        
        return $uri;
    }

    /**
     * Lấy IP address
     */
    public function ip()
    {
        $ipKeys = ['HTTP_CLIENT_IP', 'HTTP_X_FORWARDED_FOR', 'REMOTE_ADDR'];
        
        foreach ($ipKeys as $key) {
            if (array_key_exists($key, $_SERVER) === true) {
                foreach (explode(',', $_SERVER[$key]) as $ip) {
                    $ip = trim($ip);
                    if (filter_var($ip, FILTER_VALIDATE_IP, FILTER_FLAG_NO_PRIV_RANGE | FILTER_FLAG_NO_RES_RANGE) !== false) {
                        return $ip;
                    }
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
    }

    /**
     * Lấy User Agent
     */
    public function userAgent()
    {
        return $_SERVER['HTTP_USER_AGENT'] ?? '';
    }

    /**
     * Lấy header
     */
    public function header($name)
    {
        $name = 'HTTP_' . strtoupper(str_replace('-', '_', $name));
        return $_SERVER[$name] ?? null;
    }

    /**
     * Validate dữ liệu
     */
    public function validate($rules)
    {
        $errors = [];
        
        foreach ($rules as $field => $rule) {
            $value = $this->get($field);
            $fieldRules = explode('|', $rule);
            
            foreach ($fieldRules as $fieldRule) {
                $ruleParts = explode(':', $fieldRule);
                $ruleName = $ruleParts[0];
                $ruleValue = $ruleParts[1] ?? null;
                
                switch ($ruleName) {
                    case 'required':
                        if (empty($value)) {
                            $errors[$field][] = "Trường {$field} là bắt buộc";
                        }
                        break;
                        
                    case 'email':
                        if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                            $errors[$field][] = "Trường {$field} phải là email hợp lệ";
                        }
                        break;
                        
                    case 'min':
                        if (!empty($value) && strlen($value) < $ruleValue) {
                            $errors[$field][] = "Trường {$field} phải có ít nhất {$ruleValue} ký tự";
                        }
                        break;
                        
                    case 'max':
                        if (!empty($value) && strlen($value) > $ruleValue) {
                            $errors[$field][] = "Trường {$field} không được vượt quá {$ruleValue} ký tự";
                        }
                        break;
                        
                    case 'numeric':
                        if (!empty($value) && !is_numeric($value)) {
                            $errors[$field][] = "Trường {$field} phải là số";
                        }
                        break;
                        
                    case 'date':
                        if (!empty($value) && !strtotime($value)) {
                            $errors[$field][] = "Trường {$field} phải là ngày hợp lệ";
                        }
                        break;
                }
            }
        }
        
        if (!empty($errors)) {
            Response::validationError($errors);
        }
        
        return true;
    }

    /**
     * Lấy JSON input
     */
    public function json()
    {
        $input = file_get_contents('php://input');
        return json_decode($input, true);
    }
}
