<?php
include 'header1.php';
include 'authentication.php';

// User is logged in
$username = $_SESSION['Emp_id'];

// Initialize search query
$search_query = "";

// Check if a search request is made
if (isset($_GET['search'])) {
    $search_query = mysqli_real_escape_string($conn, $_GET['search']);
    $sql = "SELECT * FROM item WHERE Item_Name LIKE '%$search_query%' OR Item_Code LIKE '%$search_query%'";
} else {
    $sql = "SELECT * FROM item";
}

$result = mysqli_query($conn, $sql);
?>

<!-- Include Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

<main id="main-content" class="container-fluid mt-5 pt-3">
        <div class="row">
            <!-- Left Sidebar -->
            <aside class="col-md-3 sidebar-left">
            <h2><a href="manager.php" class="home text-decoration-none"><i class="fa fa-home"></i> Manager</a></h2>
            <nav class="d-flex flex-column">
            <a href="pending_manager.php" class="btn btn-link">Pending Requests   <i class="fas fa-bell"></i></a>
                            <a href="g_report.php" class="btn btn-link">
                    <i class="fas fa-chart-bar"></i> General Report
                </a>
                <a href="v_item_report.php" class="btn btn-link">
                    <i class="fas fa-box-open"></i> View Item Report
                </a>
                <a href="v_T_item_report.php" class="btn btn-link">
                    <i class="fas fa-hand-holding"></i> View Taken Item Report
                </a>
                <a href="v_R_item_report.php" class="btn btn-link">
                    <i class="fas fa-undo-alt"></i> View Return Item Report
                </a>

            </nav>
        </aside>      

        <!-- Main Content -->
        <section class="col-md-9">
            <div class="main">
                <h1>View Items</h1>
          <!-- Print Button -->
          <button onclick="printReport()" class="btn btn-primary mb-3">Print Report</button>
                <!-- Search Bar -->
                <form method="GET" action="view_item.php" class="mb-3">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Search by Item Name or Item Code" 
                               value="<?php echo htmlspecialchars($search_query); ?>">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="bi bi-search"></i> Search
                        </button>
                    </div>
                </form>

                <!-- Items Table -->
                <table class="table table-hover table-bordered">
                    <thead class="table-dark">
                        <tr>
                            <th>Item Name</th>
                            <th>Item Type</th>
                            <th>Item Code</th>
                            <th>Quantity</th>
                            <th>Item Model</th>
                            <th>Item Serial</th>
                            <th>Item Category</th>
                            <th>Registration Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?php echo $row["Item_Name"]; ?></td>
                            <td><?php echo $row["Item_Type"]; ?></td>
                            <td><?php echo $row["Item_Code"]; ?></td>
                            <td><?php echo $row["Quantity"]; ?></td>
                            <td><?php echo $row["Item_Model"]; ?></td>
                            <td><?php echo $row["Item_Serial"]; ?></td>
                            <td><?php echo $row["Item_Category"]; ?></td>
                            <td><?php echo $row["Reg_Date"]; ?></td>
                            <td><?php echo $row["Status"]; ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</main>
<script>
    // Function to print the report
    function printReport() {
        window.print();
    }
</script>
<?php 
// Close database connection
mysqli_close($conn);
include 'footer.php'; 
?>
