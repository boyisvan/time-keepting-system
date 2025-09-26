<?php
/**
 * Lớp cơ sở cho tất cả các model
 */

abstract class BaseModel
{
    protected $db;
    protected $table;
    protected $primaryKey = 'id';
    protected $fillable = [];
    protected $hidden = [];
    protected $timestamps = true;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * Lấy tất cả bản ghi
     */
    public function all($columns = '*')
    {
        $sql = "SELECT {$columns} FROM {$this->table}";
        if ($this->timestamps) {
            $sql .= " ORDER BY created_at DESC";
        }
        return $this->db->fetchAll($sql);
    }

    /**
     * Tìm bản ghi theo ID
     */
    public function find($id, $columns = '*')
    {
        $sql = "SELECT {$columns} FROM {$this->table} WHERE {$this->primaryKey} = :id";
        return $this->db->fetch($sql, ['id' => $id]);
    }

    /**
     * Tìm bản ghi theo điều kiện
     */
    public function findBy($column, $value, $columns = '*')
    {
        $sql = "SELECT {$columns} FROM {$this->table} WHERE {$column} = :value";
        return $this->db->fetch($sql, ['value' => $value]);
    }

    /**
     * Lấy tất cả bản ghi theo điều kiện
     */
    public function findAllBy($column, $value, $columns = '*')
    {
        $sql = "SELECT {$columns} FROM {$this->table} WHERE {$column} = :value";
        if ($this->timestamps) {
            $sql .= " ORDER BY created_at DESC";
        }
        return $this->db->fetchAll($sql, ['value' => $value]);
    }

    /**
     * Tạo bản ghi mới
     */
    public function create($data)
    {
        // Chỉ lấy các trường được phép fill
        $filteredData = $this->filterFillable($data);
        
        // Thêm timestamps nếu cần
        if ($this->timestamps) {
            $filteredData['created_at'] = date('Y-m-d H:i:s');
            $filteredData['updated_at'] = date('Y-m-d H:i:s');
        }

        return $this->db->insert($this->table, $filteredData);
    }

    /**
     * Cập nhật bản ghi
     */
    public function update($id, $data)
    {
        // Chỉ lấy các trường được phép fill
        $filteredData = $this->filterFillable($data);
        
        // Thêm updated_at nếu cần
        if ($this->timestamps) {
            $filteredData['updated_at'] = date('Y-m-d H:i:s');
        }

        return $this->db->update($this->table, $filteredData, "{$this->primaryKey} = :id", ['id' => $id]);
    }

    /**
     * Xóa bản ghi
     */
    public function delete($id)
    {
        return $this->db->delete($this->table, "{$this->primaryKey} = :id", ['id' => $id]);
    }

    /**
     * Đếm số bản ghi
     */
    public function count($where = '', $params = [])
    {
        $sql = "SELECT COUNT(*) as total FROM {$this->table}";
        if ($where) {
            $sql .= " WHERE {$where}";
        }
        $result = $this->db->fetch($sql, $params);
        return $result['total'];
    }

    /**
     * Phân trang
     */
    public function paginate($page = 1, $perPage = 20, $where = '', $params = [])
    {
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT * FROM {$this->table}";
        if ($where) {
            $sql .= " WHERE {$where}";
        }
        if ($this->timestamps) {
            $sql .= " ORDER BY created_at DESC";
        }
        $sql .= " LIMIT {$perPage} OFFSET {$offset}";
        
        $data = $this->db->fetchAll($sql, $params);
        $total = $this->count($where, $params);
        
        return [
            'data' => $data,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => ceil($total / $perPage)
        ];
    }

    /**
     * Thực hiện truy vấn tùy chỉnh
     */
    public function query($sql, $params = [])
    {
        return $this->db->query($sql, $params);
    }

    /**
     * Lọc dữ liệu theo fillable
     */
    protected function filterFillable($data)
    {
        if (empty($this->fillable)) {
            return $data;
        }
        
        return array_intersect_key($data, array_flip($this->fillable));
    }

    /**
     * Ẩn các trường sensitive
     */
    protected function hideFields($data)
    {
        if (is_array($data)) {
            foreach ($this->hidden as $field) {
                unset($data[$field]);
            }
        }
        return $data;
    }

    /**
     * Bắt đầu transaction
     */
    public function beginTransaction()
    {
        return $this->db->beginTransaction();
    }

    /**
     * Commit transaction
     */
    public function commit()
    {
        return $this->db->commit();
    }

    /**
     * Rollback transaction
     */
    public function rollback()
    {
        return $this->db->rollback();
    }
}
