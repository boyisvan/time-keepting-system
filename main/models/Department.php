<?php
require_once __DIR__ . '/../core/BaseModel.php';

/**
 * Model Department
 */
class Department extends BaseModel
{
    protected $table = 'departments';
    protected $fillable = ['name', 'code', 'description', 'manager_id', 'parent_id', 'status'];

    /**
     * Lấy tất cả department với cây phân cấp
     */
    public function getTree()
    {
        $sql = "SELECT d.*, 
                       m.first_name as manager_first_name, 
                       m.last_name as manager_last_name,
                       COUNT(u.id) as employee_count
                FROM {$this->table} d 
                LEFT JOIN users m ON d.manager_id = m.id 
                LEFT JOIN users u ON d.id = u.department_id AND u.status = 'active'
                WHERE d.status = 'active'
                GROUP BY d.id 
                ORDER BY d.parent_id, d.name";
        
        $departments = $this->db->fetchAll($sql);
        
        return $this->buildTree($departments);
    }

    /**
     * Xây dựng cây phân cấp
     */
    private function buildTree($departments, $parentId = null)
    {
        $tree = [];
        
        foreach ($departments as $department) {
            if ($department['parent_id'] == $parentId) {
                $children = $this->buildTree($departments, $department['id']);
                if ($children) {
                    $department['children'] = $children;
                }
                $tree[] = $department;
            }
        }
        
        return $tree;
    }

    /**
     * Lấy danh sách department cho select
     */
    public function getForSelect()
    {
        $sql = "SELECT id, name, code FROM {$this->table} WHERE status = 'active' ORDER BY name";
        return $this->db->fetchAll($sql);
    }

    /**
     * Lấy department với thông tin manager
     */
    public function findWithManager($id)
    {
        $sql = "SELECT d.*, 
                       m.first_name as manager_first_name, 
                       m.last_name as manager_last_name,
                       m.email as manager_email
                FROM {$this->table} d 
                LEFT JOIN users m ON d.manager_id = m.id 
                WHERE d.id = :id";
        
        return $this->db->fetch($sql, ['id' => $id]);
    }

    /**
     * Lấy thống kê department
     */
    public function getStats()
    {
        $sql = "SELECT 
                    d.id,
                    d.name,
                    COUNT(u.id) as total_employees,
                    SUM(CASE WHEN u.status = 'active' THEN 1 ELSE 0 END) as active_employees
                FROM {$this->table} d 
                LEFT JOIN users u ON d.id = u.department_id 
                WHERE d.status = 'active'
                GROUP BY d.id 
                ORDER BY total_employees DESC";
        
        return $this->db->fetchAll($sql);
    }

    /**
     * Kiểm tra department có thể xóa không
     */
    public function canDelete($id)
    {
        // Kiểm tra có nhân viên không
        $employeeCount = $this->db->fetch(
            "SELECT COUNT(*) as count FROM users WHERE department_id = :id",
            ['id' => $id]
        )['count'];
        
        // Kiểm tra có department con không
        $childCount = $this->db->fetch(
            "SELECT COUNT(*) as count FROM {$this->table} WHERE parent_id = :id",
            ['id' => $id]
        )['count'];
        
        return $employeeCount == 0 && $childCount == 0;
    }

    /**
     * Tìm kiếm phòng ban
     */
    public function search($keyword)
    {
        $sql = "SELECT id, name, description, status 
                FROM {$this->table} 
                WHERE (name LIKE :keyword OR description LIKE :keyword)
                ORDER BY name";
        
        return $this->db->fetchAll($sql, ['keyword' => "%{$keyword}%"]);
    }

    /**
     * Lấy phòng ban với chi tiết
     */
    public function findWithDetails($id)
    {
        $sql = "SELECT d.*, 
                       m.first_name as manager_first_name, 
                       m.last_name as manager_last_name,
                       m.email as manager_email,
                       COUNT(u.id) as employee_count
                FROM {$this->table} d 
                LEFT JOIN users m ON d.manager_id = m.id 
                LEFT JOIN users u ON d.id = u.department_id AND u.status = 'active'
                WHERE d.id = :id
                GROUP BY d.id";
        
        return $this->db->fetch($sql, ['id' => $id]);
    }

    /**
     * Lấy phòng ban theo tên
     */
    public function findByName($name)
    {
        return $this->findBy('name', $name);
    }
}
