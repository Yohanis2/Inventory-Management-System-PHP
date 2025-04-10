<?php
include 'header1.php'; // Include header for navigation
include 'authentication.php'; // Ensure user is logged in

// Fetch taken item details for the logged-in user
$sql = "
    SELECT ti.item_name, ti.quantity_taken, ti.take_date, u.Fname, u.Lname 
    FROM take_item ti
    JOIN users u ON ti.Emp_id = u.Emp_id
    WHERE ti.Emp_id = ?
    ORDER BY ti.take_date DESC
";

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 's', $_SESSION['Emp_id']);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
?>

<main id="main-content" class="container-fluid mt-5 pt-3">
    <div class="row">
        <!-- Left Sidebar -->
        <aside class="col-md-3 sidebar-left">
        <h2><a href="staff.php" class="home text-decoration-none"><i class="fa fa-home"></i>
                Staff</a></h2>
                <nav class="d-flex flex-column">
                <a href="Recive_Response.php" class="btn btn-link">Recive Response  <i class="fas fa-bell"></i></a>
                       <a href="Request_item.php" class="btn btn-link">
                            <i class="fas fa-box"></i> Request Item
                        </a>
                        <a href="Taken_item_request.php" class="btn btn-link">
                            <i class="fas fa-hand-paper"></i> Taken Request
                        </a>
                        <a href="Return_Request.php" class="btn btn-link">
                            <i class="fas fa-undo-alt"></i> Return Request
                        </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <section class="col-md-6">
            <h1>Taken Item Requests</h1>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Employee Name</th>
                        <th>Item Name</th>
                        <th>Quantity Taken</th>
                        <th>Take Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0) { ?>
                        <?php $count = 1; ?>
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td><?php echo $count++; ?></td>
                                <td><?php echo htmlspecialchars($row['Fname']) . ' ' . htmlspecialchars($row['Lname']); ?></td>
                                <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['quantity_taken']); ?></td>
                                <td><?php echo htmlspecialchars($row['take_date']); ?></td>
                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="5" class="text-center">No items have been taken yet.</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
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

<?php include 'footer.php'; ?>

<?php
// Close the database connection
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
