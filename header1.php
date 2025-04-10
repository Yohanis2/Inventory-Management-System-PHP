<?php
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

// Retrieve and sanitize Emp_id from session
$emp_id = isset($_SESSION['Emp_id']) ? trim($_SESSION['Emp_id']) : null;

if (!$emp_id) {
    die("Emp_id not found in session.");
}

// Prepare the SQL query to fetch the user's first and last name, email, phone, block, and office
$sql = "SELECT Fname, Lname, Email, Phone_Number, Block_Number, Office_Number, account_type FROM users WHERE Emp_id = ?";
$stmt = mysqli_prepare($conn, $sql);

if (!$stmt) {
    die("SQL Error: " . mysqli_error($conn));
}

// Bind parameters and execute the statement
mysqli_stmt_bind_param($stmt, 's', $emp_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (!$result) {
    die("Query execution failed: " . mysqli_error($conn));
}

$user = mysqli_fetch_assoc($result);

if (!$user) {
    die("User not found for Emp_id: " . htmlspecialchars($emp_id));
}

// Fetch unread messages count
$unread_count1 = 0;
$sql_unread1 = "SELECT COUNT(*) AS unread_count1 FROM messages WHERE receiver_id = ? AND `read` = 0";
$stmt_unread1 = mysqli_prepare($conn, $sql_unread1);

if ($stmt_unread1) {
    mysqli_stmt_bind_param($stmt_unread1, 's', $emp_id);
    mysqli_stmt_execute($stmt_unread1);
    $result_unread1 = mysqli_stmt_get_result($stmt_unread1);
    $row_unread1 = mysqli_fetch_assoc($result_unread1);
    $unread_count1 = $row_unread1['unread_count1'];
    mysqli_stmt_close($stmt_unread1);
}

// Assign dashboard link based on account type
$dashboard_link = '#'; // Default fallback

$dashboards = [
    'Admin' => 'admin.php',
    'Manager' => 'manager.php',
    'Staff' => 'staff.php',
    'Clerk' => 'clerk.php',
    'President' => 'president.php',
    'College Dean' => 'college_dean.php',
    'Department Head' => 'department_head.php'
];

// Use account_type from the database to determine the dashboard link
if (isset($user['account_type']) && array_key_exists($user['account_type'], $dashboards)) {
    $dashboard_link = $dashboards[$user['account_type']];
}

// Close the statement and connection
mysqli_stmt_close($stmt);
mysqli_close($conn);
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/font.css">
</head>
<body>
    <a href="#main-content" class="skip-link">Skip to main content</a>

    <header class="header fixed-top">
        <div class="container">
            <nav class="navbar">
                <div class="logo">
                    <img src="img/bongalogo.png" alt="Bonga University Logo">
                </div>
                <div class="notification-icons position-relative">
                    <a href="messages.php" class="message-icon position-relative" title="Messages">
                        <i class="fas fa-envelope fa-lg"></i>
                        <?php if ($unread_count1 > 0): ?>
                            <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                <?php echo $unread_count1; ?>
                                <span class="visually-hidden">unread messages</span>
                            </span>
                        <?php endif; ?>
                    </a>
                </div>&nbsp;&nbsp;&nbsp;&nbsp;
                <div class="dropdown">
                    <button class="dropdown-button" onclick="toggleDropdown()">
                        <img src="img/leader.jpg" alt="Profile" class="rounded-circle" width="50" height="50">                       
                        <span style="font-size: 24px;"><?php echo htmlspecialchars($user['Fname'] . ' ' . $user['Lname']); ?></span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <ul class="dropdown-menu" id="dropdownMenu">
                        <li><a class="dropdown-item" href="<?php echo $dashboard_link; ?>"><i class="fa-solid fa-gauge"></i> Dashboard</a></li>
                        <li>
                            <button class="dropdown-item" data-bs-toggle="modal" data-bs-target="#ProfileModal">
                                <i class="fa-solid fa-user"></i> Profile
                            </button>
                        </li>
                        <li><a class="dropdown-item" href="messages.php"><i class="fa-solid fa-envelope"></i> Messages</a></li>
                        <li><a class="dropdown-item" href="chenge_password.php"><i class="fa-solid fa-wrench"></i> Preferences</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="logout.php"><i class="fa-solid fa-sign-out-alt"></i> Log out</a></li>
                    </ul>
                </div>
            </nav>
        </div>
    </header>

<!-- Profile Modal -->
<div class="modal fade" id="ProfileModal" tabindex="-1" aria-labelledby="ProfileModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title" id="ProfileModalLabel">My Profile</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="text-center">
                    <img src="img/leader.jpg" alt="User Image" class="rounded-circle img-thumbnail" style="width: 150px; height: 150px;">
                    <h3 class="mt-2"><?php echo htmlspecialchars($user['Fname'] . " " . $user['Lname']); ?></h3>
                </div>

                <hr>

                <p><strong><i class="bi bi-person"></i> Employee ID:</strong> <?php echo htmlspecialchars($emp_id); ?></p>
                <p><strong><i class="bi bi-envelope"></i> Email:</strong> <?php echo htmlspecialchars($user['Email']); ?></p>
                <p><strong><i class="bi bi-phone"></i> Phone:</strong> <?php echo htmlspecialchars($user['Phone_Number']); ?></p>
                <p><strong><i class="bi bi-building"></i> Block Number:</strong> <?php echo htmlspecialchars($user['Block_Number']); ?></p>
                <p><strong><i class="bi bi-door-open"></i> Office Number:</strong> <?php echo htmlspecialchars($user['Office_Number']); ?></p>
            </div>
        </div>
    </div>
</div>

<script>
    function toggleDropdown() {
        document.getElementById('dropdownMenu').classList.toggle('show');
    }

    window.onclick = function(event) {
        if (!event.target.closest('.dropdown-button')) {
            document.getElementById('dropdownMenu').classList.remove('show');
        }
    };
</script>

</body>
</html>
