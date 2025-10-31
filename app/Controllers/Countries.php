<?php

namespace App\Controllers;

use App\Models\CountryModel;
use App\Models\ContinentModel;

class Countries extends BaseController
{
    protected $countryModel;
    protected $continentModel;
    
    public function __construct()
    {
        $this->countryModel = new CountryModel();
        $this->continentModel = new ContinentModel();
    }
    
    public function index()
    {
        $this->data['page_title'] = 'Countries Management';
        $this->data['countries'] = $this->countryModel->getWithContinent();
        
        return view('geography/countries/index', $this->data);
    }
    
    public function create()
    {
        $this->data['page_title'] = 'Add Country';
        $this->data['continents'] = $this->continentModel->getActive();
        $this->data['validation'] = \Config\Services::validation();
        
        return view('geography/countries/create', $this->data);
    }
    
    public function store()
    {
        $rules = [
            'continent_id' => 'required|integer',
            'name' => 'required|min_length[3]|max_length[100]',
            'code' => 'required|exact_length[3]|is_unique[countries.code]|alpha',
            'iso2' => 'required|exact_length[2]|alpha',
            'slug' => 'required|max_length[100]|is_unique[countries.slug]|alpha_dash',
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }
        
        $data = [
            'continent_id' => $this->request->getPost('continent_id'),
            'name' => $this->request->getPost('name'),
            'code' => strtoupper($this->request->getPost('code')),
            'iso2' => strtoupper($this->request->getPost('iso2')),
            'slug' => $this->request->getPost('slug'),
            'phone_code' => $this->request->getPost('phone_code'),
            'currency_code' => $this->request->getPost('currency_code'),
            'status' => $this->request->getPost('status') ?: 'active',
        ];
        
        if ($this->countryModel->insert($data)) {
            return redirect()->to('/countries')->with('success', 'Country created successfully');
        }
        
        return redirect()->back()->withInput()->with('error', 'Failed to create country');
    }
    
    public function edit($id)
    {
        $country = $this->countryModel->getWithStateCount($id);
        
        if (!$country) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        
        $this->data['page_title'] = 'Edit Country';
        $this->data['country'] = $country;
        $this->data['continents'] = $this->continentModel->getActive();
        $this->data['validation'] = \Config\Services::validation();
        
        return view('geography/countries/edit', $this->data);
    }
    
    public function update($id)
    {
        $rules = [
            'continent_id' => 'required|integer',
            'name' => 'required|min_length[3]|max_length[100]',
            'code' => "required|exact_length[3]|is_unique[countries.code,id,{$id}]|alpha",
            'iso2' => 'required|exact_length[2]|alpha',
            'slug' => "required|max_length[100]|is_unique[countries.slug,id,{$id}]|alpha_dash",
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }
        
        $data = [
            'continent_id' => $this->request->getPost('continent_id'),
            'name' => $this->request->getPost('name'),
            'code' => strtoupper($this->request->getPost('code')),
            'iso2' => strtoupper($this->request->getPost('iso2')),
            'slug' => $this->request->getPost('slug'),
            'phone_code' => $this->request->getPost('phone_code'),
            'currency_code' => $this->request->getPost('currency_code'),
            'status' => $this->request->getPost('status'),
        ];
        
        if ($this->countryModel->update($id, $data)) {
            return redirect()->to('/countries')->with('success', 'Country updated successfully');
        }
        
        return redirect()->back()->withInput()->with('error', 'Failed to update country');
    }
    
    public function delete($id)
    {
        // Check if country has states or cities
        $db = \Config\Database::connect();
        $stateCount = $db->table('states')->where('country_id', $id)->countAllResults();
        $cityCount = $db->table('cities')->where('country_id', $id)->countAllResults();
        
        if ($stateCount > 0 || $cityCount > 0) {
            return redirect()->to('/countries')->with('error', "Cannot delete country. It has {$stateCount} states and {$cityCount} cities.");
        }
        
        if ($this->countryModel->delete($id)) {
            return redirect()->to('/countries')->with('success', 'Country deleted successfully');
        }
        
        return redirect()->to('/countries')->with('error', 'Failed to delete country');
    }
}