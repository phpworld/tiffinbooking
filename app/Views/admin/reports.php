<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<h1 class="mb-4"><i class="fas fa-chart-bar"></i> Reports</h1>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-primary text-white">
                <h5 class="mb-0">Daily Report (Last 7 Days)</h5>
            </div>
            <div class="card-body">
                <?php if (empty($daily_report)): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No data available for daily report.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Bookings</th>
                                    <th>Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($daily_report as $report): ?>
                                    <tr>
                                        <td><?= date('M d, Y', strtotime($report['date'])) ?></td>
                                        <td><?= $report['bookings'] ?></td>
                                        <td>₹<?= number_format($report['revenue'], 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
    
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header bg-success text-white">
                <h5 class="mb-0">Monthly Report (Last 6 Months)</h5>
            </div>
            <div class="card-body">
                <?php if (empty($monthly_report)): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No data available for monthly report.
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Month</th>
                                    <th>Bookings</th>
                                    <th>Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($monthly_report as $report): ?>
                                    <tr>
                                        <td><?= $report['month'] ?></td>
                                        <td><?= $report['bookings'] ?></td>
                                        <td>₹<?= number_format($report['revenue'], 2) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg-info text-white">
                <h5 class="mb-0">Export Reports</h5>
            </div>
            <div class="card-body">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle"></i> Export functionality is not implemented in this demo.
                </div>
                
                <div class="row">
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h6>Daily Report</h6>
                                <form>
                                    <div class="mb-3">
                                        <label for="daily_date" class="form-label">Select Date</label>
                                        <input type="date" class="form-control" id="daily_date" name="daily_date" value="<?= date('Y-m-d') ?>">
                                    </div>
                                    <button type="button" class="btn btn-primary" disabled>
                                        <i class="fas fa-download"></i> Export Daily Report
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                    
                    <div class="col-md-6">
                        <div class="card mb-3">
                            <div class="card-body">
                                <h6>Monthly Report</h6>
                                <form>
                                    <div class="mb-3">
                                        <label for="monthly_date" class="form-label">Select Month</label>
                                        <input type="month" class="form-control" id="monthly_date" name="monthly_date" value="<?= date('Y-m') ?>">
                                    </div>
                                    <button type="button" class="btn btn-primary" disabled>
                                        <i class="fas fa-download"></i> Export Monthly Report
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection() ?>
