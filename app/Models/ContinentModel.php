<?php

namespace App\Models;

use CodeIgniter\Model;

class ContinentModel extends Model
{
    protected $table = 'continents';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['name', 'code', 'slug', 'status'];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[100]|is_unique[continents.name,id,{id}]',
        'code' => 'required|exact_length[2]|is_unique[continents.code,id,{id}]',
        'slug' => 'required|max_length[100]|is_unique[continents.slug,id,{id}]',
    ];
    
    public function getWithCountryCount()
    {
        return $this->select('continents.*, COUNT(countries.id) as country_count')
            ->join('countries', 'countries.continent_id = continents.id', 'left')
            ->groupBy('continents.id')
            ->orderBy('continents.name', 'ASC')
            ->findAll();
    }
    
    public function getActive()
    {
        return $this->where('status', 'active')
            ->orderBy('name', 'ASC')
            ->findAll();
    }
}