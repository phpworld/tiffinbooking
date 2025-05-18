<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\BookingModel;
use App\Models\BookingItemModel;
use App\Models\DeliverySlotModel;
use App\Models\WalletModel;
use CodeIgniter\API\ResponseTrait;

class Booking extends BaseController
{
    use ResponseTrait;

    protected $bookingModel;
    protected $bookingItemModel;
    protected $deliverySlotModel;
    protected $walletModel;
    protected $walletTransactionModel;

    public function __construct()
    {
        helper('jwt');
        $this->bookingModel = new BookingModel();
        $this->bookingItemModel = new BookingItemModel();
        $this->deliverySlotModel = new DeliverySlotModel();
        $this->walletModel = new WalletModel();
        $this->walletTransactionModel = new \App\Models\WalletTransactionModel();
    }

    /**
     * Get user bookings
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

        // Get all bookings for the user
        $bookings = $this->bookingModel->where('user_id', $userId)
                                      ->orderBy('created_at', 'DESC')
                                      ->findAll();

        $processedBookings = [];

        foreach ($bookings as $booking) {
            // Get booking items
            $items = $this->bookingItemModel->where('booking_id', $booking['id'])->findAll();

            $processedItems = [];
            foreach ($items as $item) {
                $processedItems[] = [
                    'id' => $item['id'],
                    'dish_id' => $item['dish_id'],
                    'dish_name' => $item['dish_name'],
                    'quantity' => $item['quantity'],
                    'price' => $item['price'],
                    'subtotal' => $item['price'] * $item['quantity']
                ];
            }

            // Get delivery slot if available
            $slotName = 'Standard Delivery';
            if (!empty($booking['delivery_slot_id'])) {
                $slot = $this->deliverySlotModel->find($booking['delivery_slot_id']);
                if ($slot) {
                    $slotName = $slot['slot_name'];
                }
            }

            $processedBookings[] = [
                'id' => $booking['id'],
                'booking_date' => $booking['booking_date'],
                'delivery_slot' => $slotName,
                'total_amount' => $booking['total_amount'],
                'status' => $booking['status'],
                'payment_method' => $booking['payment_method'],
                'created_at' => date('Y-m-d H:i:s', strtotime($booking['created_at'])),
                'items' => $processedItems,
                'item_count' => count($processedItems),
                'can_cancel' => $booking['status'] === 'pending'
            ];
        }

        $response = [
            'status' => true,
            'data' => $processedBookings
        ];

        return $this->respond($response);
    }

    /**
     * Get booking details
     *
     * @param int $id Booking ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function view($id)
    {
        $userData = getUserFromToken();

        if (!$userData) {
            return $this->failUnauthorized('Invalid or expired token');
        }

        $userId = $userData['id'];

        // Get booking
        $booking = $this->bookingModel->find($id);

        if (!$booking) {
            return $this->failNotFound('Booking not found');
        }

        // Check if booking belongs to user
        if ($booking['user_id'] != $userId) {
            return $this->failForbidden('You do not have permission to view this booking');
        }

        // Get booking items
        $items = $this->bookingItemModel->where('booking_id', $booking['id'])->findAll();

        $processedItems = [];
        foreach ($items as $item) {
            $processedItems[] = [
                'id' => $item['id'],
                'dish_id' => $item['dish_id'],
                'dish_name' => $item['dish_name'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'subtotal' => $item['price'] * $item['quantity']
            ];
        }

        // Get delivery slot if available
        $slotName = 'Standard Delivery';
        if (!empty($booking['delivery_slot_id'])) {
            $slot = $this->deliverySlotModel->find($booking['delivery_slot_id']);
            if ($slot) {
                $slotName = $slot['slot_name'];
            }
        }

        $response = [
            'status' => true,
            'data' => [
                'id' => $booking['id'],
                'booking_date' => $booking['booking_date'],
                'delivery_slot' => $slotName,
                'total_amount' => $booking['total_amount'],
                'status' => $booking['status'],
                'payment_method' => $booking['payment_method'],
                'created_at' => date('Y-m-d H:i:s', strtotime($booking['created_at'])),
                'items' => $processedItems,
                'item_count' => count($processedItems),
                'can_cancel' => $booking['status'] === 'pending'
            ]
        ];

        return $this->respond($response);
    }

    /**
     * Place a new order
     *
     * @return \CodeIgniter\HTTP\Response
     */
    public function placeOrder()
    {
        $userData = getUserFromToken();

        if (!$userData) {
            return $this->failUnauthorized('Invalid or expired token');
        }

        $userId = $userData['id'];

        // Get cart
        $cart = session()->get('cart_' . $userId) ?? [];

        if (empty($cart)) {
            return $this->fail('Your cart is empty');
        }

        $rules = [
            'payment_method' => 'required|in_list[cash,wallet]'
        ];

        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }

