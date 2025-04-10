<?php
include 'header1.php';
include 'authentication.php';

// Fetch all requests from the `request` table
$sql = "SELECT * FROM request ORDER BY req_date DESC";
$result = mysqli_query($conn, $sql);
?>

<main id="main-content" class="container-fluid mt-5 pt-3">
    <div class="row">
        <!-- Left Sidebar -->
        <aside class="col-md-3 sidebar-left">
            <h2><a href="clerk.php" class="home">Stock Clerk</a></h2>
            <nav>
                <a href="add_item.php" class="btn btn-link">Add Item</a>
                <a href="manage_item.php" class="btn btn-link">Manage Item</a>
                <a href="view_item.php" class="btn btn-link">View Item</a>
                <a href="clark_request_view.php" class="btn btn-link">Request View</a>
                <a href="take_item_view.php" class="btn btn-link">Taken Items</a>
                <a href="returned_view.php" class="btn btn-link">Returned Items</a>
                <a href="take_and_return_view.php" class="btn btn-link">Take and Return</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <section class="col-md-6">
            <div class="main">
                <h1>All Requests</h1>
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
                            <th>Status</th>
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
                                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">No requests found.</td>
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