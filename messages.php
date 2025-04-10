<?php
session_start();
include 'connection.php';

// Check if the user is logged in
if (!isset($_SESSION['Emp_id'])) {
    header('Location: login.php');
    exit();
}

$current_user_id = $_SESSION['Emp_id'];

// Fetch the account type and user details
$query = "SELECT Fname, Lname, Account_type FROM users WHERE Emp_id = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $current_user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Handle sending a message
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['send_message'])) {
    $receiver_id = $_POST['receiver_id'];
    $message = trim($_POST['message']);

    if (!empty($message)) {
        $query = "INSERT INTO messages (sender_id, receiver_id, message) VALUES (?, ?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param('sss', $current_user_id, $receiver_id, $message);
        $stmt->execute();
    }
}

// Fetch messages for the current user and the selected receiver
$messages = [];
$receiver_id = $_GET['receiver_id'] ?? null;

if ($receiver_id) {
    $query = "SELECT * FROM messages WHERE 
                (sender_id = ? AND receiver_id = ?) OR 
                (sender_id = ? AND receiver_id = ?) 
              ORDER BY timestamp ASC";
    $stmt = $conn->prepare($query);
    $stmt->bind_param('ssss', $current_user_id, $receiver_id, $receiver_id, $current_user_id);
    $stmt->execute();
    $result = $stmt->get_result();

    while ($row = $result->fetch_assoc()) {
        $messages[] = $row;
    }

    // Mark messages as read
    $update_read = "UPDATE messages SET `read` = 1 WHERE receiver_id = ? AND sender_id = ? AND `read` = 0";
    $stmt_update = $conn->prepare($update_read);
    $stmt_update->bind_param('ss', $current_user_id, $receiver_id);
    $stmt_update->execute();
}

// Fetch users excluding the current user
$user_query = "SELECT Emp_id, Fname, Lname FROM users WHERE Emp_id != ?";
$stmt = $conn->prepare($user_query);
$stmt->bind_param('s', $current_user_id);
$stmt->execute();
$user_result = $stmt->get_result();
$users = $user_result->fetch_all(MYSQLI_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="css/chat.css">
</head>
<body>

<div class="row">
    <!-- Left Sidebar -->
    <aside class="col-md-4 sidebar-left">
        <h2>
            <a href="#" class="home">
                <i class="fa fa-globe"></i> Online
                <i class="bi bi-person-circle" style="font-size: 50px;"></i>
                <span style="font-size: 24px;">
                    <?php echo htmlspecialchars($user['Fname'] . ' ' . $user['Lname']); ?>
                </span>
            </a>
        </h2>
        <div class="mb-2">
            <img src="img/invntory2.jpg" alt="Inventory" class="img-fluid rounded">
        </div>
    </aside>

    <!-- Main Content -->
    <section class="col-md-8"> 
        <h1 class="mt-4 chat-heading"><i class="fa fa-comments"></i> Chat</h1>
        <div class="row mt-4">
            <!-- Users List -->
            <div class="col-md-4">
                <h3><i class="fa fa-address-book"></i> Contacts</h3>
                <ul class="list-group">
                    <?php foreach ($users as $user): ?>
                        <?php
                            // Fetch unread message count for this specific user
                            $unread_query = "SELECT COUNT(*) AS unread_count FROM messages WHERE receiver_id = ? AND sender_id = ? AND `read` = 0";
                            $stmt = $conn->prepare($unread_query);
                            $stmt->bind_param('ss', $current_user_id, $user['Emp_id']);
                            $stmt->execute();
                            $result = $stmt->get_result();
                            $unread_count = $result->fetch_assoc()['unread_count'];
                        ?>
                        <li class="list-group-item">
                            <a href="?receiver_id=<?php echo $user['Emp_id']; ?>">
                                <i class="bi bi-person-circle"></i> <?php echo htmlspecialchars($user['Fname'] . ' ' . $user['Lname']); ?>
                                <?php if ($unread_count > 0): ?>
                                    <span class="badge bg-danger"><?php echo $unread_count; ?></span>
                                <?php endif; ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Chat Area -->
            <div class="col-md-8">
                <?php if ($receiver_id): ?>
                    <?php
                    $receiver_query = "SELECT Fname, Lname FROM users WHERE Emp_id = ?";
                    $stmt = $conn->prepare($receiver_query);
                    $stmt->bind_param('s', $receiver_id);
                    $stmt->execute();
                    $receiver_result = $stmt->get_result();
                    $receiver = $receiver_result->fetch_assoc();
                    ?>
                    <h2 class="d-flex align-items-center">
                        <i class="fa fa-comments me-2"></i> Chat with 
                        <i class="bi bi-person-circle ms-3" style="font-size: 50px;"></i> 
                        <i class="bi bi-circle-fill online-status ms-2"></i> 
                        <span class="receiver-name ms-2"><?php echo htmlspecialchars($receiver['Fname'] . ' ' . $receiver['Lname']); ?></span>
                    </h2>

                    <!-- Messages -->
                    <div class="messages mb-3 p-3 border rounded" style="max-height: 400px; overflow-y: auto;">
                        <?php foreach ($messages as $message): ?>
                            <div class="d-flex <?php echo ($message['sender_id'] == $current_user_id) ? 'justify-content-end' : 'justify-content-start'; ?>">
                                <div class="alert <?php echo ($message['sender_id'] == $current_user_id) ? 'alert-success' : 'alert-secondary'; ?>">
                                    <p><?php echo htmlspecialchars($message['message']); ?></p>
                                    <small><?php echo date('d M Y, H:i', strtotime($message['timestamp'])); ?></small>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <!-- Message Form -->
                    <form method="POST">
                        <div class="input-group">
                            <textarea class="form-control" name="message" placeholder="Type a message..." required></textarea>
                            <input type="hidden" name="receiver_id" value="<?php echo htmlspecialchars($receiver_id); ?>">
                            <button class="btn btn-primary" type="submit" name="send_message">
                                <i class="bi bi-send"></i> Send
                            </button>
                        </div>
                    </form>
                <?php else: ?>
                    <p>Select a user to start chatting.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>
</div>

<?php include 'footer.php'; ?>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>

<?php
mysqli_close($conn);
?>
