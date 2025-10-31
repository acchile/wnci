<?php

namespace App\Models;

use CodeIgniter\Model;

class RoleModel extends Model
{
    protected $table = 'roles';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['name', 'description'];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    public function getAllRoles()
    {
        return $this->orderBy('name', 'ASC')->findAll();
    }
    
    public function getRolePermissions($roleId)
    {
        $db = \Config\Database::connect();
        
        $builder = $db->table('role_permissions')
            ->select('permissions.*')
            ->join('permissions', 'permissions.id = role_permissions.permission_id')
            ->where('role_permissions.role_id', $roleId);
        
        return $builder->get()->getResult();
    }
    
    public function getAllPermissions()
    {
        $db = \Config\Database::connect();
        return $db->table('permissions')->orderBy('name', 'ASC')->get()->getResult();
    }
    
    public function setRolePermissions($roleId, $permissionIds)
    {
        $db = \Config\Database::connect();
        
        // Delete existing permissions
        $db->table('role_permissions')->where('role_id', $roleId)->delete();
        
        // Insert new permissions
        if (!empty($permissionIds)) {
            foreach ($permissionIds as $permissionId) {
                $db->table('role_permissions')->insert([
                    'role_id' => $roleId,
                    'permission_id' => $permissionId
                ]);
            }
        }
        
        return true;
    }
}