<?php
session_start();
include 'connection.php';

$errors = [];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submitMain'])) {
    $userid = trim($_POST['txt_userid']);
    $password = trim($_POST['txt_password']);

    if (empty($userid)) {
        $errors[] = "Please enter a user ID.";
    }

    if (empty($password)) {
        $errors[] = "Please enter a password.";
    }

    if (empty($errors)) {
        // Use prepared statement to prevent SQL injection
        $query = "SELECT Emp_id, Password, Account_Type, Status FROM users WHERE Emp_id = ? AND Password = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $userid, $password);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result && $result->num_rows > 0) {
            $row = $result->fetch_assoc();
            $status = $row['Status'];
            $account_type = $row['Account_Type'];

            if ($status == 1) {
                $_SESSION['Emp_id'] = $row['Emp_id'];
                $_SESSION['Account_Type'] = $account_type; // Store role in session

                // Redirect based on account type
                $redirect_pages = [
                    "Staff" => "staff.php",
                    "Admin" => "admin.php",
                    "Manager" => "manager.php",
                    "Clerk" => "clerk.php",
                    "Presidant" => "president.php",
                    "College Dean" => "college_dean.php",
                    "Department Head" => "department_head.php"
                ];

                if (isset($redirect_pages[$account_type])) {
                    header("Location: " . $redirect_pages[$account_type]);
                    exit();
                } else {
                    $errors[] = "Invalid account type.";
                }
            } else {
                $errors[] = "Your account is deactivated. Please contact the administrator.";
            }
        } else {
            $errors[] = "Invalid User ID or Password.";
        }
        $stmt->close();
    }
}

$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="css/loginn.css">
</head>
<body>
<nav>
    <img class="astu-logo" src="css/assets/bonga.png" alt="Logo">
</nav>
<section class="container">
    <div>
        <img class="login-svg" src="css/assets/astu Login.svg" alt="Login Image">
    </div>
    <div>
        <h1 class="form-header">LOGIN</h1>
        <form class="login-form" action="<?php echo htmlspecialchars($_SERVER['PHP_SELF']);?>" method="post">
            <div class="error-message">
                <?php if (!empty($errors)) : ?>
                    <div class="error-messages">
                        <?php foreach ($errors as $error) : ?>
                            <p><?php echo $error; ?></p>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
            </div>
            <label for="username"><strong>User ID:</strong></label>
            <input class="email-input" name="txt_userid" id="username" type="text" required>

            <label for="password"><strong>Password:</strong></label>
            <input class="password-input" name="txt_password" id="password" type="password" required>

            <div>
                <input class="remember-me" id="rememberme" name="rememberme" type="checkbox">
                <label for="rememberme">Remember me</label>
            </div>

            <input class="login-submit" type="submit" value="Login" name="submitMain">
        </form>
    </div>
</section>
<script src="js/login.js"></script>
</body>
</html>
