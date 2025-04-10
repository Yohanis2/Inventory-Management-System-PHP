<?php
// Include necessary files (e.g., database connection, authentication)
include 'header1.php';
include 'authentication.php';

// Improved database connection handling
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

// Query to fetch total items from the `item` table
$total_items_sql = "SELECT SUM(Quantity) AS total_items FROM item";
$total_items = 0; // Default value
if ($stmt = mysqli_prepare($conn, $total_items_sql)) {
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $total_items);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}

// Query to fetch total taken items from the `take_item` table
$total_taken_sql = "SELECT SUM(quantity_taken) AS total_taken FROM take_item";
$total_taken = 0; // Default value
if ($stmt = mysqli_prepare($conn, $total_taken_sql)) {
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $total_taken);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}

// Query to fetch total returned items from the `return_item` table
$total_returned_sql = "SELECT SUM(quantity_returned) AS total_returned FROM return_item";
$total_returned = 0; // Default value
if ($stmt = mysqli_prepare($conn, $total_returned_sql)) {
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $total_returned);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}

// Query to fetch total taken items (status = 'Taken') from report_item table
$total_taken_report_sql = "SELECT COUNT(*) AS total_taken_report FROM report WHERE status = 'Taken'";
$total_taken_report = 0; // Default value
if ($stmt = mysqli_prepare($conn, $total_taken_report_sql)) {
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $total_taken_report);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}

