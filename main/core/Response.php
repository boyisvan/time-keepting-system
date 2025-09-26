<?php
/**
 * Lớp xử lý response
 */

class Response
{
    /**
     * Trả về response JSON
     */
    public static function json($data = null, $status = 200, $message = '')
    {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        
        $response = [
            'success' => $status >= 200 && $status < 300,
            'status' => $status,
            'message' => $message,
            'data' => $data,
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        echo json_encode($response, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * Trả về response thành công
     */
    public static function success($data = null, $message = 'Thành công', $status = 200)
    {
        self::json($data, $status, $message);
    }

    /**
     * Trả về response lỗi
     */
    public static function error($message = 'Có lỗi xảy ra', $status = 400, $data = null)
    {
        self::json($data, $status, $message);
    }

    /**
     * Trả về response không tìm thấy
     */
    public static function notFound($message = 'Không tìm thấy dữ liệu')
    {
        self::json(null, 404, $message);
    }

    /**
     * Trả về response không có quyền
     */
    public static function unauthorized($message = 'Không có quyền truy cập')
    {
        self::json(null, 401, $message);
    }

    /**
     * Trả về response cấm
     */
    public static function forbidden($message = 'Bị cấm truy cập')
    {
        self::json(null, 403, $message);
    }

    /**
     * Trả về response validation error
     */
    public static function validationError($errors, $message = 'Dữ liệu không hợp lệ')
    {
        http_response_code(422);
        header('Content-Type: application/json');
        echo json_encode([
            'success' => false,
            'message' => $message,
            'errors' => $errors
        ]);
        exit;
    }

    /**
     * Trả về response server error
     */
    public static function serverError($message = 'Lỗi máy chủ')
    {
        self::json(null, 500, $message);
    }

    /**
     * Redirect
     */
    public static function redirect($url, $status = 302)
    {
        http_response_code($status);
        header("Location: {$url}");
        exit;
    }

    /**
     * Trả về view
     */
    public static function view($view, $data = [])
    {
        extract($data);
        include __DIR__ . "/../views/{$view}.php";
    }

    /**
     * Download file
     */
    public static function download($filePath, $filename = null)
    {
        if (!file_exists($filePath)) {
            self::notFound('File không tồn tại');
        }

        $filename = $filename ?: basename($filePath);
        
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($filePath));
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        
        readfile($filePath);
        exit;
    }
}
