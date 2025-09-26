<?php
require_once __DIR__ . '/../core/BaseController.php';
require_once __DIR__ . '/../models/Department.php';
require_once __DIR__ . '/../helpers/ViewHelper.php';

class DepartmentController extends BaseController
{
    private $departmentModel;

    public function __construct()
    {
        parent::__construct();
        $this->departmentModel = new Department();
    }

    /**
     * Hiển thị danh sách phòng ban
     */
    public function index()
    {
        // $this->checkPermission(PERMISSION_EMPLOYEE); // Tạm thời comment để test
        
        $page = $this->query('page', 1);
        $perPage = $this->query('per_page', 20);
        $search = $this->query('search', '');
        $status = $this->query('status', ''); // Hiển thị tất cả trạng thái

        $where = "1=1"; // Hiển thị tất cả phòng ban
        $params = [];
        
        // Chỉ filter theo status nếu có yêu cầu cụ thể
        if ($status) {
            $where .= " AND d.status = :status";
            $params['status'] = $status;
        }

        if ($search) {
            $where .= " AND (d.name LIKE :search OR d.code LIKE :search OR d.description LIKE :search)";
            $params['search'] = "%{$search}%";
        }

        $sql = "SELECT d.*, 
                       COUNT(u.id) as employee_count
                FROM departments d 
                LEFT JOIN users u ON d.id = u.department_id AND u.status = 'active'
                WHERE {$where}
                GROUP BY d.id
                ORDER BY d.name";

        // Count total
        $countSql = "SELECT COUNT(*) as total 
                     FROM departments d 
                     WHERE {$where}";
        $totalResult = $this->db->fetch($countSql, $params);
        $total = $totalResult['total'];
        $offset = ($page - 1) * $perPage;
        $sql .= " LIMIT {$perPage} OFFSET {$offset}";

        $departments = $this->db->fetchAll($sql, $params);

        $data = [
            'departments' => $departments,
            'filters' => [
                'search' => $search,
                'status' => $status
            ],
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'total_pages' => ceil($total / $perPage)
            ]
        ];

