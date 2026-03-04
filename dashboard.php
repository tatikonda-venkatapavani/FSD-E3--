<?php
require_once 'config.php';

// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$user_id = $_SESSION['user_id'];
$user_name = $_SESSION['full_name'];

// Get user's registered events
$sql = "SELECT e.*, r.registration_date, r.payment_status, r.registration_id
        FROM events e 
        INNER JOIN registrations r ON e.event_id = r.event_id 
        WHERE r.user_id = $user_id 
        ORDER BY r.registration_date DESC";

$result = $conn->query($sql);

// Get statistics
$stats_sql = "SELECT 
                COUNT(*) as total,
                SUM(CASE WHEN payment_status = 'pending' THEN 1 ELSE 0 END) as pending,
                SUM(CASE WHEN payment_status = 'completed' THEN 1 ELSE 0 END) as completed
              FROM registrations 
              WHERE user_id = $user_id";
$stats_result = $conn->query($stats_sql);
$stats = $stats_result->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Campus Events</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }

        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }

        .navbar {
            background: white;
            padding: 1rem 5%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .logo {
            font-size: 1.5rem;
            font-weight: bold;
            color: #667eea;
            text-decoration: none;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .nav-links a {
            text-decoration: none;
            color: #333;
            font-weight: 500;
            transition: color 0.3s;
        }

        .nav-links a:hover {
            color: #667eea;
        }

        .btn {
            padding: 0.5rem 1.5rem;
            border: none;
            border-radius: 25px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 20px;
        }

        .welcome-card {
            background: white;
            border-radius: 20px;
            padding: 30px;
            margin-bottom: 30px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.1);
            animation: slideDown 0.5s ease;
        }

        @keyframes slideDown {
            from {
                opacity: 0;
                transform: translateY(-30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .welcome-card h1 {
            color: #333;
            margin-bottom: 10px;
        }

        .welcome-card p {
            color: #666;
            font-size: 1.1rem;
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .stat-card {
            background: white;
            border-radius: 15px;
            padding: 25px;
            text-align: center;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card h3 {
            color: #666;
            font-size: 1rem;
            margin-bottom: 10px;
        }

        .stat-number {
            color: #667eea;
            font-size: 2.5rem;
            font-weight: bold;
        }

        .section-title {
            color: white;
            margin-bottom: 20px;
            font-size: 1.8rem;
        }

        .events-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(350px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }

        .event-card {
            background: white;
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 5px 15px rgba(0,0,0,0.1);
            transition: transform 0.3s;
        }

        .event-card:hover {
            transform: translateY(-5px);
        }

        .event-header {
            padding: 20px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }

        .event-header h3 {
            margin-bottom: 5px;
            font-size: 1.2rem;
        }

        .event-category {
            display: inline-block;
            padding: 3px 10px;
            background: rgba(255,255,255,0.2);
            border-radius: 15px;
            font-size: 0.8rem;
        }

        .event-body {
            padding: 20px;
        }

        .event-detail {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
            color: #666;
        }

        .event-detail i {
            color: #667eea;
            width: 20px;
        }

        .payment-status {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-top: 10px;
        }

        .status-pending {
            background: #fff3cd;
            color: #856404;
        }

        .status-completed {
            background: #d4edda;
            color: #155724;
        }

        .no-events {
            background: white;
            border-radius: 15px;
            padding: 50px;
            text-align: center;
            color: #666;
        }

        .no-events a {
            margin-top: 20px;
        }

        @media (max-width: 768px) {
            .nav-links {
                display: none;
            }
            
            .events-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="index.php" class="logo">🎓 CampusEvents</a>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="events.php">Events</a>
            <a href="dashboard.php">Dashboard</a>
            <span>👋 <?php echo htmlspecialchars($user_name); ?></span>
            <a href="logout.php" class="btn btn-primary">Logout</a>
        </div>
    </nav>

    <div class="container">
        <div class="welcome-card">
            <h1>Welcome back, <?php echo htmlspecialchars($user_name); ?>!</h1>
            <p>Manage your event registrations and explore new opportunities.</p>
        </div>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Registrations</h3>
                <div class="stat-number"><?php echo $stats['total'] ?? 0; ?></div>
            </div>
            <div class="stat-card">
                <h3>Pending Payments</h3>
                <div class="stat-number"><?php echo $stats['pending'] ?? 0; ?></div>
            </div>
            <div class="stat-card">
                <h3>Completed</h3>
                <div class="stat-number"><?php echo $stats['completed'] ?? 0; ?></div>
            </div>
        </div>

        <h2 class="section-title">My Registered Events</h2>

        <?php if ($result && $result->num_rows > 0): ?>
            <div class="events-grid">
                <?php while($event = $result->fetch_assoc()): ?>
                    <div class="event-card">
                        <div class="event-header">
                            <h3><?php echo htmlspecialchars($event['event_name']); ?></h3>
                            <span class="event-category"><?php echo $event['category']; ?></span>
                        </div>
                        <div class="event-body">
                            <div class="event-detail">
                                <i>📅</i>
                                <span><?php echo date('F j, Y', strtotime($event['event_date'])); ?></span>
                            </div>
                            <div class="event-detail">
                                <i>⏰</i>
                                <span><?php echo date('g:i A', strtotime($event['event_time'])); ?></span>
                            </div>
                            <div class="event-detail">
                                <i>📍</i>
                                <span><?php echo $event['venue']; ?></span>
                            </div>
                            <div class="event-detail">
                                <i>💰</i>
                                <span>$<?php echo $event['registration_fee']; ?></span>
                            </div>
                            <div class="payment-status status-<?php echo $event['payment_status']; ?>">
                                Payment: <?php echo ucfirst($event['payment_status']); ?>
                            </div>
                            <div style="margin-top: 15px; font-size: 0.9rem; color: #999;">
                                Registered on: <?php echo date('M j, Y', strtotime($event['registration_date'])); ?>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <div class="no-events">
                <p>You haven't registered for any events yet.</p>
                <a href="events.php" class="btn btn-primary">Browse Events</a>
            </div>
        <?php endif; ?>
    </div>

    <script>
        // Simple animation
        document.addEventListener('DOMContentLoaded', function() {
            console.log('Dashboard loaded successfully');
        });
    </script>
</body>
</html>