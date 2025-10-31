<?php

namespace App\Controllers;

use App\Models\CountryGroupMemberModel;
use App\Models\CountryGroupModel;
use App\Models\CountryModel;

class CountryGroupMembers extends BaseController
{
    protected $memberModel;
    protected $countryGroupModel;
    protected $countryModel;
    protected $session;

    public function __construct()
    {
        $this->memberModel = new CountryGroupMemberModel();
        $this->countryGroupModel = new CountryGroupModel();
        $this->countryModel = new CountryModel();
        $this->session = \Config\Services::session();
        helper(['form', 'url']);
    }

    public function index($groupId = null)
    {
        // Check permission
        if (!$this->checkPermission('view_group_members')) {
            return redirect()->to('/dashboard')->with('error', 'Not enough privilege');
        }

        if ($groupId) {
            $countryGroup = $this->countryGroupModel->find($groupId);
            
            if (!$countryGroup) {
                return redirect()->to('/country-groups')->with('error', 'Country group not found');
            }

            $data = [
                'title' => 'Members of ' . $countryGroup['name'],
                'countryGroup' => $countryGroup,
                'members' => $this->memberModel->getMembersByGroup($groupId)
            ];

            return view('admin/country_group_members/index', $data);
        } else {
            // Show all memberships
            $data = [
                'title' => 'All Group Memberships',
                'members' => $this->memberModel->getAllMembersWithDetails()
            ];

            return view('admin/country_group_members/all', $data);
        }
    }

    public function create($groupId = null)
    {
        // Check permission
        if (!$this->checkPermission('create_group_members')) {
            return redirect()->to('/country-groups')->with('error', 'Not enough privilege');
        }

        $countryGroup = null;
        if ($groupId) {
            $countryGroup = $this->countryGroupModel->find($groupId);
            if (!$countryGroup) {
                return redirect()->to('/country-groups')->with('error', 'Country group not found');
            }
        }

        $data = [
            'title' => 'Add Group Member',
            'countryGroup' => $countryGroup,
            'countryGroups' => $this->countryGroupModel->getGroupsWithTypes(),
            'countries' => $this->countryModel->orderBy('name', 'ASC')->findAll(),
            'membershipTypes' => ['full', 'associate', 'observer', 'candidate'],
            'validation' => \Config\Services::validation()
        ];

        return view('admin/country_group_members/create', $data);
    }

    public function store()
    {
        // Check permission
        if (!$this->checkPermission('create_group_members')) {
            return redirect()->to('/country-groups')->with('error', 'Not enough privilege');
        }

        $rules = [
            'country_group_id' => 'required|is_not_unique[country_groups.id]',
            'country_id' => 'required|is_not_unique[countries.id]',
            'membership_type' => 'required|in_list[full,associate,observer,candidate]',
            'joined_date' => 'permit_empty|valid_date',
            'notes' => 'permit_empty|max_length[1000]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Check if membership already exists
        $existing = $this->memberModel->where([
            'country_group_id' => $this->request->getPost('country_group_id'),
            'country_id' => $this->request->getPost('country_id')
        ])->first();

        if ($existing) {
            return redirect()->back()->withInput()->with('error', 'This country is already a member of this group');
        }

        $data = [
            'country_group_id' => $this->request->getPost('country_group_id'),
            'country_id' => $this->request->getPost('country_id'),
            'membership_type' => $this->request->getPost('membership_type'),
            'joined_date' => $this->request->getPost('joined_date'),
            'notes' => $this->request->getPost('notes'),
            'created_by' => session()->get('user_id')
        ];

        if ($this->memberModel->insert($data)) {
            $redirectUrl = $this->request->getPost('country_group_id') 
                ? '/country-group-members/group/' . $this->request->getPost('country_group_id')
                : '/country-group-members';
            
            return redirect()->to($redirectUrl)->with('success', 'Member added successfully');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to add member');
        }
    }

    public function edit($id)
    {
        // Check permission
        if (!$this->checkPermission('edit_group_members')) {
            return redirect()->to('/country-groups')->with('error', 'Not enough privilege');
        }

        $member = $this->memberModel->getMemberWithDetails($id);

        if (!$member) {
            return redirect()->to('/country-group-members')->with('error', 'Member not found');
        }

        $data = [
            'title' => 'Edit Group Member',
            'member' => $member,
            'countryGroups' => $this->countryGroupModel->getGroupsWithTypes(),
            'countries' => $this->countryModel->orderBy('name', 'ASC')->findAll(),
            'membershipTypes' => ['full', 'associate', 'observer', 'candidate'],
            'validation' => \Config\Services::validation()
        ];

        return view('admin/country_group_members/edit', $data);
    }

    public function update($id)
    {
        // Check permission
        if (!$this->checkPermission('edit_group_members')) {
            return redirect()->to('/country-groups')->with('error', 'Not enough privilege');
        }

        $member = $this->memberModel->find($id);

        if (!$member) {
            return redirect()->to('/country-group-members')->with('error', 'Member not found');
        }

        $rules = [
            'country_group_id' => 'required|is_not_unique[country_groups.id]',
            'country_id' => 'required|is_not_unique[countries.id]',
            'membership_type' => 'required|in_list[full,associate,observer,candidate]',
            'joined_date' => 'permit_empty|valid_date',
            'notes' => 'permit_empty|max_length[1000]'
        ];

        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        // Check if membership already exists (excluding current record)
        $existing = $this->memberModel->where([
            'country_group_id' => $this->request->getPost('country_group_id'),
            'country_id' => $this->request->getPost('country_id')
        ])->where('id !=', $id)->first();

        if ($existing) {
            return redirect()->back()->withInput()->with('error', 'This country is already a member of this group');
        }

        $data = [
            'country_group_id' => $this->request->getPost('country_group_id'),
            'country_id' => $this->request->getPost('country_id'),
            'membership_type' => $this->request->getPost('membership_type'),
            'joined_date' => $this->request->getPost('joined_date'),
            'notes' => $this->request->getPost('notes'),
            'updated_by' => session()->get('user_id')
        ];

        if ($this->memberModel->update($id, $data)) {
            return redirect()->to('/country-group-members/group/' . $this->request->getPost('country_group_id'))
                ->with('success', 'Member updated successfully');
        } else {
            return redirect()->back()->withInput()->with('error', 'Failed to update member');
        }
    }

    public function delete($id)
    {
        // Check permission
        if (!$this->checkPermission('delete_group_members')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Not enough privilege']);
        }

        $member = $this->memberModel->find($id);

        if (!$member) {
            return $this->response->setJSON(['success' => false, 'message' => 'Member not found']);
        }

        if ($this->memberModel->delete($id)) {
            return $this->response->setJSON(['success' => true, 'message' => 'Member removed successfully']);
        } else {
            return $this->response->setJSON(['success' => false, 'message' => 'Failed to remove member']);
        }
    }

    // Get countries by group (AJAX)
    public function getCountriesByGroup($groupId)
    {
        $members = $this->memberModel->getMembersByGroup($groupId);
        return $this->response->setJSON($members);
    }

    // Get groups by country (AJAX)
    public function getGroupsByCountry($countryId)
    {
        $memberships = $this->memberModel->where('country_id', $countryId)
            ->join('country_groups', 'country_groups.id = country_group_members.country_group_id')
            ->select('country_groups.*, country_group_members.membership_type, country_group_members.joined_date')
            ->findAll();
        
        return $this->response->setJSON($memberships);
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