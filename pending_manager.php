<?php
ob_start(); // Start output buffering
include 'header1.php'; // Include header for navigation
include 'authentication.php'; // Include authentication to ensure the user is logged in

// Initialize messages
$success_message = '';
$error_message = '';

// Fetch all pending requests from the database for approval by the Manager
$sql = "SELECT * FROM request WHERE status = 'Approved by President' ORDER BY req_date DESC";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Handle approval or rejection of the request
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $request_id = filter_input(INPUT_POST, 'request_id', FILTER_SANITIZE_NUMBER_INT);
    $action = filter_input(INPUT_POST, 'action', FILTER_SANITIZE_STRING);

    if ($request_id && ($action == 'approve' || $action == 'reject')) {
        $update_sql = "UPDATE request SET status = ? WHERE ID = ?";
        $update_stmt = mysqli_prepare($conn, $update_sql);
        
        // Determine new status based on action
        $new_status = ($action == 'approve') ? 'Approved by Manager' : 'Rejected by Manager';
        mysqli_stmt_bind_param($update_stmt, 'si', $new_status, $request_id);
        
        if (mysqli_stmt_execute($update_stmt)) {
            $success_message = "Request has been $new_status.";
        } else {
            $error_message = "Error updating request: " . mysqli_error($conn);
        }

        // Close statement and redirect to avoid resubmission
        mysqli_stmt_close($update_stmt);
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
            <h1>Pending Requests (Approved by President)</h1>
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
                                        <button type="submit" name="action" value="approve" class="btn btn-sm btn-success" aria-label="Approve Request">Approve</button>
                                        <button type="submit" name="action" value="reject" class="btn btn-sm btn-danger" aria-label="Reject Request">Reject</button>
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