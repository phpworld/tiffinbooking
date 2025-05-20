<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\WalletModel;
use App\Models\WalletTransactionModel;
use App\Models\BookingModel;
use App\Models\BookingItemModel;
use Razorpay\Api\Api as RazorpayApi;

class User extends BaseController
{
    protected $userModel;
    protected $walletModel;
    protected $walletTransactionModel;
    protected $bookingModel;
    protected $bookingItemModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->walletModel = new WalletModel();
        $this->walletTransactionModel = new WalletTransactionModel();
        $this->bookingModel = new BookingModel();
        $this->bookingItemModel = new BookingItemModel();
    }

    public function index()
    {
        return redirect()->to('/user/dashboard');
    }

    public function dashboard()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/auth/login');
        }

        $userId = session()->get('user_id');
        $data['user'] = $this->userModel->find($userId);
        $data['wallet'] = $this->walletModel->where('user_id', $userId)->first();
        $data['bookings'] = $this->bookingModel->where('user_id', $userId)->orderBy('created_at', 'DESC')->findAll();

        return view('user/dashboard', $data);
    }

    public function wallet()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/auth/login');
        }

        $userId = session()->get('user_id');
        $data['wallet'] = $this->walletModel->where('user_id', $userId)->first();
        $data['transactions'] = $this->walletTransactionModel->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll();

        return view('user/wallet', $data);
    }

    public function rechargeWallet()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/auth/login');
        }

        // Display the recharge form
        return view('user/recharge');
    }

    public function createOrder()
    {
        if (!session()->get('logged_in')) {
            return $this->response->setJSON(['error' => 'User not logged in']);
        }

        $amount = (float) $this->request->getPost('amount');

        if ($amount <= 0) {
            return $this->response->setJSON(['error' => 'Amount must be greater than zero']);
        }

        try {
            // Initialize Razorpay API
            $keyId = getenv('razorpay.key_id') ?: 'rzp_test_XaZ89XsD6ejHqt';
            $keySecret = getenv('razorpay.key_secret') ?: 'SoUETaL5nEG2tPNJe35Bz0fE';
            $api = new RazorpayApi($keyId, $keySecret);

            // Create order
            $orderData = [
                'receipt' => 'wallet_recharge_' . time(),
                'amount' => $amount * 100, // Convert to paise
                'currency' => 'INR',
                'notes' => [
                    'user_id' => session()->get('user_id'),
                    'purpose' => 'wallet_recharge'
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

    public function verifyPayment()
    {
        if (!session()->get('logged_in')) {
            return $this->response->setJSON(['error' => 'User not logged in']);
        }

        $razorpayPaymentId = $this->request->getPost('razorpay_payment_id');
        $razorpayOrderId = $this->request->getPost('razorpay_order_id');
        $razorpaySignature = $this->request->getPost('razorpay_signature');
        $amount = (float) $this->request->getPost('amount');
        $userId = session()->get('user_id');

        if (!$razorpayPaymentId || !$razorpayOrderId || !$razorpaySignature) {
            return $this->response->setJSON(['error' => 'Invalid payment data']);
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

            // Payment is verified, update wallet balance
            $wallet = $this->walletModel->where('user_id', $userId)->first();

            if (!$wallet) {
                // Create wallet if it doesn't exist
                $walletId = $this->walletModel->insert([
                    'user_id' => $userId,
                    'balance' => $amount
                ]);

                $wallet = [
                    'id' => $walletId,
                    'balance' => $amount
                ];
            } else {
                // Update existing wallet
                $newBalance = $wallet['balance'] + $amount;
                $this->walletModel->update($wallet['id'], ['balance' => $newBalance]);
                $wallet['balance'] = $newBalance;
            }

            // Record transaction
            $this->walletTransactionModel->insert([
                'wallet_id' => $wallet['id'],
                'user_id' => $userId,
                'type' => 'credit',
                'amount' => $amount,
                'description' => 'Wallet Recharge via Razorpay (Payment ID: ' . $razorpayPaymentId . ')'
            ]);

            return $this->response->setJSON([
                'success' => true,
                'message' => 'Payment verified and wallet updated successfully',
                'new_balance' => $wallet['balance']
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Razorpay payment verification failed: ' . $e->getMessage());
            return $this->response->setJSON(['error' => 'Payment verification failed: ' . $e->getMessage()]);
        }
    }

    public function profile()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/auth/login');
        }

        $userId = session()->get('user_id');
        $data['user'] = $this->userModel->find($userId);

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'name' => 'required|min_length[3]|max_length[100]',
                'phone' => 'permit_empty|numeric|min_length[10]',
            ];

            if ($this->validate($rules)) {
                $updateData = [
                    'name' => $this->request->getPost('name'),
                    'phone' => $this->request->getPost('phone'),
                    'address' => $this->request->getPost('address'),
                ];

                $this->userModel->update($userId, $updateData);

                return redirect()->to('/user/profile')->with('success', 'Profile updated successfully');
            } else {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }
        }

        return view('user/profile', $data);
    }

    public function bookings()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/auth/login');
        }

        $userId = session()->get('user_id');
        $data['bookings'] = $this->bookingModel->where('user_id', $userId)
            ->orderBy('booking_date', 'DESC')
            ->findAll();

        return view('user/bookings', $data);
    }

    public function viewBooking($id)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/auth/login');
        }

        $userId = session()->get('user_id');
        $booking = $this->bookingModel->find($id);

        if (!$booking || $booking['user_id'] != $userId) {
            return redirect()->to('/user/bookings')->with('error', 'Booking not found');
        }

        $data['booking'] = $booking;
        $data['items'] = $this->bookingItemModel->where('booking_id', $id)->findAll();

        return view('user/view_booking', $data);
    }

    public function cancelBooking($id)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/auth/login');
        }

        $userId = session()->get('user_id');
        $booking = $this->bookingModel->find($id);

        if (!$booking || $booking['user_id'] != $userId) {
            return redirect()->to('/user/bookings')->with('error', 'Booking not found');
        }

        if ($booking['status'] == 'delivered') {
            return redirect()->to('/user/bookings')->with('error', 'Cannot cancel a delivered booking');
        }

        // Prevent cancellation if the booking is confirmed by admin
        if ($booking['status'] == 'confirmed') {
            return redirect()->to('/user/bookings')->with('error', 'Cannot cancel a confirmed booking. Please contact customer support for assistance.');
        }

        // Update booking status
        $this->bookingModel->update($id, ['status' => 'cancelled']);

        // Refund to wallet if payment was made through wallet
        if ($booking['payment_method'] == 'wallet') {
            $wallet = $this->walletModel->where('user_id', $userId)->first();
            $newBalance = $wallet['balance'] + $booking['total_amount'];

            $this->walletModel->update($wallet['id'], ['balance' => $newBalance]);

            // Record refund transaction
            $this->walletTransactionModel->insert([
                'wallet_id' => $wallet['id'],
                'user_id' => $userId,
                'type' => 'credit',
                'amount' => $booking['total_amount'],
                'description' => 'Refund for cancelled booking #' . $id
            ]);
        }

        return redirect()->to('/user/bookings')->with('success', 'Booking cancelled successfully');
    }
}
