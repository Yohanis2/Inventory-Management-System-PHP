<?php
// Start session if not already started
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Include necessary files
include 'header1.php'; // Include your header file
include 'authentication.php'; // Include your authentication file

// Initialize error and success messages
$errors = []; // To hold error messages
$success = ""; // To hold success messages

// Ensure the user is logged in
if (!isset($_SESSION['Emp_id'])) {
    header("Location: login.php");
    exit();
}

$emp_id = $_SESSION['Emp_id']; // Get Emp_id from the session

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Check if current password and new password are provided
    if (isset($_POST['current_password'], $_POST['new_password'], $_POST['confirm_password'])) {
        $current_password = trim($_POST['current_password']);
        $new_password = trim($_POST['new_password']);
        $confirm_password = trim($_POST['confirm_password']);

        // Validate the new password and confirm password
        if ($new_password !== $confirm_password) {
            $errors[] = "New password and confirm password do not match.";
        } else {
            // Verify the current password
            $stmt = $conn->prepare("SELECT Password FROM users WHERE Emp_id = ?");
            if ($stmt) {
                $stmt->bind_param("s", $emp_id);
                $stmt->execute();
                $result = $stmt->get_result();

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    $stored_password = $row['Password'];

                    // Check if the current password matches
                    if ($current_password === $stored_password) {
                        // Update the user's password in the database
                        $update_stmt = $conn->prepare("UPDATE users SET Password = ? WHERE Emp_id = ?");
                        if ($update_stmt) {
                            $update_stmt->bind_param("ss", $new_password, $emp_id);
                            if ($update_stmt->execute()) {
                                $success = "Your password has been updated successfully.";
                            } else {
                                $errors[] = "Failed to update password. Please try again.";
                            }
                        } else {
                            $errors[] = "Database error: " . $conn->error;
                        }
                    } else {
                        $errors[] = "Current password is incorrect.";
                    }
                } else {
                    $errors[] = "User not found.";
                }
            } else {
                $errors[] = "Database error: " . $conn->error;
            }
        }
    }
}
?>

<main id="main-content" class="container-fluid mt-5 pt-3">
    <div class="row">
        <!-- Left Sidebar -->
        <aside class="col-md-3 sidebar-left">
            <h2><a href="chenge_password.php" class="home text-decoration-none">Change Password</a></h2>
            <nav class="d-flex flex-column"></nav>
        </aside>

        <!-- Main Content -->
        <section class="col-md-6">
            <div class="card border-primary">
                <div class="card-header text-center">
                    <h1 class="mb-0">Change Password</h1>
                </div>
                <div class="card-body">
                    <!-- Display Errors -->
                    <?php if (!empty($errors)): ?>
                        <div class="alert alert-danger">
                            <?php foreach ($errors as $error): ?>
                                <p><?php echo htmlspecialchars($error); ?></p>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>

                    <!-- Display Success -->
                    <?php if ($success): ?>
                        <div class="alert alert-success">
                            <p><?php echo htmlspecialchars($success); ?></p>
                        </div>
                    <?php endif; ?>

                    <!-- Change Password Form -->
                    <form action="" method="post" class="p-4 border rounded">
                        <div class="mb-3">
                            <label for="current_password" class="form-label">Current Password</label>
                            <input type="password" class="form-control" name="current_password" id="current_password" required>
                        </div>

                        <div class="mb-3">
                            <label for="new_password" class="form-label">New Password</label>
                            <input type="password" class="form-control" name="new_password" id="new_password" required>
                            <small class="form-text text-muted">
                                Password must be at least 8 characters long, and include an uppercase letter, a number, and a special character.
                            </small>
                        </div>

                        <div class="mb-3">
                            <label for="confirm_password" class="form-label">Confirm New Password</label>
                            <input type="password" class="form-control" name="confirm_password" id="confirm_password" required>
                        </div>

                        <button type="button" class="btn btn-secondary mb-3" onclick="togglePasswordVisibility()">Show Passwords</button>
                        <button type="submit" class="btn btn-primary w-100">Change Password</button>
                    </form>
                </div>
            </div>
        </section>

        <!-- Right Sidebar -->
        <aside class="col-md-3 sidebar-right">
            <div class="image mb-3">
                <img src="img/inventory2.jpg" alt="Inventory" class="img-fluid rounded">
            </div>
            <div class="image">
                <img src="img/logo1.jpg" alt="Logo" class="img-fluid rounded">
            </div>
        </aside>
    </div>
</main>

<!-- Bootstrap JS and Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

<!-- Show Password Toggle Script -->
<script>
    let passwordsVisible = false;

    function togglePasswordVisibility() {
        const passwordInputs = [
            document.getElementById("current_password"),
            document.getElementById("new_password"),
            document.getElementById("confirm_password")
        ];
        passwordsVisible = !passwordsVisible;

        passwordInputs.forEach(input => {
            input.type = passwordsVisible ? "text" : "password";
        });
    }
</script>

<?php include 'footer.php'; ?>