// Query to fetch total returned items (status = 'Returned') from report_item table
$total_returned_report_sql = "SELECT COUNT(*) AS total_returned_report FROM report WHERE status = 'Returned'";
$total_returned_report = 0; // Default value
if ($stmt = mysqli_prepare($conn, $total_returned_report_sql)) {
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $total_returned_report);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
}

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manager Dashboard</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome Icons -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css" rel="stylesheet">
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <!-- Custom CSS -->
    <style>
        body {
            background-color: #f8f9fa;
        }
        .dashboard-header {
            background-color: #343a40;
            color: #fff;
            padding: 20px;
            text-align: center;
        }
        .card {
            margin-bottom: 20px;
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        .card-body {
            padding: 20px;
            text-align: center;
        }
        .card-title {
            font-size: 1.25rem;
            font-weight: bold;
        }
        .card-text {
            font-size: 2rem;
            color: #007bff;
            font-weight: bold;
        }
        .btn-more-info {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 5px 15px;
            border-radius: 5px;
            text-decoration: none;
            display: inline-block;
            margin-top: 10px;
        }
        .btn-more-info:hover {
            background-color: #0056b3;
        }
        .progress-circle {
            width: 120px;
            height: 120px;
            margin: 0 auto;
        }
        .icon-container {
            color: #007bff;
            font-size: 3rem;
        }
    </style>
</head>
<body>
<main id="main-content" class="container-fluid mt-5 pt-3">
    <div class="row">
        <!-- Left Sidebar -->
        <aside class="col-md-3 sidebar-left">
            <h2><a href="manager.php" class="home text-decoration-none"><i class="fa fa-home"></i> Manager</a></h2>
            <nav class="d-flex flex-column">
                <a href="pending_manager.php" class="btn btn-link">Pending Requests <i class="fas fa-bell"></i></a>
                <a href="g_report.php" class="btn btn-link"><i class="fas fa-chart-bar"></i> General Report</a>
                <a href="v_item_report.php" class="btn btn-link"><i class="fas fa-box-open"></i> View Item Report</a>
                <a href="v_T_item_report.php" class="btn btn-link"><i class="fas fa-hand-holding"></i> View Taken Item Report</a>
                <a href="v_R_item_report.php" class="btn btn-link"><i class="fas fa-undo-alt"></i> View Return Item Report</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <section class="col-md-6">
            <div class="main">
                <h1>General Report</h1>
                <div class="row">
                    <!-- Summary Cards -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-cogs"></i> Current Total Items</h5>
                                <p class="card-text"><?php echo $total_items; ?></p>
                                <a href="v_item_report.php" class="btn-more-info">More Info</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-box"></i> Current Total Taken Items</h5>
                                <p class="card-text"><?php echo $total_taken; ?></p>
                                <a href="v_T_item_report.php" class="btn-more-info">More Info</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-undo"></i> Current Total Returned Items</h5>
                                <p class="card-text"><?php echo $total_returned; ?></p>
                                <a href="v_R_item_report.php" class="btn-more-info">More Info</a>
                            </div>
                        </div>
                    </div>
                    <!-- Report Item -->
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-clipboard-list"></i> Report Item</h5>
                                <p class="card-text">Total Taken: <?php echo $total_taken_report; ?></p>
                                <p class="card-text">Total Returned: <?php echo $total_returned_report; ?></p>
                                <a href="report_item.php" class="btn-more-info">More Info</a>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Graphs and Circular Progress -->
                <div class="row mt-4">
                    <!-- Bar Chart -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Item Distribution</h5>
                                <canvas id="itemChart"></canvas>
                            </div>
                        </div>
                    </div>

                    <!-- Pie Chart -->
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title">Item Status</h5>
                                <canvas id="statusChart"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Circular Progress Indicators -->
                <div class="row mt-4">
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-tachometer-alt"></i> Inventory Level</h5>
                                <div class="progress-circle">
                                    <canvas id="inventoryProgress"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-check-circle"></i> Order Fulfillment</h5>
                                <div class="progress-circle">
                                    <canvas id="orderProgress"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <h5 class="card-title"><i class="fas fa-box"></i> Stock Availability</h5>
                                <div class="progress-circle">
                                    <canvas id="stockProgress"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Right Sidebar -->
        <aside class="col-md-3 sidebar-right">
            <div class="image">
                <img src="img/invntory2.jpg" alt="Inventory" class="img-fluid">
                <img src="img/logo1.jpg" alt="Logo" class="img-fluid">
            </div>
        </aside>
    </div>
</main>

<!-- Bootstrap JS and dependencies -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
<!-- Chart.js Script -->
<script>
    // Bar Chart for Item Distribution
    const itemChart = new Chart(document.getElementById('itemChart'), {
        type: 'bar',
        data: {
            labels: ['Items', 'Taken Items', 'Returned Items'],
            datasets: [{
                label: 'Quantity',
                data: [<?php echo $total_items; ?>, <?php echo $total_taken; ?>, <?php echo $total_returned; ?>],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 99, 132, 0.2)',
                    'rgba(75, 192, 192, 0.2)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)',
                    'rgba(75, 192, 192, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Pie Chart for Item Status
    const statusChart = new Chart(document.getElementById('statusChart'), {
        type: 'pie',
        data: {
            labels: ['Available', 'Taken', 'Returned'],
            datasets: [{
                label: 'Item Status',
                data: [<?php echo $total_items - $total_taken; ?>, <?php echo $total_taken - $total_returned; ?>, <?php echo $total_returned; ?>],
                backgroundColor: [
                    'rgba(75, 192, 192, 0.2)',
                    'rgba(255, 206, 86, 0.2)',
                    'rgba(255, 99, 132, 0.2)'
                ],
                borderColor: [
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 206, 86, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1
            }]
        }
    });

    // Circular Progress (Placeholder)
    const inventoryProgress = new Chart(document.getElementById('inventoryProgress'), {
        type: 'doughnut',
        data: {
            labels: ['Available', 'Not Available'],
            datasets: [{
                label: 'Inventory Progress',
                data: [<?php echo $total_items - $total_taken; ?>, <?php echo $total_taken; ?>],
                backgroundColor: [
                    'rgba(54, 162, 235, 0.2)',
                    'rgba(255, 99, 132, 0.2)'
                ],
                borderColor: [
                    'rgba(54, 162, 235, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 1
            }]
        },
        options: {
            circumference: Math.PI,
            rotation: Math.PI,
            responsive: true,
            cutoutPercentage: 70
        }
    });
</script>
<?php include 'footer.php'; ?>
</body>
</html>
