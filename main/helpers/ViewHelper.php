<?php
/**
 * View Helper
 */

class ViewHelper
{
    /**
     * Render view với layout
     */
    public static function render($view, $data = [])
    {
        // Extract data to variables
        extract($data);
        
        // Start output buffering
        ob_start();
        
        // Include view file
        $viewPath = __DIR__ . '/../views/' . $view . '.php';
        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            echo "View not found: " . $view;
        }
        
        // Get content
        $content = ob_get_clean();
        
        // Set default values
        $pageTitle = $pageTitle ?? 'Hệ thống chấm công';
        $pageDescription = $pageDescription ?? 'Hệ thống quản lý chấm công nhân viên';
        $currentPage = $currentPage ?? 'dashboard';
        $user = $user ?? ['first_name' => 'User', 'last_name' => '', 'role_name' => 'Nhân viên'];
        $breadcrumbs = $breadcrumbs ?? [];
        $pageCSS = $pageCSS ?? [];
        $pageJS = $pageJS ?? [];
        $customJS = $customJS ?? '';
        
        // Set assets path based on view location
        $assetsPath = self::getAssetsPath($view);
        
        // Include layout
        include __DIR__ . '/../views/layouts/app.php';
    }
    
    /**
     * Get correct assets path based on view location
     */
    private static function getAssetsPath($view)
    {
        // Count directory levels in view path
        $levels = substr_count($view, '/');
        
        // Build relative path
        $path = '';
        for ($i = 0; $i <= $levels; $i++) {
            $path .= '../';
        }
        
        return $path . 'assets/';
    }
    
    /**
     * Render partial view
     */
    public static function partial($view, $data = [])
    {
        extract($data);
        
        $viewPath = __DIR__ . '/../views/' . $view . '.php';
        if (file_exists($viewPath)) {
            include $viewPath;
        } else {
            echo "Partial view not found: " . $view;
        }
    }
}
