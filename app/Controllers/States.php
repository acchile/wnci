<?php

namespace App\Controllers;

use App\Models\StateModel;
use App\Models\CountryModel;

class States extends BaseController
{
    protected $stateModel;
    protected $countryModel;
    
    public function __construct()
    {
        $this->stateModel = new StateModel();
        $this->countryModel = new CountryModel();
    }
    
    public function index()
    {
        $this->data['page_title'] = 'States/Provinces Management';
        $this->data['states'] = $this->stateModel->getWithCountry();
        
        return view('geography/states/index', $this->data);
    }
    
    public function create()
    {
        $this->data['page_title'] = 'Add State/Province';
        $this->data['countries'] = $this->countryModel->getActive();
        $this->data['validation'] = \Config\Services::validation();
        
        return view('geography/states/create', $this->data);
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
            'name' => $this->request->getPost('name'),
            'code' => $this->request->getPost('code'),
            'slug' => $this->request->getPost('slug'),
            'status' => $this->request->getPost('status') ?: 'active',
        ];
        
        if ($this->stateModel->insert($data)) {
            return redirect()->to('/states')->with('success', 'State/Province created successfully');
        }
        
        return redirect()->back()->withInput()->with('error', 'Failed to create state/province');
    }
    
    public function edit($id)
    {
        $state = $this->stateModel->select('states.*, countries.name as country_name')
            ->join('countries', 'countries.id = states.country_id', 'left')
            ->where('states.id', $id)
            ->first();
        
        if (!$state) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }
        
        $this->data['page_title'] = 'Edit State/Province';
        $this->data['state'] = $state;
        $this->data['countries'] = $this->countryModel->getActive();
        $this->data['validation'] = \Config\Services::validation();
        
        return view('geography/states/edit', $this->data);
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
            'name' => $this->request->getPost('name'),
            'code' => $this->request->getPost('code'),
            'slug' => $this->request->getPost('slug'),
            'status' => $this->request->getPost('status'),
        ];
        
        if ($this->stateModel->update($id, $data)) {
            return redirect()->to('/states')->with('success', 'State/Province updated successfully');
        }
        
        return redirect()->back()->withInput()->with('error', 'Failed to update state/province');
    }
    
    public function delete($id)
    {
        // Check if state has cities
        $db = \Config\Database::connect();
        $cityCount = $db->table('cities')->where('state_id', $id)->countAllResults();
        
        if ($cityCount > 0) {
            return redirect()->to('/states')->with('error', "Cannot delete state/province. It has {$cityCount} cities.");
        }
        
        if ($this->stateModel->delete($id)) {
            return redirect()->to('/states')->with('success', 'State/Province deleted successfully');
        }
        
        return redirect()->to('/states')->with('error', 'Failed to delete state/province');
    }
}