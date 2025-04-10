<?php
include 'header1.php';
include 'authentication.php';

// Initialize search query
$search_query = "";

// Check if a search request is made
if (isset($_GET['search'])) {
    $search_query = mysqli_real_escape_string($conn, $_GET['search']);
    $sql = "
        SELECT ti.item_name, ti.quantity_taken, ti.take_date, u.Fname, u.Lname 
        FROM take_item ti
        JOIN users u ON ti.Emp_id = u.Emp_id
        WHERE ti.item_name LIKE '%$search_query%'
        ORDER BY ti.take_date DESC
    ";
} else {
    $sql = "
        SELECT ti.item_name, ti.quantity_taken, ti.take_date, u.Fname, u.Lname 
        FROM take_item ti
        JOIN users u ON ti.Emp_id = u.Emp_id
        ORDER BY ti.take_date DESC
    ";
}

$result = mysqli_query($conn, $sql);

// Handle database query errors
if (!$result) {
    die("Database query failed: " . mysqli_error($conn));
}
?>

<!-- Include Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

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
            <h1 class="text-center">Taken Items</h1>

            <!-- Search Bar -->
            <form method="GET" action="take_item_view.php" class="mb-3">
                <div class="input-group">
                    <input type="text" name="search" class="form-control" 
                           placeholder="Search by Item Name" 
                           value="<?php echo htmlspecialchars($search_query); ?>">
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="bi bi-search"></i> Search
                    </button>
                </div>
            </form>

            <?php if (mysqli_num_rows($result) > 0): ?>
                <table class="table table-striped table-bordered">
                    <thead class="table-dark">
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
                <div class="alert alert-info text-center">No items found for "<strong><?php echo htmlspecialchars($search_query); ?></strong>".</div>
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
