<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class Debug extends BaseController
{
    use ResponseTrait;
    
    public function index()
    {
        $response = [
            'status' => true,
            'message' => 'Debug API is working!',
            'timestamp' => date('Y-m-d H:i:s'),
            'request_method' => $this->request->getMethod(),
            'request_headers' => $this->request->getHeaders(),
            'request_body' => $this->request->getBody(),
            'request_json' => $this->request->getJSON(),
            'post_data' => $this->request->getPost(),
            'get_data' => $this->request->getGet(),
            'server_data' => $_SERVER
        ];
        
        return $this->respond($response);
    }
    
    public function login()
    {
        $response = [
            'status' => true,
            'message' => 'Debug login API is working!',
            'timestamp' => date('Y-m-d H:i:s'),
            'request_method' => $this->request->getMethod(),
            'content_type' => $this->request->getHeaderLine('Content-Type'),
            'raw_input' => file_get_contents('php://input'),
            'post_data' => $this->request->getPost(),
            'json_data' => $this->request->getJSON()
        ];
        
        return $this->respond($response);
    }
}
