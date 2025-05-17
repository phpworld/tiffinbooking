<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<h1 class="mb-4"><i class="fas fa-calendar-alt"></i> Manage Bookings</h1>

<?php if (empty($bookings)): ?>
    <div class="alert alert-info">
        <i class="fas fa-info-circle"></i> No bookings found.
    </div>
<?php else: ?>
    <div class="card">
        <div class="card-body">
            <ul class="nav nav-tabs mb-3" id="bookingTabs" role="tablist">
                <li class="nav-item" role="presentation">
                    <button class="nav-link active" id="all-tab" data-bs-toggle="tab" data-bs-target="#all" type="button" role="tab" aria-controls="all" aria-selected="true">All</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="pending-tab" data-bs-toggle="tab" data-bs-target="#pending" type="button" role="tab" aria-controls="pending" aria-selected="false">Pending</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="confirmed-tab" data-bs-toggle="tab" data-bs-target="#confirmed" type="button" role="tab" aria-controls="confirmed" aria-selected="false">Confirmed</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="delivered-tab" data-bs-toggle="tab" data-bs-target="#delivered" type="button" role="tab" aria-controls="delivered" aria-selected="false">Delivered</button>
                </li>
                <li class="nav-item" role="presentation">
                    <button class="nav-link" id="cancelled-tab" data-bs-toggle="tab" data-bs-target="#cancelled" type="button" role="tab" aria-controls="cancelled" aria-selected="false">Cancelled</button>
                </li>
            </ul>
            
            <div class="tab-content" id="bookingTabsContent">
                <div class="tab-pane fade show active" id="all" role="tabpanel" aria-labelledby="all-tab">
                    <?= view('admin/bookings/_booking_table', ['bookings' => $bookings]) ?>
                </div>
                <div class="tab-pane fade" id="pending" role="tabpanel" aria-labelledby="pending-tab">
                    <?php
                    $pendingBookings = array_filter($bookings, function($booking) {
                        return $booking['status'] == 'pending';
                    });
                    ?>
                    <?= view('admin/bookings/_booking_table', ['bookings' => $pendingBookings]) ?>
                </div>
                <div class="tab-pane fade" id="confirmed" role="tabpanel" aria-labelledby="confirmed-tab">
                    <?php
                    $confirmedBookings = array_filter($bookings, function($booking) {
                        return $booking['status'] == 'confirmed';
                    });
                    ?>
                    <?= view('admin/bookings/_booking_table', ['bookings' => $confirmedBookings]) ?>
                </div>
                <div class="tab-pane fade" id="delivered" role="tabpanel" aria-labelledby="delivered-tab">
                    <?php
                    $deliveredBookings = array_filter($bookings, function($booking) {
                        return $booking['status'] == 'delivered';
                    });
                    ?>
                    <?= view('admin/bookings/_booking_table', ['bookings' => $deliveredBookings]) ?>
                </div>
                <div class="tab-pane fade" id="cancelled" role="tabpanel" aria-labelledby="cancelled-tab">
                    <?php
                    $cancelledBookings = array_filter($bookings, function($booking) {
                        return $booking['status'] == 'cancelled';
                    });
                    ?>
                    <?= view('admin/bookings/_booking_table', ['bookings' => $cancelledBookings]) ?>
                </div>
            </div>
        </div>
    </div>
<?php endif; ?>
<?= $this->endSection() ?>
