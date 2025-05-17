<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\UserModel;
use App\Models\WalletModel;
use App\Models\WalletTransactionModel;
use App\Models\BookingModel;
use App\Models\BookingItemModel;

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

        if ($this->request->getMethod() === 'POST') {
            $amount = (float) $this->request->getPost('amount');
            $userId = session()->get('user_id');

            if ($amount <= 0) {
                return redirect()->back()->with('error', 'Amount must be greater than zero');
            }

            $wallet = $this->walletModel->where('user_id', $userId)->first();

            if (!$wallet) {
                // Create wallet if it doesn't exist
                $this->walletModel->insert([
                    'user_id' => $userId,
                    'balance' => $amount
                ]);
            } else {
                // Update existing wallet
                $newBalance = $wallet['balance'] + $amount;
                $this->walletModel->update($wallet['id'], ['balance' => $newBalance]);
            }

            // Record transaction
            $this->walletTransactionModel->insert([
                'user_id' => $userId,
                'type' => 'credit',
                'amount' => $amount,
                'description' => 'Wallet Recharge'
            ]);

            return redirect()->to('/user/wallet')->with('success', 'Wallet recharged successfully');
        }

        return view('user/recharge');
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

        // Update booking status
        $this->bookingModel->update($id, ['status' => 'cancelled']);

        // Refund to wallet if payment was made through wallet
        if ($booking['payment_method'] == 'wallet') {
            $wallet = $this->walletModel->where('user_id', $userId)->first();
            $newBalance = $wallet['balance'] + $booking['total_amount'];

            $this->walletModel->update($wallet['id'], ['balance' => $newBalance]);

            // Record refund transaction
            $this->walletTransactionModel->insert([
                'user_id' => $userId,
                'type' => 'credit',
                'amount' => $booking['total_amount'],
                'description' => 'Refund for cancelled booking #' . $id
            ]);
        }

        return redirect()->to('/user/bookings')->with('success', 'Booking cancelled successfully');
    }
}
