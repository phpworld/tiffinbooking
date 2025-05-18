<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\DishModel;
use CodeIgniter\API\ResponseTrait;

class Cart extends BaseController
{
    use ResponseTrait;

    protected $dishModel;

    public function __construct()
    {
        helper('jwt');
        $this->dishModel = new DishModel();
    }

    /**
     * Get cart contents
     *
     * @return \CodeIgniter\HTTP\Response
     */
    public function index()
    {
        $userData = getUserFromToken();

        if (!$userData) {
            return $this->failUnauthorized('Invalid or expired token');
        }

        $userId = $userData['id'];

        // Get cart from session or database
        $cart = session()->get('cart_' . $userId) ?? [];

        // Calculate total
        $total = 0;
        $items = [];

        foreach ($cart as $item) {
            $subtotal = $item['price'] * $item['quantity'];
            $total += $subtotal;

            // Add image URL
            $imageUrl = '';
            if (!empty($item['image'])) {
                $imageUrl = base_url('/uploads/dishes/' . $item['image']);
            }

            $items[] = [
                'id' => $item['id'],
                'name' => $item['name'],
                'price' => $item['price'],
                'quantity' => $item['quantity'],
                'subtotal' => $subtotal,
                'image' => $imageUrl,
                'is_vegetarian' => (bool) ($item['is_vegetarian'] ?? true)
            ];
        }

        $response = [
            'status' => true,
            'data' => [
                'items' => $items,
                'total' => $total,
                'item_count' => count($items)
            ]
        ];

        return $this->respond($response);
    }

    /**
     * Add item to cart
     *
     * @return \CodeIgniter\HTTP\Response
     */
    public function add()
    {
        $userData = getUserFromToken();

        if (!$userData) {
            return $this->failUnauthorized('Invalid or expired token');
        }

        $userId = $userData['id'];

        $rules = [
            'dish_id' => 'required|numeric',
            'quantity' => 'required|numeric|greater_than[0]|less_than[11]'
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $dishId = $this->request->getPost('dish_id');
        $quantity = $this->request->getPost('quantity');

        // Get dish details
        $dish = $this->dishModel->find($dishId);

        if (!$dish) {
            return $this->failNotFound('Dish not found');
        }

        // Check if dish is available (if the field exists)
        if (isset($dish['available']) && !$dish['available']) {
            return $this->fail('This dish is currently not available');
        }

        // Get current cart
        $cart = session()->get('cart_' . $userId) ?? [];

        // Check if dish already in cart
        if (isset($cart[$dishId])) {
            $cart[$dishId]['quantity'] += $quantity;

            // Limit quantity to 10
            if ($cart[$dishId]['quantity'] > 10) {
                $cart[$dishId]['quantity'] = 10;
            }
        } else {
            // Add new item to cart
            $cart[$dishId] = [
                'id' => $dishId, // Use the dish_id from the request
                'name' => $dish['name'] ?? 'Unknown Dish',
                'price' => $dish['price'] ?? 0,
                'quantity' => $quantity,
                'image' => $dish['image'] ?? null,
                'is_vegetarian' => $dish['is_vegetarian'] ?? 1
            ];
        }

        // Save cart to session
        session()->set('cart_' . $userId, $cart);

        // Calculate total
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $response = [
            'status' => true,
            'message' => 'Item added to cart',
            'data' => [
                'item_count' => count($cart),
                'total' => $total
            ]
        ];

        return $this->respond($response);
    }

    /**
     * Update cart item quantity
     *
     * @return \CodeIgniter\HTTP\Response
     */
    public function update()
    {
        $userData = getUserFromToken();

        if (!$userData) {
            return $this->failUnauthorized('Invalid or expired token');
        }

        $userId = $userData['id'];

        $rules = [
            'dish_id' => 'required|numeric',
            'quantity' => 'required|numeric|greater_than[0]|less_than[11]'
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $dishId = $this->request->getPost('dish_id');
        $quantity = $this->request->getPost('quantity');

        // Get current cart
        $cart = session()->get('cart_' . $userId) ?? [];

        // Check if dish exists in cart
        if (!isset($cart[$dishId])) {
            return $this->failNotFound('Item not found in cart');
        }

        // Update quantity
        $cart[$dishId]['quantity'] = $quantity;

        // Save cart to session
        session()->set('cart_' . $userId, $cart);

        // Calculate total
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $response = [
            'status' => true,
            'message' => 'Cart updated',
            'data' => [
                'item_count' => count($cart),
                'total' => $total
            ]
        ];

        return $this->respond($response);
    }

    /**
     * Remove item from cart
     *
     * @return \CodeIgniter\HTTP\Response
     */
    public function remove()
    {
        $userData = getUserFromToken();

        if (!$userData) {
            return $this->failUnauthorized('Invalid or expired token');
        }

        $userId = $userData['id'];

        $rules = [
            'dish_id' => 'required|numeric'
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $dishId = $this->request->getPost('dish_id');

        // Get current cart
        $cart = session()->get('cart_' . $userId) ?? [];

        // Check if dish exists in cart
        if (!isset($cart[$dishId])) {
            return $this->failNotFound('Item not found in cart');
        }

        // Remove item from cart
        unset($cart[$dishId]);

        // Save cart to session
        session()->set('cart_' . $userId, $cart);

        // Calculate total
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        $response = [
            'status' => true,
            'message' => 'Item removed from cart',
            'data' => [
                'item_count' => count($cart),
                'total' => $total
            ]
        ];

        return $this->respond($response);
    }

    /**
     * Clear cart
     *
     * @return \CodeIgniter\HTTP\Response
     */
    public function clear()
    {
        $userData = getUserFromToken();

        if (!$userData) {
            return $this->failUnauthorized('Invalid or expired token');
        }

        $userId = $userData['id'];

        // Clear cart
        session()->set('cart_' . $userId, []);

        $response = [
            'status' => true,
            'message' => 'Cart cleared',
            'data' => [
                'item_count' => 0,
                'total' => 0
            ]
        ];

        return $this->respond($response);
    }
}
