<?php

namespace App\Controllers;

use App\Models\ContinentModel;

class Continents extends BaseController
{
    protected $continentModel;
    
    public function __construct()
    {
        $this->continentModel = new ContinentModel();
    }
    
    public function index()
    {
        $this->data['page_title'] = 'Continents Management';
        $this->data['continents'] = $this->continentModel->getWithCountryCount();
        
        return view('geography/continents/index', $this->data);
    }
    
    public function create()
    {
        $this->data['page_title'] = 'Add Continent';
        $this->data['validation'] = \Config\Services::validation();
        
        return view('geography/continents/create', $this->data);
    }
    
    public function store()
    {
        $rules = [
            'name' => 'required|min_length[3]|max_length[100]|is_unique[continents.name]',
            'code' => 'required|exact_length[2]|is_unique[continents.code]|alpha',
            'slug' => 'required|max_length[100]|is_unique[continents.slug]|alpha_dash',
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }
        
        $data = [
            'name' => $this->request->getPost('name'),
            'code' => strtoupper($this->request->getPost('code')),
            'slug' => $this->request->getPost('slug'),
            'status' => $this->request->getPost('status') ?: 'active',
        ];
        
        if ($this->continentModel->insert($data)) {
            return redirect()->to('/continents')->with('success', 'Continent created successfully');
        }
        
        return redirect()->back()->withInput()->with('error', 'Failed to create continent');
    }
    
    public function edit($id)
    {
        $continent = $this->continentModel->find($id);
        
        if (!$continent) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        
        $this->data['page_title'] = 'Edit Continent';
        $this->data['continent'] = $continent;
        $this->data['validation'] = \Config\Services::validation();
        
        return view('geography/continents/edit', $this->data);
    }
    
    public function update($id)
    {
        $rules = [
            'name' => "required|min_length[3]|max_length[100]|is_unique[continents.name,id,{$id}]",
            'code' => "required|exact_length[2]|is_unique[continents.code,id,{$id}]|alpha",
            'slug' => "required|max_length[100]|is_unique[continents.slug,id,{$id}]|alpha_dash",
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }
        
        $data = [
            'name' => $this->request->getPost('name'),
            'code' => strtoupper($this->request->getPost('code')),
            'slug' => $this->request->getPost('slug'),
            'status' => $this->request->getPost('status'),
        ];
        
        if ($this->continentModel->update($id, $data)) {
            return redirect()->to('/continents')->with('success', 'Continent updated successfully');
        }
        
        return redirect()->back()->withInput()->with('error', 'Failed to update continent');
    }
    
    public function delete($id)
    {
        // Check if continent has countries
        $db = \Config\Database::connect();
        $countryCount = $db->table('countries')->where('continent_id', $id)->countAllResults();
        
        if ($countryCount > 0) {
            return redirect()->to('/continents')->with('error', "Cannot delete continent. It has {$countryCount} countries assigned.");
        }
        
        if ($this->continentModel->delete($id)) {
            return redirect()->to('/continents')->with('success', 'Continent deleted successfully');
        }
        
        return redirect()->to('/continents')->with('error', 'Failed to delete continent');
    }
}