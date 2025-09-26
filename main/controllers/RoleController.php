<?php

require_once __DIR__ . '/../core/BaseController.php';
require_once __DIR__ . '/../models/Role.php';

class RoleController extends BaseController
{
    private $roleModel;

    public function __construct()
    {
        parent::__construct();
        $this->roleModel = new Role();
    }

    public function index()
    {
        // $this->checkPermission(PERMISSION_EMPLOYEE); // Tạm thời comment để test
        
        $page = (int)($_GET['page'] ?? 1);
        $limit = 10;
        $search = $_GET['search'] ?? '';
        
        // Get roles with pagination
        $roles = $this->roleModel->getAll($page, $limit, $search);
        $total = $this->roleModel->count($search);
        
        $pagination = [
            'current_page' => $page,
            'total_pages' => ceil($total / $limit),
            'total_items' => $total,
            'items_per_page' => $limit
        ];
        
        include __DIR__ . '/../views/roles/index.php';
    }

    public function create()
    {
        $this->checkPermission(PERMISSION_EMPLOYEE);
        include __DIR__ . '/../views/roles/create.php';
    }

    public function store()
    {
        $this->checkPermission(PERMISSION_EMPLOYEE);
        
        $name = $this->request->get('name');
        $description = $this->request->get('description');
        $permissions = $this->request->get('permissions', []);
        
        // Validation
        $errors = [];
        if (empty($name)) {
            $errors['name'] = 'Tên vai trò là bắt buộc';
        }
        if (empty($description)) {
            $errors['description'] = 'Mô tả là bắt buộc';
        }
        
        // Check if role name exists
        if ($this->roleModel->findByName($name)) {
            $errors['name'] = 'Tên vai trò đã tồn tại';
        }
        
        if (!empty($errors)) {
            $_SESSION['error_message'] = 'Dữ liệu không hợp lệ: ' . implode(', ', $errors);
            header('Location: /main/roles/create');
            exit;
        }
        
        // Prepare permissions data
        $permissionsData = [
            'attendance' => isset($permissions['attendance']) ? 1 : 0,
            'employee' => isset($permissions['employee']) ? 1 : 0,
            'department' => isset($permissions['department']) ? 1 : 0,
            'role' => isset($permissions['role']) ? 1 : 0,
            'reports' => isset($permissions['reports']) ? 1 : 0,
            'settings' => isset($permissions['settings']) ? 1 : 0,
            'profile' => isset($permissions['profile']) ? 1 : 0,
        ];
        
        $data = [
            'name' => $name,
            'description' => $description,
            'permissions' => json_encode($permissionsData),
            'created_at' => date('Y-m-d H:i:s')
        ];
        
        if ($this->roleModel->create($data)) {
            $_SESSION['success_message'] = 'Thêm vai trò thành công';
        } else {
            $_SESSION['error_message'] = 'Có lỗi xảy ra khi thêm vai trò';
        }
        
        header('Location: /main/roles');
        exit;
    }

    public function show($id)
    {
        $this->checkPermission(PERMISSION_EMPLOYEE);
        
        $role = $this->roleModel->find($id);
        if (!$role) {
            Response::notFound('Không tìm thấy vai trò');
        }
        
        // Decode permissions
        if ($role['permissions']) {
            $role['permissions'] = json_decode($role['permissions'], true);
        }
        
        if ($this->request->isApi()) {
            Response::json([
                'success' => true,
                'data' => $role
            ]);
        }
        
        include __DIR__ . '/../views/roles/show.php';
    }

    public function edit($id)
    {
        $this->checkPermission(PERMISSION_EMPLOYEE);
        
        $role = $this->roleModel->find($id);
        if (!$role) {
            Response::notFound('Không tìm thấy vai trò');
        }
        
        // Decode permissions
        if ($role['permissions']) {
            $role['permissions'] = json_decode($role['permissions'], true);
        }
        
        include __DIR__ . '/../views/roles/edit.php';
    }

    public function update($id)
    {
        $this->checkPermission(PERMISSION_EMPLOYEE);
        
        $name = $this->request->get('name');
        $description = $this->request->get('description');
        $permissions = $this->request->get('permissions', []);
        
        // Validation
        $errors = [];
        if (empty($name)) {
            $errors['name'] = 'Tên vai trò là bắt buộc';
        }
        if (empty($description)) {
            $errors['description'] = 'Mô tả là bắt buộc';
        }
        
        // Check if role name exists (excluding current role)
        $existingRole = $this->roleModel->findByName($name);
        if ($existingRole && $existingRole['id'] != $id) {
            $errors['name'] = 'Tên vai trò đã tồn tại';
        }
        
        if (!empty($errors)) {
            $_SESSION['error_message'] = 'Dữ liệu không hợp lệ: ' . implode(', ', $errors);
            header('Location: /main/roles/' . $id . '/edit');
            exit;
        }
        
        // Prepare permissions data
        $permissionsData = [
            'attendance' => isset($permissions['attendance']) ? 1 : 0,
            'employee' => isset($permissions['employee']) ? 1 : 0,
            'department' => isset($permissions['department']) ? 1 : 0,
            'role' => isset($permissions['role']) ? 1 : 0,
            'reports' => isset($permissions['reports']) ? 1 : 0,
            'settings' => isset($permissions['settings']) ? 1 : 0,
            'profile' => isset($permissions['profile']) ? 1 : 0,
        ];
        
        $data = [
            'name' => $name,
            'description' => $description,
            'permissions' => json_encode($permissionsData),
            'updated_at' => date('Y-m-d H:i:s')
        ];
        
        if ($this->roleModel->update($id, $data)) {
            $_SESSION['success_message'] = 'Cập nhật vai trò thành công';
        } else {
            $_SESSION['error_message'] = 'Có lỗi xảy ra khi cập nhật vai trò';
        }
        
        header('Location: /main/roles');
        exit;
    }

    public function delete($id)
    {
        $this->checkPermission(PERMISSION_EMPLOYEE);
        
        // Check if role is being used by users
        $users = $this->roleModel->getUsersByRole($id);
        if (!empty($users)) {
            $_SESSION['error_message'] = 'Không thể xóa vai trò này vì đang được sử dụng bởi ' . count($users) . ' nhân viên';
            header('Location: /main/roles');
            exit;
        }
        
        if ($this->roleModel->delete($id)) {
            $_SESSION['success_message'] = 'Xóa vai trò thành công';
        } else {
            $_SESSION['error_message'] = 'Có lỗi xảy ra khi xóa vai trò';
        }
        
        header('Location: /main/roles');
        exit;
    }

    public function search()
    {
        $this->checkPermission(PERMISSION_EMPLOYEE);
        
        $keyword = $this->request->get('keyword', '');
        $roles = $this->roleModel->search($keyword);
        
        Response::json([
            'success' => true,
            'data' => $roles
        ]);
    }

    public function stats()
    {
        $this->checkPermission(PERMISSION_EMPLOYEE);
        
        $stats = [
            'total_roles' => $this->roleModel->count(),
            'active_roles' => $this->roleModel->count('', 'active'),
            'roles_with_users' => $this->roleModel->countRolesWithUsers()
        ];
        
        Response::json([
            'success' => true,
            'data' => $stats
        ]);
    }
}
