<?php

namespace App\Models;

use CodeIgniter\Model;

class StateModel extends Model
{
    protected $table = 'states';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = ['country_id', 'name', 'code', 'slug', 'status'];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $validationRules = [
        'country_id' => 'required|integer',
        'name' => 'required|min_length[2]|max_length[100]',
        'slug' => 'required|max_length[100]',
    ];
    
    public function getWithCountry()
    {
        return $this->select('states.*, countries.name as country_name, continents.name as continent_name')
            ->join('countries', 'countries.id = states.country_id', 'left')
            ->join('continents', 'continents.id = countries.continent_id', 'left')
            ->orderBy('countries.name', 'ASC')
            ->orderBy('states.name', 'ASC')
            ->findAll();
    }
    
    public function getByCountry($countryId)
    {
        return $this->where('country_id', $countryId)
            ->where('status', 'active')
            ->orderBy('name', 'ASC')
            ->findAll();
    }
    
    public function getActive()
    {
        return $this->where('status', 'active')
            ->orderBy('name', 'ASC')
            ->findAll();
    }
}