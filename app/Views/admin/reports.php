<?= $this->extend('layouts/admin') ?>

<?= $this->section('content') ?>
<h1 class="mb-4"><i class="fas fa-chart-bar"></i> Reports</h1>

<div class="row mb-4">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Revenue Overview</h5>
                <div class="btn-group" role="group">
                    <button type="button" class="btn btn-sm btn-outline-primary active" id="revenueWeekBtn">Weekly</button>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="revenueMonthBtn">Monthly</button>
                    <button type="button" class="btn btn-sm btn-outline-primary" id="revenueYearBtn">Yearly</button>
                </div>
            </div>
            <div class="card-body">
                <div class="chart-container" style="height: 300px;">
                    <canvas id="revenueChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daily Report (Last 7 Days)</h5>
                <button class="btn btn-sm btn-outline-primary" id="toggleDailyView">
                    <i class="fas fa-chart-bar"></i> Toggle View
                </button>
            </div>
            <div class="card-body">
                <?php if (empty($daily_report)): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No data available for daily report.
                    </div>
                <?php else: ?>
                    <div id="dailyTableView">
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
                    </div>
                    <div id="dailyChartView" style="display: none;">
                        <div class="chart-container" style="height: 250px;">
                            <canvas id="dailyReportChart"></canvas>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-md-6 mb-4">
        <div class="card h-100">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Monthly Report (Last 6 Months)</h5>
                <button class="btn btn-sm btn-outline-success" id="toggleMonthlyView">
                    <i class="fas fa-chart-bar"></i> Toggle View
                </button>
            </div>
            <div class="card-body">
                <?php if (empty($monthly_report)): ?>
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> No data available for monthly report.
                    </div>
                <?php else: ?>
                    <div id="monthlyTableView">
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
                    </div>
                    <div id="monthlyChartView" style="display: none;">
                        <div class="chart-container" style="height: 250px;">
                            <canvas id="monthlyReportChart"></canvas>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<div class="row mb-4">
    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">Order Status Distribution</h5>
            </div>
            <div class="card-body">
                <div class="chart-container" style="height: 250px;">
                    <canvas id="orderStatusChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">Payment Methods</h5>
            </div>
            <div class="card-body">
                <div class="chart-container" style="height: 250px;">
                    <canvas id="paymentMethodsChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card h-100">
            <div class="card-header">
                <h5 class="mb-0">Top Selling Dishes</h5>
            </div>
            <div class="card-body">
                <div class="chart-container" style="height: 250px;">
                    <canvas id="topDishesChart"></canvas>
                </div>
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
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Revenue Overview Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        const revenueChart = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: ['Week 1', 'Week 2', 'Week 3', 'Week 4'],
                datasets: [{
                    label: 'Revenue',
                    data: [12500, 19000, 15000, 25000],
                    backgroundColor: 'rgba(78, 115, 223, 0.05)',
                    borderColor: 'rgba(78, 115, 223, 1)',
                    pointBackgroundColor: 'rgba(78, 115, 223, 1)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgba(78, 115, 223, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Daily Report Chart
        <?php if (!empty($daily_report)): ?>
            const dailyReportCtx = document.getElementById('dailyReportChart').getContext('2d');
            const dailyReportChart = new Chart(dailyReportCtx, {
                type: 'bar',
                data: {
                    labels: [
                        <?php foreach ($daily_report as $report): ?> '<?= date('M d', strtotime($report['date'])) ?>',
                        <?php endforeach; ?>
                    ],
                    datasets: [{
                        label: 'Revenue',
                        data: [
                            <?php foreach ($daily_report as $report): ?>
                                <?= $report['revenue'] ?>,
                            <?php endforeach; ?>
                        ],
                        backgroundColor: 'rgba(78, 115, 223, 0.8)',
                        borderColor: 'rgba(78, 115, 223, 1)',
                        borderWidth: 1
                    }, {
                        label: 'Bookings',
                        data: [
                            <?php foreach ($daily_report as $report): ?>
                                <?= $report['bookings'] ?>,
                            <?php endforeach; ?>
                        ],
                        backgroundColor: 'rgba(28, 200, 138, 0.8)',
                        borderColor: 'rgba(28, 200, 138, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        <?php endif; ?>

        // Monthly Report Chart
        <?php if (!empty($monthly_report)): ?>
            const monthlyReportCtx = document.getElementById('monthlyReportChart').getContext('2d');
            const monthlyReportChart = new Chart(monthlyReportCtx, {
                type: 'bar',
                data: {
                    labels: [
                        <?php foreach ($monthly_report as $report): ?> '<?= $report['month'] ?>',
                        <?php endforeach; ?>
                    ],
                    datasets: [{
                        label: 'Revenue',
                        data: [
                            <?php foreach ($monthly_report as $report): ?>
                                <?= $report['revenue'] ?>,
                            <?php endforeach; ?>
                        ],
                        backgroundColor: 'rgba(78, 115, 223, 0.8)',
                        borderColor: 'rgba(78, 115, 223, 1)',
                        borderWidth: 1
                    }, {
                        label: 'Bookings',
                        data: [
                            <?php foreach ($monthly_report as $report): ?>
                                <?= $report['bookings'] ?>,
                            <?php endforeach; ?>
                        ],
                        backgroundColor: 'rgba(28, 200, 138, 0.8)',
                        borderColor: 'rgba(28, 200, 138, 1)',
                        borderWidth: 1
                    }]
                },
                options: {
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'top'
                        }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            grid: {
                                color: 'rgba(0, 0, 0, 0.05)'
                            }
                        },
                        x: {
                            grid: {
                                display: false
                            }
                        }
                    }
                }
            });
        <?php endif; ?>

        // Order Status Chart
        const orderStatusCtx = document.getElementById('orderStatusChart').getContext('2d');
        const orderStatusChart = new Chart(orderStatusCtx, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Confirmed', 'Delivered', 'Cancelled'],
                datasets: [{
                    data: [15, 25, 55, 5],
                    backgroundColor: [
                        'rgba(246, 194, 62, 0.8)',
                        'rgba(78, 115, 223, 0.8)',
                        'rgba(28, 200, 138, 0.8)',
                        'rgba(231, 74, 59, 0.8)'
                    ],
                    borderColor: [
                        'rgba(246, 194, 62, 1)',
                        'rgba(78, 115, 223, 1)',
                        'rgba(28, 200, 138, 1)',
                        'rgba(231, 74, 59, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                },
                cutout: '70%'
            }
        });

        // Payment Methods Chart
        const paymentMethodsCtx = document.getElementById('paymentMethodsChart').getContext('2d');
        const paymentMethodsChart = new Chart(paymentMethodsCtx, {
            type: 'pie',
            data: {
                labels: ['Wallet', 'Cash on Delivery'],
                datasets: [{
                    data: [65, 35],
                    backgroundColor: [
                        'rgba(78, 115, 223, 0.8)',
                        'rgba(28, 200, 138, 0.8)'
                    ],
                    borderColor: [
                        'rgba(78, 115, 223, 1)',
                        'rgba(28, 200, 138, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    }
                }
            }
        });

        // Top Dishes Chart
        const topDishesCtx = document.getElementById('topDishesChart').getContext('2d');
        const topDishesChart = new Chart(topDishesCtx, {
            type: 'horizontalBar',
            type: 'bar',
            data: {
                labels: ['Dish 1', 'Dish 2', 'Dish 3', 'Dish 4', 'Dish 5'],
                datasets: [{
                    label: 'Orders',
                    data: [45, 37, 30, 27, 22],
                    backgroundColor: [
                        'rgba(78, 115, 223, 0.8)',
                        'rgba(28, 200, 138, 0.8)',
                        'rgba(54, 185, 204, 0.8)',
                        'rgba(246, 194, 62, 0.8)',
                        'rgba(231, 74, 59, 0.8)'
                    ],
                    borderColor: [
                        'rgba(78, 115, 223, 1)',
                        'rgba(28, 200, 138, 1)',
                        'rgba(54, 185, 204, 1)',
                        'rgba(246, 194, 62, 1)',
                        'rgba(231, 74, 59, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                indexAxis: 'y',
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    x: {
                        beginAtZero: true,
                        grid: {
                            color: 'rgba(0, 0, 0, 0.05)'
                        }
                    },
                    y: {
                        grid: {
                            display: false
                        }
                    }
                }
            }
        });

        // Toggle view buttons
        document.getElementById('toggleDailyView').addEventListener('click', function() {
            const tableView = document.getElementById('dailyTableView');
            const chartView = document.getElementById('dailyChartView');

            if (tableView.style.display === 'none') {
                tableView.style.display = 'block';
                chartView.style.display = 'none';
                this.innerHTML = '<i class="fas fa-chart-bar"></i> Toggle View';
            } else {
                tableView.style.display = 'none';
                chartView.style.display = 'block';
                this.innerHTML = '<i class="fas fa-table"></i> Toggle View';
            }
        });

        document.getElementById('toggleMonthlyView').addEventListener('click', function() {
            const tableView = document.getElementById('monthlyTableView');
            const chartView = document.getElementById('monthlyChartView');

            if (tableView.style.display === 'none') {
                tableView.style.display = 'block';
                chartView.style.display = 'none';
                this.innerHTML = '<i class="fas fa-chart-bar"></i> Toggle View';
            } else {
                tableView.style.display = 'none';
                chartView.style.display = 'block';
                this.innerHTML = '<i class="fas fa-table"></i> Toggle View';
            }
        });

        // Revenue period buttons
        document.getElementById('revenueWeekBtn').addEventListener('click', function() {
            revenueChart.data.labels = ['Week 1', 'Week 2', 'Week 3', 'Week 4'];
            revenueChart.data.datasets[0].data = [12500, 19000, 15000, 25000];
            revenueChart.update();

            document.getElementById('revenueWeekBtn').classList.add('active');
            document.getElementById('revenueMonthBtn').classList.remove('active');
            document.getElementById('revenueYearBtn').classList.remove('active');
        });

        document.getElementById('revenueMonthBtn').addEventListener('click', function() {
            revenueChart.data.labels = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
            revenueChart.data.datasets[0].data = [15000, 17000, 20000, 22000, 24000, 30000, 32000, 38000, 35000, 40000, 45000, 50000];
            revenueChart.update();

            document.getElementById('revenueWeekBtn').classList.remove('active');
            document.getElementById('revenueMonthBtn').classList.add('active');
            document.getElementById('revenueYearBtn').classList.remove('active');
        });

        document.getElementById('revenueYearBtn').addEventListener('click', function() {
            revenueChart.data.labels = ['2020', '2021', '2022', '2023', '2024', '2025'];
            revenueChart.data.datasets[0].data = [120000, 180000, 250000, 320000, 400000, 350000];
            revenueChart.update();

            document.getElementById('revenueWeekBtn').classList.remove('active');
            document.getElementById('revenueMonthBtn').classList.remove('active');
            document.getElementById('revenueYearBtn').classList.add('active');
        });
    });
</script>
<?= $this->endSection() ?>