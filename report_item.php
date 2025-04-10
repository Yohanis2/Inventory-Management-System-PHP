<?php
include 'header1.php';
include 'authentication.php';

// Fetch all records from the report table
$sql = "SELECT * FROM report ORDER BY take_item_date DESC";
$result = mysqli_query($conn, $sql);
?>

<main id="main-content" class="container-fluid mt-5 pt-3">
    <div class="row">
  
        <!-- Main Content -->
        <section class="col-md-12">
            <div class="main">
                <h1>Item Report</h1>
                <!-- Print Button -->
                <button onclick="printReport()" class="btn btn-primary mb-3">Print Report</button>
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th>Employee ID</th>
                            <th>Item Name</th>
                            <th>Quantity</th>
                            <th>Take Date</th>
                            <th>Return Date</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (mysqli_num_rows($result) > 0): ?>
                            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                                <tr>
                                    <td><?php echo htmlspecialchars($row['Emp_id']); ?></td>
                                    <td><?php echo htmlspecialchars($row['Item_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['quantity']); ?></td>
                                    <td><?php echo htmlspecialchars($row['take_item_date']); ?></td>
                                    <td>
                                        <?php 
                                            echo ($row['return_item_date'] !== null) ? htmlspecialchars($row['return_item_date']) : 'Not Returned';
                                        ?>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['status']); ?></td>
                                </tr>
                            <?php endwhile; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="6" class="text-center">No report data available.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </section>
   
    </div>
</main>

<script>
    // Function to print the report
    function printReport() {
        window.print();
    }
</script>

<?php
// Close the database connection
mysqli_close($conn);
include 'footer.php';
?>
