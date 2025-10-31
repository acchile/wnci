<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Models\RoleModel;

class Dashboard extends BaseController
{
    public function index()
    {
        // Check if user is logged in
        if (!session()->get('logged_in')) {
            return redirect()->to('/login');
        }
        
        $userModel = new UserModel();
        $roleModel = new RoleModel();
        
        $data = [
            'page_title' => 'Dashboard',
            'total_users' => $userModel->countAll(),
            'total_roles' => $roleModel->countAll(),
            'recent_users' => $userModel->getAllUsersWithRoles(),
            'current_user' => session()->get('user_data'),
            'permissions' => session()->get('permissions') ?? []
        ];
        
        return view('dashboard/index', $data);
    }
}