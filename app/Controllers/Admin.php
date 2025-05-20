<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AdminModel;
use App\Models\UserModel;
use App\Models\DishModel;
use App\Models\BookingModel;
use App\Models\BookingItemModel;
use App\Models\DeliverySlotModel;
use App\Models\BannerModel;

class Admin extends BaseController
{
    protected $adminModel;
    protected $userModel;
    protected $dishModel;
    protected $bookingModel;
    protected $bookingItemModel;
    protected $deliverySlotModel;
    protected $bannerModel;

    public function __construct()
    {
        $this->adminModel = new AdminModel();
        $this->userModel = new UserModel();
        $this->dishModel = new DishModel();
        $this->bookingModel = new BookingModel();
        $this->bookingItemModel = new BookingItemModel();
        $this->deliverySlotModel = new DeliverySlotModel();
        $this->bannerModel = new BannerModel();
    }

    public function index()
    {
        return redirect()->to('/admin/login');
    }

    public function login()
    {
        if (session()->get('is_admin')) {
            return redirect()->to('/admin/dashboard');
        }

        if ($this->request->getMethod() === 'POST') {
            $username = $this->request->getPost('username');
            $password = $this->request->getPost('password');

            $admin = $this->adminModel->where('username', $username)->first();

            if ($admin && password_verify($password, $admin['password'])) {
                $session = session();
                $adminData = [
                    'admin_id' => $admin['id'],
                    'username' => $admin['username'],
                    'name' => $admin['name'],
                    'is_admin' => true,
                    'logged_in' => true
                ];

                $session->set($adminData);
                return redirect()->to('/admin/dashboard');
            } else {
                return redirect()->back()->with('error', 'Invalid username or password');
            }
        }

        return view('admin/login');
    }

