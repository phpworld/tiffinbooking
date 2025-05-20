<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BookingModel;
use App\Models\BookingItemModel;
use App\Models\DishModel;
use App\Models\DeliverySlotModel;
use App\Models\WalletModel;
use App\Models\WalletTransactionModel;
use Razorpay\Api\Api as RazorpayApi;

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
                'quantity' => $quantity,
                'is_vegetarian' => $dish['is_vegetarian'] ?? 1
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

    public function updateQuantity($id, $quantity)
    {
        if (!session()->get('logged_in')) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'You must be logged in to update cart'
            ]);
        }

        // Validate quantity
        $quantity = (int) $quantity;
        if ($quantity < 1 || $quantity > 10) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Invalid quantity. Must be between 1 and 10.'
            ]);
        }

        $cart = session()->get('cart');

        // Check if item exists in cart
        if (!isset($cart[$id])) {
            return $this->response->setJSON([
                'success' => false,
                'message' => 'Item not found in cart'
            ]);
        }

        // Update quantity
        $cart[$id]['quantity'] = $quantity;
        session()->set('cart', $cart);

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Quantity updated successfully'
        ]);
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
        $data['total'] = $this->calculateTotal($cart);

        $userId = session()->get('user_id');
        $data['wallet'] = $this->walletModel->where('user_id', $userId)->first();

        return view('booking/checkout', $data);
    }

    public function createOrder()
    {
        if (!session()->get('logged_in')) {
            return $this->response->setJSON(['error' => 'User not logged in']);
        }

        $cart = session()->get('cart');

        if (empty($cart)) {
            return $this->response->setJSON(['error' => 'Your cart is empty']);
        }

        $totalAmount = $this->calculateTotal($cart);

        try {
            // Initialize Razorpay API
            $keyId = getenv('razorpay.key_id') ?: 'rzp_test_XaZ89XsD6ejHqt';
            $keySecret = getenv('razorpay.key_secret') ?: 'SoUETaL5nEG2tPNJe35Bz0fE';
            $api = new RazorpayApi($keyId, $keySecret);

            // Create order
            $orderData = [
                'receipt' => 'order_' . time(),
                'amount' => $totalAmount * 100, // Convert to paise
                'currency' => 'INR',
                'notes' => [
                    'user_id' => session()->get('user_id'),
                    'purpose' => 'order_payment'
                ]
            ];

            $order = $api->order->create($orderData);

            return $this->response->setJSON([
                'order_id' => $order->id,
                'amount' => $order->amount,
                'currency' => $order->currency
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Razorpay order creation failed: ' . $e->getMessage());
            return $this->response->setJSON(['error' => 'Failed to create payment order: ' . $e->getMessage()]);
        }
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
        $paymentMethod = $this->request->getPost('payment_method');
        $totalAmount = $this->calculateTotal($cart);

        // Set default booking date to tomorrow
        $bookingDate = date('Y-m-d', strtotime('+1 day'));

        // Set default delivery slot (first active slot)
        $defaultSlot = $this->deliverySlotModel->where('is_active', 1)->first();
        $deliverySlotId = $defaultSlot ? $defaultSlot['id'] : null;

        // Handle payment based on method
        if ($paymentMethod == 'wallet') {
            // Check wallet balance
            $wallet = $this->walletModel->where('user_id', $userId)->first();

            if (!$wallet || $wallet['balance'] < $totalAmount) {
                return redirect()->back()->with('error', 'Insufficient wallet balance');
            }

            // Deduct from wallet
            $newBalance = $wallet['balance'] - $totalAmount;
            $this->walletModel->update($wallet['id'], ['balance' => $newBalance]);

            // Record transaction
            $this->walletTransactionModel->insert([
                'wallet_id' => $wallet['id'],
                'user_id' => $userId,
                'type' => 'debit',
                'amount' => $totalAmount,
                'description' => 'Payment for tiffin booking'
            ]);
        } elseif ($paymentMethod == 'razorpay') {
            // Verify Razorpay payment
            $razorpayPaymentId = $this->request->getPost('razorpay_payment_id');
            $razorpayOrderId = $this->request->getPost('razorpay_order_id');
            $razorpaySignature = $this->request->getPost('razorpay_signature');

            if (!$razorpayPaymentId || !$razorpayOrderId || !$razorpaySignature) {
                return redirect()->back()->with('error', 'Invalid payment data');
            }

            try {
                // Initialize Razorpay API
                $keyId = getenv('razorpay.key_id') ?: 'rzp_test_XaZ89XsD6ejHqt';
                $keySecret = getenv('razorpay.key_secret') ?: 'SoUETaL5nEG2tPNJe35Bz0fE';
                $api = new RazorpayApi($keyId, $keySecret);

                // Verify signature
                $attributes = [
                    'razorpay_payment_id' => $razorpayPaymentId,
                    'razorpay_order_id' => $razorpayOrderId,
                    'razorpay_signature' => $razorpaySignature
                ];

                $api->utility->verifyPaymentSignature($attributes);
            } catch (\Exception $e) {
                log_message('error', 'Razorpay payment verification failed: ' . $e->getMessage());
                return redirect()->back()->with('error', 'Payment verification failed: ' . $e->getMessage());
            }
        }

        // Create booking
        $bookingData = [
            'user_id' => $userId,
            'booking_date' => $bookingDate,
            'delivery_slot_id' => $deliverySlotId ?? null,
            'total_amount' => $totalAmount,
            'status' => 'pending',
            'payment_method' => $paymentMethod,
            'payment_id' => $paymentMethod == 'razorpay' ? $this->request->getPost('razorpay_payment_id') : null
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
