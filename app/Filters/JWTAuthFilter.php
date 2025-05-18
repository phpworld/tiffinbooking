<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Config\Services;

class JWTAuthFilter implements FilterInterface
{
    /**
     * Do whatever processing this filter needs to do.
     *
     * @param RequestInterface $request
     * @param array|null       $arguments
     *
     * @return mixed
     */
    public function before(RequestInterface $request, $arguments = null)
    {
        helper('jwt');
        
        $response = Services::response();
        $authHeader = $request->getHeaderLine('Authorization');
        
        if (empty($authHeader)) {
            return $response->setJSON([
                'status' => false,
                'message' => 'Access denied. No token provided.'
            ])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
        }
        
        // Extract token from Bearer header
        if (preg_match('/Bearer\s(\S+)/', $authHeader, $matches)) {
            $token = $matches[1];
            $decoded = validateJWTToken($token);
            
            if (!$decoded) {
                return $response->setJSON([
                    'status' => false,
                    'message' => 'Invalid or expired token.'
                ])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
            }
            
            // Token is valid, set user data in request
            $request->user = (array) $decoded->data;
            return $request;
        }
        
        return $response->setJSON([
            'status' => false,
            'message' => 'Invalid token format.'
        ])->setStatusCode(ResponseInterface::HTTP_UNAUTHORIZED);
    }

    /**
     * Allows After filters to inspect and modify the response
     * object as needed. This method does not allow any way
     * to stop execution of other after filters, short of
     * throwing an Exception or Error.
     *
     * @param RequestInterface  $request
     * @param ResponseInterface $response
     * @param array|null        $arguments
     *
     * @return mixed
     */
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do nothing after
    }
}
