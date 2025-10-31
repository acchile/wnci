<?php

namespace App\Models;

use CodeIgniter\Model;

class GroupTypeModel extends Model
{
    protected $table = 'group_types';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'name', 'slug', 'description', 'color', 'icon', 'status'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[50]|is_unique[group_types.name,id,{id}]',
        'slug' => 'required|max_length[50]|is_unique[group_types.slug,id,{id}]|alpha_dash',
    ];
    
    public function getActive()
    {
        return $this->where('status', 'active')
            ->orderBy('name', 'ASC')
            ->findAll();
    }
    
    public function getWithGroupCount()
    {
        return $this->select('group_types.*, COUNT(country_groups.id) as group_count')
            ->join('country_groups', 'country_groups.group_type_id = group_types.id', 'left')
            ->groupBy('group_types.id')
            ->orderBy('group_types.name', 'ASC')
            ->findAll();
    }
}