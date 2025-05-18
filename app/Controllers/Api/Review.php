<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\ReviewModel;
use App\Models\BookingModel;
use App\Models\BookingItemModel;
use CodeIgniter\API\ResponseTrait;

class Review extends BaseController
{
    use ResponseTrait;
    
    protected $reviewModel;
    protected $bookingModel;
    protected $bookingItemModel;
    
    public function __construct()
    {
        helper('jwt');
        $this->reviewModel = new ReviewModel();
        $this->bookingModel = new BookingModel();
        $this->bookingItemModel = new BookingItemModel();
    }
    
    /**
     * Add a review
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
            'booking_id' => 'required|numeric',
            'rating' => 'required|numeric|greater_than[0]|less_than[6]',
            'comment' => 'permit_empty|max_length[400]'
        ];
        
        if (!$this->validate($rules)) {
            return $this->failValidationErrors($this->validator->getErrors());
        }
        
        $bookingId = $this->request->getPost('booking_id');
        $rating = $this->request->getPost('rating');
        $comment = $this->request->getPost('comment');
        
        // Check if booking exists and belongs to user
        $booking = $this->bookingModel->find($bookingId);
        
        if (!$booking) {
            return $this->failNotFound('Booking not found');
        }
        
        if ($booking['user_id'] != $userId) {
            return $this->failForbidden('You do not have permission to review this booking');
        }
        
        // Check if booking is delivered
        if ($booking['status'] != 'delivered') {
            return $this->fail('You can only review delivered orders');
        }
        
        // Check if review already exists
        $existingReview = $this->reviewModel->where('booking_id', $bookingId)
                                           ->where('user_id', $userId)
                                           ->first();
        
        if ($existingReview) {
            // Update existing review
            $this->reviewModel->update($existingReview['id'], [
                'rating' => $rating,
                'comment' => $comment
            ]);
            
            $message = 'Review updated successfully';
        } else {
            // Create new review
            $this->reviewModel->insert([
                'user_id' => $userId,
                'booking_id' => $bookingId,
                'rating' => $rating,
                'comment' => $comment
            ]);
            
            $message = 'Review added successfully';
        }
        
        $response = [
            'status' => true,
            'message' => $message
        ];
        
        return $this->respond($response);
    }
    
    /**
     * Get reviews for a dish
     *
     * @param int $dishId Dish ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function getDishReviews($dishId)
    {
        // Get reviews for the dish
        $db = \Config\Database::connect();
        $builder = $db->table('reviews');
        $builder->select('reviews.*, users.name as user_name');
        $builder->join('users', 'users.id = reviews.user_id');
        $builder->join('booking_items', 'booking_items.booking_id = reviews.booking_id');
        $builder->where('booking_items.dish_id', $dishId);
        $builder->orderBy('reviews.created_at', 'DESC');
        $reviews = $builder->get()->getResultArray();
        
        // Calculate average rating
        $totalRating = 0;
        $reviewCount = count($reviews);
        
        foreach ($reviews as $review) {
            $totalRating += $review['rating'];
        }
        
        $avgRating = $reviewCount > 0 ? round($totalRating / $reviewCount, 1) : 0;
        
        // Process reviews
        $processedReviews = [];
        foreach ($reviews as $review) {
            $processedReviews[] = [
                'id' => $review['id'],
                'user_name' => $review['user_name'],
                'rating' => (int) $review['rating'],
                'comment' => $review['comment'],
                'date' => date('Y-m-d', strtotime($review['created_at']))
            ];
        }
        
        $response = [
            'status' => true,
            'data' => [
                'average_rating' => $avgRating,
                'review_count' => $reviewCount,
                'reviews' => $processedReviews
            ]
        ];
        
        return $this->respond($response);
    }
}
