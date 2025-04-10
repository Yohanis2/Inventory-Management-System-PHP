<?php
include 'header1.php';
include 'authentication.php';

// Fetch all approved requests for the Clerk
$sql = "SELECT * FROM request WHERE status = 'Approved by Manager' ORDER BY req_date DESC";
$result = mysqli_query($conn, $sql);

// Handle request approval by Clerk
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_id = $_POST['request_id'];
    $item_name = $_POST['item_name'];
    $quantity_taken = $_POST['quantity'];
    $emp_id = $_POST['emp_id'];
    $taken_item_date = date("Y-m-d H:i:s"); // Capture the taken date

    // Begin transaction to ensure consistency
    mysqli_begin_transaction($conn);

    try {
        // Check if the requested quantity is available
        $check_quantity_sql = "SELECT Quantity FROM item WHERE Item_Name = ? AND Quantity >= ?";
        $check_quantity_stmt = mysqli_prepare($conn, $check_quantity_sql);
        
        if ($check_quantity_stmt === false) {
            throw new Exception("Failed to prepare the statement for checking quantity.");
        }
        
        mysqli_stmt_bind_param($check_quantity_stmt, 'si', $item_name, $quantity_taken);
        mysqli_stmt_execute($check_quantity_stmt);
        mysqli_stmt_store_result($check_quantity_stmt);
        
        // If the item quantity is less than the requested quantity
        if (mysqli_stmt_num_rows($check_quantity_stmt) === 0) {
            throw new Exception("Insufficient stock available for this item.");
        }

        // Deduct quantity from item table, ensuring quantity does not become negative
        $update_item_sql = "
            UPDATE item 
            SET Quantity = Quantity - ?, 
                Status = CASE WHEN Quantity - ? <= 0 THEN 'Out of Stock' ELSE 'Available' END
            WHERE Item_Name = ? AND Quantity >= ?
        ";
        $update_item_stmt = mysqli_prepare($conn, $update_item_sql);
        
        if ($update_item_stmt === false) {
            throw new Exception("Failed to prepare the statement for updating the item.");
        }
        
        mysqli_stmt_bind_param($update_item_stmt, 'iisi', $quantity_taken, $quantity_taken, $item_name, $quantity_taken);
        if (!mysqli_stmt_execute($update_item_stmt) || mysqli_stmt_affected_rows($update_item_stmt) === 0) {
            throw new Exception("Failed to update item quantity or item not found.");
        }

        // Insert into `report` table with taken_item_date
        $insert_report_sql = "INSERT INTO report (Emp_id, Item_name, quantity, take_item_date, status) VALUES (?, ?, ?, ?, 'Taken')";
        $insert_report_stmt = mysqli_prepare($conn, $insert_report_sql);
        
        if ($insert_report_stmt === false) {
            throw new Exception("Failed to prepare the statement for inserting into the report table.");
        }

        mysqli_stmt_bind_param($insert_report_stmt, 'ssis', $emp_id, $item_name, $quantity_taken, $taken_item_date);
        if (!mysqli_stmt_execute($insert_report_stmt)) {
            throw new Exception("Failed to insert into the report table.");
        }

        // Add record to `take_item` table
        $insert_take_item_sql = "INSERT INTO take_item (item_name, quantity_taken, Emp_id) VALUES (?, ?, ?)";
        $insert_take_item_stmt = mysqli_prepare($conn, $insert_take_item_sql);
        
        if ($insert_take_item_stmt === false) {
            throw new Exception("Failed to prepare the statement for inserting into the take_item table.");
        }

        mysqli_stmt_bind_param($insert_take_item_stmt, 'sis', $item_name, $quantity_taken, $emp_id);
        if (!mysqli_stmt_execute($insert_take_item_stmt)) {
            throw new Exception("Failed to insert into the take_item table.");
        }

        // Update request status to "Completed"
        $update_request_sql = "UPDATE request SET status = 'Completed' WHERE ID = ?";
        $update_request_stmt = mysqli_prepare($conn, $update_request_sql);
        
        if ($update_request_stmt === false) {
            throw new Exception("Failed to prepare the statement for updating the request status.");
        }

        mysqli_stmt_bind_param($update_request_stmt, 'i', $request_id);
        if (!mysqli_stmt_execute($update_request_stmt)) {
            throw new Exception("Failed to update request status.");
        }

        // Commit transaction
        mysqli_commit($conn);

        $success_message = "Request processed successfully!";
    } catch (Exception $e) {
        // Rollback transaction on failure
        mysqli_rollback($conn);
        $error_message = "Error processing request: " . $e->getMessage();
    }
}
?>

<main id="main-content" class="container-fluid mt-5 pt-3">
    <div class="row">
        <!-- Left Sidebar -->
        <aside class="col-md-3 sidebar-left">
            <h2><a href="clerk.php" class="home text-decoration-none"><i class="fa fa-home"></i>
                Clerk</a></h2>
                <nav class="d-flex flex-column">
                <a href="pending_clerk.php" class="btn btn-link">Pending Requests   <i class="fas fa-bell"></i></a>
                    <a href="add_item.php" class="btn btn-link">
                            <i class="fas fa-plus-circle"></i> Add Item
                        </a>
                        <a href="manage_item.php" class="btn btn-link">
                            <i class="fas fa-tasks"></i> Manage Item
                        </a>
                        <a href="view_item.php" class="btn btn-link">
                            <i class="fas fa-eye"></i> View Item
                        </a>
                        <a href="take_item_view.php" class="btn btn-link">
                            <i class="fas fa-hand-holding"></i> Taken Items
                        </a>
                        <a href="returned_view.php" class="btn btn-link">
                            <i class="fas fa-undo-alt"></i> Returned Items
                        </a>
                        <a href="report_item.php" class="btn btn-link">
                            <i class="fas fa-file-alt"></i> Report Item
                        </a>

                </nav>
            </aside>

        <!-- Main Content -->
        <section class="col-md-6">
            <div class="main">
                <h1>Approved Requests</h1>
                <?php if (isset($success_message)): ?>
                    <div class="alert alert-success"><?php echo $success_message; ?></div>
                <?php elseif (isset($error_message)): ?>
                    <div class="alert alert-danger"><?php echo $error_message; ?></div>
                <?php endif; ?>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>First Name</th>
                            <th>Last Name</th>
                            <th>Item Name</th>
                            <th>Quantity</th>
                            <th>Request Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['Fname']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Lname']); ?></td>
                                    <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                                    <td><?php echo htmlspecialchars($row['req_date']); ?></td>
                                    <td>
                                        <form method="POST" action="" class="d-inline">
                                            <input type="hidden" name="request_id" value="<?php echo $row['ID']; ?>">
                                            <input type="hidden" name="item_name" value="<?php echo $row['item_name']; ?>">
                                            <input type="hidden" name="quantity" value="<?php echo $row['quantity']; ?>">
                                            <input type="hidden" name="emp_id" value="<?php echo $row['Emp_id']; ?>">
                                            <button type="submit" class="btn btn-success btn-sm">Process</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">No approved requests available.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Right Sidebar -->
        <aside class="col-md-3 sidebar-right">
            <div class="image">
                <img src="img/invntory2.jpg" alt="Placeholder Image" class="img-fluid">
                <img src="img/logo1.jpg" alt="Placeholder Image" class="img-fluid">
            </div>
        </aside>
    </div>
</main>

<?php
// Close the database connection
mysqli_close($conn);
include 'footer.php';
?>
