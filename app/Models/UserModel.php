<?php

namespace App\Models;

use CodeIgniter\Model;

class UserModel extends Model
{
    protected $table = 'users';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'username', 'email', 'password', 'first_name', 
        'last_name', 'role_id', 'language', 'profile_image', 'status', 'last_login'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    // Don't auto-hash password in callbacks, we'll do it manually
    protected $beforeInsert = [];
    protected $beforeUpdate = [];
    
    public function getAllUsersWithRoles()
    {
        return $this->select('users.*, roles.name as role_name')
            ->join('roles', 'roles.id = users.role_id', 'left')
            ->orderBy('users.created_at', 'DESC')
            ->findAll();
    }
    
    public function getUserWithRole($id)
    {
        return $this->select('users.*, roles.name as role_name')
            ->join('roles', 'roles.id = users.role_id', 'left')
            ->where('users.id', $id)
            ->first();
    }
    
    public function getUserByUsername($username)
    {
        return $this->where('username', $username)->first();
    }
    
    public function updateLastLogin($id)
    {
        return $this->update($id, ['last_login' => date('Y-m-d H:i:s')]);
    }
    
    public function getUserPermissions($userId)
    {
        $db = \Config\Database::connect();
        
        $builder = $db->table('users')
            ->select('permissions.name')
            ->join('roles', 'roles.id = users.role_id')
            ->join('role_permissions', 'role_permissions.role_id = roles.id')
            ->join('permissions', 'permissions.id = role_permissions.permission_id')
            ->where('users.id', $userId);
        
        $query = $builder->get();
        $permissions = [];
        
        foreach ($query->getResult() as $row) {
            $permissions[] = $row->name;
        }
        
        return $permissions;
    }
}