    public function dashboard()
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/admin/login');
        }

        $data['total_users'] = $this->userModel->countAll();
        $data['total_dishes'] = $this->dishModel->countAll();
        $data['total_bookings'] = $this->bookingModel->countAll();
        $data['pending_bookings'] = $this->bookingModel->where('status', 'pending')->countAllResults();

        // Today's bookings
        $today = date('Y-m-d');
        $data['today_bookings'] = $this->bookingModel->where('booking_date', $today)->countAllResults();

        return view('admin/dashboard', $data);
    }

    public function logout()
    {
        $session = session();
        $session->destroy();

        return redirect()->to('/admin/login');
    }

    // Dish Management
    public function dishes()
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/admin/login');
        }

        $data['dishes'] = $this->dishModel->findAll();

        return view('admin/dishes/index', $data);
    }

    public function createDish()
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/admin/login');
        }

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'name' => 'required|min_length[3]|max_length[100]',
                'price' => 'required|numeric',
            ];

            // Only validate image if it's uploaded
            if ($this->request->getFile('image')->isValid()) {
                $rules['image'] = 'max_size[image,1024]|is_image[image]';
            }

            if ($this->validate($rules)) {
                $data = [
                    'name' => $this->request->getPost('name'),
                    'description' => $this->request->getPost('description'),
                    'price' => $this->request->getPost('price'),
                    'available' => $this->request->getPost('available') ? 1 : 0,
                    'is_vegetarian' => $this->request->getPost('is_vegetarian') ? 1 : 0,
                ];

                // Handle image upload if provided
                $img = $this->request->getFile('image');
                if ($img->isValid() && !$img->hasMoved()) {
                    $newName = $img->getRandomName();
                    $img->move(ROOTPATH . 'uploads/dishes', $newName);
                    $data['image'] = $newName;
                }

                $this->dishModel->insert($data);

                return redirect()->to('/admin/dishes')->with('success', 'Dish created successfully');
            } else {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }
        }

        return view('admin/dishes/create');
    }

    public function editDish($id)
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/admin/login');
        }

        $dish = $this->dishModel->find($id);

        if (!$dish) {
            return redirect()->to('/admin/dishes')->with('error', 'Dish not found');
        }

        $data['dish'] = $dish;

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'name' => 'required|min_length[3]|max_length[100]',
                'price' => 'required|numeric',
            ];

            // Only validate image if a new one is uploaded
            if ($this->request->getFile('image')->isValid()) {
                $rules['image'] = 'uploaded[image]|max_size[image,1024]|is_image[image]';
            }

            if ($this->validate($rules)) {
                $updateData = [
                    'name' => $this->request->getPost('name'),
                    'description' => $this->request->getPost('description'),
                    'price' => $this->request->getPost('price'),
                    'available' => $this->request->getPost('available') ? 1 : 0,
                    'is_vegetarian' => $this->request->getPost('is_vegetarian') ? 1 : 0,
                ];

                // Handle image upload if a new one is provided
                if ($this->request->getFile('image')->isValid()) {
                    $img = $this->request->getFile('image');
                    $newName = $img->getRandomName();
                    $img->move(ROOTPATH . 'uploads/dishes', $newName);

                    // Delete old image
                    if ($dish['image'] && file_exists(ROOTPATH . 'uploads/dishes/' . $dish['image'])) {
                        unlink(ROOTPATH . 'uploads/dishes/' . $dish['image']);
                    }

                    $updateData['image'] = $newName;
                }

                $this->dishModel->update($id, $updateData);

                return redirect()->to('/admin/dishes')->with('success', 'Dish updated successfully');
            } else {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }
        }

        return view('admin/dishes/edit', $data);
    }

    public function deleteDish($id)
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/admin/login');
        }

        $dish = $this->dishModel->find($id);

        if (!$dish) {
            return redirect()->to('/admin/dishes')->with('error', 'Dish not found');
        }

        // Delete image
        if ($dish['image'] && file_exists(ROOTPATH . 'uploads/dishes/' . $dish['image'])) {
            unlink(ROOTPATH . 'uploads/dishes/' . $dish['image']);
        }

        $this->dishModel->delete($id);

        return redirect()->to('/admin/dishes')->with('success', 'Dish deleted successfully');
    }

    // Booking Management
    public function bookings()
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/admin/login');
        }

        $data['bookings'] = $this->bookingModel->orderBy('booking_date', 'DESC')->findAll();

        return view('admin/bookings/index', $data);
    }

    public function viewBooking($id)
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/admin/login');
        }

        $booking = $this->bookingModel->find($id);

        if (!$booking) {
            return redirect()->to('/admin/bookings')->with('error', 'Booking not found');
        }

        $data['booking'] = $booking;
        $data['user'] = $this->userModel->find($booking['user_id']);
        $data['items'] = $this->bookingItemModel->where('booking_id', $id)->findAll();
        $data['slot'] = $this->deliverySlotModel->find($booking['delivery_slot_id']);

        return view('admin/bookings/view', $data);
    }

    public function updateBookingStatus($id)
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/admin/login');
        }

        $booking = $this->bookingModel->find($id);

        if (!$booking) {
            return redirect()->to('/admin/bookings')->with('error', 'Booking not found');
        }

        $status = $this->request->getPost('status');

        if (!in_array($status, ['pending', 'confirmed', 'delivered', 'cancelled'])) {
            return redirect()->back()->with('error', 'Invalid status');
        }

        $this->bookingModel->update($id, ['status' => $status]);

        return redirect()->to('/admin/bookings')->with('success', 'Booking status updated successfully');
    }

    // User Management
    public function users()
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/admin/login');
        }

        $data['users'] = $this->userModel->findAll();

        return view('admin/users/index', $data);
    }

    public function viewUser($id)
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/admin/login');
        }

        $user = $this->userModel->find($id);

        if (!$user) {
            return redirect()->to('/admin/users')->with('error', 'User not found');
        }

        $data['user'] = $user;
        $data['bookings'] = $this->bookingModel->where('user_id', $id)->findAll();

        return view('admin/users/view', $data);
    }

    // Delivery Slot Management
    public function deliverySlots()
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/admin/login');
        }

        $data['slots'] = $this->deliverySlotModel->findAll();

        return view('admin/slots/index', $data);
    }

    public function createSlot()
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/admin/login');
        }

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'slot_time' => 'required|min_length[3]|max_length[50]',
            ];

            if ($this->validate($rules)) {
                $data = [
                    'slot_time' => $this->request->getPost('slot_time'),
                    'is_active' => $this->request->getPost('is_active') ? 1 : 0,
                ];

                $this->deliverySlotModel->insert($data);

                return redirect()->to('/admin/delivery-slots')->with('success', 'Delivery slot created successfully');
            } else {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }
        }

        return view('admin/slots/create');
    }

    public function editSlot($id)
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/admin/login');
        }

        $slot = $this->deliverySlotModel->find($id);

        if (!$slot) {
            return redirect()->to('/admin/delivery-slots')->with('error', 'Delivery slot not found');
        }

        $data['slot'] = $slot;

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'slot_time' => 'required|min_length[3]|max_length[50]',
            ];

            if ($this->validate($rules)) {
                $updateData = [
                    'slot_time' => $this->request->getPost('slot_time'),
                    'is_active' => $this->request->getPost('is_active') ? 1 : 0,
                ];

                $this->deliverySlotModel->update($id, $updateData);

                return redirect()->to('/admin/delivery-slots')->with('success', 'Delivery slot updated successfully');
            } else {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }
        }

        return view('admin/slots/edit', $data);
    }

    public function deleteSlot($id)
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/admin/login');
        }

        $slot = $this->deliverySlotModel->find($id);

        if (!$slot) {
            return redirect()->to('/admin/delivery-slots')->with('error', 'Delivery slot not found');
        }

        // Check if slot is used in any bookings
        $bookingsWithSlot = $this->bookingModel->where('delivery_slot_id', $id)->countAllResults();

        if ($bookingsWithSlot > 0) {
            return redirect()->to('/admin/delivery-slots')->with('error', 'Cannot delete delivery slot as it is used in bookings');
        }

        $this->deliverySlotModel->delete($id);

        return redirect()->to('/admin/delivery-slots')->with('success', 'Delivery slot deleted successfully');
    }

    // Reports
    public function reports()
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/admin/login');
        }

        $data['daily_report'] = [];
        $data['monthly_report'] = [];

        // Daily report for the last 7 days
        for ($i = 0; $i < 7; $i++) {
            $date = date('Y-m-d', strtotime("-$i days"));
            $bookings = $this->bookingModel->where('booking_date', $date)->findAll();

            $total = 0;
            foreach ($bookings as $booking) {
                $total += $booking['total_amount'];
            }

            $data['daily_report'][] = [
                'date' => $date,
                'bookings' => count($bookings),
                'revenue' => $total
            ];
        }

        // Monthly report for the last 6 months
        for ($i = 0; $i < 6; $i++) {
            $month = date('Y-m', strtotime("-$i months"));
            $startDate = $month . '-01';
            $endDate = date('Y-m-t', strtotime($startDate));

            $bookings = $this->bookingModel->where('booking_date >=', $startDate)
                ->where('booking_date <=', $endDate)
                ->findAll();

            $total = 0;
            foreach ($bookings as $booking) {
                $total += $booking['total_amount'];
            }

            $data['monthly_report'][] = [
                'month' => date('F Y', strtotime($startDate)),
                'bookings' => count($bookings),
                'revenue' => $total
            ];
        }

        return view('admin/reports', $data);
    }

    // Banner Management
    public function banners()
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/admin/login');
        }

        $data['banners'] = $this->bannerModel->orderBy('order', 'ASC')->findAll();

        return view('admin/banners/index', $data);
    }

    public function createBanner()
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/admin/login');
        }

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'title' => 'required|min_length[3]|max_length[100]',
                'image' => 'uploaded[image]|max_size[image,2048]|is_image[image]',
            ];

            if ($this->validate($rules)) {
                $data = [
                    'title' => $this->request->getPost('title'),
                    'subtitle' => $this->request->getPost('subtitle'),
                    'button_text' => $this->request->getPost('button_text'),
                    'button_link' => $this->request->getPost('button_link'),
                    'order' => $this->request->getPost('order') ?? 0,
                    'is_active' => $this->request->getPost('is_active') ? 1 : 0,
                ];

                // Handle image upload
                $img = $this->request->getFile('image');
                if ($img->isValid() && !$img->hasMoved()) {
                    $newName = $img->getRandomName();
                    $img->move(ROOTPATH . 'public/uploads/banners', $newName);
                    $data['image'] = $newName;
                }

                $this->bannerModel->insert($data);

                return redirect()->to('/admin/banners')->with('success', 'Banner created successfully');
            } else {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }
        }

        return view('admin/banners/create');
    }

    public function editBanner($id)
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/admin/login');
        }

        $banner = $this->bannerModel->find($id);

        if (!$banner) {
            return redirect()->to('/admin/banners')->with('error', 'Banner not found');
        }

        $data['banner'] = $banner;

        if ($this->request->getMethod() === 'POST') {
            $rules = [
                'title' => 'required|min_length[3]|max_length[100]',
            ];

            // Only validate image if a new one is uploaded
            if ($this->request->getFile('image')->isValid()) {
                $rules['image'] = 'max_size[image,2048]|is_image[image]';
            }

            if ($this->validate($rules)) {
                $updateData = [
                    'title' => $this->request->getPost('title'),
                    'subtitle' => $this->request->getPost('subtitle'),
                    'button_text' => $this->request->getPost('button_text'),
                    'button_link' => $this->request->getPost('button_link'),
                    'order' => $this->request->getPost('order') ?? 0,
                    'is_active' => $this->request->getPost('is_active') ? 1 : 0,
                ];

                // Handle image upload if a new one is provided
                $img = $this->request->getFile('image');
                if ($img->isValid() && !$img->hasMoved()) {
                    $newName = $img->getRandomName();
                    $img->move(ROOTPATH . 'public/uploads/banners', $newName);

                    // Delete old image
                    if ($banner['image'] && file_exists(ROOTPATH . 'public/uploads/banners/' . $banner['image'])) {
                        unlink(ROOTPATH . 'public/uploads/banners/' . $banner['image']);
                    }

                    $updateData['image'] = $newName;
                }

                $this->bannerModel->update($id, $updateData);

                return redirect()->to('/admin/banners')->with('success', 'Banner updated successfully');
            } else {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }
        }

        return view('admin/banners/edit', $data);
    }

    public function deleteBanner($id)
    {
        if (!session()->get('is_admin')) {
            return redirect()->to('/admin/login');
        }

        $banner = $this->bannerModel->find($id);

        if (!$banner) {
            return redirect()->to('/admin/banners')->with('error', 'Banner not found');
        }

        // Delete image
        if ($banner['image'] && file_exists(ROOTPATH . 'public/uploads/banners/' . $banner['image'])) {
            unlink(ROOTPATH . 'public/uploads/banners/' . $banner['image']);
        }

        $this->bannerModel->delete($id);

        return redirect()->to('/admin/banners')->with('success', 'Banner deleted successfully');
    }
}
