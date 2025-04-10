<?php
include 'header1.php';
include 'authentication.php';

// Initialize error and success message variables
$error_message = '';
$success_message = '';

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Get form data and escape to prevent SQL injection
    $item_name = mysqli_real_escape_string($conn, $_POST["Item_Name"]);
    $item_type = mysqli_real_escape_string($conn, $_POST["Item_Type"]);
    $item_id = mysqli_real_escape_string($conn, $_POST["Item_Code"]);
    $item_quantity = (int)$_POST["Quantity"];
    $item_model = mysqli_real_escape_string($conn, $_POST["Item_Model"]);
    $item_serial = mysqli_real_escape_string($conn, $_POST["Item_Serial"]);
    $item_category = mysqli_real_escape_string($conn, $_POST["Item_Category"]);
    $reg_date = date('Y-m-d'); // Use a standard date format
    $status = mysqli_real_escape_string($conn, $_POST["Status"]);

    // Insert the new item into the database
    $sql = "INSERT INTO item (Item_Name, Item_Type, Item_Code, Quantity, Item_Model, Item_Serial, Status, Item_Category, Reg_Date)
            VALUES ('$item_name', '$item_type', '$item_id', '$item_quantity', '$item_model', '$item_serial', '$status', '$item_category', '$reg_date')";
    $result = mysqli_query($conn, $sql);

    if ($result) {
        $success_message = "New item registered successfully!";
    } else {
        // Capture the error message if the query fails
        $error_message = "Error: " . mysqli_error($conn);
    }
}

$sql = "SELECT * FROM item";
$result = mysqli_query($conn, $sql);

mysqli_close($conn);
?>

<main id="main-content" class="container-fluid mt-5 pt-3">
    <div class="row">
        <!-- Left Sidebar -->
        <aside class="col-md-3 sidebar-left">
            <h2><a href="clerk.php" class="home text-decoration-none"><i class="fa fa-home"></i>
                Clerk</a></h2>
                <nav class="d-flex flex-column">
                <a href="pending_clerk.php" class="btn btn-link">Pending Requests   <i class="fas fa-bell"></i></a>
                    <a href="add_item.php" class="btn btn-link">
                            <i class="fas fa-plus-circle"></i> Add Item
                        </a>
                        <a href="manage_item.php" class="btn btn-link">
                            <i class="fas fa-tasks"></i> Manage Item
                        </a>
                        <a href="view_item.php" class="btn btn-link">
                            <i class="fas fa-eye"></i> View Item
                        </a>
                        <a href="take_item_view.php" class="btn btn-link">
                            <i class="fas fa-hand-holding"></i> Taken Items
                        </a>
                        <a href="returned_view.php" class="btn btn-link">
                            <i class="fas fa-undo-alt"></i> Returned Items
                        </a>
                        <a href="report_item.php" class="btn btn-link">
                            <i class="fas fa-file-alt"></i> Report Item
                        </a>

                </nav>
            </aside>

        <section class="col-md-6">
            <div class="main">
                <h1>Add Item</h1>
                <?php if ($error_message): ?>
                    <div class="alert alert-danger">
                        <?php echo htmlspecialchars($error_message); ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($success_message): ?>
                    <div class="alert alert-success">
                        <?php echo htmlspecialchars($success_message); ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="add_item.php">
                    <div class="form-group">
                        <label>Item Name:</label>
                        <input type="text" name="Item_Name" class="form-control" required>
                    </div>
                    
                    <div class="form-group">
                        <label>Item Type:</label>
                        <input type="text" name="Item_Type" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Item ID:</label>
                        <input type="text" name="Item_Code" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Quantity:</label>
                        <input type="number" name="Quantity" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Item Model:</label>
                        <input type="text" name="Item_Model" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Item Serial:</label>
                        <input type="text" name="Item_Serial" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Item Category:</label>
                        <input type="text" name="Item_Category" class="form-control" required>
                    </div>

                    <div class="form-group">
                        <label>Registration Date:</label>
                        <input type="date" name="Reg_Date" class="form-control" value="<?php echo date('Y-m-d'); ?>" required readonly>
                    </div>

                    <div class="form-group">
                        <label>Status:</label>
                        <select name="Status" class="form-control" required>
                            <option value="Available">Available</option>
                            <option value="Out of Stock">Out of Stock</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-start mb-3">
                        <input type="submit" name="submit" value="Add Item" class="btn btn-primary me-2">
                        <input type="reset" name="clear" value="Reset" class="btn btn-secondary">
                    </div>
                </form>

                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Item Name</th>
                            <th>Item Type</th>
                            <th>Item Code</th>
                            <th>Quantity</th>
                            <th>Item Model</th>
                            <th>Item Serial</th>
                            <th>Item Category</th>
                            <th>Registration Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = mysqli_fetch_assoc($result)) { ?>
                        <tr>
                            <td><?php echo htmlspecialchars($row["Item_Name"]); ?></td>
                            <td><?php echo htmlspecialchars($row["Item_Type"]); ?></td>
                            <td><?php echo htmlspecialchars($row["Item_Code"]); ?></td>
                            <td><?php echo htmlspecialchars($row["Quantity"]); ?></td>
                            <td><?php echo htmlspecialchars($row["Item_Model"]); ?></td>
                            <td><?php echo htmlspecialchars($row["Item_Serial"]); ?></td>
                            <td><?php echo htmlspecialchars($row["Item_Category"]); ?></td>
                            <td><?php echo htmlspecialchars($row["Reg_Date"]); ?></td>
                            <td><?php echo htmlspecialchars($row["Status"]); ?></td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </section>

        <!-- Right Sidebar -->
        <aside class="col-md-3 sidebar-right">
            <div class="image">
                <img src="img/invntory2.jpg" alt="Inventory Image" class="img-fluid">
                <img src="img/logo1.jpg" alt="Logo" class="img-fluid">
            </div>
        </aside>
    </div>
</main>

<?php include 'footer.php'; ?>