        ViewHelper::render('departments/index', $data);
    }

    /**
     * Hiển thị form thêm phòng ban
     */
    public function create()
    {
        $this->checkPermission(PERMISSION_EMPLOYEE);
        include __DIR__ . '/../views/departments/create.php';
    }

    /**
     * Xử lý thêm phòng ban
     */
    public function store()
    {
        $this->checkPermission(PERMISSION_EMPLOYEE);
        
        $this->validate([
            'name' => 'required|min:2',
            'code' => 'required|min:2',
            'description' => 'required|min:5',
            'status' => 'required|in:active,inactive'
        ]);

        $data = [
            'name' => $this->request->post('name'),
            'code' => $this->request->post('code'),
            'description' => $this->request->post('description'),
            'status' => $this->request->post('status')
        ];

        // Kiểm tra tên phòng ban trùng lặp
        $existing = $this->departmentModel->findBy('name', $data['name']);
        if ($existing) {
            Response::validationError(['name' => ['Tên phòng ban đã tồn tại']]);
        }

        // Kiểm tra mã phòng ban trùng lặp
        $existingCode = $this->departmentModel->findBy('code', $data['code']);
        if ($existingCode) {
            Response::validationError(['code' => ['Mã phòng ban đã tồn tại']]);
        }

        try {
            $this->db->beginTransaction();
            
            $id = $this->departmentModel->create($data);
            
            // Ghi log audit (tạm thời comment)
            // $this->logAudit('create', 'departments', $id, null, $data);
            
            $this->db->commit();

            if ($this->request->isApi()) {
                Response::success(['id' => $id], 'Thêm phòng ban thành công');
            } else {
                // Redirect về trang danh sách với thông báo thành công
                $_SESSION['success_message'] = 'Thêm phòng ban thành công';
                header('Location: /main/departments');
                exit;
            }
        } catch (Exception $e) {
            $this->db->rollback();
            Response::error('Có lỗi xảy ra khi thêm phòng ban: ' . $e->getMessage());
        }
    }

    /**
     * Hiển thị chi tiết phòng ban
     */
    public function show($id)
    {
        $this->checkPermission(PERMISSION_EMPLOYEE);
        
        $department = $this->departmentModel->findWithDetails($id);
        if (!$department) {
            Response::notFound('Không tìm thấy phòng ban');
        }

        // Check if this is an AJAX request (for edit modal)
        if ($this->request->isApi()) {
            Response::success($department);
        } else {
            // Regular page request - render view
            ViewHelper::render('departments/show', ['department' => $department]);
        }
    }

    /**
     * Hiển thị form sửa phòng ban
     */
    public function edit($id)
    {
        $this->checkPermission(PERMISSION_EMPLOYEE);
        
        $department = $this->departmentModel->find($id);
        if (!$department) {
            Response::notFound('Không tìm thấy phòng ban');
        }

        include __DIR__ . '/../views/departments/edit.php';
    }

    /**
     * Xử lý cập nhật phòng ban
     */
    public function update($id)
    {
        $this->checkPermission(PERMISSION_EMPLOYEE);
        
        $department = $this->departmentModel->find($id);
        if (!$department) {
            Response::notFound('Không tìm thấy phòng ban');
        }

        $this->validate([
            'name' => 'required|min:2',
            'code' => 'required|min:2',
            'description' => 'required|min:5',
            'status' => 'required|in:active,inactive'
        ]);

        $data = [
            'name' => $this->request->post('name'),
            'code' => $this->request->post('code'),
            'description' => $this->request->post('description'),
            'status' => $this->request->post('status')
        ];

        // Kiểm tra tên phòng ban trùng lặp (trừ chính nó)
        $existing = $this->departmentModel->findBy('name', $data['name']);
        if ($existing && $existing['id'] != $id) {
            Response::validationError(['name' => ['Tên phòng ban đã tồn tại']]);
        }

        // Kiểm tra mã phòng ban trùng lặp (trừ chính nó)
        $existingCode = $this->departmentModel->findBy('code', $data['code']);
        if ($existingCode && $existingCode['id'] != $id) {
            Response::validationError(['code' => ['Mã phòng ban đã tồn tại']]);
        }

        try {
            $this->db->beginTransaction();
            
            $this->departmentModel->update($id, $data);
            
            // Ghi log audit (tạm thời comment)
            // $this->logAudit('update', 'departments', $id, $department, $data);
            
            $this->db->commit();

            if ($this->request->isApi()) {
                Response::success(null, 'Cập nhật phòng ban thành công');
            } else {
                // Redirect về trang danh sách với thông báo thành công
                $_SESSION['success_message'] = 'Cập nhật phòng ban thành công';
                header('Location: /main/departments');
                exit;
            }
        } catch (Exception $e) {
            $this->db->rollback();
            Response::error('Có lỗi xảy ra khi cập nhật phòng ban: ' . $e->getMessage());
        }
    }

    /**
     * Xóa phòng ban
     */
    public function delete($id)
    {
        $this->checkPermission(PERMISSION_EMPLOYEE);
        
        $department = $this->departmentModel->find($id);
        if (!$department) {
            Response::notFound('Không tìm thấy phòng ban');
        }

        // Kiểm tra xem phòng ban có nhân viên không
        $employeeCount = $this->db->fetch(
            "SELECT COUNT(*) as count FROM users WHERE department_id = :id AND status = 'active'",
            ['id' => $id]
        )['count'];

        if ($employeeCount > 0) {
            if ($this->request->isApi()) {
                Response::error("Không thể xóa phòng ban vì còn {$employeeCount} nhân viên");
            } else {
                $_SESSION['error_message'] = "Không thể xóa phòng ban vì còn {$employeeCount} nhân viên";
                header('Location: /main/departments');
                exit;
            }
        }

        try {
            $this->db->beginTransaction();

            // Hard delete - xóa hoàn toàn khỏi database
            $this->departmentModel->delete($id);

            // Ghi log audit (tạm thời comment)
            // $this->logAudit('delete', 'departments', $id, $department, null);

            $this->db->commit();

            if ($this->request->isApi()) {
                Response::success(null, 'Xóa phòng ban thành công');
            } else {
                // Redirect về trang danh sách với thông báo thành công
                $_SESSION['success_message'] = 'Xóa phòng ban thành công';
                header('Location: /main/departments');
                exit;
            }
        } catch (Exception $e) {
            $this->db->rollback();
            Response::error('Có lỗi xảy ra khi xóa phòng ban: ' . $e->getMessage());
        }
    }

    /**
     * Tìm kiếm phòng ban (API)
     */
    public function search()
    {
        $this->checkPermission(PERMISSION_EMPLOYEE);
        
        $search = $this->query('q', '');
        $departments = $this->departmentModel->search($search);
        
        Response::success($departments);
    }

    /**
     * Thống kê phòng ban (API)
     */
    public function stats()
    {
        $this->checkPermission(PERMISSION_EMPLOYEE);
        
        $stats = $this->departmentModel->getStats();
        Response::success($stats);
    }

    /**
     * Lấy HTML form thêm phòng ban
     */
    private function getCreateFormHtml()
    {
        ob_start();
        ?>
        <form id="addDepartmentForm">
            <div class="mb-3">
                <label class="form-label">Tên phòng ban <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="add_name" name="name" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Mô tả <span class="text-danger">*</span></label>
                <textarea class="form-control" id="add_description" name="description" rows="3" required></textarea>
            </div>
        </form>
        <?php
        return ob_get_clean();
    }

    /**
     * Lấy HTML form sửa phòng ban
     */
    private function getEditFormHtml($department)
    {
        ob_start();
        ?>
        <form id="editDepartmentForm">
            <input type="hidden" id="edit_id" name="id" value="<?php echo $department['id']; ?>">
            <div class="mb-3">
                <label class="form-label">Tên phòng ban <span class="text-danger">*</span></label>
                <input type="text" class="form-control" id="edit_name" name="name" value="<?php echo htmlspecialchars($department['name']); ?>" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Mô tả <span class="text-danger">*</span></label>
                <textarea class="form-control" id="edit_description" name="description" rows="3" required><?php echo htmlspecialchars($department['description']); ?></textarea>
            </div>
        </form>
        <?php
        return ob_get_clean();
    }
}
