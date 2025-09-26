<?php
require_once __DIR__ . '/../core/BaseController.php';
require_once __DIR__ . '/../models/User.php';
require_once __DIR__ . '/../models/Department.php';
require_once __DIR__ . '/../models/Role.php';
require_once __DIR__ . '/../models/Position.php';
require_once __DIR__ . '/../helpers/ViewHelper.php';

/**
 * Controller quản lý nhân sự
 */
class EmployeeController extends BaseController
{
    private $userModel;
    private $departmentModel;
    private $roleModel;
    private $positionModel;

    public function __construct()
    {
        parent::__construct();
        $this->userModel = new User();
        $this->departmentModel = new Department();
        $this->roleModel = new Role();
        $this->positionModel = new Position();
    }

    /**
     * Danh sách nhân viên
     */
    public function index()
    {
        $this->checkPermission(PERMISSION_EMPLOYEE);
        
        $page = $this->query('page', 1);
        $perPage = $this->query('per_page', 20);
        $search = $this->query('search', '');
        $departmentId = $this->query('department_id', '');
        $roleId = $this->query('role_id', '');
        $status = $this->query('status', ''); // Hiển thị tất cả trạng thái

        $where = "1=1"; // Hiển thị tất cả nhân viên
        $params = [];
        
        // Chỉ filter theo status nếu có yêu cầu cụ thể
        if ($status) {
            $where .= " AND u.status = :status";
            $params['status'] = $status;
        }

        if ($search) {
            $where .= " AND (u.first_name LIKE :search OR u.last_name LIKE :search OR u.employee_id LIKE :search OR u.email LIKE :search)";
            $params['search'] = "%{$search}%";
        }

        if ($departmentId) {
            $where .= " AND u.department_id = :department_id";
            $params['department_id'] = $departmentId;
        }

        if ($roleId) {
            $where .= " AND u.role_id = :role_id";
            $params['role_id'] = $roleId;
        }

        $sql = "SELECT u.*, r.name as role_name, d.name as department_name, p.name as position_name 
                FROM users u 
                LEFT JOIN roles r ON u.role_id = r.id 
                LEFT JOIN departments d ON u.department_id = d.id 
                LEFT JOIN positions p ON u.position_id = p.id 
                WHERE {$where}
                ORDER BY u.first_name, u.last_name";

        // Count total with proper JOIN
        $countSql = "SELECT COUNT(*) as total 
                     FROM users u 
                     LEFT JOIN roles r ON u.role_id = r.id 
                     LEFT JOIN departments d ON u.department_id = d.id 
                     LEFT JOIN positions p ON u.position_id = p.id 
                     WHERE {$where}";
        $totalResult = $this->db->fetch($countSql, $params);
        $total = $totalResult['total'];
        $offset = ($page - 1) * $perPage;
        $sql .= " LIMIT {$perPage} OFFSET {$offset}";

        $employees = $this->db->fetchAll($sql, $params);
        $departments = $this->departmentModel->getForSelect();
        $roles = $this->roleModel->getForSelect();
        $positions = $this->positionModel->getForSelect();

        $data = [
            'employees' => $employees,
            'departments' => $departments,
            'roles' => $roles,
            'positions' => $positions,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'total_pages' => ceil($total / $perPage)
            ],
            'filters' => [
                'search' => $search,
                'department_id' => $departmentId,
                'role_id' => $roleId,
                'status' => $status
            ]
        ];

