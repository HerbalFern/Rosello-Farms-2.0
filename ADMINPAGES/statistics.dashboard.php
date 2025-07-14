<?php
require '../ADMINPAGES/adminheader.php';
require_once '../includes/dbh.inc.php';
require_once '../ADMINPAGES/statistics.dashboard.functions.php';
?>

<div class="admin-main">
    <div>
        <aside class="adminsidebar">
            <br>
            <h2>ADMIN FEATURES</h2>
            <br>    
                <nav class="adminsidebarlink">
                    <div class="sidebardiv"><a href="../ADMINPAGES/admin.php"><img src="../ADMINPAGES/adminicons/dashboard.png"> Main Dashboard</a></div>
                    <div class="sidebardiv"><a href="../ADMINPAGES/currinventory.dashboard.php"><img src="../ADMINPAGES/adminicons/inventory.png"> Current Inventory</a></div>
                    <div class="sidebardiv"><a href="../ADMINPAGES/shopinventory.dashboard.php"><img src="../ADMINPAGES/adminicons/shoppingcart.png"> Shopping Inventory</a></div>
                    <div class="sidebardiv"><a href="../ADMINPAGES/usermanagement.dashboard.php"><img src="../ADMINPAGES/adminicons/usermanagement.png">User Management</a></div>
                    <div class="sidebardiv"><a href="#"><img src="../ADMINPAGES/adminicons/statistics.png">Buyer Statistics</a></div>
                    <div class="sidebardiv"><a href="../ADMINPAGES/createannouncement.dashboard.php"><img src="../ADMINPAGES/adminicons/announcment.png">Create Announcements</a></div>
                </nav>
        </aside>
    </div>

    <div class="contentpanel">
        <div class="dashboard-container">
            <h1 class="dashboard-title">Rosello Farms - Sales Analytics</h1>
            
            <!-- Date Selection Form -->
            <div class="card time-period">
                <div class="card-header">
                    Select Time Period
                </div>
                <div class="card-body">
                    <form action="" method="GET" class="date-selection-form">
                        <div class="form-group">
                            <label for="month-select">Month:</label>
                            <?php echo generateMonthDropdown($currentMonth); ?>
                        </div>
                        <div class="form-group">
                            <label for="year-select">Year:</label>
                            <?php echo generateYearDropdown($currentYear); ?>
                        </div>
                        <div class="form-group">
                            <button type="submit" class="submit-btn">View Report</button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Monthly Summary -->
            <div class="card monthly-summary">
                <div class="card-header">
                    <?php echo $monthName; ?> <?php echo $currentYear; ?> Summary
                </div>
                <div class="card-body">
                    <div class="summary-stats">
                        <div class="stat-box">
                            <div class="stat-value"><?php echo number_format($monthlySummary['total_transactions']); ?></div>
                            <div class="stat-label">Total Transactions</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-value">₱<?php echo number_format($monthlySummary['total_sales'], 2); ?></div>
                            <div class="stat-label">Total Revenue</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-value"><?php echo number_format($monthlySummary['unique_customers']); ?></div>
                            <div class="stat-label">Unique Customers</div>
                        </div>
                        <div class="stat-box">
                            <div class="stat-value">₱<?php echo number_format($monthlySummary['average_transaction'], 2); ?></div>
                            <div class="stat-label">Avg. Transaction</div>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Daily Charts Row -->
            <div class="charts-row">
                <!-- Daily Transactions Chart -->
                <div class="card chart-card full-width">
                    <div class="card-header">
                        Daily Transaction Count
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="dailyTransactionsChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- Daily Sales Chart -->
                <div class="card chart-card full-width">
                    <div class="card-header">
                        Daily Sales Amount (PHP)
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="dailySalesChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Category and Products Charts Row -->
            <div class="charts-row">
                <!-- Category Transactions Chart -->
                <div class="card chart-card half-width">
                    <div class="card-header">
                        Transactions by Category
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="categoryChart"></canvas>
                        </div>
                    </div>
                </div>
                
                <!-- Top Products Chart -->
                <div class="card chart-card half-width">
                    <div class="card-header">
                        Top Selling Products
                    </div>
                    <div class="card-body">
                        <div class="chart-container">
                            <canvas id="productsChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Top Products Table -->
            <div class="card data-table">
                <div class="card-header">
                    Top Selling Products Details
                </div>
                <div class="card-body">
                    <div class="table-wrapper">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>Product Name</th>
                                    <th>Quantity Sold</th>
                                    <th>Revenue (PHP)</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($topProducts as $product => $data): ?>
                                <tr>
                                    <td><?php echo $product; ?></td>
                                    <td><?php echo number_format($data['total_quantity']); ?></td>
                                    <td>₱<?php echo number_format($data['total_sales'], 2); ?></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom CSS -->
