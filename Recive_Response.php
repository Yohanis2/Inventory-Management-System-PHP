<?php
include 'header1.php'; // Include header for navigation
include 'authentication.php'; // Include authentication to ensure user is logged in

// Fetch the current user's requests and their statuses from the database
$sql = "SELECT * FROM request WHERE Emp_id = ? ORDER BY req_date DESC";
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
            <h1>Receive Response</h1>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Quantity</th>
                        <th>Status</th>
                        <th>Request Date</th>
                        <th>Response</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($result) > 0) { ?>
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                            <tr>
                                <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                                <td>
                                    <?php
                                    // Fetch stock availability for the item
                                    $stock_sql = "SELECT Quantity FROM item WHERE Item_Name = ?";
                                    $stock_stmt = mysqli_prepare($conn, $stock_sql);
                                    mysqli_stmt_bind_param($stock_stmt, 's', $row['item_name']);
                                    mysqli_stmt_execute($stock_stmt);
                                    $stock_result = mysqli_stmt_get_result($stock_stmt);
                                    $stock_row = mysqli_fetch_assoc($stock_result);

                                    // Determine stock status
                                    $out_of_stock = $stock_row && $stock_row['Quantity'] <= 0;

                                    // Display request status
                                    switch ($row['status']) {
                                        case 'Pending':
                                            echo '<span class="badge bg-warning">Pending</span>';
                                            break;
                                        case 'Approved by Head':
                                            echo '<span class="badge bg-primary">Approved by Department Head</span>';
                                            break;
                                        case 'Rejected by Head':
                                            echo '<span class="badge bg-danger">Rejected by Department Head</span>';
                                            break;
                                        case 'Approved by Dean':
                                            echo '<span class="badge bg-primary">Approved by Dean</span>';
                                            break;
                                        case 'Rejected by Dean':
                                            echo '<span class="badge bg-danger">Rejected by Dean</span>';
                                            break;
                                        case 'Approved by President':
                                            echo '<span class="badge bg-primary">Approved by President</span>';
                                            break;
                                        case 'Rejected by President':
                                            echo '<span class="badge bg-danger">Rejected by President</span>';
                                            break;
                                        case 'Approved by Manager':
                                            if ($out_of_stock) {
                                                echo '<span class="badge bg-danger">Out of Stock</span>';
                                            } else {
                                                echo '<span class="badge bg-primary">Approved by Manager</span>';
                                            }
                                            break;
                                        case 'Completed':
                                            echo '<span class="badge bg-success">Completed</span>';
                                            break;
                                        default:
                                            echo '<span class="badge bg-secondary">Unknown Status</span>';
                                    }
                                    ?>
                                </td>
                                <td><?php echo htmlspecialchars($row['req_date']); ?></td>
                                <td>
                                    <?php
                                    // Determine the response button
                                    if ($row['status'] == 'Pending') {
                                        echo '<button class="btn btn-sm btn-warning">Awaiting Approval</button>';
                                    } elseif ($row['status'] == 'Completed') {
                                        echo '<a href="Taken_item_request.php" class="btn btn-sm btn-success">Item Taken</a>';
                                    } elseif ($out_of_stock) {
                                        echo '<button class="btn btn-sm btn-danger">Out of Stock</button>';
                                    } elseif (in_array($row['status'], ['Approved by Head', 'Approved by Dean', 'Approved by President', 'Approved by Manager'])) {
                                        echo '<button class="btn btn-sm btn-success">Approved</button>';
                                    } elseif (in_array($row['status'], ['Rejected by Head', 'Rejected by Dean', 'Rejected by President'])) {
                                        echo '<button class="btn btn-sm btn-danger">Rejected</button>';
                                    } else {
                                        echo '<button class="btn btn-sm btn-secondary">No Action</button>';
                                    }
                                    ?>
                                </td>

                            </tr>
                        <?php } ?>
                    <?php } else { ?>
                        <tr>
                            <td colspan="5" class="text-center">No requests found.</td>
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
