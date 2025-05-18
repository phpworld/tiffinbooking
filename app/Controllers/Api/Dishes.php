<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\DishModel;
use CodeIgniter\API\ResponseTrait;

class Dishes extends BaseController
{
    use ResponseTrait;
    
    protected $dishModel;
    
    public function __construct()
    {
        helper('jwt');
        $this->dishModel = new DishModel();
    }
    
    /**
     * Get all dishes
     *
     * @return \CodeIgniter\HTTP\Response
     */
    public function index()
    {
        $dishes = $this->dishModel->where('available', 1)->findAll();
        
        // Process dishes to add image URLs and calculate ratings
        $processedDishes = [];
        foreach ($dishes as $dish) {
            // Calculate average rating
            $db = \Config\Database::connect();
            $builder = $db->table('reviews');
            $builder->select('AVG(rating) as avg_rating, COUNT(*) as review_count');
            $builder->join('booking_items', 'booking_items.booking_id = reviews.booking_id');
            $builder->where('booking_items.dish_id', $dish['id']);
            $result = $builder->get()->getRowArray();
            
            $avgRating = round($result['avg_rating'] ?? 0, 1);
            $reviewCount = $result['review_count'] ?? 0;
            
            // Add image URL
            $imageUrl = '';
            if (!empty($dish['image'])) {
                $imageUrl = base_url('/uploads/dishes/' . $dish['image']);
            }
            
            $processedDishes[] = [
                'id' => $dish['id'],
                'name' => $dish['name'],
                'description' => $dish['description'],
                'price' => $dish['price'],
                'image' => $imageUrl,
                'is_vegetarian' => (bool) ($dish['is_vegetarian'] ?? true),
                'rating' => $avgRating,
                'review_count' => $reviewCount
            ];
        }
        
        $response = [
            'status' => true,
            'data' => $processedDishes
        ];
        
        return $this->respond($response);
    }
    
    /**
     * Get dish details
     *
     * @param int $id Dish ID
     * @return \CodeIgniter\HTTP\Response
     */
    public function view($id)
    {
        $dish = $this->dishModel->find($id);
        
        if (!$dish) {
            return $this->failNotFound('Dish not found');
        }
        
        // Calculate average rating
        $db = \Config\Database::connect();
        $builder = $db->table('reviews');
        $builder->select('AVG(rating) as avg_rating, COUNT(*) as review_count');
        $builder->join('booking_items', 'booking_items.booking_id = reviews.booking_id');
        $builder->where('booking_items.dish_id', $dish['id']);
        $result = $builder->get()->getRowArray();
        
        $avgRating = round($result['avg_rating'] ?? 0, 1);
        $reviewCount = $result['review_count'] ?? 0;
        
        // Get reviews
        $builder = $db->table('reviews');
        $builder->select('reviews.*, users.name as user_name');
        $builder->join('users', 'users.id = reviews.user_id');
        $builder->join('booking_items', 'booking_items.booking_id = reviews.booking_id');
        $builder->where('booking_items.dish_id', $dish['id']);
        $builder->orderBy('reviews.created_at', 'DESC');
        $builder->limit(5); // Limit to 5 most recent reviews
        $reviews = $builder->get()->getResultArray();
        
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
        
        // Add image URL
        $imageUrl = '';
        if (!empty($dish['image'])) {
            $imageUrl = base_url('/uploads/dishes/' . $dish['image']);
        }
        
        $response = [
            'status' => true,
            'data' => [
                'id' => $dish['id'],
                'name' => $dish['name'],
                'description' => $dish['description'],
                'price' => $dish['price'],
                'image' => $imageUrl,
                'is_vegetarian' => (bool) ($dish['is_vegetarian'] ?? true),
                'available' => (bool) $dish['available'],
                'rating' => $avgRating,
                'review_count' => $reviewCount,
                'reviews' => $processedReviews
            ]
        ];
        
        return $this->respond($response);
    }
}
