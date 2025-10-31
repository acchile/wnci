<?php

namespace App\Controllers;

use App\Models\UserModel;

class Profile extends BaseController
{
    protected $userModel;
    
    public function __construct()
    {
        $this->userModel = new UserModel();
    }
    
    public function index()
    {
        $userId = session()->get('user_id');
        
        $this->data['page_title'] = 'My Profile';
        $this->data['user'] = $this->userModel->getUserWithRole($userId);
        $this->data['validation'] = \Config\Services::validation();
        
        return view('profile/index', $this->data);
    }
    
    public function update()
    {
        $userId = session()->get('user_id');
        
        $rules = [
            'email' => "required|valid_email|is_unique[users.email,id,{$userId}]",
            'first_name' => 'required|min_length[2]',
            'last_name' => 'required|min_length[2]',
        ];
        
        // If password is provided, validate it
        if (!empty($this->request->getPost('password'))) {
            $rules['password'] = 'min_length[6]';
            $rules['password_confirm'] = 'matches[password]';
        }
        
        // Validate image if uploaded
        if ($this->request->getFile('profile_image')->isValid()) {
            $rules['profile_image'] = 'uploaded[profile_image]|max_size[profile_image,2048]|is_image[profile_image]|mime_in[profile_image,image/jpg,image/jpeg,image/png,image/gif]';
        }
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }
        
        $data = [
            'email' => $this->request->getPost('email'),
            'first_name' => $this->request->getPost('first_name'),
            'last_name' => $this->request->getPost('last_name'),
            'language' => $this->request->getPost('language'),
        ];
        
        // Handle profile image upload
        $file = $this->request->getFile('profile_image');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            // Delete old image if exists
            $oldUser = $this->userModel->find($userId);
            if ($oldUser->profile_image && file_exists(FCPATH . 'uploads/profiles/' . $oldUser->profile_image)) {
                unlink(FCPATH . 'uploads/profiles/' . $oldUser->profile_image);
            }
            
            // Generate unique filename
            $newName = 'profile_' . $userId . '_' . time() . '.' . $file->getExtension();
            
            // Move file to uploads/profiles
            $file->move(FCPATH . 'uploads/profiles', $newName);
            
            $data['profile_image'] = $newName;
        }
        
        // Update password only if provided
        $password = $this->request->getPost('password');
        if (!empty($password)) {
            $data['password'] = password_hash($password, PASSWORD_BCRYPT);
        }
        
        if ($this->userModel->update($userId, $data)) {
            // Update session data
            session()->set('language', $data['language']);
            $user = $this->userModel->getUserWithRole($userId);
            session()->set('user_data', $user);
            
            return redirect()->to('/profile')->with('success', 'Profile updated successfully');
        }
        
        return redirect()->back()->withInput()->with('error', 'Failed to update profile');
    }
    
    public function deleteImage()
    {
        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);
        
        if ($user->profile_image) {
            // Delete file
            $filePath = FCPATH . 'uploads/profiles/' . $user->profile_image;
            if (file_exists($filePath)) {
                unlink($filePath);
            }
            
            // Update database
            $this->userModel->update($userId, ['profile_image' => null]);
            
            // Update session
            $user = $this->userModel->getUserWithRole($userId);
            session()->set('user_data', $user);
            
            return redirect()->to('/profile')->with('success', 'Profile image deleted successfully');
        }
        
        return redirect()->to('/profile')->with('error', 'No profile image to delete');
    }
    
    public function changeLanguage()
    {
        $language = $this->request->getPost('language');
        $userId = session()->get('user_id');
        
        if ($language && in_array($language, ['en', 'es', 'fr'])) {
            $this->userModel->update($userId, ['language' => $language]);
            session()->set('language', $language);
            
            return $this->response->setJSON(['success' => true]);
        }
        
        return $this->response->setJSON(['success' => false]);
    }
}