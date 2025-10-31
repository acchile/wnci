<?php

namespace App\Models;

use CodeIgniter\Model;

class CountryModel extends Model
{
    protected $table = 'countries';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'continent_id', 'name', 'code', 'iso2', 'slug', 
        'phone_code', 'currency_code', 'status'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $validationRules = [
        'continent_id' => 'required|integer',
        'name' => 'required|min_length[3]|max_length[100]',
        'code' => 'required|exact_length[3]|is_unique[countries.code,id,{id}]',
        'iso2' => 'required|exact_length[2]',
        'slug' => 'required|max_length[100]|is_unique[countries.slug,id,{id}]',
    ];
    
    public function getWithContinent()
    {
        return $this->select('countries.*, continents.name as continent_name')
            ->join('continents', 'continents.id = countries.continent_id', 'left')
            ->orderBy('countries.name', 'ASC')
            ->findAll();
    }
    
    public function getByContinent($continentId)
    {
        return $this->where('continent_id', $continentId)
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
    
    public function getWithStateCount($id)
    {
        $db = \Config\Database::connect();
        return $db->table('countries')
            ->select('countries.*, continents.name as continent_name, COUNT(states.id) as state_count')
            ->join('continents', 'continents.id = countries.continent_id', 'left')
            ->join('states', 'states.country_id = countries.id', 'left')
            ->where('countries.id', $id)
            ->groupBy('countries.id')
            ->get()
            ->getRow();
    }
}