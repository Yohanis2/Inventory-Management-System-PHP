<?php
include 'header1.php';
include 'authentication.php';

// Fetch total active users (status 1) and deactive users (status 0)
$sql_active = "SELECT COUNT(*) AS total_active FROM users WHERE Status = 1";
$sql_deactive = "SELECT COUNT(*) AS total_deactive FROM users WHERE Status = 0";

// Execute the queries
$result_active = mysqli_query($conn, $sql_active);
$result_deactive = mysqli_query($conn, $sql_deactive);

// Fetch the counts
$total_active = mysqli_fetch_assoc($result_active)['total_active'];
$total_deactive = mysqli_fetch_assoc($result_deactive)['total_deactive'];

// Fetch user details for the current session (if needed)
$user_query = "SELECT * FROM users WHERE Emp_id = ?";
$stmt = $conn->prepare($user_query);
$stmt->bind_param("s", $emp_id);
$stmt->execute();
$user_result = $stmt->get_result();
$user = $user_result->fetch_assoc();

// Close the database connection
mysqli_close($conn);
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
                <h1><i class="fas fa-tachometer-alt"></i> Welcome to the Administrator Dashboard</h1>
                <p><i class="fas fa-user"></i> Full Name: <strong><?php echo htmlspecialchars($user['Fname'] . ' ' . $user['Lname']); ?></strong></p>
                <p><i class="fas fa-user-shield"></i> Account Type: <strong><?php echo htmlspecialchars($user['Account_type']); ?></strong></p>
                <p><i class="fas fa-tasks"></i>This page serves as the admin dashboard where you can manage users, add users, and handle all administrative tasks.</p>
            </div>
      

        <!-- Stats Cards -->
        <div class="col-md-5">
            <div class="card text-white bg-success mb-4">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-box"></i> Current Total Active Users</h5>
                    <p class="card-text"><?php echo $total_active; ?></p>
                    <a href="manage_user.php" class="btn btn-light">More Info</a>
                </div>
            </div>
       
        
            <div class="card text-white bg-danger mb-4">
                <div class="card-body">
                    <h5 class="card-title"><i class="fas fa-cogs"></i> Current Total Deactive Users</h5>
                    <p class="card-text"><?php echo $total_deactive; ?></p>
                    <a href="manage_user.php" class="btn btn-light">More Info</a>
                </div>
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
