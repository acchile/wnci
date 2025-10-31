<?php

namespace App\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\CLIRequest;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

abstract class BaseController extends Controller
{
    protected $request;
    protected $helpers = ['url', 'form'];
    protected $session;
    protected $data = [];

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);
        
        $this->session = \Config\Services::session();
        
        // Set common data for all views
        $this->data['current_user'] = $this->session->get('user_data');
        $this->data['permissions'] = $this->session->get('permissions') ?? [];
    }
    
    protected function checkPermission($permission)
    {
        if (!in_array($permission, $this->data['permissions'])) {
            return redirect()->to('/dashboard')
                ->with('error', lang('App.no_permission'));
        }
    }
    
    protected function hasPermission($permission)
    {
        return in_array($permission, $this->data['permissions']);
    }
}