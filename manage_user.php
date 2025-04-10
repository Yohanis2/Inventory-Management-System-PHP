<?php 
include 'header1.php';
include 'authentication.php';

// Default query
$sql = "SELECT * FROM users";

// Check if a search request is made
if (isset($_GET['search'])) {
    $search_query = mysqli_real_escape_string($conn, $_GET['search']);
    $sql = "SELECT * FROM users WHERE Emp_id LIKE '%$search_query%'";
} else {
    $search_query = ''; // Empty search query if not set
}

$result = mysqli_query($conn, $sql);
?>

<main id="main-content" class="container-fluid mt-5 pt-3">
    <div class="row">
        <!-- Left Sidebar -->
        <aside class="col-md-3 sidebar-left">
            <h2><a href="admin.php" class="home"><i class="fas fa-home"></i> Administrator</a></h2>
            <nav>
                <a href="add_user.php" class="btn btn-link">
                    <i class="fas fa-user-plus"></i> Add User
                </a>
                <a href="manage_user.php" class="btn btn-link">
                    <i class="fas fa-users-cog"></i> Manage Users
                </a>
                <a href="all_user.php" class="btn btn-link">
                    <i class="fas fa-users"></i> View Users
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <section class="col-md-6">
            <div class="main">
                <!-- Search Bar -->
                <form method="GET" action="manage_user.php" class="mb-3">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" 
                               placeholder="Search by Emp ID" 
                               value="<?php echo htmlspecialchars($search_query); ?>">
                        <button type="submit" class="btn btn-outline-primary">
                            <i class="bi-search"></i> Search
                        </button>
                    </div>
                </form>

                <!-- Users Table -->
                <div class="cont">
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Account Type</th>
                                <th>Employee ID</th>
                                <th>View Details <i class="fas fa-eye"></i></th>
                                <th>Edit <i class="fas fa-edit"></i></th>
                                <th>Activate/Deactivate <i class="fas fa-toggle-on"></i></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php while ($row = mysqli_fetch_array($result)) {
                                $emp_id = htmlspecialchars($row['Emp_id']);
                                $account_type = htmlspecialchars($row['Account_type']);
                                $status = $row['Status'];
                            ?>
                            <tr>
                                <td><?php echo $account_type; ?></td>
                                <td><?php echo $emp_id; ?></td>
                                <td>
                                    <a href="view_user.php?Emp_id=<?php echo $emp_id; ?>" class="btn btn-info" title="View">
                                        <i class="fas fa-eye"></i>View
                                    </a>
                                </td>
                                <td>
                                    <a href="edit_user.php?Emp_id=<?php echo $emp_id; ?>" class="btn btn-warning" title="Edit">
                                        <i class="fas fa-edit"></i>Edit
                                    </a>
                                </td>
                                <td>
                                    <?php if ($status == '1'): ?>
                                        <form action="deactivate_user.php" method="post" style="display:inline;">
                                            <input type="hidden" name="Emp_id" value="<?php echo $emp_id; ?>">
                                            <button type="submit" name="deactivate" class="btn btn-danger" title="Deactivate">
                                                <i class="fas fa-toggle-off"></i>
                                            </button>
                                        </form>
                                    <?php else: ?>
                                        <form action="activate_user.php" method="post" style="display:inline;">
                                            <input type="hidden" name="Emp_id" value="<?php echo $emp_id; ?>">
                                            <button type="submit" name="activate" class="btn btn-success" title="Activate">
                                                <i class="fas fa-toggle-on"></i>
                                            </button>
                                        </form>
                                    <?php endif; ?>
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
                <img src="img/invntory2.jpg" alt="Placeholder Image" class="img-fluid">
                <img src="img/logo1.jpg" alt="Placeholder Image" class="img-fluid">
            </div>
        </aside>
    </div>
</main>

<?php include 'footer.php'; ?>
