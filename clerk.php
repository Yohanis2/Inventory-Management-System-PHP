
  <?php
  ob_start(); // Start output buffering
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include authentication and database connection
include 'authentication.php';

// Ensure the database connection is established
if (!$conn) {
    die("Database connection failed: " . mysqli_connect_error());
}

// Retrieve Emp_id from session
$emp_id = $_SESSION['Emp_id'] ?? null;

if (!$emp_id) {
    die("Emp_id not found in session.");
}

// Prepare the SQL query to fetch the user's first and last name
$sql = "SELECT Fname, Lname, account_type FROM users WHERE Emp_id = ?";
$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("SQL Error: " . mysqli_error($conn));
}

// Bind parameters and execute the statement
mysqli_stmt_bind_param($stmt, 's', $emp_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);
mysqli_stmt_close($stmt);

if (!$user) {
    die("User not found for Emp_id: " . htmlspecialchars($emp_id));
}



// Fetch count of pending requests
$count_sql = "SELECT COUNT(*) AS unread_count FROM request WHERE status = 'Approved by Manager'";
$count_stmt = mysqli_prepare($conn, $count_sql);
mysqli_stmt_execute($count_stmt);
$count_result = mysqli_stmt_get_result($count_stmt);
$count_row = mysqli_fetch_assoc($count_result);
$unread_count = $count_row['unread_count'] ?? 0;
mysqli_stmt_close($count_stmt);

// Close the database connection
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Department Head Dashboard">
    <title>Clerk Dashboard</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body>
    <?php include 'header1.php'; ?>

    <main id="main-content" class="container-fluid mt-5 pt-3">
        <div class="row">
        <aside class="col-md-3 sidebar-left">
            <h2><a href="clerk.php" class="home text-decoration-none"><i class="fa fa-home"></i>
                Clerk</a></h2>
                <nav class="d-flex flex-column">
                    <div class="dropdown">
                        <a href="pending_clerk.php" class="btn btn-link" title="Notifications" onclick="toggleDropdown('notificationMenu')">
                         Pending Requests <i class="fas fa-bell"></i>
                           <sup> <?php if ($unread_count > 0): ?>
                                <span class="badge bg-danger"><?php echo $unread_count; ?></span>
                            <?php endif; ?></sup>
                        </a>
                    </div>
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

            <section class="col-md-6">
            <div class="main">
    <h1><i class="fas fa-tachometer-alt"></i> Welcome to the Clerk Dashboard</h1>
    <p><i class="fas fa-user"></i> Full Name: <strong><?php echo htmlspecialchars($user['Fname'] . ' ' . $user['Lname']); ?></strong></p>
    <p><i class="fas fa-user-shield"></i> Account Type: <strong><?php echo htmlspecialchars($user['account_type']); ?></strong></p>
    <p><i class="fas fa-bell"></i> Pending Requests: <strong><?php echo $unread_count; ?></strong></p>
    <p><i class="fas fa-tasks"></i> This dashboard allows you to manage requests and oversee departmental activities.</p>
</div>


            </section>

            <aside class="col-md-3 sidebar-right">
               
                <div class="image">
                    <img src="img/logo1.jpg" alt="Logo" class="img-fluid rounded">
                </div>
            </aside>
        </div>
    </main>

    <?php include 'footer.php'; ?>

    <script>
        // Any additional JavaScript can be added here
    </script>
</body>
</html>