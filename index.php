<?php require_once 'config.php'; ?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - Home</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
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
            box-shadow: 0 2px 20px rgba(0,0,0,0.1);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .logo {
            font-size: 1.8rem;
            font-weight: bold;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
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
            border-radius: 50px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }

        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            box-shadow: 0 4px 15px rgba(102, 126, 234, 0.3);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(102, 126, 234, 0.4);
        }

        .btn-outline {
            background: transparent;
            border: 2px solid #667eea;
            color: #667eea;
        }

        .btn-outline:hover {
            background: #667eea;
            color: white;
        }

        .hero {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 5%;
            min-height: 80vh;
            color: white;
            max-width: 1400px;
            margin: 0 auto;
        }

        .hero-content {
            flex: 1;
            animation: fadeInUp 1s ease;
            padding-right: 50px;
        }

        .hero-content h1 {
            font-size: 3.5rem;
            margin-bottom: 1rem;
            line-height: 1.2;
        }

        .hero-content p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.95;
            line-height: 1.6;
        }

        .hero-image {
            flex: 1;
            text-align: center;
            animation: fadeInRight 1s ease;
        }

        .hero-image img {
            max-width: 100%;
            border-radius: 20px;
            box-shadow: 0 20px 40px rgba(0,0,0,0.3);
        }

        .features {
            padding: 5rem 5%;
            background: white;
        }

        .features h2 {
            text-align: center;
            font-size: 2.5rem;
            margin-bottom: 3rem;
            color: #333;
        }

        .features-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 2rem;
            max-width: 1200px;
            margin: 0 auto;
        }

        .feature-card {
            text-align: center;
            padding: 2.5rem 2rem;
            border-radius: 20px;
            background: #f8f9fa;
            transition: all 0.3s;
            box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        }

        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 15px 30px rgba(102, 126, 234, 0.2);
        }

        .feature-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto 1.5rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 2rem;
        }

        .feature-card h3 {
            margin-bottom: 1rem;
            color: #333;
            font-size: 1.3rem;
        }

        .feature-card p {
            color: #666;
            line-height: 1.6;
        }

        .cta-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 5rem 5%;
            text-align: center;
            color: white;
        }

        .cta-section h2 {
            font-size: 2.5rem;
            margin-bottom: 1rem;
        }

        .cta-section p {
            font-size: 1.2rem;
            margin-bottom: 2rem;
            opacity: 0.95;
        }

        .cta-buttons {
            display: flex;
            gap: 1rem;
            justify-content: center;
        }

        .cta-buttons .btn {
            padding: 1rem 2.5rem;
            font-size: 1.1rem;
        }

        .cta-buttons .btn-outline {
            border-color: white;
            color: white;
        }

        .cta-buttons .btn-outline:hover {
            background: white;
            color: #667eea;
        }

        footer {
            background: #1a1a1a;
            color: white;
            padding: 3rem 5%;
            text-align: center;
        }

        .footer-links {
            display: flex;
            gap: 2rem;
            justify-content: center;
            margin-bottom: 2rem;
        }

        .footer-links a {
            color: #999;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-links a:hover {
            color: white;
        }

        .copyright {
            color: #666;
            font-size: 0.9rem;
        }

        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes fadeInRight {
            from {
                opacity: 0;
                transform: translateX(30px);
            }
            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        @media (max-width: 968px) {
            .hero {
                flex-direction: column;
                text-align: center;
                gap: 3rem;
                padding: 3rem 5%;
            }

            .hero-content {
                padding-right: 0;
            }

            .hero-content h1 {
                font-size: 2.5rem;
            }

            .nav-links {
                display: none;
            }

            .cta-buttons {
                flex-direction: column;
                align-items: center;
            }
        }

        .welcome-message {
            background: #4CAF50;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 50px;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="index.php" class="logo">🎓 <?php echo SITE_NAME; ?></a>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="events.php">Events</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="dashboard.php">Dashboard</a>
                <span class="welcome-message">👋 Hi, <?php echo $_SESSION['full_name']; ?></span>
                <a href="logout.php" class="btn btn-primary">Logout</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-outline">Login</a>
                <a href="register.php" class="btn btn-primary">Sign Up</a>
            <?php endif; ?>
        </div>
    </nav>

    <section class="hero">
        <div class="hero-content">
            <h1>Discover & Join Amazing Campus Events</h1>
            <p>Your one-stop platform for all academic, cultural, and technical events happening on campus. Register, participate, and make memories that last a lifetime!</p>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="events.php" class="btn btn-primary" style="margin-right: 1rem; padding: 1rem 2rem; font-size: 1.1rem;">Browse Events</a>
                <a href="dashboard.php" class="btn btn-outline" style="color: white; border-color: white; padding: 1rem 2rem; font-size: 1.1rem;">My Dashboard</a>
            <?php else: ?>
                <a href="events.php" class="btn btn-primary" style="margin-right: 1rem; padding: 1rem 2rem; font-size: 1.1rem;">Browse Events</a>
                <a href="register.php" class="btn btn-outline" style="color: white; border-color: white; padding: 1rem 2rem; font-size: 1.1rem;">Get Started</a>
            <?php endif; ?>
        </div>
        <div class="hero-image">
            <img src="https://images.unsplash.com/photo-1523050854058-8df90110c9f1?w=500" alt="Campus Event">
        </div>
    </section>

    <section class="features">
        <h2>Why Choose <?php echo SITE_NAME; ?>?</h2>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon">📅</div>
                <h3>Easy Registration</h3>
                <p>Register for multiple events with just a few clicks. No complicated forms, no hassle. Get instant confirmation!</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">🎯</div>
                <h3>Diverse Events</h3>
                <p>From technical symposiums to cultural fests, find events that match your interests and boost your skills.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">💳</div>
                <h3>Secure Payments</h3>
                <p>Demo payment gateway for safe and secure transaction processing. Test the experience without real money!</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon">📊</div>
                <h3>Track Progress</h3>
                <p>View your registered events, payment status, and event details in one comprehensive dashboard.</p>
            </div>
        </div>
    </section>

    <section class="cta-section">
        <h2>Ready to Get Started?</h2>
        <p>Join thousands of students who have already discovered amazing campus events!</p>
        <div class="cta-buttons">
            <?php if(!isset($_SESSION['user_id'])): ?>
                <a href="register.php" class="btn btn-primary">Create Account</a>
                <a href="login.php" class="btn btn-outline">Login</a>
            <?php else: ?>
                <a href="events.php" class="btn btn-primary">Browse Events</a>
                <a href="dashboard.php" class="btn btn-outline">My Dashboard</a>
            <?php endif; ?>
        </div>
    </section>

    <footer>
        <div class="footer-links">
            <a href="index.php">Home</a>
            <a href="events.php">Events</a>
            <a href="#">About Us</a>
            <a href="#">Contact</a>
            <a href="#">Privacy Policy</a>
            <a href="#">Terms of Service</a>
        </div>
        <div class="copyright">
            &copy; <?php echo date('Y'); ?> <?php echo SITE_NAME; ?>. All rights reserved. | Academic Campus Event Portal
        </div>
    </footer>

    <script>
        // Check if page loaded properly
        window.addEventListener('load', function() {
            console.log('<?php echo SITE_NAME; ?> loaded successfully!');
        });
    </script>
</body>
</html>