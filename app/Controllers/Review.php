<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\ReviewModel;
use App\Models\BookingModel;
use App\Models\UserModel;
use App\Models\DishModel;
use App\Models\BookingItemModel;

class Review extends BaseController
{
    protected $reviewModel;
    protected $bookingModel;
    protected $userModel;
    protected $dishModel;
    protected $bookingItemModel;

    public function __construct()
    {
        $this->reviewModel = new ReviewModel();
        $this->bookingModel = new BookingModel();
        $this->userModel = new UserModel();
        $this->dishModel = new DishModel();
        $this->bookingItemModel = new BookingItemModel();
    }

    public function create($bookingId)
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/auth/login');
        }

        $userId = session()->get('user_id');
        $booking = $this->bookingModel->find($bookingId);

        // Check if booking exists and belongs to the user
        if (!$booking || $booking['user_id'] != $userId) {
            return redirect()->to('/user/bookings')->with('error', 'Booking not found');
        }

        // Check if booking is delivered
        if ($booking['status'] != 'delivered') {
            return redirect()->to('/user/bookings')->with('error', 'You can only review delivered orders');
        }

        // Check if review already exists
        $existingReview = $this->reviewModel->where('booking_id', $bookingId)->first();
        if ($existingReview) {
            return redirect()->to('/user/bookings')->with('error', 'You have already reviewed this order');
        }

        $data['booking'] = $booking;

        return view('review/create', $data);
    }

    public function store()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/auth/login');
        }

        $userId = session()->get('user_id');
        $bookingId = $this->request->getPost('booking_id');
        $booking = $this->bookingModel->find($bookingId);

        // Check if booking exists and belongs to the user
        if (!$booking || $booking['user_id'] != $userId) {
            return redirect()->to('/user/bookings')->with('error', 'Booking not found');
        }

        // Check if booking is delivered
        if ($booking['status'] != 'delivered') {
            return redirect()->to('/user/bookings')->with('error', 'You can only review delivered orders');
        }

        // Check if review already exists
        $existingReview = $this->reviewModel->where('booking_id', $bookingId)->first();
        if ($existingReview) {
            return redirect()->to('/user/bookings')->with('error', 'You have already reviewed this order');
        }

        $rules = [
            'rating' => 'required|numeric|greater_than[0]|less_than[6]',
            'comment' => 'permit_empty|max_length[400]',
        ];

        if ($this->validate($rules)) {
            $data = [
                'user_id' => $userId,
                'booking_id' => $bookingId,
                'rating' => $this->request->getPost('rating'),
                'comment' => $this->request->getPost('comment'),
            ];

            $this->reviewModel->insert($data);

            return redirect()->to('/user/bookings')->with('success', 'Thank you for your review!');
        } else {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }
    }

    public function storeDishReview()
    {
        if (!session()->get('logged_in')) {
            return redirect()->to('/auth/login');
        }

        $userId = session()->get('user_id');
        $bookingId = $this->request->getPost('booking_id');
        $dishId = $this->request->getPost('dish_id');

        // Verify the booking exists and belongs to the user
        $booking = $this->bookingModel->find($bookingId);
        if (!$booking || $booking['user_id'] != $userId || $booking['status'] != 'delivered') {
            return redirect()->back()->with('error', 'Invalid booking or you cannot review this dish');
        }

        // Verify the dish was part of this booking
        $db = \Config\Database::connect();
        $builder = $db->table('booking_items');
        $builder->where('booking_id', $bookingId);
        $builder->where('dish_id', $dishId);
        $bookingItem = $builder->get()->getRowArray();

        if (!$bookingItem) {
            return redirect()->back()->with('error', 'This dish was not part of your order');
        }

        // Check if user already reviewed this dish for this booking
        $builder = $db->table('reviews');
        $builder->where('user_id', $userId);
        $builder->where('booking_id', $bookingId);
        $existingReview = $builder->get()->getRowArray();

        if ($existingReview) {
            // Update existing review
            $data = [
                'rating' => $this->request->getPost('rating'),
                'comment' => $this->request->getPost('comment'),
                'updated_at' => date('Y-m-d H:i:s')
            ];

            $this->reviewModel->update($existingReview['id'], $data);
            $message = 'Your review has been updated!';
        } else {
            // Create new review
            $data = [
                'user_id' => $userId,
                'booking_id' => $bookingId,
                'rating' => $this->request->getPost('rating'),
                'comment' => $this->request->getPost('comment'),
            ];

            $this->reviewModel->insert($data);
            $message = 'Thank you for your review!';
        }

        return redirect()->back()->with('success', $message);
    }

    // Admin methods for managing reviews

    public function index()
    {
        if (!session()->get('logged_in') || !session()->get('is_admin')) {
            return redirect()->to('/auth/login');
        }

        $db = \Config\Database::connect();
        $builder = $db->table('reviews');
        $builder->select('reviews.*, users.name as user_name');
        $builder->join('users', 'users.id = reviews.user_id');
        $builder->orderBy('reviews.created_at', 'DESC');
        $data['reviews'] = $builder->get()->getResultArray();

        return view('admin/reviews/index', $data);
    }

    public function view($id)
    {
        if (!session()->get('logged_in') || !session()->get('is_admin')) {
            return redirect()->to('/auth/login');
        }

        $db = \Config\Database::connect();
        $builder = $db->table('reviews');
        $builder->select('reviews.*, users.name as user_name, users.email as user_email');
        $builder->join('users', 'users.id = reviews.user_id');
        $builder->where('reviews.id', $id);
        $data['review'] = $builder->get()->getRowArray();

        if (!$data['review']) {
            return redirect()->to('/admin/reviews')->with('error', 'Review not found');
        }

        // Get booking details
        $builder = $db->table('bookings');
        $builder->select('bookings.*');
        $builder->where('bookings.id', $data['review']['booking_id']);
        $data['booking'] = $builder->get()->getRowArray();

        // Get delivery slot details if available
        if (!empty($data['booking']['delivery_slot_id'])) {
            $slotBuilder = $db->table('delivery_slots');
            $slotBuilder->where('id', $data['booking']['delivery_slot_id']);
            $slot = $slotBuilder->get()->getRowArray();
            if ($slot) {
                $data['booking']['slot_name'] = $slot['slot_name'] ?? 'N/A';
            } else {
                $data['booking']['slot_name'] = 'N/A';
            }
        } else {
            $data['booking']['slot_name'] = 'N/A';
        }

        // Get booking items
        $builder = $db->table('booking_items');
        $builder->select('booking_items.*, dishes.name as dish_name, dishes.image as dish_image');
        $builder->join('dishes', 'dishes.id = booking_items.dish_id');
        $builder->where('booking_items.booking_id', $data['review']['booking_id']);
        $data['items'] = $builder->get()->getResultArray();

        return view('admin/reviews/view', $data);
    }

    public function delete($id)
    {
        if (!session()->get('logged_in') || !session()->get('is_admin')) {
            return redirect()->to('/auth/login');
        }

        $review = $this->reviewModel->find($id);

        if (!$review) {
            return redirect()->to('/admin/reviews')->with('error', 'Review not found');
        }

        $this->reviewModel->delete($id);

        return redirect()->to('/admin/reviews')->with('success', 'Review deleted successfully');
    }
}
