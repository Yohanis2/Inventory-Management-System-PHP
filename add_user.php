<?php include 'header1.php';
include 'authentication.php';

$error_message = ''; // Initialize error message variable
$success_message = ''; // Initialize success message variable

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['submit'])) {
    $rdate = $_POST['Reg_Deta'];
    $account_type = $_POST['Account_type'];
    $emp_id = $_POST['Emp_id'];
    $password = $_POST['Password'];

    // Define the password validation pattern
    $pattern = '/^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@#$%^&*()\-_=+!.,?;:<>])[A-Za-z\d@#$%^&*()\-_=+!.,?;:<>]{8,}$/';

    if (!preg_match($pattern, $password)) {
        // Set error message if validation fails
        $error_message = "Error: Password must be at least 8 characters long and include at least one uppercase letter, one lowercase letter, one number, and one special character.";
    } else {
        // Store the password as plain text
        $firstname = $_POST['Fname'];
        $lastname = $_POST['Lname'];
        $email = $_POST['Email'];
        $phonenumber = $_POST['Phone_Number'];
        $blocknumber = $_POST['Block_Number'];
        $officenumber = $_POST['Office_Number'];

        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO users (Account_type, Emp_id, Password, Fname, Lname, Email, Phone_Number, Block_Number, Office_Number, Reg_Deta, Status) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        
        if ($stmt) {
            $status = 1; // Default status
            $stmt->bind_param("ssssssssssi", $account_type, $emp_id, $password, $firstname, $lastname, $email, $phonenumber, $blocknumber, $officenumber, $rdate, $status);
            
            if ($stmt->execute()) {
                $success_message = "New User registered successfully!";
            } else {
                $error_message = "Error: User may already exist!";
            }
            $stmt->close();
        } else {
            // Handle error with statement preparation
            $error_message = "Error preparing statement: " . $conn->error;
        }
    }
}

$conn->close();
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

            <section class="col-md-6 ">
            <div class="main">
                <h1>All fields are required</h1>
                
                <?php if ($error_message): ?>
                    <div style="width: 100%; border: solid 2px red; padding: 10px; color: red;">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($success_message): ?>
                    <div style="width: 100%; border: solid 2px green; padding: 10px; color: green;">
                        <?php echo $success_message; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="add_user.php">
                    <div class="form-row">
                        <div class="form-group">
                            <label for="Account_type">Account Type</label>
                            <select name="Account_type" id="Account_type" required class="form-control">
                                <option value="">Select an account type</option>
                                <option value="Admin">Admin</option>
                                <option value="Manager">Manager</option>
                                <option value="Staff">Staff</option>
                                <option value="Clerk">Clerk</option>
                                <option value="Presidant">Presidant</option>
                                <option value="College Dean">College Dean</option>
                                <option value="Department Head">Department Head</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="Emp_id">Emp ID</label>
                            <input type="text" name="Emp_id" id="Emp_id" placeholder="Enter User ID" required class="form-control">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="Password">Password</label>
                            <input type="password" name="Password" id="Password" placeholder="Enter password" required class="form-control">
                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" id="showPassword" onclick="togglePassword()">
                                <label class="form-check-label" for="showPassword">Show Password</label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="Fname">First Name</label>
                            <input type="text" name="Fname" id="Fname" placeholder="Enter First Name" required class="form-control">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="Lname">Last Name</label>
                            <input type="text" name="Lname" id="Lname" placeholder="Enter Last Name" required class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label for="Email">Email</label>
                            <input type="email" name="Email" id="Email" placeholder="Enter Email" required class="form-control">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="Phone_Number">Phone Number</label>
                            <input type="text" name="Phone_Number" id="Phone_Number" placeholder="Enter Phone Number" required class="form-control">
                        </div>
                        
                        <div class="form-group">
                            <label for="Block_Number">Block Number</label>
                            <input type="text" name="Block_Number" id="Block_Number" placeholder="Enter Block Number" required class="form-control">
                        </div>
                    </div>

                    <div class="form-row">
                        <div class="form-group">
                            <label for="Office_Number">Office Number</label>
                            <input type="text" name="Office_Number" id="Office_Number" placeholder="Enter Office Number" required class="form-control">
                        </div>
                    </div>

                    <input type="hidden" name="Reg_Deta" value="<?php echo htmlspecialchars(date('Y-m-d')); ?>">
                    <div class="d-flex justify-content-start mb-3">
        <input type="submit" name="submit" value="Add User" class="btn btn-primary btn-sm me-3">
        <input type="reset" name="clear" value="Reset" class="btn btn-secondary btn-sm">
    </div>
                </form>
                </div>
                </section>

            <aside class="col-md-3 sidebar-right">
                <div class="image">
                    <img src="img/invntory2.jpg" alt="Placeholder Image" class="img-fluid">
                    <img src="img/logo1.jpg" alt="Placeholder Image" class="img-fluid">
                </div>
            </aside>
        </div>
    </main>
    <?php include 'footer.php' ?>
    <script>
        function togglePassword() {
            var passwordInput = document.getElementById("Password");
            if (passwordInput.type === "password") {
                passwordInput.type = "text";
            } else {
                passwordInput.type = "password";
            }
        }
    </script>