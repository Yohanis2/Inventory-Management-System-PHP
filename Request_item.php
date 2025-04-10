<?php
include 'header1.php';
include 'authentication.php';

// Handle request form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $Fname = $_POST['Fname'];
    $Lname = $_POST['Lname'];
    $item_name = $_POST['item_name'];
    $quantity = $_POST['quantity'];
    $req_date = $_POST['req_date'];

    // Check if the user already has a pending request for the same item in the 'request' table
    $check_sql_request = "SELECT * FROM request WHERE Emp_id = ? AND item_name = ? AND status = 'Pending'";
    $check_stmt_request = mysqli_prepare($conn, $check_sql_request);
    mysqli_stmt_bind_param($check_stmt_request, 'ss', $_SESSION['Emp_id'], $item_name);
    mysqli_stmt_execute($check_stmt_request);
    $check_result_request = mysqli_stmt_get_result($check_stmt_request);

    // Check if the user already has the item in the 'take_item' table
    $check_sql_taken_item = "SELECT * FROM take_item WHERE Emp_id = ? AND item_name = ?";
    $check_stmt_taken_item = mysqli_prepare($conn, $check_sql_taken_item);
    mysqli_stmt_bind_param($check_stmt_taken_item, 'ss', $_SESSION['Emp_id'], $item_name);
    mysqli_stmt_execute($check_stmt_taken_item);
    $check_result_taken_item = mysqli_stmt_get_result($check_stmt_taken_item);

    // Check if a pending request or a taken item exists
    if (mysqli_num_rows($check_result_request) > 0) {
        // If a pending request for the item exists, show an alert
        $error_message = "You already have a pending request for this item.";
    } elseif (mysqli_num_rows($check_result_taken_item) > 0) {
        // If the item is already taken by the user, show an alert
        $error_message = "You have already taken this item. Please return it before requesting again.";
    } else {
        // Prepare and execute the SQL statement to insert the new request
        $sql = "INSERT INTO request (Emp_id, Fname, Lname, item_name, quantity, req_date, status, notified) VALUES (?, ?, ?, ?, ?, ?, 'Pending', 0)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'ssssis', $_SESSION['Emp_id'], $Fname, $Lname, $item_name, $quantity, $req_date);

        if (mysqli_stmt_execute($stmt)) {
            $success_message = "Request submitted successfully.";
        } else {
            $error_message = "Failed to submit request: " . mysqli_error($conn);
        }

        mysqli_stmt_close($stmt);
    }

    mysqli_stmt_close($check_stmt_request);
    mysqli_stmt_close($check_stmt_taken_item);
}

// Fetch items for the dropdown
$item_sql = "SELECT item_name FROM item";
$item_result = mysqli_query($conn, $item_sql);

// Fetch notifications
$notification_sql = "SELECT item_name, status FROM request WHERE Emp_id=? AND notified=0";
$notification_stmt = mysqli_prepare($conn, $notification_sql);
mysqli_stmt_bind_param($notification_stmt, 's', $_SESSION['Emp_id']);
mysqli_stmt_execute($notification_stmt);
$result = mysqli_stmt_get_result($notification_stmt);

$notifications = [];
if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {
        $notifications[] = [
            'item_name' => $row['item_name'],
            'status' => $row['status'],
        ];
    }
}
mysqli_stmt_close($notification_stmt);

// Close connection
mysqli_close($conn);
?>
<main id="main-content" class="container-fluid mt-5 pt-3">
    <div class="row">
        <!-- Left Sidebar -->
        <aside class="col-md-3 sidebar-left">
            <h2><a href="staff.php" class="home text-decoration-none"><i class="fa fa-home"></i> Staff</a></h2>
            <nav class="d-flex flex-column">
                <a href="Recive_Response.php" class="btn btn-link">Receive Response <i class="fas fa-bell"></i></a>
                <a href="Request_item.php" class="btn btn-link"><i class="fas fa-box"></i> Request Item</a>
                <a href="Taken_item_request.php" class="btn btn-link"><i class="fas fa-hand-paper"></i> Taken Request</a>
                <a href="Return_Request.php" class="btn btn-link"><i class="fas fa-undo-alt"></i> Return Request</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <section class="col-md-6">
            <div class="card">
                <div class="card-header text-center">
                    <h1>Request Item</h1>
                </div>
                <div class="card-body">
                    <?php if (isset($success_message)): ?>
                        <div class="alert alert-success"><?php echo $success_message; ?></div>
                    <?php elseif (isset($error_message)): ?>
                        <div class="alert alert-danger"><?php echo $error_message; ?></div>
                    <?php endif; ?>

                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="Fname" class="form-label">First Name</label>
                            <input type="text" class="form-control" id="Fname" name="Fname" required>
                        </div>
                        <div class="mb-3">
                            <label for="Lname" class="form-label">Last Name</label>
                            <input type="text" class="form-control" id="Lname" name="Lname" required>
                        </div>
                        <div class="mb-3">
                            <label for="item_name" class="form-label">Item Name</label>
                            <select class="form-control" id="item_name" name="item_name" required>
                                <option value="">Select an item</option>
                                <?php while ($row = mysqli_fetch_assoc($item_result)): ?>
                                    <option value="<?php echo htmlspecialchars($row['item_name']); ?>">
                                        <?php echo htmlspecialchars($row['item_name']); ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="quantity" class="form-label">Quantity</label>
                            <input type="number" class="form-control" id="quantity" name="quantity" required min="1">
                        </div>
                        <div class="mb-3">
                            <label for="req_date" class="form-label">Request Date</label>
                            <input type="text" class="form-control" id="req_date" name="req_date" value="<?php echo date('Y-m-d'); ?>" readonly>
                        </div>
                        <button type="submit" class="btn btn-primary">Submit Request</button>
                    </form>
                </div>
            </div>
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
