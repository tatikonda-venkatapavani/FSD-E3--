<?php
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: " . BASE_URL . "login.php?redirect=" . urlencode($_SERVER['REQUEST_URI']));
    exit();
}

$user_id = $_SESSION['user_id'];
$event_id = isset($_GET['event_id']) ? (int)$_GET['event_id'] : 0;
$error = '';
$success = '';

if ($event_id <= 0) {
    header("Location: " . BASE_URL . "events.php");
    exit();
}

// Fetch event details
$event_sql = "SELECT * FROM events WHERE event_id = $event_id";
$event_result = $conn->query($event_sql);

if (!$event_result || $event_result->num_rows == 0) {
    header("Location: " . BASE_URL . "events.php");
    exit();
}

$event = $event_result->fetch_assoc();

// Check if already registered
$check_sql = "SELECT * FROM registrations WHERE user_id = $user_id AND event_id = $event_id";
$check_result = $conn->query($check_sql);

if ($check_result && $check_result->num_rows > 0) {
    $error = "You have already registered for this event!";
}

// Handle registration
if ($_SERVER['REQUEST_METHOD'] == 'POST' && !$error) {
    $payment_status = ($event['registration_fee'] > 0) ? 'pending' : 'completed';
    
    $reg_sql = "INSERT INTO registrations (user_id, event_id, payment_status) 
                VALUES ($user_id, $event_id, '$payment_status')";
    
    if ($conn->query($reg_sql) === TRUE) {
        $reg_id = $conn->insert_id;
        if ($event['registration_fee'] > 0) {
            $_SESSION['registration_id'] = $reg_id;
            header("Location: " . BASE_URL . "payment-demo.php?registration_id=" . $reg_id);
            exit();
        } else {
            $success = "Successfully registered for the event!";
        }
    } else {
        $error = "Registration failed: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register for Event - <?php echo SITE_NAME; ?></title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .container { background: white; border-radius: 20px; box-shadow: 0 20px 40px rgba(0,0,0,0.2); width: 100%; max-width: 600px; padding: 40px; animation: slideUp 0.5s ease; }
        @keyframes slideUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        .event-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); margin: -40px -40px 30px -40px; padding: 40px; border-radius: 20px 20px 0 0; color: white; }
        .event-header h1 { font-size: 2rem; margin-bottom: 10px; }
        .event-details { background: #f8f9fa; border-radius: 15px; padding: 20px; margin-bottom: 30px; }
        .detail-row { display: flex; padding: 10px 0; border-bottom: 1px solid #e0e0e0; }
        .detail-row:last-child { border-bottom: none; }
        .detail-label { flex: 0 0 120px; font-weight: 600; color: #555; }
        .detail-value { flex: 1; color: #333; }
        .price-tag { font-size: 2rem; font-weight: bold; color: #667eea; text-align: center; margin: 20px 0; }
        .price-tag.free { color: #28a745; }
        .btn-register { width: 100%; padding: 15px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 10px; font-size: 1.1rem; font-weight: 600; cursor: pointer; transition: all 0.3s; }
        .btn-register:hover { transform: translateY(-2px); box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4); }
        .alert { padding: 15px; border-radius: 10px; margin-bottom: 20px; }
        .alert-error { background: #fee; color: #c33; border: 1px solid #fcc; }
        .alert-success { background: #efe; color: #3c3; border: 1px solid #cfc; }
        .user-info { background: #e8f0fe; border-radius: 10px; padding: 15px; margin-bottom: 20px; }
        .back-link { display: block; text-align: center; margin-top: 20px; color: #667eea; text-decoration: none; }
        .back-link:hover { text-decoration: underline; }
        .checkbox-group { display: flex; align-items: center; gap: 10px; margin: 15px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="event-header">
            <h1><?php echo htmlspecialchars($event['event_name']); ?></h1>
            <p>Complete your registration</p>
        </div>

        <?php if($error): ?>
            <div class="alert alert-error"><?php echo $error; ?></div>
        <?php endif; ?>

        <?php if($success): ?>
            <div class="alert alert-success"><?php echo $success; ?></div>
            <a href="events.php" class="back-link">← Browse More Events</a>
            <a href="dashboard.php" class="back-link">→ Go to Dashboard</a>
        <?php else: ?>
            <div class="event-details">
                <div class="detail-row"><span class="detail-label">Date:</span><span class="detail-value"><?php echo date('F j, Y', strtotime($event['event_date'])); ?></span></div>
                <div class="detail-row"><span class="detail-label">Time:</span><span class="detail-value"><?php echo date('g:i A', strtotime($event['event_time'])); ?></span></div>
                <div class="detail-row"><span class="detail-label">Venue:</span><span class="detail-value"><?php echo $event['venue']; ?></span></div>
                <div class="detail-row"><span class="detail-label">Category:</span><span class="detail-value"><?php echo $event['category']; ?></span></div>
                <div class="detail-row"><span class="detail-label">Capacity:</span><span class="detail-value"><?php echo $event['capacity']; ?> seats</span></div>
            </div>

            <div class="user-info">
                <h3>Registering as:</h3>
                <p><strong><?php echo $_SESSION['full_name']; ?></strong> (<?php echo $_SESSION['email']; ?>)</p>
            </div>

            <div class="<?php echo $event['registration_fee'] > 0 ? 'price-tag' : 'price-tag free'; ?>">
                <?php if($event['registration_fee'] > 0): ?>
                    Registration Fee: $<?php echo $event['registration_fee']; ?>
                <?php else: ?>
                    🎉 Free Registration
                <?php endif; ?>
            </div>

            <form method="POST" action="">
                <div class="checkbox-group">
                    <input type="checkbox" id="terms" required>
                    <label for="terms">I agree to the Terms and Conditions</label>
                </div>
                
                <div class="checkbox-group">
                    <input type="checkbox" id="notify" checked>
                    <label for="notify">Receive email notifications</label>
                </div>

                <button type="submit" class="btn-register" <?php echo $error ? 'disabled' : ''; ?>>
                    <?php echo ($event['registration_fee'] > 0) ? 'Proceed to Payment' : 'Confirm Registration'; ?>
                </button>
            </form>

            <a href="events.php" class="back-link">← Back to Events</a>
        <?php endif; ?>
    </div>
</body>
</html>