<style>
    /* Reset and Base Styles */
    * {
        box-sizing: border-box;
        margin: 0;
        padding: 0;
    }
    
    .dashboard-container {
        width: 100%;
        max-width: 1200px;
        margin: 2rem auto;
        font-family: Arial, sans-serif;
    }
    
    .dashboard-title {
        font-size: 2.5rem;
        margin-bottom: 1.5rem;
        color: #333;
    }
    
    /* Card Styles */
    .card {
        background: #fff;
        border-radius: 4px;
        box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        margin-bottom: 1.5rem;
        overflow: hidden;
    }
    
    .card-header {
        background: #4CAF50;
        color: white;
        padding: 0.8rem 1rem;
        font-weight: bold;
    }
    
    .card-body {
        padding: 1rem;
    }
    
    /* Form Styles */
    .date-selection-form {
        display: flex;
        flex-wrap: wrap;
        gap: 1rem;
        align-items: flex-end;
    }
    
    .form-group {
        flex: 1;
        min-width: 200px;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: bold;
    }
    
    select {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 1rem;
    }
    
    .submit-btn {
        background: #4CAF50;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        cursor: pointer;
        font-size: 1rem;
    }
    
    .submit-btn:hover {
        background: #45a049;
    }
    
    /* Summary Stats */
    .summary-stats {
        display: flex;
        flex-wrap: wrap;
        justify-content: space-between;
        gap: 1rem;
    }
    
    .stat-box {
        flex: 1;
        min-width: 200px;
        text-align: center;
        padding: 1rem;
    }
    
    .stat-value {
        font-size: 1.5rem;
        font-weight: bold;
        margin-bottom: 0.5rem;
    }
    
    .stat-label {
        color: #666;
    }
    
    /* Charts */
    .charts-row {
        display: flex;
        flex-wrap: wrap;
        gap: 1.5rem;
        margin-bottom: 1.5rem;
    }
    
    .chart-card {
        flex: 1 1 100%;
    }
    
    .full-width {
        width: 100%;
    }
    
    .half-width {
        flex: 1 1 calc(50% - 0.75rem);
        min-width: 300px;
    }
    
    .chart-container {
        position: relative;
        height: 300px;
        width: 100%;
    }
    
    /* Table Styles */
    .table-wrapper {
        overflow-x: auto;
    }
    
    .data-table {
        width: 100%;
        border-collapse: collapse;
    }
    
    .data-table th,
    .data-table td {
        border: 1px solid #ddd;
        padding: 0.7rem;
        text-align: left;
    }
    
    .data-table th {
        background-color: #f2f2f2;
        color: #333;
        font-weight: bold;
    }
    
    .data-table tr:nth-child(even) {
        background-color: #f9f9f9;
    }
    
    .data-table tr:hover {
        background-color: #f1f1f1;
    }
    
    /* Responsive adjustments */
    @media (max-width: 768px) {
        .half-width {
            flex: 1 1 100%;
        }
        
        .stat-box {
            flex: 1 1 calc(50% - 0.5rem);
        }
    }
</style>

<!-- Include Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>
    // Parse JSON data from PHP
    const dailyData = <?php echo $dailyDataJSON; ?>;
    const categoryData = <?php echo $categoryDataJSON; ?>;
    const productData = <?php echo $productDataJSON; ?>;

    // Daily Transactions Chart
    const dailyTransactionsCtx = document.getElementById('dailyTransactionsChart').getContext('2d');
    new Chart(dailyTransactionsCtx, {
        type: 'bar',
        data: {
            labels: dailyData.dates,
            datasets: [{
                label: 'Number of Transactions',
                data: dailyData.transactions,
                backgroundColor: 'rgba(76, 175, 80, 0.7)',
                borderColor: 'rgba(46, 125, 50, 1)',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });

    // Daily Sales Chart
    const dailySalesCtx = document.getElementById('dailySalesChart').getContext('2d');
    new Chart(dailySalesCtx, {
        type: 'line',
        data: {
            labels: dailyData.dates,
            datasets: [{
                label: 'Total Sales (PHP)',
                data: dailyData.sales,
                backgroundColor: 'rgba(33, 150, 243, 0.2)',
                borderColor: 'rgba(33, 150, 243, 1)',
                borderWidth: 2,
                fill: true,
                tension: 0.1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Category Transactions Chart
    const categoryCtx = document.getElementById('categoryChart').getContext('2d');
    new Chart(categoryCtx, {
        type: 'pie',
        data: {
            labels: categoryData.categories,
            datasets: [{
                data: categoryData.sales,
                backgroundColor: [
                    'rgba(255, 99, 132, 0.7)',
                    'rgba(54, 162, 235, 0.7)',
                    'rgba(255, 206, 86, 0.7)',
                    'rgba(75, 192, 192, 0.7)',
                    'rgba(153, 102, 255, 0.7)',
                    'rgba(255, 159, 64, 0.7)'
                ],
                borderColor: [
                    'rgba(255, 99, 132, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(153, 102, 255, 1)',
                    'rgba(255, 159, 64, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'right'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            let label = context.label || '';
                            if (label) {
                                label += ': ';
                            }
                            label += new Intl.NumberFormat('en-PH', { 
                                style: 'currency', 
                                currency: 'PHP' 
                            }).format(context.raw);
                            return label;
                        }
                    }
                }
            }
        }
    });

    // Top Products Chart
    const productsCtx = document.getElementById('productsChart').getContext('2d');
    new Chart(productsCtx, {
        type: 'bar',
        data: {
            labels: productData.names,
            datasets: [{
                label: 'Sales (PHP)',
                data: productData.sales,
                backgroundColor: 'rgba(255, 87, 34, 0.7)',
                borderColor: 'rgba(230, 74, 25, 1)',
                borderWidth: 1,
                yAxisID: 'y'
            }, {
                label: 'Quantity Sold',
                data: productData.quantities,
                backgroundColor: 'rgba(121, 85, 72, 0.7)',
                borderColor: 'rgba(93, 64, 55, 1)',
                borderWidth: 1,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Sales (PHP)'
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    beginAtZero: true,
                    grid: {
                        drawOnChartArea: false
                    },
                    title: {
                        display: true,
                        text: 'Quantity'
                    },
                    ticks: {
                        precision: 0
                    }
                }
            }
        }
    });

    // Add event listeners to form controls to submit form when changed
    document.getElementById('month-select').addEventListener('change', function() {
        this.form.submit();
    });
    document.getElementById('year-select').addEventListener('change', function() {
        this.form.submit();
    });
</script>

<?php
require 'adminfooter.php';
?>