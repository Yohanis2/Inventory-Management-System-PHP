<?php
session_start();
include 'connection.php';

$errors = [];

if (isset($_POST['submitMain'])) {
  $account_type = $_POST['acc_type'];
  $userid = $_POST['txt_userid'];
  $password = $_POST['txt_password'];

  if (empty($account_type)) {
    $errors[] = "Please select an account type.";
  }

  if (empty($userid)) {
    $errors[] = "Please enter a user ID.";
  }

  if (empty($password)) {
    $errors[] = "Please enter a password.";
  }

  if (empty($errors)) {
    // Check if the account type exists
    $query = "SELECT * FROM users WHERE Account_Type = '$account_type'";
    $result = mysqli_query($conn, $query);

    if ($result && mysqli_num_rows($result) > 0) {
      // Check if the user ID exists for the given account type
      $query = "SELECT * FROM users WHERE Account_Type = '$account_type' AND Emp_id = '$userid'";
      $result = mysqli_query($conn, $query);

      if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $stored_password = $row['Password'];
        $Status = $row['Status'];

        // Check if the password is correct
        if ($password == $stored_password) {
          if ($Status == 1) {
            $_SESSION['Emp_id'] = $row['Emp_id'];

            switch ($account_type) {
              case 'Staff':
                header('Location: staff.php');
                exit();
              case 'Admin':
                header('Location: admin.php');
                exit();
              case 'Manager':
                header('Location: manager.php');
                exit();
              case 'Clerk':
                header('Location: clerk.php');
                exit();
              default:
                $errors[] = "Invalid account type.";
            }
          } else {
            $errors[] = "Your account is deactivated. Please contact the administrator.";
          }
        } else {
          $errors[] = "Incorrect password.";
        }
      } else {
        $errors[] = "User ID is incorrect.";
      }
    } else {
      $errors[] = "Account type is incorrect.";
    }
  }

  mysqli_close($conn);
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
    <link rel="stylesheet" href="css/loginn.css">

</head>
<body>
    <!-- Skip Navigation for accessibility -->
    <a href="#main-content" class="skip-link">Skip to main content</a>

    <!-- Header -->
    <header class="header fixed-top">
        <div class="container">
            <nav class="navbar navbar-expand-lg">
                <div class="logo">
                        <img src="img/bongalogo.png" alt="Bonga University Logo" >
                </div>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarMain">
                    <i class="fa-solid fa-bars"></i>
                </button>

                <div class="collapse navbar-collapse" id="navbarMain">
                    <ul class="navbar-nav mx-auto">
                        <li class="nav-item"><a href="Home.php" class="nav-link">Home</a></li>
                        <li class="nav-item"><a href="about.php" class="nav-link">About Us</a></li>
                        <li class="nav-item"><a href="services.php" class="nav-link">Services</a></li>
                        <li class="nav-item"><a href="contact.php" class="nav-link">Contact Us</a></li>
                    </ul>
                    <div class="login-section">
                        
                    </div>
                </div>
            </nav>
        </div>
    </header>
    <!-- Main Container -->
    <div class="container-fluid mt-5 pt-5">
    <section class="container">
    <div>
        <img class="login-svg" src="css/assets/astu Login.svg" alt="Login Image">
    </div>
    <div class="login">
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
            <label class="username"><strong>Account Type:</strong></label>
            <select name="acc_type" class="email-input" required>
                <option value="" selected disabled>Select an account type</option>
                <option value="Admin">Admin</option>
                <option value="Manager">Manager</option>
                <option value="Staff">Staff</option>
                <option value="Clerk">Clerk</option>
            </select>
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
    </div>
    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>
    <script src="js/script.js"></script> <!-- Link to external JavaScript file -->
    <script src="js/login.js"></script>
</body>
</html>