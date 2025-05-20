<?php

namespace App\Controllers\Api;

use App\Controllers\BaseController;
use App\Models\BannerModel;
use CodeIgniter\API\ResponseTrait;

class Banners extends BaseController
{
    use ResponseTrait;

    protected $bannerModel;

    public function __construct()
    {
        $this->bannerModel = new BannerModel();
    }

    public function index()
    {
        $banners = $this->bannerModel->getActiveBanners();

        // Format the banner data for the API
        $formattedBanners = [];
        foreach ($banners as $banner) {
            $formattedBanners[] = [
                'id' => $banner['id'],
                'title' => $banner['title'],
                'subtitle' => $banner['subtitle'],
                'image' => base_url('uploads/banners/' . $banner['image']),
                'button_text' => $banner['button_text'],
                'button_link' => $banner['button_link'],
                'order' => $banner['order'],
            ];
        }

        return $this->respond([
            'status' => 'success',
            'data' => $formattedBanners
        ]);
    }
}
