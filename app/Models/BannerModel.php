<?php

namespace App\Models;

use CodeIgniter\Model;

class BannerModel extends Model
{
    protected $table            = 'banners';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'title', 'subtitle', 'image', 'button_text', 'button_link', 'order', 'is_active'
    ];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    // Validation
    protected $validationRules      = [
        'title'     => 'required|min_length[3]|max_length[100]',
        'image'     => 'required',
        'order'     => 'permit_empty|integer',
        'is_active' => 'permit_empty|integer|in_list[0,1]',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    /**
     * Get active banners ordered by the order field
     *
     * @return array
     */
    public function getActiveBanners()
    {
        return $this->where('is_active', 1)
                    ->orderBy('order', 'ASC')
                    ->findAll();
    }
}
