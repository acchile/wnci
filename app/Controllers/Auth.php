<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        // If already logged in, redirect
        if (session()->get('logged_in')) {
            return redirect()->to('/dashboard');
        }
        
        return view('auth/login');
    }
    
    public function loginPost()
    {
        // Show errors for debugging
        try {
            $userModel = new UserModel();
            
            $rules = [
                'username' => 'required',
                'password' => 'required'
            ];
            
            if (!$this->validate($rules)) {
                return redirect()->back()
                    ->with('error', 'Please fill all fields')
                    ->withInput();
            }
            
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');
            
            // Check if user exists
            $user = $userModel->where('username', $username)->first();
            
            if (!$user) {
                return redirect()->back()
                    ->with('error', 'User not found')
                    ->withInput();
            }
			

            
            // Check password
            if (!password_verify($password, $user->password)) {
                return redirect()->back()
                    ->with('error', 'Invalid password')
                    ->withInput();
            }
            
            // Check status
            if ($user->status !== 'active') {
                return redirect()->back()
                    ->with('error', 'Account is inactive')
                    ->withInput();
            }
            
            // Get permissions
            $permissions = $userModel->getUserPermissions($user->id);
            
            // Get full user data with role
            $userData = $userModel->getUserWithRole($user->id);
            
            // Set session data
            $sessionData = [
                'user_id' => $user->id,
                'username' => $user->username,
                'email' => $user->email,
                'role_id' => $user->role_id,
                'language' => $user->language,
                'logged_in' => true,
                'user_data' => $userData,
                'permissions' => $permissions
            ];
            
            session()->set($sessionData);
            
            // Update last login
            $userModel->update($user->id, ['last_login' => date('Y-m-d H:i:s')]);
            
            return redirect()->to('/dashboard')
                ->with('success', 'Welcome back, ' . $user->first_name . '!');
                
        } catch (\Exception $e) {
            // Show the actual error
            return redirect()->back()
                ->with('error', 'Login Error: ' . $e->getMessage())
                ->withInput();
        }
    }
    
    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login')->with('success', 'You have been logged out');
    }
	
	
}