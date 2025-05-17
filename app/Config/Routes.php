<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');

// Auth Routes
$routes->get('/auth/login', 'Auth::login');
$routes->post('/auth/login', 'Auth::login');
$routes->get('/auth/register', 'Auth::register');
$routes->post('/auth/register', 'Auth::register');
$routes->get('/auth/logout', 'Auth::logout');

// User Routes
$routes->get('/user', 'User::index');
$routes->get('/user/dashboard', 'User::dashboard');
$routes->get('/user/profile', 'User::profile');
$routes->post('/user/profile', 'User::profile');
$routes->get('/user/wallet', 'User::wallet');
$routes->get('/user/wallet/recharge', 'User::rechargeWallet');
$routes->post('/user/wallet/recharge', 'User::rechargeWallet');
$routes->get('/user/bookings', 'User::bookings');
$routes->get('/user/bookings/view/(:num)', 'User::viewBooking/$1');
$routes->get('/user/bookings/cancel/(:num)', 'User::cancelBooking/$1');

// Dish Routes
$routes->get('/dishes', 'Dish::index');
$routes->get('/dishes/view/(:num)', 'Dish::view/$1');

// Booking Routes
$routes->get('/booking', 'Booking::index');
$routes->get('/booking/create', 'Booking::create');
$routes->get('/booking/add-to-cart/(:num)', 'Booking::addToCart/$1');
$routes->get('/booking/remove-from-cart/(:num)', 'Booking::removeFromCart/$1');
$routes->get('/booking/clear-cart', 'Booking::clearCart');
$routes->get('/booking/checkout', 'Booking::checkout');
$routes->post('/booking/place-order', 'Booking::placeOrder');
$routes->get('/booking/update-quantity/(:num)/(:num)', 'Booking::updateQuantity/$1/$2');

// Review Routes
$routes->get('/review/create/(:num)', 'Review::create/$1');
$routes->post('/review/store', 'Review::store');
$routes->post('/review/store-dish-review', 'Review::storeDishReview');

// Admin Review Routes
$routes->get('/admin/reviews', 'Review::index');
$routes->get('/admin/reviews/view/(:num)', 'Review::view/$1');
$routes->get('/admin/reviews/delete/(:num)', 'Review::delete/$1');

// Admin Routes
$routes->get('/admin', 'Admin::index');
$routes->get('/admin/login', 'Admin::login');
$routes->post('/admin/login', 'Admin::login');
$routes->get('/admin/logout', 'Admin::logout');
$routes->get('/admin/dashboard', 'Admin::dashboard');

// Admin Dish Management
$routes->get('/admin/dishes', 'Admin::dishes');
$routes->get('/admin/dishes/create', 'Admin::createDish');
$routes->post('/admin/dishes/create', 'Admin::createDish');
$routes->get('/admin/dishes/edit/(:num)', 'Admin::editDish/$1');
$routes->post('/admin/dishes/edit/(:num)', 'Admin::editDish/$1');
$routes->get('/admin/dishes/delete/(:num)', 'Admin::deleteDish/$1');

// Admin Booking Management
$routes->get('/admin/bookings', 'Admin::bookings');
$routes->get('/admin/bookings/view/(:num)', 'Admin::viewBooking/$1');
$routes->post('/admin/bookings/update-status/(:num)', 'Admin::updateBookingStatus/$1');

// Admin User Management
$routes->get('/admin/users', 'Admin::users');
$routes->get('/admin/users/view/(:num)', 'Admin::viewUser/$1');

// Admin Delivery Slot Management
$routes->get('/admin/delivery-slots', 'Admin::deliverySlots');
$routes->get('/admin/delivery-slots/create', 'Admin::createSlot');
$routes->post('/admin/delivery-slots/create', 'Admin::createSlot');
$routes->get('/admin/delivery-slots/edit/(:num)', 'Admin::editSlot/$1');
$routes->post('/admin/delivery-slots/edit/(:num)', 'Admin::editSlot/$1');
$routes->get('/admin/delivery-slots/delete/(:num)', 'Admin::deleteSlot/$1');

// Admin Reports
$routes->get('/admin/reports', 'Admin::reports');
