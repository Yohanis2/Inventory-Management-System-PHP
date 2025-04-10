<?php
session_start();
include 'header1.php'; // Include your header file
include 'authentication.php';  // Include your database connection file

// Get sender and receiver IDs (You can use session variables for the logged-in user)
$sender_id = $_SESSION['Emp_id']; // Assuming logged-in user's ID is stored in session
$receiver_id = isset($_GET['receiver_id']) ? $_GET['receiver_id'] : ''; // Receiver ID from URL parameter

// Fetch messages from the database
$query = "SELECT * FROM messages WHERE (sender_id = ? AND receiver_id = ?) OR (sender_id = ? AND receiver_id = ?) ORDER BY sent_at ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("ssss", $sender_id, $receiver_id, $receiver_id, $sender_id);
$stmt->execute();
$result = $stmt->get_result();

// Mark all messages as read for this user
$update_query = "UPDATE messages SET read_status = 1 WHERE receiver_id = ? AND read_status = 0";
$update_stmt = $conn->prepare($update_query);
$update_stmt->bind_param("s", $sender_id);
$update_stmt->execute();

$stmt->close();
$update_stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Messages</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-KyZXEJ3QxNn1T+Vr5F7wOdpmTxP0RzjQau7BkP0zF5tLtxBbX9/VfIuGz6VK1Ysm" crossorigin="anonymous">
</head>
<body>
    <div class="container mt-5">
        <div class="row">
            <div class="col-md-12">
                <div class="card">
                    <div class="card-header">
                        <h4>Chat Messages</h4>
                    </div>
                    <div class="card-body" style="height: 400px; overflow-y: scroll;">
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <div class="message <?php echo $row['sender_id'] == $sender_id ? 'text-end' : ''; ?>">
                                <div class="d-flex justify-content-<?php echo $row['sender_id'] == $sender_id ? 'end' : 'start'; ?>">
                                    <div class="message-box bg-<?php echo $row['sender_id'] == $sender_id ? 'primary' : 'light'; ?> text-<?php echo $row['sender_id'] == $sender_id ? 'white' : 'dark'; ?> p-2 rounded mb-2" style="max-width: 80%;">
                                        <p class="mb-0"><?php echo htmlspecialchars($row['message_text']); ?></p>
                                        <small class="text-muted"><?php echo date('H:i', strtotime($row['sent_at'])); ?></small>
                                    </div>
                                </div>
                            </div>
                        <?php endwhile; ?>
                    </div>
                    <div class="card-footer">
                        <form method="POST" action="send_message.php">
                            <div class="input-group">
                                <input type="text" class="form-control" name="message_text" placeholder="Type your message..." required>
                                <button class="btn btn-primary" type="submit" name="send_message">Send</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.bundle.min.js" integrity="sha384-pzjw8f+ua7Kw1TIq0YeXwvR4pANyA+zF5J9g8sA6zK2Rejz5W70EMzNfY9mL4Fq0" crossorigin="anonymous"></script>
</body>
</html>
