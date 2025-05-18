<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use CodeIgniter\API\ResponseTrait;

class Test extends BaseController
{
    use ResponseTrait;
    
    public function index()
    {
        $response = [
            'status' => true,
            'message' => 'API is working!',
            'timestamp' => date('Y-m-d H:i:s')
        ];
        
        return $this->respond($response);
    }
}
