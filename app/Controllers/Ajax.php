<?php

namespace App\Controllers;

use App\Models\CountryModel;
use App\Models\StateModel;
use App\Models\CityModel;

class Ajax extends BaseController
{
    public function countriesByContinent($continentId)
    {
        $countryModel = new CountryModel();
        $countries = $countryModel->getByContinent($continentId);
        
        return $this->response->setJSON($countries);
    }
    
    public function statesByCountry($countryId)
    {
        $stateModel = new StateModel();
        $states = $stateModel->getByCountry($countryId);
        
        return $this->response->setJSON($states);
    }
    
    public function citiesByState($stateId)
    {
        $cityModel = new CityModel();
        $cities = $cityModel->getByState($stateId);
        
        return $this->response->setJSON($cities);
    }
    
    public function citiesByCountry($countryId)
    {
        $cityModel = new CityModel();
        $cities = $cityModel->getByCountry($countryId);
        
        return $this->response->setJSON($cities);
    }
}