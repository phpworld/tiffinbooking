<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BookingModel;
use App\Models\BookingItemModel;
use App\Models\DishModel;
use App\Models\DeliverySlotModel;
use App\Models\WalletModel;
use App\Models\WalletTransactionModel;

class Booking extends BaseController
{
    protected $bookingModel;
    protected $bookingItemModel;
    protected $dishModel;
    protected $deliverySlotModel;
    protected $walletModel;
    protected $walletTransactionModel;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->bookingItemModel = new BookingItemModel();
        $this->dishModel = new DishModel();
        $this->deliverySlotModel = new DeliverySlotModel();
        $this->walletModel = new WalletModel();
        $this->walletTransactionModel = new WalletTransactionModel();

        // Initialize cart session if not exists
        if (!session()->has('cart')) {
            session()->set('cart', []);
        }
    }

    public function index()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/auth/login');
        }

        return redirect()->to('/booking/create');
    }

    public function create()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/auth/login');
        }

        $data['dishes'] = $this->dishModel->where('available', 1)->findAll();
        $data['slots'] = $this->deliverySlotModel->where('is_active', 1)->findAll();
        $data['cart'] = session()->get('cart');

        return view('booking/create', $data);
    }

    public function addToCart($id)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/auth/login');
        }

        $dish = $this->dishModel->find($id);

        if (!$dish || $dish['available'] != 1) {
            return redirect()->back()->with('error', 'Dish not available');
        }

        $cart = session()->get('cart');
        $quantity = $this->request->getGet('quantity') ?? 1;

        // Check if dish already in cart
        if (isset($cart[$id])) {
            $cart[$id]['quantity'] += $quantity;
        } else {
            $cart[$id] = [
                'id' => $dish['id'],
                'name' => $dish['name'],
                'price' => $dish['price'],
                'quantity' => $quantity
            ];
        }

        session()->set('cart', $cart);

        return redirect()->back()->with('success', 'Dish added to cart');
    }

    public function removeFromCart($id)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/auth/login');
        }

        $cart = session()->get('cart');

        if (isset($cart[$id])) {
            unset($cart[$id]);
            session()->set('cart', $cart);
        }

        return redirect()->back()->with('success', 'Dish removed from cart');
    }

    public function clearCart()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/auth/login');
        }

        session()->set('cart', []);

        return redirect()->back()->with('success', 'Cart cleared');
    }

    public function checkout()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/auth/login');
        }

        $cart = session()->get('cart');

        if (empty($cart)) {
            return redirect()->to('/booking/create')->with('error', 'Your cart is empty');
        }

        $data['cart'] = $cart;
        $data['slots'] = $this->deliverySlotModel->where('is_active', 1)->findAll();
        $data['total'] = $this->calculateTotal($cart);

        $userId = session()->get('user_id');
        $data['wallet'] = $this->walletModel->where('user_id', $userId)->first();

        return view('booking/checkout', $data);
    }

    public function placeOrder()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/auth/login');
        }

        $cart = session()->get('cart');

        if (empty($cart)) {
            return redirect()->to('/booking/create')->with('error', 'Your cart is empty');
        }

        $userId = session()->get('user_id');
        $bookingDate = $this->request->getPost('booking_date');
        $deliverySlotId = $this->request->getPost('delivery_slot_id');
        $paymentMethod = $this->request->getPost('payment_method');
        $totalAmount = $this->calculateTotal($cart);

        // Validate booking date
        if (!$bookingDate || strtotime($bookingDate) < strtotime(date('Y-m-d'))) {
            return redirect()->back()->with('error', 'Invalid booking date');
        }

        // Validate delivery slot
        $slot = $this->deliverySlotModel->find($deliverySlotId);
        if (!$slot) {
            return redirect()->back()->with('error', 'Invalid delivery slot');
        }

        // Check wallet balance if payment method is wallet
        if ($paymentMethod == 'wallet') {
            $wallet = $this->walletModel->where('user_id', $userId)->first();

            if (!$wallet || $wallet['balance'] < $totalAmount) {
                return redirect()->back()->with('error', 'Insufficient wallet balance');
            }

            // Deduct from wallet
            $newBalance = $wallet['balance'] - $totalAmount;
            $this->walletModel->update($wallet['id'], ['balance' => $newBalance]);

            // Record transaction
            $this->walletTransactionModel->insert([
                'user_id' => $userId,
                'type' => 'debit',
                'amount' => $totalAmount,
                'description' => 'Payment for tiffin booking'
            ]);
        }

        // Create booking
        $bookingData = [
            'user_id' => $userId,
            'booking_date' => $bookingDate,
            'delivery_slot_id' => $deliverySlotId,
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'payment_method' => $paymentMethod
        ];

        $this->bookingModel->insert($bookingData);
        $bookingId = $this->bookingModel->getInsertID();

        // Add booking items
        foreach ($cart as $item) {
            $itemData = [
                'booking_id' => $bookingId,
                'dish_id' => $item['id'],
                'quantity' => $item['quantity'],
                'price' => $item['price']
            ];

            $this->bookingItemModel->insert($itemData);
        }

        // Clear cart
        session()->set('cart', []);

        return redirect()->to('/user/bookings')->with('success', 'Booking placed successfully');
    }

    private function calculateTotal($cart)
    {
        $total = 0;

        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        return $total;
    }
}
