<?php
include 'header1.php';
include 'authentication.php';

// Fetch all taken items and their corresponding employee information
$sql = "
    SELECT ti.item_name, ti.quantity_taken, ti.take_date, u.Fname, u.Lname 
    FROM take_item ti
    JOIN users u ON ti.Emp_id = u.Emp_id
    ORDER BY ti.take_date DESC
";
$result = mysqli_query($conn, $sql);

// Handle database query errors
if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}
?>

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
        <section class="col-md-6">
            <h1 class="text-center">Taken Items</h1>
            <?php if (mysqli_num_rows($result) > 0): ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Employee Name</th>
                            <th>Item Name</th>
                            <th>Quantity Taken</th>
                            <th>Take Date</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['Fname']) . ' ' . htmlspecialchars($row['Lname']); ?></td>
                                <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['quantity_taken']); ?></td>
                                <td><?php echo htmlspecialchars($row['take_date']); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="alert alert-info text-center">No items taken yet.</div>
            <?php endif; ?>
        </section>

        <!-- Right Sidebar -->
        <aside class="col-md-3 sidebar-right">
            <div class="image mb-3">
                <img src="img/invntory2.jpg" alt="Inventory" class="img-fluid rounded">
            </div>
            <div class="image">
                <img src="img/logo1.jpg" alt="Logo" class="img-fluid rounded">
            </div>
        </aside>
    </div>
</main>

<?php
// Close the database connection
mysqli_close($conn);
include 'footer.php';
?>
