<?php

namespace App\Controllers;

use App\Models\GroupTypeModel;

class GroupTypes extends BaseController
{
    protected $groupTypeModel;
    protected $session;

    public function __construct()
    {
        $this->groupTypeModel = new GroupTypeModel();
        $this->session = \Config\Services::session();
        helper(['form', 'url']);
    }

    public function index()
    {
        // Check permission
        if (!$this->checkPermission('view_group_types')) {
            return redirect()->to('/dashboard')->with('error', 'Not enough privilege');
        }

        $data = [
            'title' => 'Group Types',
            'groupTypes' => $this->groupTypeModel->orderBy('name', 'ASC')->findAll()
        ];

        return view('groups/types/index', $data);
    }

    public function create()
    {
        // Check permission
        if (!$this->checkPermission('create_group_types')) {
            return redirect()->to('/group-types')->with('error', 'Not enough privilege');
        }

        $data = [
            'title' => 'Create Group Type',
            'validation' => \Config\Services::validation()
        ];

        return view('groups/types/create', $data);
    }

    public function store()
    {
        // Check permission
        if (!$this->checkPermission('create_group_types')) {
            return redirect()->to('/group-types')->with('error', 'Not enough privilege');
        }

        $rules = [
            'name' => 'required|min_length[3]|max_length[100]|is_unique[group_types.name]',
            'description' => 'permit_empty|max_length[500]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'created_by' => session()->get('user_id')
        ];

        if ($this->groupTypeModel->insert($data)) {
            return redirect()->to('/group-types')->with('success', 'Group type created successfully');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to create group type');
        }
    }

    public function edit($id)
    {
        // Check permission
        if (!$this->checkPermission('edit_group_types')) {
            return redirect()->to('/group-types')->with('error', 'Not enough privilege');
        }

        $groupType = $this->groupTypeModel->find($id);

        if (!$groupType) {
            return redirect()->to('/group-types')->with('error', 'Group type not found');
        }

        $data = [
            'title' => 'Edit Group Type',
            'groupType' => $groupType,
            'validation' => \Config\Services::validation()
        ];

        return view('groups/types/edit', $data);
    }

    public function update($id)
    {
        // Check permission
        if (!$this->checkPermission('edit_group_types')) {
            return redirect()->to('/group-types')->with('error', 'Not enough privilege');
        }

        $groupType = $this->groupTypeModel->find($id);

        if (!$groupType) {
            return redirect()->to('/group-types')->with('error', 'Group type not found');
        }

        $rules = [
            'name' => "required|min_length[3]|max_length[100]|is_unique[group_types.name,id,{$id}]",
            'description' => 'permit_empty|max_length[500]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'name' => $this->request->getPost('name'),
            'description' => $this->request->getPost('description'),
            'updated_by' => session()->get('user_id')
        ];

        if ($this->groupTypeModel->update($id, $data)) {
            return redirect()->to('/group-types')->with('success', 'Group type updated successfully');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update group type');
        }
    }

    public function delete($id)
    {
        // Check permission
        if (!$this->checkPermission('delete_group_types')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Not enough privilege']);
        }

        $groupType = $this->groupTypeModel->find($id);

        if (!$groupType) {
            return $this->response->setJSON(['success' => false, 'message' => 'Group type not found']);
        }

        // Check if group type is being used
        $countryGroupModel = new \App\Models\CountryGroupModel();
        $usageCount = $countryGroupModel->where('group_type_id', $id)->countAllResults();

        if ($usageCount > 0) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Cannot delete group type. It is being used by ' . $usageCount . ' group(s).'
            ]);
        }

        if ($this->groupTypeModel->delete($id)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Group type deleted successfully']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete group type']);
        }
    }

    protected function checkPermission($permission)
    {
        // If you have a permission system, implement it here
        // For now, returning true to allow all actions
        return true;
        
        // Example with role-based permissions:
        // $userRole = session()->get('role');
        // $rolePermissionModel = new \App\Models\RolePermissionModel();
        // return $rolePermissionModel->hasPermission($userRole, $permission);
    }
}