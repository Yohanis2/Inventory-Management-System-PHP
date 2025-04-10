 <?php
include 'header1.php';
include 'authentication.php';

// Enable detailed error reporting for debugging
mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);

// Process the return item when the "Process" button is clicked
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['process_return'])) {
    $return_id = $_POST['return_id'];

    // Get return item details
    $return_item_sql = "
        SELECT ri.item_name, ri.quantity_returned, ri.Emp_id 
        FROM return_item ri
        WHERE ri.id = ?
    ";
    $stmt = mysqli_prepare($conn, $return_item_sql);
    mysqli_stmt_bind_param($stmt, 'i', $return_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $return_item = mysqli_fetch_assoc($result);

    if ($return_item) {
        $item_name = $return_item['item_name'];
        $quantity_returned = $return_item['quantity_returned'];
        $Emp_id = $return_item['Emp_id'];

        // Begin transaction to ensure atomicity
        mysqli_begin_transaction($conn);

        try {
            // 1. Update the `item` table (add quantity back)
            $update_item_sql = "UPDATE item SET Quantity = Quantity + ? WHERE Item_Name = ?";
            $stmt1 = mysqli_prepare($conn, $update_item_sql);
            mysqli_stmt_bind_param($stmt1, 'is', $quantity_returned, $item_name);
            mysqli_stmt_execute($stmt1);

            // 2. Delete the corresponding item from the `take_item` table
            $delete_take_sql = "DELETE FROM take_item WHERE item_name = ? AND Emp_id = ?";
            $stmt2 = mysqli_prepare($conn, $delete_take_sql);
            mysqli_stmt_bind_param($stmt2, 'ss', $item_name, $Emp_id);
            mysqli_stmt_execute($stmt2);

            // 3. Delete the processed item from the `return_item` table
            $delete_return_sql = "DELETE FROM return_item WHERE id = ?";
            $stmt3 = mysqli_prepare($conn, $delete_return_sql);
            mysqli_stmt_bind_param($stmt3, 'i', $return_id);
            mysqli_stmt_execute($stmt3);

            // 4. Insert return details into the `report` table
            $insert_report_sql = "
                INSERT INTO report (Emp_id, Item_name, quantity, take_item_date, return_item_date, status) 
                VALUES (?, ?, ?, 
                    (SELECT take_date FROM take_item WHERE item_name = ? AND Emp_id = ? LIMIT 1), 
                    NOW(), 'Returned')
            ";
            $stmt4 = mysqli_prepare($conn, $insert_report_sql);
            mysqli_stmt_bind_param($stmt4, 'ssiss', $Emp_id, $item_name, $quantity_returned, $item_name, $Emp_id);
            mysqli_stmt_execute($stmt4);

            // Commit the transaction
            mysqli_commit($conn);
            $success_message = "Return item processed successfully!";
        } catch (Exception $e) {
            // Rollback transaction on error
            mysqli_rollback($conn);
            $error_message = "Error processing return item: " . $e->getMessage();
        }

        // Close statements
        mysqli_stmt_close($stmt1);
        mysqli_stmt_close($stmt2);
        mysqli_stmt_close($stmt3);
        mysqli_stmt_close($stmt4);
    } else {
        $error_message = "Invalid return item ID.";
    }

    mysqli_stmt_close($stmt);
}

// Fetch all returned items for display
$returned_items_sql = "
    SELECT ri.id, ri.item_name, ri.quantity_returned, ri.return_date, u.Fname, u.Lname
    FROM return_item ri
    JOIN users u ON ri.Emp_id = u.Emp_id
    ORDER BY ri.return_date DESC
";
$returned_items_result = mysqli_query($conn, $returned_items_sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Returned Items</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
</head>
<body>
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

        <section class="col-md-6">
            <h1 class="text-center">Returned Items</h1>
            <?php if (isset($success_message)): ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php elseif (isset($error_message)): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Employee Name</th>
                        <th>Item Name</th>
                        <th>Quantity Returned</th>
                        <th>Return Date</th>
                        
                    </tr>
                </thead>
                <tbody>
                    <?php if (mysqli_num_rows($returned_items_result) > 0): ?>
                        <?php $count = 1; ?>
                        <?php while ($row = mysqli_fetch_assoc($returned_items_result)): ?>
                            <tr>
                                <td><?php echo $count++; ?></td>
                                <td><?php echo htmlspecialchars($row['Fname']) . ' ' . htmlspecialchars($row['Lname']); ?></td>
                                <td><?php echo htmlspecialchars($row['item_name']); ?></td>
                                <td><?php echo htmlspecialchars($row['quantity_returned']); ?></td>
                                <td><?php echo htmlspecialchars($row['return_date']); ?></td>
                             
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No returned items found.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </section>
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
mysqli_close($conn);
include 'footer.php';
?>
</body>
</html>
