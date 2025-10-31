<?php

namespace App\Controllers;

use App\Models\CountryGroupModel;
use App\Models\GroupTypeModel;

class CountryGroups extends BaseController
{
    protected $countryGroupModel;
    protected $groupTypeModel;
    protected $session;

    public function __construct()
    {
        $this->countryGroupModel = new CountryGroupModel();
        $this->groupTypeModel = new GroupTypeModel();
        $this->session = \Config\Services::session();
        helper(['form', 'url', 'filesystem']);
    }

    public function index()
    {
        // Check permission
        if (!$this->checkPermission('view_country_groups')) {
            return redirect()->to('/dashboard')->with('error', 'Not enough privilege');
        }

        $data = [
            'title' => 'Country Groups',
            'groups' => $this->countryGroupModel->getGroupsWithTypes()
        ];

        return view('admin/country_groups/index', $data);
    }

    public function create()
    {
        // Check permission
        if (!$this->checkPermission('create_country_groups')) {
            return redirect()->to('/country-groups')->with('error', 'Not enough privilege');
        }

        $data = [
            'title' => 'Create Country Group',
            'groupTypes' => $this->groupTypeModel->orderBy('name', 'ASC')->findAll(),
            'validation' => \Config\Services::validation()
        ];

        return view('admin/country_groups/create', $data);
    }

    public function store()
    {
        // Check permission
        if (!$this->checkPermission('create_country_groups')) {
            return redirect()->to('/country-groups')->with('error', 'Not enough privilege');
        }

        $rules = [
            'group_type_id' => 'required|is_not_unique[group_types.id]',
            'name' => 'required|min_length[2]|max_length[200]|is_unique[country_groups.name]',
            'acronym' => 'permit_empty|max_length[20]',
            'description' => 'permit_empty|max_length[2000]',
            'website' => 'permit_empty|valid_url|max_length[255]',
            'founding_year' => 'permit_empty|integer|max_length[4]',
            'headquarters' => 'permit_empty|max_length[200]',
            'logo' => 'permit_empty|uploaded[logo]|max_size[logo,2048]|ext_in[logo,png,jpg,jpeg,svg,webp]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'group_type_id' => $this->request->getPost('group_type_id'),
            'name' => $this->request->getPost('name'),
            'acronym' => $this->request->getPost('acronym'),
            'description' => $this->request->getPost('description'),
            'website' => $this->request->getPost('website'),
            'founding_year' => $this->request->getPost('founding_year'),
            'headquarters' => $this->request->getPost('headquarters'),
            'slug' => url_title($this->request->getPost('name'), '-', true),
            'created_by' => session()->get('user_id')
        ];

        // Handle logo upload
        $logo = $this->request->getFile('logo');
        if ($logo && $logo->isValid() && !$logo->hasMoved()) {
            $newName = $logo->getRandomName();
            $logo->move(FCPATH . 'uploads/group_logos', $newName);
            $data['logo'] = $newName;
        }

        if ($this->countryGroupModel->insert($data)) {
            return redirect()->to('/country-groups')->with('success', 'Country group created successfully');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to create country group');
        }
    }

    public function edit($id)
    {
        // Check permission
        if (!$this->checkPermission('edit_country_groups')) {
            return redirect()->to('/country-groups')->with('error', 'Not enough privilege');
        }

        $group = $this->countryGroupModel->find($id);

        if (!$group) {
            return redirect()->to('/country-groups')->with('error', 'Country group not found');
        }

        $data = [
            'title' => 'Edit Country Group',
            'group' => $group,
            'groupTypes' => $this->groupTypeModel->orderBy('name', 'ASC')->findAll(),
            'validation' => \Config\Services::validation()
        ];

        return view('admin/country_groups/edit', $data);
    }

    public function update($id)
    {
        // Check permission
        if (!$this->checkPermission('edit_country_groups')) {
            return redirect()->to('/country-groups')->with('error', 'Not enough privilege');
        }

        $group = $this->countryGroupModel->find($id);

        if (!$group) {
            return redirect()->to('/country-groups')->with('error', 'Country group not found');
        }

        $rules = [
            'group_type_id' => 'required|is_not_unique[group_types.id]',
            'name' => "required|min_length[2]|max_length[200]|is_unique[country_groups.name,id,{$id}]",
            'acronym' => 'permit_empty|max_length[20]',
            'description' => 'permit_empty|max_length[2000]',
            'website' => 'permit_empty|valid_url|max_length[255]',
            'founding_year' => 'permit_empty|integer|max_length[4]',
            'headquarters' => 'permit_empty|max_length[200]',
            'logo' => 'permit_empty|uploaded[logo]|max_size[logo,2048]|ext_in[logo,png,jpg,jpeg,svg,webp]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $data = [
            'group_type_id' => $this->request->getPost('group_type_id'),
            'name' => $this->request->getPost('name'),
            'acronym' => $this->request->getPost('acronym'),
            'description' => $this->request->getPost('description'),
            'website' => $this->request->getPost('website'),
            'founding_year' => $this->request->getPost('founding_year'),
            'headquarters' => $this->request->getPost('headquarters'),
            'slug' => url_title($this->request->getPost('name'), '-', true),
            'updated_by' => session()->get('user_id')
        ];

        // Handle logo upload
        $logo = $this->request->getFile('logo');
        if ($logo && $logo->isValid() && !$logo->hasMoved()) {
            // Delete old logo if exists
            if ($group['logo'] && file_exists(FCPATH . 'uploads/group_logos/' . $group['logo'])) {
                unlink(FCPATH . 'uploads/group_logos/' . $group['logo']);
            }
            
            $newName = $logo->getRandomName();
            $logo->move(FCPATH . 'uploads/group_logos', $newName);
            $data['logo'] = $newName;
        }

        if ($this->countryGroupModel->update($id, $data)) {
            return redirect()->to('/country-groups')->with('success', 'Country group updated successfully');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update country group');
        }
    }

    public function delete($id)
    {
        // Check permission
        if (!$this->checkPermission('delete_country_groups')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Not enough privilege']);
        }

        $group = $this->countryGroupModel->find($id);

        if (!$group) {
            return $this->response->setJSON(['success' => false, 'message' => 'Country group not found']);
        }

        // Check if group has members
        $memberModel = new \App\Models\CountryGroupMemberModel();
        $memberCount = $memberModel->where('country_group_id', $id)->countAllResults();

        if ($memberCount > 0) {
            return $this->response->setJSON([
                'success' => false, 
                'message' => 'Cannot delete group. It has ' . $memberCount . ' member(s). Remove all members first.'
            ]);
        }

        // Delete logo file if exists
        if ($group['logo'] && file_exists(FCPATH . 'uploads/group_logos/' . $group['logo'])) {
            unlink(FCPATH . 'uploads/group_logos/' . $group['logo']);
        }

        if ($this->countryGroupModel->delete($id)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Country group deleted successfully']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to delete country group']);
        }
    }

    public function view($id)
    {
        // Check permission
        if (!$this->checkPermission('view_country_groups')) {
            return redirect()->to('/dashboard')->with('error', 'Not enough privilege');
        }

        $group = $this->countryGroupModel->getGroupWithType($id);

        if (!$group) {
            return redirect()->to('/country-groups')->with('error', 'Country group not found');
        }

        // Get members
        $memberModel = new \App\Models\CountryGroupMemberModel();
        $members = $memberModel->getMembersByGroup($id);

        $data = [
            'title' => $group['name'],
            'group' => $group,
            'members' => $members
        ];

        return view('admin/country_groups/view', $data);
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