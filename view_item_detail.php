<?php
include 'header1.php';
include 'authentication.php';

// Initialize the item variable
$item = null;

// Check if the Item_Code is provided in the URL
if (isset($_GET['Item_Code'])) {
    $item_code = mysqli_real_escape_string($conn, $_GET['Item_Code']);
    
    // Retrieve item details from the database
    $sql = "SELECT * FROM item WHERE Item_Code = '$item_code'";
    $result = mysqli_query($conn, $sql);
    
    if ($result) {
        $item = mysqli_fetch_assoc($result);
    }
}

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

            <section class="col-md-6 ">
            <div class="main">
                <h1 class="text-center" style="margin-top: 50px;" >Item Details</h1>
                <?php if ($item): ?>
                    <table class="table table-bordered custom-table">
                        <tbody>
                            <tr>
                                <th>Item Name</th>
                                <td><?php echo htmlspecialchars($item['Item_Name']); ?></td>
                            </tr>
                            <tr>
                                <th>Item Type</th>
                                <td><?php echo htmlspecialchars($item['Item_Type']); ?></td>
                            </tr>
                            <tr>
                                <th>Item Code</th>
                                <td><?php echo htmlspecialchars($item['Item_Code']); ?></td>
                            </tr>
                            <tr>
                                <th>Quantity</th>
                                <td><?php echo htmlspecialchars($item['Quantity']); ?></td>
                            </tr>
                            <tr>
                                <th>Item Model</th>
                                <td><?php echo htmlspecialchars($item['Item_Model']); ?></td>
                            </tr>
                            <tr>
                                <th>Item Serial</th>
                                <td><?php echo htmlspecialchars($item['Item_Serial']); ?></td>
                            </tr>
                            <tr>
                                <th>Item Category</th>
                                <td><?php echo htmlspecialchars($item['Item_Category']); ?></td>
                            </tr>
                            <tr>
                                <th>Registration Date</th>
                                <td><?php echo htmlspecialchars($item['Reg_Date']); ?></td>
                            </tr>
                            <tr>
                                <th>Status</th>
                                <td><?php echo htmlspecialchars($item['Status']); ?></td>
                            </tr>
                        </tbody>
                    </table>
                <?php else: ?>
                    <p class="text-danger">Item not found.</p>
                <?php endif; ?>
                </div>
                </section>

            <!-- Right Sidebar -->
            <aside class="col-md-3 sidebar-right">
                <div class="image">
                     <img src="img/invntory2.jpg" alt="Placeholder Image" class="img-fluid">
                    <img src="img/logo1.jpg" alt="Placeholder Image" class="img-fluid"> <!-- Placeholder image -->
                </div>
            </aside>
        </div>
        </main>
        </body>
</html>
  <?php include 'footer.php' ?>