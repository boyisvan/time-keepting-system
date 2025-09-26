<?php
require_once __DIR__ . '/../core/BaseModel.php';

/**
 * Model Role
 */
class Role extends BaseModel
{
    protected $table = 'roles';
    protected $fillable = ['name', 'description', 'permissions'];

    /**
     * Lấy tất cả role với số lượng user
     */
    public function getAllWithUserCount()
    {
        $sql = "SELECT r.*, COUNT(u.id) as user_count 
                FROM {$this->table} r 
                LEFT JOIN users u ON r.id = u.role_id 
                GROUP BY r.id 
                ORDER BY r.name";
        
        return $this->db->fetchAll($sql);
    }

    /**
     * Lấy permissions của role
     */
    public function getPermissions($roleId)
    {
        $role = $this->find($roleId);
        if ($role && $role['permissions']) {
            return json_decode($role['permissions'], true);
        }
        return [];
    }

    /**
     * Cập nhật permissions
     */
    public function updatePermissions($roleId, $permissions)
    {
        return $this->update($roleId, [
            'permissions' => json_encode($permissions)
        ]);
    }

    /**
     * Kiểm tra role có permission không
     */
    public function hasPermission($roleId, $permission)
    {
        $permissions = $this->getPermissions($roleId);
        
        // Admin có tất cả quyền
        if (in_array('all', $permissions)) {
            return true;
        }
        
        return in_array($permission, $permissions);
    }

    /**
     * Lấy danh sách role cho select
     */
    public function getForSelect()
    {
        $sql = "SELECT id, name FROM {$this->table} ORDER BY name";
        return $this->db->fetchAll($sql);
    }

    /**
     * Lấy tất cả roles với pagination và search
     */
    public function getAll($page = 1, $limit = 10, $search = '')
    {
        $offset = ($page - 1) * $limit;
        $where = '';
        $params = [];
        
        if ($search) {
            $where = "WHERE name LIKE :search OR description LIKE :search";
            $params['search'] = "%{$search}%";
        }
        
        $sql = "SELECT * FROM {$this->table} {$where} ORDER BY created_at DESC LIMIT :limit OFFSET :offset";
        $params['limit'] = $limit;
        $params['offset'] = $offset;
        
        return $this->db->fetchAll($sql, $params);
    }

    /**
     * Tìm role theo tên
     */
    public function findByName($name)
    {
        $sql = "SELECT * FROM {$this->table} WHERE name = :name";
        return $this->db->fetch($sql, ['name' => $name]);
    }

    /**
     * Tìm kiếm roles
     */
    public function search($keyword)
    {
        $sql = "SELECT * FROM {$this->table} WHERE name LIKE :keyword OR description LIKE :keyword ORDER BY name";
        return $this->db->fetchAll($sql, ['keyword' => "%{$keyword}%"]);
    }

    /**
     * Lấy users theo role
     */
    public function getUsersByRole($roleId)
    {
        $sql = "SELECT id, username, first_name, last_name FROM users WHERE role_id = :role_id";
        return $this->db->fetchAll($sql, ['role_id' => $roleId]);
    }

    /**
     * Đếm roles có users
     */
    public function countRolesWithUsers()
    {
        $sql = "SELECT COUNT(DISTINCT r.id) as count FROM {$this->table} r 
                INNER JOIN users u ON r.id = u.role_id";
        $result = $this->db->fetch($sql);
        return $result['count'] ?? 0;
    }
}
