<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\DishModel;

class Dish extends BaseController
{
    protected $dishModel;

    public function __construct()
    {
        $this->dishModel = new DishModel();
    }

    public function index()
    {
        $data['dishes'] = $this->dishModel->where('available', 1)->findAll();
        return view('dishes/index', $data);
    }

    public function view($id)
    {
        $dish = $this->dishModel->find($id);

        if (!$dish) {
            return redirect()->to('/dishes')->with('error', 'Dish not found');
        }

        $data['dish'] = $dish;
        return view('dishes/view', $data);
    }
}
