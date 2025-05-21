<?php

namespace App\Models;

use CodeIgniter\Model;

class BookingItemModel extends Model
{
    protected $table            = 'booking_items';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['booking_id', 'dish_id', 'quantity', 'price'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'booking_id' => 'required|numeric',
        'dish_id'    => 'required|numeric',
        'quantity'   => 'required|numeric',
        'price'      => 'required|numeric',
    ];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    /**
     * Get booking items with dish details
     *
     * @param int $bookingId Booking ID
     * @return array
     */
    public function getItemsWithDishDetails($bookingId)
    {
        $builder = $this->db->table($this->table);
        $builder->select($this->table . '.*, dishes.name as dish_name, dishes.image');
        $builder->join('dishes', 'dishes.id = ' . $this->table . '.dish_id', 'left');
        $builder->where($this->table . '.booking_id', $bookingId);

        return $builder->get()->getResultArray();
    }
}
