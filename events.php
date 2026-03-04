<?php
require_once 'config.php';

// Fetch all events
$sql = "SELECT * FROM events ORDER BY event_date ASC";
$result = $conn->query($sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Events - Campus Events</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; }
        body { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; }
        .navbar { background: rgba(255,255,255,0.95); padding: 1rem 5%; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 10px rgba(0,0,0,0.1); position: sticky; top: 0; z-index: 1000; }
        .logo { font-size: 1.5rem; font-weight: bold; color: #667eea; text-decoration: none; }
        .nav-links { display: flex; gap: 2rem; align-items: center; }
        .nav-links a { text-decoration: none; color: #333; font-weight: 500; transition: color 0.3s; }
        .nav-links a:hover { color: #667eea; }
        .btn { padding: 0.5rem 1.5rem; border: none; border-radius: 25px; cursor: pointer; font-weight: 500; transition: transform 0.3s, box-shadow 0.3s; text-decoration: none; display: inline-block; }
        .btn-primary { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
        .btn-primary:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4); }
        .btn-outline { background: transparent; border: 2px solid #667eea; color: #667eea; }
        .btn-outline:hover { background: #667eea; color: white; }
        .container { max-width: 1200px; margin: 2rem auto; padding: 0 20px; }
        .page-header { text-align: center; color: white; margin-bottom: 3rem; animation: fadeInDown 0.5s ease; }
        .page-header h1 { font-size: 2.5rem; margin-bottom: 1rem; }
        .page-header p { font-size: 1.2rem; opacity: 0.9; }
        .filters { background: white; border-radius: 50px; padding: 1rem; margin-bottom: 3rem; display: flex; justify-content: center; gap: 1rem; flex-wrap: wrap; box-shadow: 0 10px 30px rgba(0,0,0,0.1); animation: fadeInUp 0.5s ease; }
        .filter-btn { padding: 0.5rem 1.5rem; border: none; border-radius: 25px; background: transparent; color: #666; cursor: pointer; font-weight: 500; transition: all 0.3s; }
        .filter-btn:hover { background: #f0f0f0; }
        .filter-btn.active { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; }
        .events-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(350px, 1fr)); gap: 30px; animation: fadeInUp 0.8s ease; }
        .event-card { background: white; border-radius: 20px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.1); transition: all 0.3s ease; position: relative; }
        .event-card:hover { transform: translateY(-10px); box-shadow: 0 20px 40px rgba(0,0,0,0.2); }
        .event-image { height: 200px; overflow: hidden; position: relative; }
        .event-image img { width: 100%; height: 100%; object-fit: cover; transition: transform 0.5s; }
        .event-card:hover .event-image img { transform: scale(1.1); }
        .event-category { position: absolute; top: 20px; right: 20px; padding: 5px 15px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border-radius: 25px; font-size: 0.8rem; font-weight: 600; z-index: 1; }
        .event-content { padding: 20px; }
        .event-title { font-size: 1.3rem; color: #333; margin-bottom: 10px; font-weight: 600; }
        .event-description { color: #666; margin-bottom: 15px; line-height: 1.6; display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden; }
        .event-details { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; margin-bottom: 15px; }
        .event-detail { display: flex; align-items: center; gap: 5px; color: #555; font-size: 0.9rem; }
        .event-detail i { color: #667eea; width: 20px; }
        .event-footer { display: flex; justify-content: space-between; align-items: center; padding-top: 15px; border-top: 1px solid #eee; }
        .event-price { font-size: 1.2rem; font-weight: bold; color: #667eea; }
        .event-price.free { color: #28a745; }
        .btn-register { padding: 8px 20px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 25px; cursor: pointer; font-weight: 500; transition: transform 0.3s, box-shadow 0.3s; text-decoration: none; display: inline-block; }
        .btn-register:hover { transform: translateY(-2px); box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4); }
        @keyframes fadeInDown { from { opacity: 0; transform: translateY(-30px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes fadeInUp { from { opacity: 0; transform: translateY(30px); } to { opacity: 1; transform: translateY(0); } }
        @media (max-width: 768px) { .nav-links { display: none; } .events-grid { grid-template-columns: 1fr; } }
        .search-bar { margin-bottom: 2rem; display: flex; justify-content: center; }
        .search-input { width: 100%; max-width: 500px; padding: 1rem 2rem; border: none; border-radius: 50px; font-size: 1rem; box-shadow: 0 5px 15px rgba(0,0,0,0.1); outline: none; }
    </style>
</head>
<body>
    <nav class="navbar">
        <a href="index.php" class="logo">🎓 CampusEvents</a>
        <div class="nav-links">
            <a href="index.php">Home</a>
            <a href="events.php">Events</a>
            <?php if(isset($_SESSION['user_id'])): ?>
                <a href="dashboard.php">Dashboard</a>
                <span>Welcome, <?php echo $_SESSION['full_name']; ?></span>
                <a href="logout.php" class="btn btn-primary">Logout</a>
            <?php else: ?>
                <a href="login.php" class="btn btn-outline">Login</a>
                <a href="register.php" class="btn btn-primary">Sign Up</a>
            <?php endif; ?>
        </div>
    </nav>

    <div class="container">
        <div class="page-header">
            <h1>Discover Campus Events</h1>
            <p>Find and register for exciting events happening on campus</p>
        </div>

        <div class="search-bar">
            <input type="text" class="search-input" placeholder="Search events..." id="searchInput">
        </div>

        <div class="filters">
            <button class="filter-btn active" data-filter="all">All Events</button>
            <button class="filter-btn" data-filter="Technical">Technical</button>
            <button class="filter-btn" data-filter="Cultural">Cultural</button>
            <button class="filter-btn" data-filter="Sports">Sports</button>
            <button class="filter-btn" data-filter="Academic">Academic</button>
            <button class="filter-btn" data-filter="Career">Career</button>
        </div>

        <div class="events-grid" id="eventsGrid">
            <?php if($result->num_rows > 0): ?>
                <?php while($event = $result->fetch_assoc()): ?>
                    <div class="event-card" data-category="<?php echo $event['category']; ?>">
                        <div class="event-image">
                            <img src="<?php echo $event['image_url']; ?>" alt="<?php echo $event['event_name']; ?>">
                            <span class="event-category"><?php echo $event['category']; ?></span>
                        </div>
                        <div class="event-content">
                            <h3 class="event-title"><?php echo htmlspecialchars($event['event_name']); ?></h3>
                            <p class="event-description"><?php echo htmlspecialchars($event['description']); ?></p>
                            <div class="event-details">
                                <div class="event-detail"><i>📅</i><span><?php echo date('M d, Y', strtotime($event['event_date'])); ?></span></div>
                                <div class="event-detail"><i>⏰</i><span><?php echo date('g:i A', strtotime($event['event_time'])); ?></span></div>
                                <div class="event-detail"><i>📍</i><span><?php echo $event['venue']; ?></span></div>
                                <div class="event-detail"><i>👥</i><span>Capacity: <?php echo $event['capacity']; ?></span></div>
                            </div>
                            <div class="event-footer">
                                <?php if($event['registration_fee'] > 0): ?>
                                    <span class="event-price">$<?php echo $event['registration_fee']; ?></span>
                                <?php else: ?>
                                    <span class="event-price free">Free</span>
                                <?php endif; ?>
                                <a href="register-event.php?event_id=<?php echo $event['event_id']; ?>" class="btn-register">Register Now</a>
                            </div>
                        </div>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p style="color: white; text-align: center;">No events found.</p>
            <?php endif; ?>
        </div>
    </div>

    <script>
        // Filter functionality
        const filterButtons = document.querySelectorAll('.filter-btn');
        const eventCards = document.querySelectorAll('.event-card');
        const searchInput = document.getElementById('searchInput');

        filterButtons.forEach(button => {
            button.addEventListener('click', () => {
                filterButtons.forEach(btn => btn.classList.remove('active'));
                button.classList.add('active');
                const filter = button.dataset.filter;
                eventCards.forEach(card => {
                    if (filter === 'all' || card.dataset.category === filter) {
                        card.style.display = 'block';
                    } else {
                        card.style.display = 'none';
                    }
                });
            });
        });

        searchInput.addEventListener('input', (e) => {
            const searchTerm = e.target.value.toLowerCase();
            eventCards.forEach(card => {
                const title = card.querySelector('.event-title').textContent.toLowerCase();
                if (title.includes(searchTerm)) {
                    card.style.display = 'block';
                } else {
                    card.style.display = 'none';
                }
            });
        });
    </script>
</body>
</html>