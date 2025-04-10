<?php
include 'header1.php';
include 'authentication.php';
// Initialize error and success message variables
$error_message = '';
$success_message = '';

// Check if the item ID is provided
if (!isset($_GET['Item_Code'])) {
    $_SESSION['message'] = "Invalid item ID!";
    header("Location: manage_item.php");
    exit();
}

$item_id = mysqli_real_escape_string($conn, $_GET['Item_Code']);

// Fetch existing item data from the database
$sql = "SELECT * FROM item WHERE Item_Code = '$item_id'";
$result = mysqli_query($conn, $sql);
$item = mysqli_fetch_assoc($result);

if (!$item) {
    $_SESSION['message'] = "Item not found!";
    header("Location: manage_item.php");
    exit();
}

// Check if the form is submitted
if (isset($_POST['submit'])) {
    // Get form data and escape to prevent SQL injection
    $item_name = mysqli_real_escape_string($conn, $_POST["Item_Name"]);
    $item_type = mysqli_real_escape_string($conn, $_POST["Item_Type"]);
    $item_quantity = (int)$_POST["Quantity"];
    $item_model = mysqli_real_escape_string($conn, $_POST["Item_Model"]);
    $item_serial = mysqli_real_escape_string($conn, $_POST["Item_Serial"]);
    $item_category = mysqli_real_escape_string($conn, $_POST["Item_Category"]);
    $status = mysqli_real_escape_string($conn, $_POST["Status"]);

    // Update the item in the database with prepared statement
    $stmt = $conn->prepare("UPDATE item SET Item_Name = ?, Item_Type = ?, Quantity = ?, Item_Model = ?, Item_Serial = ?, Status = ?, Item_Category = ? WHERE Item_Code = ?");
    $stmt->bind_param("ssisssss", $item_name, $item_type, $item_quantity, $item_model, $item_serial, $status, $item_category, $item_id);

    if ($stmt->execute()) {
        $success_message = "Item updated successfully!";
    } else {
        $error_message = "Error: " . $stmt->error;
    }
    $stmt->close();
}

mysqli_close($conn);
?>
<main id="main-content" class="container-fluid mt-5 pt-3">
        <div class="row">
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

            <section class="col-md-6 ">
            <div class="main">
                <h1>Update Item</h1>

                <?php if ($error_message): ?>
                    <div class="alert alert-danger" role="alert">
                        <?php echo $error_message; ?>
                    </div>
                <?php endif; ?>
                
                <?php if ($success_message): ?>
                    <div class="alert alert-success" role="alert">
                        <?php echo $success_message; ?>
                    </div>
                <?php endif; ?>

                <form method="POST" action="update_item.php?Item_Code=<?php echo $item_id; ?>">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Item Name:</label>
                            <input type="text" name="Item_Name" value="<?php echo htmlspecialchars($item['Item_Name']); ?>" class="form-control" required>
                        </div>
                        
                        <div class="col-md-6">
                            <label>Item Type:</label>
                            <input type="text" name="Item_Type" value="<?php echo htmlspecialchars($item['Item_Type']); ?>" class="form-control" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Quantity:</label>
                            <input type="number" name="Quantity" value="<?php echo (int)$item['Quantity']; ?>" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label>Item Model:</label>
                            <input type="text" name="Item_Model" value="<?php echo htmlspecialchars($item['Item_Model']); ?>" class="form-control" required>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label>Item Serial:</label>
                            <input type="text" name="Item_Serial" value="<?php echo htmlspecialchars($item['Item_Serial']); ?>" class="form-control" required>
                        </div>

                        <div class="col-md-6">
                            <label>Item Category:</label>
                            <input type="text" name="Item_Category" value="<?php echo htmlspecialchars($item['Item_Category']); ?>" class="form-control" required>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label>Status:</label>
                        <select name="Status" class="form-control" required>
                            <option value="Available" <?php echo $item['Status'] == 'Available' ? 'selected' : ''; ?>>Available</option>
                            <option value="Out of Stock" <?php echo $item['Status'] == 'Out of Stock' ? 'selected' : ''; ?>>Out of Stock</option>
                        </select>
                    </div>

                    <div class="d-flex justify-content-start mb-3">
                        <input type="submit" name="submit" value="Update Item" class="btn btn-primary btn-sm me-2">
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
                <!-- Right Sidebar -->
                <aside class="col-md-3 sidebar-right">
                <div class="image">
                     <img src="img/invntory2.jpg" alt="Placeholder Image" class="img-fluid">
                    <img src="img/logo1.jpg" alt="Placeholder Image" class="img-fluid"> <!-- Placeholder image -->
                </div>
            </aside>
        </div>
        </main>

  <?php include 'footer.php' ?>