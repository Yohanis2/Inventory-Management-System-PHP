<?php
include 'connection.php';

if (isset($_POST['activate'])) {
    $emp_id = $_POST['Emp_id'];
    // Perform activation operation in the database
    $sql = "UPDATE users SET status = '1' WHERE Emp_id = '$emp_id'";
    if (mysqli_query($conn, $sql)) {
        echo "<script>
                alert('User activated successfully');
                window.location.href='manage_user.php';
              </script>";
    } else {
        echo "<script>
                alert('Error activating user');
                window.location.href='manage_user.php';
              </script>";
    }
    mysqli_close($conn);
}
?>
