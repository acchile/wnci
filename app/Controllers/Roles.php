<?php

namespace App\Controllers;

use App\Models\RoleModel;

class Roles extends BaseController
{
    protected $roleModel;
    
    public function __construct()
    {
        $this->roleModel = new RoleModel();
    }
    
    public function index()
    {
        // Check permission
        if (!in_array('roles.view', $this->data['permissions'])) {
            return redirect()->to('/dashboard')->with('error', 'No permission to view roles');
        }
        
        $this->data['page_title'] = 'Roles Management';
        $this->data['roles'] = $this->roleModel->getAllRoles();
        
        // Get permission count for each role
        foreach ($this->data['roles'] as &$role) {
            $permissions = $this->roleModel->getRolePermissions($role->id);
            $role->permission_count = count($permissions);
        }
        
        return view('roles/index', $this->data);
    }
    
    public function create()
    {
        // Check permission
        if (!in_array('roles.create', $this->data['permissions'])) {
            return redirect()->to('/dashboard')->with('error', 'No permission to create roles');
        }
        
        $this->data['page_title'] = 'Create Role';
        $this->data['all_permissions'] = $this->roleModel->getAllPermissions();
        $this->data['validation'] = \Config\Services::validation();
        
        return view('roles/create', $this->data);
    }
    
    public function store()
    {
        // Check permission
        if (!in_array('roles.create', $this->data['permissions'])) {
            return redirect()->to('/dashboard')->with('error', 'No permission to create roles');
        }
        
        $rules = [
            'name' => 'required|min_length[3]|max_length[50]|is_unique[roles.name]|alpha_dash',
            'description' => 'permit_empty|max_length[255]',
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }
        
        $data = [
            'name' => strtolower($this->request->getPost('name')),
            'description' => $this->request->getPost('description'),
        ];
        
        $roleId = $this->roleModel->insert($data);
        
        if ($roleId) {
            // Set permissions
            $permissions = $this->request->getPost('permissions') ?: [];
            $this->roleModel->setRolePermissions($roleId, $permissions);
            
            return redirect()->to('/roles')->with('success', 'Role created successfully');
        }
        
        return redirect()->back()->withInput()->with('error', 'Failed to create role');
    }
    
    public function edit($id)
    {
        // Check permission
        if (!in_array('roles.edit', $this->data['permissions'])) {
            return redirect()->to('/dashboard')->with('error', 'No permission to edit roles');
        }
        
        $role = $this->roleModel->find($id);
        
        if (!$role) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        
        $this->data['page_title'] = 'Edit Role';
        $this->data['role'] = $role;
        $this->data['all_permissions'] = $this->roleModel->getAllPermissions();
        $this->data['role_permissions'] = $this->roleModel->getRolePermissions($id);
        $this->data['validation'] = \Config\Services::validation();
        
        return view('roles/edit', $this->data);
    }
    
    public function update($id)
    {
        // Check permission
        if (!in_array('roles.edit', $this->data['permissions'])) {
            return redirect()->to('/dashboard')->with('error', 'No permission to edit roles');
        }
        
        $rules = [
            'description' => 'permit_empty|max_length[255]',
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }
        
        $data = [
            'description' => $this->request->getPost('description'),
        ];
        
        if ($this->roleModel->update($id, $data)) {
            // Update permissions
            $permissions = $this->request->getPost('permissions') ?: [];
            $this->roleModel->setRolePermissions($id, $permissions);
            
            return redirect()->to('/roles')->with('success', 'Role updated successfully');
        }
        
        return redirect()->back()->withInput()->with('error', 'Failed to update role');
    }
    
    public function delete($id)
    {
        // Check permission
        if (!in_array('roles.delete', $this->data['permissions'])) {
            return redirect()->to('/dashboard')->with('error', 'No permission to delete roles');
        }
        
        // Check if role has users
        $db = \Config\Database::connect();
        $userCount = $db->table('users')->where('role_id', $id)->countAllResults();
        
        if ($userCount > 0) {
            return redirect()->to('/roles')->with('error', "Cannot delete role. It has {$userCount} users assigned.");
        }
        
        if ($this->roleModel->delete($id)) {
            return redirect()->to('/roles')->with('success', 'Role deleted successfully');
        }
        
        return redirect()->to('/roles')->with('error', 'Failed to delete role');
    }
}