        ViewHelper::render('employees/index', $data);
    }

    /**
     * Tạo nhân viên mới
     */
    public function create()
    {
        $this->checkPermission(PERMISSION_EMPLOYEE);
        
        $departments = $this->departmentModel->getForSelect();
        $roles = $this->roleModel->getForSelect();
        $positions = $this->positionModel->getForSelect();

        ViewHelper::render('employees/create', [
            'departments' => $departments,
            'roles' => $roles,
            'positions' => $positions
        ]);
    }

    /**
     * Lưu nhân viên mới
     */
    public function store()
    {
        // $this->checkPermission(PERMISSION_EMPLOYEE); // Comment out for debugging
        
        $this->validate([
            'employee_id' => 'required',
            'username' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:4',
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'position_id' => 'required|numeric',
            'role_id' => 'required|numeric',
            'department_id' => 'required|numeric'
        ]);

        $data = $this->input();
        $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
        unset($data['password']);

        try {
            $this->db->beginTransaction();

            // Kiểm tra employee_id và username đã tồn tại chưa
            $existingEmployee = $this->userModel->findBy('employee_id', $data['employee_id']);
            if ($existingEmployee) {
                Response::error('Mã nhân viên đã tồn tại');
            }

            $existingUsername = $this->userModel->findBy('username', $data['username']);
            if ($existingUsername) {
                Response::error('Tên đăng nhập đã tồn tại');
            }

            $existingEmail = $this->userModel->findBy('email', $data['email']);
            if ($existingEmail) {
                Response::error('Email đã tồn tại');
            }

            // Tạo nhân viên mới
            $userId = $this->userModel->create($data);

            // Ghi log audit (tạm thời comment)
            // $this->logAudit('create', 'users', $userId, null, $data);

            $this->db->commit();

            if ($this->request->isApi()) {
                Response::success(['id' => $userId], 'Tạo nhân viên thành công');
            } else {
                Response::success(['id' => $userId], 'Tạo nhân viên thành công');
            }

        } catch (Exception $e) {
            $this->db->rollback();
            Response::error('Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Chi tiết nhân viên
     */
    public function show($id)
    {
        // $this->checkPermission(PERMISSION_EMPLOYEE); // Comment out for debugging
        
        $employee = $this->userModel->findWithDetails($id);
        if (!$employee) {
            Response::notFound('Không tìm thấy nhân viên');
        }

        // Check if this is an AJAX request (for edit modal)
        if ($this->request->isApi()) {
            Response::success($employee);
        } else {
            // Regular page request - render view
            ViewHelper::render('employees/show', ['employee' => $employee]);
        }
    }

    /**
     * Chỉnh sửa nhân viên
     */
    public function edit($id)
    {
        $this->checkPermission(PERMISSION_EMPLOYEE);
        
        $employee = $this->userModel->findWithDetails($id);
        if (!$employee) {
            Response::notFound('Không tìm thấy nhân viên');
        }

        $departments = $this->departmentModel->getForSelect();
        $roles = $this->roleModel->getForSelect();

        ViewHelper::render('employees/edit', [
            'employee' => $employee,
            'departments' => $departments,
            'roles' => $roles
        ]);
    }

    /**
     * Cập nhật nhân viên
     */
    public function update($id)
    {
        // $this->checkPermission(PERMISSION_EMPLOYEE); // Comment out for debugging
        
        $employee = $this->userModel->find($id);
        if (!$employee) {
            Response::notFound('Không tìm thấy nhân viên');
        }

        $this->validate([
            'employee_id' => 'required',
            'username' => 'required',
            'email' => 'required|email',
            'first_name' => 'required',
            'last_name' => 'required',
            'phone' => 'required',
            'position_id' => 'required|numeric',
            'role_id' => 'required|numeric',
            'department_id' => 'required|numeric'
        ]);

        $data = $this->input();
        $oldData = $employee;

        try {
            $this->db->beginTransaction();

            // Kiểm tra employee_id đã tồn tại chưa (trừ chính nó)
            $existingEmployee = $this->userModel->findBy('employee_id', $data['employee_id']);
            if ($existingEmployee && $existingEmployee['id'] != $id) {
                Response::error('Mã nhân viên đã tồn tại');
            }

            // Kiểm tra username đã tồn tại chưa (trừ chính nó)
            $existingUsername = $this->userModel->findBy('username', $data['username']);
            if ($existingUsername && $existingUsername['id'] != $id) {
                Response::error('Tên đăng nhập đã tồn tại');
            }

            // Kiểm tra email đã tồn tại chưa (trừ chính nó)
            $existingEmail = $this->userModel->findBy('email', $data['email']);
            if ($existingEmail && $existingEmail['id'] != $id) {
                Response::error('Email đã tồn tại');
            }

            // Cập nhật nhân viên
            $this->userModel->update($id, $data);

            // Ghi log audit (tạm thời comment)
            // $this->logAudit('update', 'users', $id, $oldData, $data);

            $this->db->commit();

            if ($this->request->isApi()) {
                Response::success(null, 'Cập nhật nhân viên thành công');
            } else {
                Response::success(null, 'Cập nhật nhân viên thành công');
            }

        } catch (Exception $e) {
            $this->db->rollback();
            Response::error('Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Xóa nhân viên
     */
    public function delete($id)
    {
        // $this->checkPermission(PERMISSION_EMPLOYEE); // Comment out for debugging
        
        $employee = $this->userModel->find($id);
        if (!$employee) {
            Response::notFound('Không tìm thấy nhân viên');
        }

        // Không cho phép xóa chính mình
        if ($id == $this->getCurrentUserId()) {
            Response::error('Không thể xóa chính mình');
        }

        try {
            $this->db->beginTransaction();

            // Hard delete - xóa hoàn toàn khỏi database
            $this->userModel->delete($id);

            // Ghi log audit (tạm thời comment)
            // $this->logAudit('delete', 'users', $id, $employee, null);

            $this->db->commit();

            if ($this->request->isApi()) {
                Response::success(null, 'Xóa nhân viên thành công');
            } else {
                Response::success(null, 'Xóa nhân viên thành công');
            }

        } catch (Exception $e) {
            $this->db->rollback();
            Response::error('Có lỗi xảy ra: ' . $e->getMessage());
        }
    }

    /**
     * Tìm kiếm nhân viên (API)
     */
    public function search()
    {
        $this->checkPermission(PERMISSION_EMPLOYEE);
        
        $keyword = $this->query('q', '');
        $departmentId = $this->query('department_id', '');
        $roleId = $this->query('role_id', '');

        if (empty($keyword)) {
            Response::error('Vui lòng nhập từ khóa tìm kiếm');
        }

        $employees = $this->userModel->search($keyword, $departmentId, $roleId);
        
        Response::success($employees);
    }

    /**
     * Lấy thống kê nhân viên
     */
    public function stats()
    {
        $this->checkPermission(PERMISSION_EMPLOYEE);
        
        $stats = $this->userModel->getStats();
        $departmentStats = $this->departmentModel->getStats();
        
        Response::success([
            'user_stats' => $stats,
            'department_stats' => $departmentStats
        ]);
    }

    /**
     * Reset mật khẩu nhân viên
     */
    public function resetPassword($id)
    {
        $this->checkPermission(PERMISSION_EMPLOYEE);
        
        $employee = $this->userModel->find($id);
        if (!$employee) {
            Response::notFound('Không tìm thấy nhân viên');
        }

        $newPassword = $this->post('new_password', '');
        if (empty($newPassword)) {
            Response::error('Vui lòng nhập mật khẩu mới');
        }

        try {
            $this->userModel->updatePassword($id, $newPassword);
            
            // Ghi log audit (tạm thời comment)
            // $this->logAudit('reset_password', 'users', $id, null, ['action' => 'password_reset']);

            Response::success(null, 'Reset mật khẩu thành công');

        } catch (Exception $e) {
            Response::error('Có lỗi xảy ra: ' . $e->getMessage());
        }
    }
}
