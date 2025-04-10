<?php

include 'header1.php';
include 'authentication.php';

// Start session if not already active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Redirect if user is not authenticated
if (!isset($_SESSION['Emp_id'])) {
    header('Location: login.php');
    exit;
}

// Retrieve Emp_id from session
$emp_id = $_SESSION['Emp_id'];

// Query user data securely
$sql = "SELECT * FROM users WHERE Emp_id = ?";
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 's', $emp_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

// Fetch user data
$row = $result ? mysqli_fetch_assoc($result) : null;

// Close database resources
mysqli_stmt_close($stmt);
mysqli_close($conn);
?>
 <div class="modal fade" id="ProfiletModal">
<main id="main-content" class="container-fluid mt-5 pt-4">
    <div class="row">
        <section class="col-md-5 col-lg-4">
            <div class="card border-primary mb-4 shadow-lg">
                <div class="card-header text-center bg-warning text-white">
                    <img src="img/leader.jpg" alt="User Image" class="rounded-circle img-thumbnail" style="width: 150px; height: 150px;">
                    <h1 class="card-title"><?php echo htmlspecialchars($row["Fname"] . " " . $row["Lname"]); ?></h1>
                </div>
                
                <div class="card-body">
                    <?php if ($row): ?>
                        <p><strong><i class="bi bi-person"></i> Employee ID:</strong> <?php echo htmlspecialchars($row["Emp_id"]); ?></p>
                        <p><strong><i class="bi bi-envelope"></i> Email:</strong> <?php echo htmlspecialchars($row["Email"]); ?></p>
                        <p><strong><i class="bi bi-phone"></i> Phone:</strong> <?php echo htmlspecialchars($row["Phone_Number"]); ?></p>
                        <p><strong><i class="bi bi-building"></i> Block Number:</strong> <?php echo htmlspecialchars($row["Block_Number"]); ?></p>
                        <p><strong><i class="bi bi-door-open"></i> Office Number:</strong> <?php echo htmlspecialchars($row["Office_Number"]); ?></p>
                    <?php else: ?>
                        <p class="text-center text-danger">User details not available.</p>
                    <?php endif; ?>
                </div>
                
                <div class="card-footer text-center">
                    <a href="edit_profile.php" class="btn btn-primary"><i class="bi bi-pencil-square"></i> Edit Profile</a>
                </div>
            </div>
        </section>
    </div>
</main>
</div>
<?php include 'footer.php'; ?>
