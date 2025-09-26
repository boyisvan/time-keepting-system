<?php
require_once __DIR__ . '/../core/BaseModel.php';

/**
 * Model User
 */
class User extends BaseModel
{
    protected $table = 'users';
    protected $fillable = [
        'employee_id', 'username', 'email', 'password_hash', 'first_name', 'last_name',
        'phone', 'avatar', 'role_id', 'department_id', 'position_id', 'hire_date', 'status'
    ];
    protected $hidden = ['password_hash'];

    /**
     * Tìm user theo username hoặc email
     */
    public function findByUsernameOrEmail($username)
    {
        $sql = "SELECT u.*, r.name as role_name, r.permissions, d.name as department_name 
                FROM {$this->table} u 
                LEFT JOIN roles r ON u.role_id = r.id 
                LEFT JOIN departments d ON u.department_id = d.id 
                WHERE (u.username = :username OR u.email = :email)";
        
        $user = $this->db->fetch($sql, [
            'username' => $username,
            'email' => $username
        ]);
        
        if ($user && $user['permissions']) {
            $user['permissions'] = json_decode($user['permissions'], true);
        }
        
        return $user;
    }

    /**
     * Tìm user với thông tin đầy đủ
     */
    public function findWithDetails($id)
    {
        $sql = "SELECT u.*, r.name as role_name, r.permissions, d.name as department_name 
                FROM {$this->table} u 
                LEFT JOIN roles r ON u.role_id = r.id 
                LEFT JOIN departments d ON u.department_id = d.id 
                WHERE u.id = :id";
        
        $user = $this->db->fetch($sql, ['id' => $id]);
        
        if ($user && $user['permissions']) {
            $user['permissions'] = json_decode($user['permissions'], true);
        }
        
        return $user;
    }

    /**
     * Lấy danh sách user theo phòng ban
     */
    public function getByDepartment($departmentId, $status = 'active')
    {
        $sql = "SELECT u.*, r.name as role_name, d.name as department_name 
                FROM {$this->table} u 
                LEFT JOIN roles r ON u.role_id = r.id 
                LEFT JOIN departments d ON u.department_id = d.id 
                WHERE u.department_id = :department_id AND u.status = :status
                ORDER BY u.first_name, u.last_name";
        
        return $this->db->fetchAll($sql, [
            'department_id' => $departmentId,
            'status' => $status
        ]);
    }

    /**
     * Tìm kiếm user
     */
    public function search($keyword, $departmentId = null, $roleId = null)
    {
        $sql = "SELECT u.*, r.name as role_name, d.name as department_name 
                FROM {$this->table} u 
                LEFT JOIN roles r ON u.role_id = r.id 
                LEFT JOIN departments d ON u.department_id = d.id 
                WHERE (u.first_name LIKE :keyword 
                       OR u.last_name LIKE :keyword 
                       OR u.employee_id LIKE :keyword 
                       OR u.email LIKE :keyword)";
        
        $params = ['keyword' => "%{$keyword}%"];
        
        if ($departmentId) {
            $sql .= " AND u.department_id = :department_id";
            $params['department_id'] = $departmentId;
        }
        
        if ($roleId) {
            $sql .= " AND u.role_id = :role_id";
            $params['role_id'] = $roleId;
        }
        
        $sql .= " ORDER BY u.first_name, u.last_name";
        
        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Cập nhật mật khẩu
     */
    public function updatePassword($userId, $newPassword)
    {
        $passwordHash = password_hash($newPassword, PASSWORD_DEFAULT);
        return $this->update($userId, ['password_hash' => $passwordHash]);
    }

    /**
     * Kiểm tra mật khẩu
     */
    public function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }

    /**
     * Cập nhật lần đăng nhập cuối
     */
    public function updateLastLogin($userId)
    {
        return $this->update($userId, ['last_login' => date('Y-m-d H:i:s')]);
    }

    /**
     * Lấy thống kê user
     */
    public function getStats()
    {
        $sql = "SELECT 
                    COUNT(*) as total_users,
                    SUM(CASE WHEN status = 'active' THEN 1 ELSE 0 END) as active_users,
                    SUM(CASE WHEN status = 'inactive' THEN 1 ELSE 0 END) as inactive_users,
                    SUM(CASE WHEN status = 'suspended' THEN 1 ELSE 0 END) as suspended_users
                FROM {$this->table}";
        
        return $this->db->fetch($sql);
    }

    /**
     * Lấy user mới nhất
     */
    public function getRecentUsers($limit = 10)
    {
        $sql = "SELECT u.*, r.name as role_name, d.name as department_name 
                FROM {$this->table} u 
                LEFT JOIN roles r ON u.role_id = r.id 
                LEFT JOIN departments d ON u.department_id = d.id 
                ORDER BY u.created_at DESC 
                LIMIT :limit";
        
        return $this->db->fetchAll($sql, ['limit' => $limit]);
    }
}
