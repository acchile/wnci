<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\RoleModel;

class Users extends BaseController
{
    protected $userModel;
    protected $roleModel;
    
    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->roleModel = new RoleModel();
    }
    
    public function index()
    {
        // Check permission
        if (!in_array('users.view', $this->data['permissions'])) {
            return redirect()->to('/dashboard')->with('error', 'No permission to view users');
        }
        
        $this->data['page_title'] = 'Users Management';
        $this->data['users'] = $this->userModel->getAllUsersWithRoles();
        
        return view('users/index', $this->data);
    }
    
    public function create()
    {
        // Check permission
        if (!in_array('users.create', $this->data['permissions'])) {
            return redirect()->to('/dashboard')->with('error', 'No permission to create users');
        }
        
        $this->data['page_title'] = 'Create User';
        $this->data['roles'] = $this->roleModel->getAllRoles();
        $this->data['validation'] = \Config\Services::validation();
        
        return view('users/create', $this->data);
    }
    
    public function store()
    {
        // Check permission
        if (!in_array('users.create', $this->data['permissions'])) {
            return redirect()->to('/dashboard')->with('error', 'No permission to create users');
        }
        
        $rules = [
            'username' => 'required|min_length[3]|max_length[50]|is_unique[users.username]',
            'email' => 'required|valid_email|is_unique[users.email]',
            'password' => 'required|min_length[6]',
            'first_name' => 'required|min_length[2]',
            'last_name' => 'required|min_length[2]',
            'role_id' => 'required|integer',
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }
        
        // Hash password manually
        $password = $this->request->getPost('password');
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
        
        $data = [
            'username' => $this->request->getPost('username'),
            'email' => $this->request->getPost('email'),
            'password' => $hashedPassword,
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'role_id' => $this->request->getPost('role_id'),
            'language' => $this->request->getPost('language') ?: 'en',
            'status' => $this->request->getPost('status') ?: 'active',
        ];
        
        if ($this->userModel->insert($data)) {
            return redirect()->to('/users')->with('success', 'User created successfully');
        }
        
        return redirect()->back()->withInput()->with('error', 'Failed to create user');
    }
    
    public function edit($id)
    {
        // Check permission
        if (!in_array('users.edit', $this->data['permissions'])) {
            return redirect()->to('/dashboard')->with('error', 'No permission to edit users');
        }
        
        $user = $this->userModel->getUserWithRole($id);
        
        if (!$user) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        
        $this->data['page_title'] = 'Edit User';
        $this->data['user'] = $user;
        $this->data['roles'] = $this->roleModel->getAllRoles();
        $this->data['validation'] = \Config\Services::validation();
        
        return view('users/edit', $this->data);
    }
    
    public function update($id)
    {
        // Check permission
        if (!in_array('users.edit', $this->data['permissions'])) {
            return redirect()->to('/dashboard')->with('error', 'No permission to edit users');
        }
        
        $rules = [
            'email' => "required|valid_email|is_unique[users.email,id,{$id}]",
            'first_name' => 'required|min_length[2]',
            'last_name' => 'required|min_length[2]',
            'role_id' => 'required|integer',
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }
        
        $data = [
            'email' => $this->request->getPost('email'),
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'role_id' => $this->request->getPost('role_id'),
            'language' => $this->request->getPost('language'),
            'status' => $this->request->getPost('status'),
        ];
        
        // Update password only if provided
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_BCRYPT);
        }
        
        if ($this->userModel->update($id, $data)) {
            return redirect()->to('/users')->with('success', 'User updated successfully');
        }
        
        return redirect()->back()->withInput()->with('error', 'Failed to update user');
    }
    
    public function delete($id)
    {
        // Check permission
        if (!in_array('users.delete', $this->data['permissions'])) {
            return redirect()->to('/dashboard')->with('error', 'No permission to delete users');
        }
        
        // Prevent deleting yourself
        if ($id == session()->get('user_id')) {
            return redirect()->to('/users')->with('error', 'You cannot delete your own account');
        }
        
        if ($this->userModel->delete($id)) {
            return redirect()->to('/users')->with('success', 'User deleted successfully');
        }
        
        return redirect()->to('/users')->with('error', 'Failed to delete user');
    }
}