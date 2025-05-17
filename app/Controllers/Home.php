<?php

namespace App\Controllers;

use App\Models\DishModel;

class Home extends BaseController
{
    public function index()
    {
        $dishModel = new DishModel();

        // Get featured dishes
        $data['featured_dishes'] = $dishModel->where('available', 1)->orderBy('id', 'DESC')->findAll(6);

        // Get top reviews
        $db = \Config\Database::connect();
        $builder = $db->table('reviews');
        $builder->select('reviews.*, users.name as user_name');
        $builder->join('users', 'users.id = reviews.user_id');
        $builder->where('reviews.rating >=', 4); // Only show good reviews (4-5 stars)
        $builder->orderBy('reviews.created_at', 'DESC');
        $builder->limit(3);
        $data['reviews'] = $builder->get()->getResultArray();

        return view('home', $data);
    }
}
