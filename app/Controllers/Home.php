<?php

namespace App\Controllers;

use App\Models\DishModel;

class Home extends BaseController
{
    public function index()
    {
        $dishModel = new DishModel();
        $data['featured_dishes'] = $dishModel->where('available', 1)->orderBy('id', 'DESC')->findAll(6);

        return view('home', $data);
    }
}
