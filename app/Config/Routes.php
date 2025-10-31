<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// Default route - MUST BE AT THE TOP
$routes->get('/', 'Auth::login');
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::loginPost');
$routes->get('logout', 'Auth::logout');
// User management routes
$routes->get('users', 'Users::index');
$routes->get('users/create', 'Users::create');
$routes->post('users/create', 'Users::store');
$routes->get('users/edit/(:num)', 'Users::edit/$1');
$routes->post('users/edit/(:num)', 'Users::update/$1');
$routes->get('users/delete/(:num)', 'Users::delete/$1');
// Role management routes
$routes->get('roles', 'Roles::index');
$routes->get('roles/create', 'Roles::create');
$routes->post('roles/create', 'Roles::store');
$routes->get('roles/edit/(:num)', 'Roles::edit/$1');
$routes->post('roles/edit/(:num)', 'Roles::update/$1');
$routes->get('roles/delete/(:num)', 'Roles::delete/$1');
// Profile routes
$routes->get('profile', 'Profile::index');
$routes->post('profile/update', 'Profile::update');
$routes->post('profile/change-language', 'Profile::changeLanguage');
$routes->get('profile/delete-image', 'Profile::deleteImage');
// Geographic Management Routes
$routes->group('', function($routes) {
    // Continents
    $routes->get('continents', 'Continents::index');
    $routes->get('continents/create', 'Continents::create');
    $routes->post('continents/create', 'Continents::store');
    $routes->get('continents/edit/(:num)', 'Continents::edit/$1');
    $routes->post('continents/edit/(:num)', 'Continents::update/$1');
    $routes->get('continents/delete/(:num)', 'Continents::delete/$1');
    
    // Countries
    $routes->get('countries', 'Countries::index');
    $routes->get('countries/create', 'Countries::create');
    $routes->post('countries/create', 'Countries::store');
    $routes->get('countries/edit/(:num)', 'Countries::edit/$1');
    $routes->post('countries/edit/(:num)', 'Countries::update/$1');
    $routes->get('countries/delete/(:num)', 'Countries::delete/$1');
    
    // States
    $routes->get('states', 'States::index');
    $routes->get('states/create', 'States::create');
    $routes->post('states/create', 'States::store');
    $routes->get('states/edit/(:num)', 'States::edit/$1');
    $routes->post('states/edit/(:num)', 'States::update/$1');
    $routes->get('states/delete/(:num)', 'States::delete/$1');
    
    // Cities
    $routes->get('cities', 'Cities::index');
    $routes->get('cities/create', 'Cities::create');
    $routes->post('cities/create', 'Cities::store');
    $routes->get('cities/edit/(:num)', 'Cities::edit/$1');
    $routes->post('cities/edit/(:num)', 'Cities::update/$1');
    $routes->get('cities/delete/(:num)', 'Cities::delete/$1');
    
    // AJAX endpoints for cascading dropdowns
    $routes->get('ajax/countries-by-continent/(:num)', 'Ajax::countriesByContinent/$1');
    $routes->get('ajax/states-by-country/(:num)', 'Ajax::statesByCountry/$1');
    $routes->get('ajax/cities-by-state/(:num)', 'Ajax::citiesByState/$1');
    $routes->get('ajax/cities-by-country/(:num)', 'Ajax::citiesByCountry/$1');
    
// Group Types Management
$routes->get('group-types', 'GroupTypes::index');
$routes->get('group-types/create', 'GroupTypes::create');
$routes->post('group-types/create', 'GroupTypes::store');
$routes->get('group-types/edit/(:num)', 'GroupTypes::edit/$1');
$routes->post('group-types/edit/(:num)', 'GroupTypes::update/$1');
$routes->post('group-types/delete/(:num)', 'GroupTypes::delete/$1');

// Country Groups Management
$routes->get('country-groups', 'CountryGroups::index');
$routes->get('country-groups/create', 'CountryGroups::create');
$routes->post('country-groups/create', 'CountryGroups::store');
$routes->get('country-groups/view/(:num)', 'CountryGroups::view/$1');
$routes->get('country-groups/edit/(:num)', 'CountryGroups::edit/$1');
$routes->post('country-groups/edit/(:num)', 'CountryGroups::update/$1');
$routes->post('country-groups/delete/(:num)', 'CountryGroups::delete/$1');

// Country Group Members Management
$routes->get('country-group-members', 'CountryGroupMembers::index');
$routes->get('country-group-members/group/(:num)', 'CountryGroupMembers::index/$1');
$routes->get('country-group-members/create', 'CountryGroupMembers::create');
$routes->get('country-group-members/create/(:num)', 'CountryGroupMembers::create/$1');
$routes->post('country-group-members/create', 'CountryGroupMembers::store');
$routes->get('country-group-members/edit/(:num)', 'CountryGroupMembers::edit/$1');
$routes->post('country-group-members/edit/(:num)', 'CountryGroupMembers::update/$1');
$routes->post('country-group-members/delete/(:num)', 'CountryGroupMembers::delete/$1');

// AJAX endpoints for group members
$routes->get('ajax/countries-by-group/(:num)', 'CountryGroupMembers::getCountriesByGroup/$1');
$routes->get('ajax/groups-by-country/(:num)', 'CountryGroupMembers::getGroupsByCountry/$1');





});


// Test route to verify routing works
$routes->get('test', function() {
    return 'Routing works!';
});

// Dashboard routes (uncomment after auth is working)
// $routes->group('', ['filter' => 'auth'], function($routes) {
     $routes->get('dashboard', 'Dashboard::index');
//     $routes->get('users', 'Users::index');
// });