<?php 
include 'header1.php';
include 'authentication.php';

// Initialize search variables
$search_query = "";

// Check if a search request is made
if (isset($_GET['search'])) {
    $search_query = mysqli_real_escape_string($conn, $_GET['search']);
    $sql = "SELECT * FROM users WHERE Fname LIKE '%$search_query%' OR Emp_id LIKE '%$search_query%'";
} else {
    $sql = "SELECT * FROM users";
}

$result = mysqli_query($conn, $sql);
?>

<!-- Include Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

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
        <section class="col-md-9">
    <div class="main">
        <h1>View Users</h1>

        <!-- Add User Button -->
        <a href="add_user.php" class="btn btn-success mb-3">
            <i class="fas fa-user-plus"></i> Add User
        </a>

        <!-- Search Bar -->
        <form method="GET" action="all_user.php" class="mb-3">
            <div class="input-group">
                <input type="text" name="search" class="form-control" 
                       placeholder="Search by First Name or Emp ID" 
                       value="<?php echo htmlspecialchars($search_query); ?>">
                <button type="submit" class="btn btn-outline-primary">
                    <i class="bi-search"></i> Search
                </button>
            </div>
        </form>

        <!-- Users Table -->
        <table class="table table-hover table-bordered">
            <thead class="table-dark">
                <tr>
                    <th>Account Type</th>
                    <th>Emp ID</th>
                    <th>Password</th>
                    <th>First Name</th>
                    <th>Last Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Block No.</th>
                    <th>Office No.</th>
                    <th>Reg Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                <tr>
                    <td><?php echo htmlspecialchars($row["Account_type"]); ?></td>
                    <td><?php echo htmlspecialchars($row["Emp_id"]); ?></td>
                    <td>******</td> <!-- Hiding the password for security -->
                    <td><?php echo htmlspecialchars($row["Fname"]); ?></td>
                    <td><?php echo htmlspecialchars($row["Lname"]); ?></td>
                    <td><?php echo htmlspecialchars($row["Email"]); ?></td>
                    <td><?php echo htmlspecialchars($row["Phone_Number"]); ?></td>
                    <td><?php echo htmlspecialchars($row["Block_Number"]); ?></td>
                    <td><?php echo htmlspecialchars($row["Office_Number"]); ?></td>
                    <td><?php echo htmlspecialchars($row["Reg_Deta"]); ?></td>
                    <td><?php echo ($row["Status"] == 1) ? 'Active' : 'Inactive'; ?></td>
                </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
</section>
    </div>
    </main>

<?php 
// Close database connection
mysqli_close($conn);
include 'footer.php'; 
?>