        $paymentMethod = $this->request->getPost('payment_method');

        // Calculate total
        $total = 0;
        foreach ($cart as $item) {
            $total += $item['price'] * $item['quantity'];
        }

        // Check wallet balance if payment method is wallet
        if ($paymentMethod === 'wallet') {
            $wallet = $this->walletModel->where('user_id', $userId)->first();

            if (!$wallet || $wallet['balance'] < $total) {
                return $this->fail('Insufficient wallet balance');
            }
        }

        // Set default booking date to tomorrow
        $bookingDate = date('Y-m-d', strtotime('+1 day'));

        // Set default delivery slot (first active slot)
        $defaultSlot = $this->deliverySlotModel->where('is_active', 1)->first();
        $deliverySlotId = $defaultSlot ? $defaultSlot['id'] : null;

        // Create booking
        $bookingData = [
            'user_id' => $userId,
            'booking_date' => $bookingDate,
            'delivery_slot_id' => $deliverySlotId,
            'total_amount' => $total,
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
                'dish_name' => $item['name'],
                'quantity' => $item['quantity'],
                'price' => $item['price']
            ];

            $this->bookingItemModel->insert($itemData);
        }

        // Deduct from wallet if payment method is wallet
        if ($paymentMethod === 'wallet') {
            $wallet = $this->walletModel->where('user_id', $userId)->first();

            if ($wallet) {
                $newBalance = $wallet['balance'] - $total;
                $this->walletModel->update($wallet['id'], ['balance' => $newBalance]);

                // Add transaction record for the payment
                $transactionData = [
                    'wallet_id' => $wallet['id'],
                    'amount' => $total,
                    'type' => 'debit',
                    'description' => 'Payment for order #' . $bookingId
                ];

                $this->walletTransactionModel->insert($transactionData);
            }
        }

        // Clear cart
        session()->set('cart_' . $userId, []);

        $response = [
            'status' => true,
            'message' => 'Order placed successfully',
            'data' => [
                'booking_id' => $bookingId,
                'total_amount' => $total,
                'payment_method' => $paymentMethod
            ]
        ];

        return $this->respond($response, 201);
    }

    /**
     * Cancel a booking
     *
     * @param int $id Booking ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function cancel($id)
    {
        $userData = getUserFromToken();

        if (!$userData) {
            return $this->failUnauthorized('Invalid or expired token');
        }

        $userId = $userData['id'];

        // Get booking
        $booking = $this->bookingModel->find($id);

        if (!$booking) {
            return $this->failNotFound('Booking not found');
        }

        // Check if booking belongs to user
        if ($booking['user_id'] != $userId) {
            return $this->failForbidden('You do not have permission to cancel this booking');
        }

        // Check if booking can be cancelled
        if ($booking['status'] !== 'pending') {
            return $this->fail('Only pending bookings can be cancelled');
        }

        // Update booking status
        $this->bookingModel->update($id, ['status' => 'cancelled']);

        // Get or create wallet for refund
        $wallet = $this->walletModel->where('user_id', $userId)->first();

        if (!$wallet) {
            // Create wallet if it doesn't exist
            $walletData = [
                'user_id' => $userId,
                'balance' => 0
            ];

            $this->walletModel->insert($walletData);
            $walletId = $this->walletModel->getInsertID();
            $wallet = [
                'id' => $walletId,
                'balance' => 0
            ];
        }

        // Refund amount to wallet regardless of payment method
        $refundAmount = $booking['total_amount'];
        $newBalance = $wallet['balance'] + $refundAmount;
        $this->walletModel->update($wallet['id'], ['balance' => $newBalance]);

        // Add transaction record for the refund
        $transactionData = [
            'wallet_id' => $wallet['id'],
            'amount' => $refundAmount,
            'type' => 'credit',
            'description' => 'Refund for cancelled order #' . $id
        ];

        $this->walletTransactionModel->insert($transactionData);

        $response = [
            'status' => true,
            'message' => 'Booking cancelled successfully. ₹' . number_format($refundAmount, 2) . ' has been refunded to your wallet.',
            'data' => [
                'refund_amount' => $refundAmount,
                'new_wallet_balance' => $newBalance
            ]
        ];

        return $this->respond($response);
    }
}
