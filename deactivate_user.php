<?php
include 'connection.php';

if (isset($_POST['deactivate'])) {
    $emp_id = $_POST['Emp_id'];
    // Perform deactivation operation in the database
    $sql = "UPDATE users SET status = '0' WHERE Emp_id = '$emp_id'";
    if (mysqli_query($conn, $sql)) {
        echo "<script>
                alert('User deactivated successfully');
                window.location.href='manage_user.php';
              </script>";
    } else {
        echo "<script>
                alert('Error deactivating user');
                window.location.href='manage_user.php';
              </script>";
    }
    mysqli_close($conn);
}
?>
