<?php

namespace App\Controllers;

use App\Models\CityModel;
use App\Models\CountryModel;
use App\Models\StateModel;

class Cities extends BaseController
{
    protected $cityModel;
    protected $countryModel;
    protected $stateModel;
    
    public function __construct()
    {
        $this->cityModel = new CityModel();
        $this->countryModel = new CountryModel();
        $this->stateModel = new StateModel();
    }
    
    public function index()
    {
        $this->data['page_title'] = 'Cities Management';
        $this->data['cities'] = $this->cityModel->getWithLocation();
        
        return view('geography/cities/index', $this->data);
    }
    
    public function create()
    {
        $this->data['page_title'] = 'Add City';
        $this->data['countries'] = $this->countryModel->getActive();
        $this->data['validation'] = \Config\Services::validation();
        
        return view('geography/cities/create', $this->data);
    }
    
    public function store()
    {
        $rules = [
            'country_id' => 'required|integer',
            'name' => 'required|min_length[2]|max_length[100]',
            'slug' => 'required|max_length[100]|alpha_dash',
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }
        
        $data = [
            'country_id' => $this->request->getPost('country_id'),
            'state_id' => $this->request->getPost('state_id') ?: null,
            'name' => $this->request->getPost('name'),
            'slug' => $this->request->getPost('slug'),
            'latitude' => $this->request->getPost('latitude') ?: null,
            'longitude' => $this->request->getPost('longitude') ?: null,
            'population' => $this->request->getPost('population') ?: null,
            'status' => $this->request->getPost('status') ?: 'active',
        ];
        
        if ($this->cityModel->insert($data)) {
            return redirect()->to('/cities')->with('success', 'City created successfully');
        }
        
        return redirect()->back()->withInput()->with('error', 'Failed to create city');
    }
    
    public function edit($id)
    {
        $city = $this->cityModel->select('cities.*, countries.name as country_name, states.name as state_name')
            ->join('countries', 'countries.id = cities.country_id', 'left')
            ->join('states', 'states.id = cities.state_id', 'left')
            ->where('cities.id', $id)
            ->first();
        
        if (!$city) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        
        $this->data['page_title'] = 'Edit City';
        $this->data['city'] = $city;
        $this->data['countries'] = $this->countryModel->getActive();
        
        // Get states for the selected country
        if ($city->country_id) {
            $this->data['states'] = $this->stateModel->getByCountry($city->country_id);
        } else {
            $this->data['states'] = [];
        }
        
        $this->data['validation'] = \Config\Services::validation();
        
        return view('geography/cities/edit', $this->data);
    }
    
    public function update($id)
    {
        $rules = [
            'country_id' => 'required|integer',
            'name' => 'required|min_length[2]|max_length[100]',
            'slug' => 'required|max_length[100]|alpha_dash',
        ];
        
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('validation', $this->validator);
        }
        
        $data = [
            'country_id' => $this->request->getPost('country_id'),
            'state_id' => $this->request->getPost('state_id') ?: null,
            'name' => $this->request->getPost('name'),
            'slug' => $this->request->getPost('slug'),
            'latitude' => $this->request->getPost('latitude') ?: null,
            'longitude' => $this->request->getPost('longitude') ?: null,
            'population' => $this->request->getPost('population') ?: null,
            'status' => $this->request->getPost('status'),
        ];
        
        if ($this->cityModel->update($id, $data)) {
            return redirect()->to('/cities')->with('success', 'City updated successfully');
        }
        
        return redirect()->back()->withInput()->with('error', 'Failed to update city');
    }
    
    public function delete($id)
    {
        if ($this->cityModel->delete($id)) {
            return redirect()->to('/cities')->with('success', 'City deleted successfully');
        }
        
        return redirect()->to('/cities')->with('error', 'Failed to delete city');
    }
}