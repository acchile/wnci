<?php

namespace App\Models;

use CodeIgniter\Model;

class CountryGroupModel extends Model
{
    protected $table = 'country_groups';
    protected $primaryKey = 'id';
    protected $useAutoIncrement = true;
    protected $returnType = 'object';
    protected $useSoftDeletes = false;
    protected $allowedFields = [
        'name', 'acronym', 'slug', 'group_type_id', 'description', 
        'logo', 'website', 'founded_year', 'headquarters', 'status'
    ];
    
    protected $useTimestamps = true;
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';
    
    protected $validationRules = [
        'name' => 'required|min_length[3]|max_length[100]',
        'slug' => 'required|max_length[100]|is_unique[country_groups.slug,id,{id}]|alpha_dash',
        'group_type_id' => 'required|integer',
    ];
    
    public function getWithType()
    {
        return $this->select('country_groups.*, group_types.name as type_name, group_types.color as type_color, group_types.icon as type_icon')
            ->join('group_types', 'group_types.id = country_groups.group_type_id', 'left')
            ->orderBy('country_groups.name', 'ASC')
            ->findAll();
    }
    
    public function getWithMemberCount($id)
    {
        $db = \Config\Database::connect();
        return $db->table('country_groups')
            ->select('country_groups.*, group_types.name as type_name, group_types.color as type_color, COUNT(country_group_members.id) as member_count')
            ->join('group_types', 'group_types.id = country_groups.group_type_id', 'left')
            ->join('country_group_members', 'country_group_members.group_id = country_groups.id', 'left')
            ->where('country_groups.id', $id)
            ->groupBy('country_groups.id')
            ->get()
            ->getRow();
    }
    
    public function getGroupMembers($groupId)
    {
        $db = \Config\Database::connect();
        return $db->table('country_group_members')
            ->select('country_group_members.*, countries.name as country_name, countries.code as country_code, countries.iso2')
            ->join('countries', 'countries.id = country_group_members.country_id')
            ->where('country_group_members.group_id', $groupId)
            ->orderBy('countries.name', 'ASC')
            ->get()
            ->getResult();
    }
    
    public function addMember($groupId, $countryId, $data = [])
    {
        $db = \Config\Database::connect();
        
        $insertData = [
            'group_id' => $groupId,
            'country_id' => $countryId,
            'joined_date' => $data['joined_date'] ?? null,
            'membership_type' => $data['membership_type'] ?? 'full',
            'notes' => $data['notes'] ?? null
        ];
        
        return $db->table('country_group_members')->insert($insertData);
    }
    
    public function removeMember($groupId, $countryId)
    {
        $db = \Config\Database::connect();
        return $db->table('country_group_members')
            ->where('group_id', $groupId)
            ->where('country_id', $countryId)
            ->delete();
    }
    
    public function getCountryGroups($countryId)
    {
        $db = \Config\Database::connect();
        return $db->table('country_group_members')
            ->select('country_groups.*, group_types.name as type_name, group_types.color as type_color, country_group_members.membership_type, country_group_members.joined_date')
            ->join('country_groups', 'country_groups.id = country_group_members.group_id')
            ->join('group_types', 'group_types.id = country_groups.group_type_id')
            ->where('country_group_members.country_id', $countryId)
            ->orderBy('country_groups.name', 'ASC')
            ->get()
            ->getResult();
    }
}