<?php
include 'header1.php';
include 'authentication.php';

// Initialize search query
$search_query = "";

// Check if a search request is made
if (isset($_GET['search'])) {
    $search_query = mysqli_real_escape_string($conn, $_GET['search']);
    $sql = "SELECT * FROM item WHERE Item_Name LIKE '%$search_query%' OR Item_Code LIKE '%$search_query%'";
} else {
    $sql = "SELECT * FROM item";
}

$result = mysqli_query($conn, $sql);
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
            <div class="main">
                <div class="cont">
                    <h1>Manage Items</h1>

                    <!-- Search Bar -->
                    <form method="GET" action="manage_item.php" class="mb-3">
                        <div class="input-group">
                            <input type="text" name="search" class="form-control" 
                                   placeholder="Search by Item Name or Item Code" 
                                   value="<?php echo htmlspecialchars($search_query); ?>">
                            <button type="submit" class="btn btn-outline-primary">
                                <i class="bi bi-search"></i> Search
                            </button>
                        </div>
                    </form>

                    <table class="table table-striped table-bordered">
                        <thead class="table-dark">
                            <tr>
                                <th>Item Name</th>
                                <th>Item Code</th>
                                <th>Status</th>
                                <th>View Details</th>
                                <th>Edit</th>
                                <th>Delete</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_array($result)) {
                                 $item_name = htmlspecialchars($row['Item_Name']);
                                 $item_code = htmlspecialchars($row['Item_Code']);
                                 $status = htmlspecialchars($row['Status']);
                            ?>
                                <tr>
                                    <td><?php echo $item_name; ?></td>
                                    <td><?php echo $item_code; ?></td>
                                    <td><?php echo $status; ?></td>
                                    <td>
                                        <a href="view_item_detail.php?Item_Code=<?php echo $item_code; ?>">
                                            <img src="img/v.jpg" alt="View Details" style="width: 40px; height: 40px;" />
                                        </a>
                                    </td>
                                    <td>
                                        <a href="update_item.php?Item_Code=<?php echo $item_code; ?>" class="btn btn-warning" title="Edit">Edit</a>
                                    </td>
                                    <td>
                                        <a href="delete_item.php?Item_Code=<?php echo $item_code; ?>" class="btn btn-danger" title="Delete">Delete</a>
                                    </td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>

        <!-- Right Sidebar -->
        <aside class="col-md-3 sidebar-right">
            <div class="image">
                <img src="img/invntory2.jpg" alt="Inventory Image" class="img-fluid">
                <img src="img/logo1.jpg" alt="Logo" class="img-fluid">
            </div>
        </aside>
    </div>
</main>

<?php include 'footer.php'; ?>
