<?php 
include 'header1.php';  // Include header for navigation
include 'authentication.php';  // Ensure the user is logged in

// Handle the form submission
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Get form data
    $item_name = $_POST['item_name'];
    $quantity_returned = intval($_POST['quantity']);
    $Emp_id = $_SESSION['Emp_id'];  // Assuming Emp_id is stored in the session

    // Check if the item exists in the 'take_item' table and fetch the quantity taken for the logged-in user
    $sql_check = "SELECT quantity_taken FROM take_item WHERE item_name = ? AND Emp_id = ?";
    $stmt_check = mysqli_prepare($conn, $sql_check);
    mysqli_stmt_bind_param($stmt_check, 'si', $item_name, $Emp_id);
    mysqli_stmt_execute($stmt_check);
    mysqli_stmt_bind_result($stmt_check, $quantity_taken);
    mysqli_stmt_fetch($stmt_check);
    mysqli_stmt_close($stmt_check);

    // Check if the item exists in the taken_item table and if the quantity is valid
    if ($quantity_taken === null) {
        $error_message = "You have not taken this item, or it doesn't exist in the records.";
    } elseif ($quantity_returned > $quantity_taken) {
        $error_message = "You cannot return more items than you have taken.";
    } elseif ($quantity_returned <= 0) {
        $error_message = "Please enter a valid quantity greater than zero.";
    } else {
        // Get the current date for the return
        $return_date = date('Y-m-d');

        // Insert the return item into the return_item table, with the user's Emp_id
        $sql = "INSERT INTO return_item (Emp_id, item_name, quantity_returned, return_date) VALUES (?, ?, ?, ?)";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 'ssis', $Emp_id, $item_name, $quantity_returned, $return_date);

        // Execute the query and check for success
        if (mysqli_stmt_execute($stmt)) {
            $success_message = "Return request submitted successfully!";
        } else {
            $error_message = "Failed to submit the return request.";
        }

        // Close the statement
        mysqli_stmt_close($stmt);
    }
}

// Fetch available item names taken by the logged-in user
$sql_items = "SELECT item_name,Emp_id FROM take_item WHERE Emp_id = ? GROUP BY item_name";  // Ensure only items taken by the user are fetched
$stmt_items = mysqli_prepare($conn, $sql_items);
mysqli_stmt_bind_param($stmt_items, 'i', $_SESSION['Emp_id']);
mysqli_stmt_execute($stmt_items);
$result_items = mysqli_stmt_get_result($stmt_items);
mysqli_stmt_close($stmt_items);
?>

<main id="main-content" class="container-fluid mt-5 pt-3">
    <div class="row">
        <!-- Left Sidebar -->
        <aside class="col-md-3 sidebar-left">
            <h2><a href="staff.php" class="home text-decoration-none"><i class="fa fa-home"></i> Staff</a></h2>
            <nav class="d-flex flex-column">
                <a href="Recive_Response.php" class="btn btn-link">Receive Response <i class="fas fa-bell"></i></a>
                <a href="Request_item.php" class="btn btn-link"><i class="fas fa-box"></i> Request Item</a>
                <a href="Taken_item_request.php" class="btn btn-link"><i class="fas fa-hand-paper"></i> Taken Request</a>
                <a href="Return_Request.php" class="btn btn-link"><i class="fas fa-undo-alt"></i> Return Request</a>
            </nav>
        </aside>

        <!-- Main Content -->
        <section class="col-md-6">
            <h1>Return Request</h1>

            <!-- Display success or error message -->
            <?php if (isset($success_message)): ?>
                <div class="alert alert-success"><?php echo $success_message; ?></div>
            <?php elseif (isset($error_message)): ?>
                <div class="alert alert-danger"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <!-- Return request form -->
            <form method="POST">
                <div class="form-group">
                    <label for="item_name">Item Name</label>
                    <select name="item_name" id="item_name" class="form-control" required>
                        <option value="">Select Item</option>
                        <?php while ($row = mysqli_fetch_assoc($result_items)): ?>
                            <option value="<?php echo $row['item_name']; ?>"><?php echo $row['item_name']; ?></option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label for="quantity">Quantity</label>
                    <input type="number" name="quantity" id="quantity" class="form-control" required min="1" 
                    <?php if (isset($quantity_taken)) { echo 'max="' . $quantity_taken . '"'; } ?>>
                </div>

                <button type="submit" class="btn btn-primary mt-3">Submit Return Request</button>
            </form>
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

<?php include 'footer.php'; ?>

<?php
// Close the database connection
mysqli_close($conn);
?>
