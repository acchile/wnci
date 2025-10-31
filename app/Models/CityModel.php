<?php

namespace App\Models;

use CodeIgniter\Model;

class CityModel extends Model
{
    protected $table = 'cities';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'state_id', 'country_id', 'name', 'slug', 
        'latitude', 'longitude', 'population', 'status'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $validationRules = [
        'country_id' => 'required|integer',
        'name' => 'required|min_length[2]|max_length[100]',
        'slug' => 'required|max_length[100]',
    ];
    
    public function getWithLocation()
    {
        return $this->select('cities.*, states.name as state_name, countries.name as country_name, continents.name as continent_name')
            ->join('states', 'states.id = cities.state_id', 'left')
            ->join('countries', 'countries.id = cities.country_id', 'left')
            ->join('continents', 'continents.id = countries.continent_id', 'left')
            ->orderBy('countries.name', 'ASC')
            ->orderBy('cities.name', 'ASC')
            ->findAll();
    }
    
    public function getByCountry($countryId)
    {
        return $this->where('country_id', $countryId)
            ->where('status', 'active')
            ->orderBy('name', 'ASC')
            ->findAll();
    }
    
    public function getByState($stateId)
    {
        return $this->where('state_id', $stateId)
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