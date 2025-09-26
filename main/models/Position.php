<?php
require_once __DIR__ . '/../core/BaseModel.php';

/**
 * Model Position
 */
class Position extends BaseModel
{
    protected $table = 'positions';
    protected $fillable = ['name', 'code', 'description', 'level', 'status'];

    /**
     * Lấy danh sách position cho select
     */
    public function getForSelect()
    {
        $sql = "SELECT id, name, code FROM {$this->table} WHERE status = 'active' ORDER BY level, name";
        return $this->db->fetchAll($sql);
    }

    /**
     * Lấy position theo level
     */
    public function getByLevel($level)
    {
        $sql = "SELECT * FROM {$this->table} WHERE level = :level AND status = 'active' ORDER BY name";
        return $this->db->fetchAll($sql, ['level' => $level]);
    }

    /**
     * Lấy thống kê position
     */
    public function getStats()
    {
        $sql = "SELECT 
                    p.id,
                    p.name,
                    p.level,
                    COUNT(u.id) as total_employees
                FROM {$this->table} p 
                LEFT JOIN users u ON p.id = u.position_id AND u.status = 'active'
                WHERE p.status = 'active'
                GROUP BY p.id 
                ORDER BY p.level, p.name";
        
        return $this->db->fetchAll($sql);
    }
}
