<?php
// Set secure session parameters before starting the session
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'domain' => '', // Set to your domain if needed
    'secure' => true, // Set true if using HTTPS
    'httponly' => true,
    'samesite' => 'Strict'
]);

session_start();
include 'connection.php';

// Check if the user is logged in
if (!isset($_SESSION['Emp_id'])) {
    $_SESSION['message'] = "You Are Not Logged In! Please Login to access this page";
    header("Location: login.php");
    exit();
}

// User is logged in
$username = $_SESSION['Emp_id'];

// Handle form submission for user updates
if (isset($_POST['update_user_info'])) {
    // Retrieve form data
    $emp_id = $_POST['Emp_id'];
    $account_type = $_POST['Account_type'];
    $fname = $_POST['Fname'];
    $lname = $_POST['Lname'];
    $email = $_POST['Email'];
    $phone_number = $_POST['Phone_Number'];

    // Prepare the SQL statement to prevent SQL injection
    $stmt = $conn->prepare("UPDATE users SET Account_type = ?, Fname = ?, Lname = ?, Email = ?, Phone_Number = ? WHERE Emp_id = ?");
    
    // Bind parameters: "ssssis" corresponds to the types
    $stmt->bind_param("ssssis", $account_type, $fname, $lname, $email, $phone_number, $emp_id);

    // Execute the statement
    if ($stmt->execute()) {
        // If successful, show a success message and redirect
        echo "<script>alert('User updated successfully.'); window.location.href='manage_user.php';</script>";
    } else {
        // If there was an error, show an error message
        echo "<script>alert('Error updating user: " . htmlspecialchars($stmt->error) . "'); window.history.back();</script>";
    }

    // Close the prepared statement
    $stmt->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Bonga University - Official Website">
    <meta name="keywords" content="Bonga University, Education, Higher Learning">
    <title>Bonga University</title>
    
    <!-- CSS Links -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/styles.css"> <!-- Link to external CSS file -->
    <link rel="stylesheet" href="css/main.css">
</head>
<body>
    <!-- Skip Navigation for accessibility -->
    <a href="#main-content" class="skip-link">Skip to main content</a>

    <!-- Header -->
    <header class="header fixed-top">
        <div class="container">
            <nav class="navbar navbar-expand-lg">
                <div class="logo">
                    <img src="img/bongalogo.png" alt="Bonga University Logo">
                </div>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <div class="collapse navbar-collapse" id="navbarMain">
                    <ul class="navbar-nav mx-auto"></ul>
                    <div class="login-section">
                        <a href="logout.php" class="btn btn-primary">Logout</a>
                    </div>
                </div>
            </nav>
        </div>
    </header>

    <!-- Main Container -->
    <div class="container-fluid mt-3 pt-5">
        <div class="row">
            <!-- Left Sidebar -->
            <aside class="col-md-3 sidebar-left">
                <h2><a href="admin.php" class="home">Administrator</a></h2>
                <nav>
                    <a href="add_user.php" class="btn btn-link">Add User</a>
                    <a href="manage_user.php" class="btn btn-link">Manage Users</a>
                    <a href="all_user.php" class="btn btn-link">View Users</a>
                </nav>
            </aside>

            <!-- Main Content -->
            <main class="col-md-6 main-content" id="main-content">
                <h1>Edit User Information</h1>
                <form action="" method="POST">
                    <input type="hidden" name="Emp_id" value="<?php echo htmlspecialchars($emp_id); ?>">
                    <div class="mb-3">
                        <label for="Account_type" class="form-label">Account Type:</label>
                        <select name="Account_type" id="Account_type" class="form-select" required>
                            <option value="" disabled>Select Account Type</option>
                            <option value="Admin">Admin</option>
                            <option value="Manager">Manager</option>
                            <option value="Staff">Staff</option>
                            <option value="Clerk">Clerk</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="Fname" class="form-label">First Name:</label>
                        <input type="text" name="Fname" id="Fname" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="Lname" class="form-label">Last Name:</label>
                        <input type="text" name="Lname" id="Lname" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="Email" class="form-label">Email:</label>
                        <input type="email" name="Email" id="Email" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="Phone_Number" class="form-label">Phone Number:</label>
                        <input type="text" name="Phone_Number" id="Phone_Number" class="form-control" required pattern="\d{10}">
                        <small class="form-text text-muted">Please enter a 10-digit phone number.</small>
                    </div>
                    <button type="submit" name="update_user_info" class="btn btn-primary">Update User</button>
                </form>
            </main>

            <!-- Right Sidebar -->
            <aside class="col-md-3 sidebar-right">
                <div class="image">
                    <img src="img/invntory2.jpg" alt="Placeholder Image" class="img-fluid">
                    <img src="img/logo1.jpg" alt="Placeholder Image" class="img-fluid"> <!-- Placeholder image -->
                </div>
            </aside>
        </div>
    </div>

    <!-- Footer -->
    <footer class="footer text-center py-4">
        <p>&copy; 2024 Bonga University. All rights reserved.</p>
        <div class="social-media">
            <ul class="social-links">
                <li><a href="https://web.facebook.com/bongauofficial/?_rdc=1&_rdr#" target="_blank"><i class="fab fa-facebook-f"></i></a></li>
                <li><a href="https://twitter.com/bongauniversity?lang=en" target="_blank"><i class="fab fa-twitter"></i></a></li>
                <li><a href="https://www.youtube.com/@bongauniversity" target="_blank"><i class="fab fa-youtube"></i></a></li>
                <li><a href="https://t.me/Bonga_University_Official" target="_blank"><i class="fab fa-telegram"></i></a></li>
            </ul>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <script src="js/script.js"></script> <!-- Link to external JavaScript file -->
</body>
</html>