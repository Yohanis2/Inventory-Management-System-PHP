<?php
include 'header1.php';
include 'authentication.php';

// Check if Emp_id is provided in the URL
if (isset($_GET['Emp_id'])) {
    $emp_id = $_GET['Emp_id'];

    // Fetch user details from the database
    $stmt = $conn->prepare("SELECT * FROM users WHERE Emp_id = ?");
    $stmt->bind_param("s", $emp_id);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result && mysqli_num_rows($result) > 0) {
        $user = $result->fetch_assoc();
    } else {
        // User not found
        $error_message = "User not found.";
    }
    $stmt->close();
} else {
    // If Emp_id is not provided, redirect to manage users
    header("Location: manage_user.php");
    exit();
}

// Handle form submission for updating user details
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $account_type = $_POST['Account_type'];
    $firstname = $_POST['Fname'];
    $lastname = $_POST['Lname'];
    $email = $_POST['Email'];
    $phonenumber = $_POST['Phone_Number'];
    $blocknumber = $_POST['Block_Number'];
    $officenumber = $_POST['Office_Number'];
    $rdate = $_POST['Reg_Deta'];
    $password = $_POST['Password'] ?? ''; // Optional password

    // Flag to check if password should be updated
    $update_password = !empty($password);

    // Prepare the SQL statement
    if ($update_password) {
        // Update user information, including password (plain text for simplicity, but consider hashing)
        $stmt = $conn->prepare("UPDATE users SET Account_type = ?, Fname = ?, Lname = ?, Email = ?, Phone_Number = ?, Block_Number = ?, Office_Number = ?, Reg_Deta = ?, Password = ? WHERE Emp_id = ?");
        $stmt->bind_param("ssssssssss", $account_type, $firstname, $lastname, $email, $phonenumber, $blocknumber, $officenumber, $rdate, $password, $emp_id);
    } else {
        // Update user information without changing the password
        $stmt = $conn->prepare("UPDATE users SET Account_type = ?, Fname = ?, Lname = ?, Email = ?, Phone_Number = ?, Block_Number = ?, Office_Number = ?, Reg_Deta = ? WHERE Emp_id = ?");
        $stmt->bind_param("sssssssss", $account_type, $firstname, $lastname, $email, $phonenumber, $blocknumber, $officenumber, $rdate, $emp_id);
    }

    if ($stmt->execute()) {
        $success_message = "User updated successfully!";
    } else {
        $error_message = "Error updating user: " . $stmt->error;
    }

    $stmt->close();
}

$conn->close();
?>

<main id="main-content" class="container-fluid mt-5 pt-3">
    <div class="row">
        <!-- Left Sidebar -->
        <aside class="col-md-3 sidebar-left">
            <h2><a href="admin.php" class="home"><i class="fas fa-home"></i> Administrator</a></h2>
            <nav>
                <a href="add_user.php" class="btn btn-link"><i class="fas fa-user-plus"></i> Add User</a>
                <a href="manage_user.php" class="btn btn-link"><i class="fas fa-users-cog"></i> Manage Users</a>
                <a href="all_user.php" class="btn btn-link"><i class="fas fa-users"></i> View Users</a>
            </nav>
        </aside>

        <!-- Edit User Form -->
        <section class="col-md-6">
            <h1>Edit User Information</h1>

            <!-- Success or Error Message -->
            <?php if (isset($success_message)): ?>
                <div class="alert alert-success"><?php echo htmlspecialchars($success_message); ?></div>
            <?php endif; ?>
            <?php if (isset($error_message)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error_message); ?></div>
            <?php endif; ?>

            <!-- User Update Form -->
            <form action="edit_user.php?Emp_id=<?php echo htmlspecialchars($emp_id); ?>" method="POST">
                <div class="mb-3">
                    <label for="Account_type" class="form-label">Account Type:</label>
                    <select name="Account_type" id="Account_type" class="form-select" required>
                        <option value="" disabled>Select Account Type</option>
                        <option value="Admin" <?php echo ($user['Account_type'] == 'Admin') ? 'selected' : ''; ?>>Admin</option>
                        <option value="Manager" <?php echo ($user['Account_type'] == 'Manager') ? 'selected' : ''; ?>>Manager</option>
                        <option value="Staff" <?php echo ($user['Account_type'] == 'Staff') ? 'selected' : ''; ?>>Staff</option>
                        <option value="Clerk" <?php echo ($user['Account_type'] == 'Clerk') ? 'selected' : ''; ?>>Clerk</option>
                        <option value="President" <?php echo ($user['Account_type'] == 'President') ? 'selected' : ''; ?>>President</option>
                        <option value="College Dean" <?php echo ($user['Account_type'] == 'College Dean') ? 'selected' : ''; ?>>College Dean</option>
                        <option value="Department Head" <?php echo ($user['Account_type'] == 'Department Head') ? 'selected' : ''; ?>>Department Head</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="Fname" class="form-label">First Name:</label>
                    <input type="text" name="Fname" id="Fname" value="<?php echo htmlspecialchars($user['Fname']); ?>" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="Lname" class="form-label">Last Name:</label>
                    <input type="text" name="Lname" id="Lname" value="<?php echo htmlspecialchars($user['Lname']); ?>" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="Password" class="form-label">Password:</label>
                    <input type="password" name="Password" id="Password" class="form-control">
                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" id="showPassword" onclick="togglePassword()">
                        <label class="form-check-label" for="showPassword">Show Password</label>
                    </div>
                </div>
                <div class="mb-3">
                    <label for="Email" class="form-label">Email:</label>
                    <input type="email" name="Email" id="Email" value="<?php echo htmlspecialchars($user['Email']); ?>" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="Phone_Number" class="form-label">Phone Number:</label>
                    <input type="text" name="Phone_Number" id="Phone_Number" value="<?php echo htmlspecialchars($user['Phone_Number']); ?>" class="form-control" required pattern="\d{10}">
                    <small class="form-text text-muted">Please enter a 10-digit phone number.</small>
                </div>
                <div class="mb-3">
                    <label for="Block_Number" class="form-label">Block Number:</label>
                    <input type="text" name="Block_Number" id="Block_Number" value="<?php echo htmlspecialchars($user['Block_Number']); ?>" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="Office_Number" class="form-label">Office Number:</label>
                    <input type="text" name="Office_Number" id="Office_Number" value="<?php echo htmlspecialchars($user['Office_Number']); ?>" class="form-control" required>
                </div>
                <div class="mb-3">
                    <label for="Reg_Deta" class="form-label">Reg Date:</label>
                    <input type="date" name="Reg_Deta" id="Reg_Deta" value="<?php echo htmlspecialchars($user['Reg_Deta']); ?>" class="form-control" required>
                </div>
                <button type="submit" class="btn btn-primary">Update User</button>
            </form>
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

<script>
function togglePassword() {
    var passwordField = document.getElementById("Password");
    var checkBox = document.getElementById("showPassword");
    if (checkBox.checked) {
        passwordField.type = "text";
    } else {
        passwordField.type = "password";
    }
}
</script>
