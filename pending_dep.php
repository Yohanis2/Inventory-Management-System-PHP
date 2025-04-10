<?php
ob_start(); // Start output buffering
include 'header1.php';
include 'authentication.php';

// Ensure the database connection is established
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Fetch all pending requests
$sql = "SELECT * FROM request WHERE status = 'Pending' ORDER BY req_date DESC";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

$success_message = '';
$error_message = '';

// Handle approval or rejection
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_id = filter_input(INPUT_POST, 'request_id', FILTER_SANITIZE_NUMBER_INT);
    $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);

    if ($request_id && ($action == 'approve' || $action == 'reject')) {
        $status = ($action == 'approve') ? 'Approved by Head' : 'Rejected by Head';
        $update_sql = "UPDATE request SET status = ?, notified = 1 WHERE ID = ?";
        $update_stmt = mysqli_prepare($conn, $update_sql);
        mysqli_stmt_bind_param($update_stmt, 'si', $status, $request_id);

        if (mysqli_stmt_execute($update_stmt)) {
            $success_message = ($action == 'approve') ? "Request approved successfully." : "Request rejected successfully.";
        } else {
            $error_message = "Error updating request: " . mysqli_error($conn);
        }
        mysqli_stmt_close($update_stmt);

        // Redirect to prevent form resubmission
        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    } else {
        $error_message = "Invalid request.";
    }
}
?>


<main id="main-content" class="container-fluid mt-5 pt-3">
    <div class="row">
        <!-- Left Sidebar -->
        <aside class="col-md-3 sidebar-left">
            <h2><a href="department_head.php" class="home text-decoration-none"><i class="fa fa-home"></i>
            Department Head</a></h2>
            <nav class="d-flex flex-column">
                <a href="pending_dep.php" class="btn btn-link">Pending Requests   <i class="fas fa-bell"></i></a>
                <!-- Add other navigation links as needed -->
            </nav>
        </aside>

        <!-- Main Content -->
        <section class="col-md-6">
            <h1>Pending Requests</h1>
            <?php if ($success_message): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
            <?php elseif ($error_message): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
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
                                        <input type="hidden" name="request_id" value="<?php echo htmlspecialchars($row['ID']); ?>">
                                        <button type="submit" name="action" value="approve" class="btn btn-sm btn-success">Approve</button>
                                        <button type="submit" name="action" value="reject" class="btn btn-sm btn-danger">Reject</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="6" class="text-center">No pending requests found.</td>
                        </tr>
                    <?php endif; ?>
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
// Close database connection
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>