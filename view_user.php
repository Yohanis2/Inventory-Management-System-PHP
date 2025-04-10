<?php
include 'header1.php';
include 'authentication.php';
// User is logged in
$username = $_SESSION['Emp_id'];

// Fetch user details if Emp_id is set
$user = null;
if (isset($_GET['Emp_id'])) {
    $emp_id = mysqli_real_escape_string($conn, $_GET['Emp_id']);
    
    // Retrieve user details from the database
    $sql = "SELECT * FROM users WHERE Emp_id = '$emp_id'";
    $result = mysqli_query($conn, $sql);
    if ($result) {
        $user = mysqli_fetch_assoc($result);
    }
}

mysqli_close($conn);


?>
<!DOCTYPE html>
<html>
    <head>
    <style>
        .custom-table {
            background-color: white; /* White background */
            color: black; /* Black text color */
        }
        .custom-table th {
            background-color: white; /* White header background */
            color: black; /* Black text for header */
        }
    </style>
    </head>
<body>





    <!-- Main Container -->
    <div class="container-fluid mt-3 pt-5">
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

            <main class="col-md-6" id="main-content">
                <h1 class="text-center">User Details</h1>
                <?php if ($user): ?>
                    <table class="table table-bordered custom-table">
                        <tbody>
                            <tr>
                                <th>Account Type</th>
                                <td><?php echo htmlspecialchars($user['Account_type']); ?></td>
                            </tr>
                            <tr>
                                <th>User ID</th>
                                <td><?php echo htmlspecialchars($user['Emp_id']); ?></td>
                            </tr>
                            <tr>
                                <th>First Name</th>
                                <td><?php echo htmlspecialchars($user['Fname']); ?></td>
                            </tr>
                            <tr>
                                <th>Last Name</th>
                                <td><?php echo htmlspecialchars($user['Lname']); ?></td>
                            </tr>
                            <tr>
                                <th>Email</th>
                                <td><?php echo htmlspecialchars($user['Email']); ?></td>
                            </tr>
                            <tr>
                                <th>Phone Number</th>
                                <td><?php echo htmlspecialchars($user['Phone_Number']); ?></td>
                            </tr>
                            <tr>
                                <th>Block Number</th>
                                <td><?php echo htmlspecialchars($user['Block_Number']); ?></td>
                            </tr>
                            <tr>
                                <th>Office Number</th>
                                <td><?php echo htmlspecialchars($user['Office_Number']); ?></td>
                            </tr>
                            <tr>
                                <th>Registration Date</th>
                                <td><?php echo htmlspecialchars($user['Reg_Deta']); ?></td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td><?php echo $user['Status'] == '1' ? 'Active' : 'Inactive'; ?></td>
                            </tr>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-danger">User not found.</p>
                <?php endif; ?>
            </main>

            <!-- Right Sidebar -->
            <aside class="col-md-3 sidebar-right">
                <div class="image">
                    <img src="img/invntory2.jpg" alt="Inventory Image" class="img-fluid">
                    <img src="img/logo1.jpg" alt="Logo Image" class="img-fluid">
                </div>
            </aside>
        </div>
    </div>
    </body>
    </html>
    <?php include 'footer.php' ?>