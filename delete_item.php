<?php
include 'connection.php';

// Check if the item ID is provided
if (isset($_GET["Item_Code"])) {
    // Get the item ID and sanitize it
    $id = mysqli_real_escape_string($conn, $_GET["Item_Code"]);

    // Check if the action is to delete
    if (isset($_GET["confirm"]) && $_GET["confirm"] === 'yes') {
        // Prepare and execute the delete statement
        $sql = "DELETE FROM item WHERE Item_Code = ?";
        $stmt = mysqli_prepare($conn, $sql);
        mysqli_stmt_bind_param($stmt, 's', $id);

        if (mysqli_stmt_execute($stmt)) {
            echo "<script>
                    alert('Successfully Deleted Item');
                    window.location='manage_item.php';
                  </script>";
        } else {
            echo "<script>
                    alert('Error deleting item: " . mysqli_error($conn) . "');
                    window.location='manage_item.php';
                  </script>";
        }

        // Close the prepared statement
        mysqli_stmt_close($stmt);
    } else {
        // Show confirmation message
        echo "<script>
                if (confirm('Are you sure you want to delete this item?')) {
                    window.location='?Item_Code=$id&confirm=yes';
                } else {
                    window.location='manage_item.php';
                }
              </script>";
    }
} else {
    echo "<script>
            alert('No Item Code provided.');
            window.location='manage_item.php';
          </script>";
}

// Close the database connection
mysqli_close($conn);
?>