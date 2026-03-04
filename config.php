<?php
// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'event_portal');
define('BASE_URL', 'http://localhost/academic-event-portal/');

// Create connection
$conn = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);

// Check connection
if ($conn->connect_error) {
    die("<div style='background: #f8d7da; color: #721c24; padding: 20px; margin: 20px; border-radius: 5px; border: 1px solid #f5c6cb;'>
            <h3>❌ Database Connection Failed!</h3>
            <p><strong>Error:</strong> " . $conn->connect_error . "</p>
            <p><strong>Please check:</strong></p>
            <ul>
                <li>XAMPP is running (Apache and MySQL)</li>
                <li>Database 'event_portal' exists</li>
                <li>Check credentials in config.php</li>
            </ul>
            <p><a href='http://localhost/phpmyadmin' target='_blank'>Open phpMyAdmin</a></p>
          </div>");
}

// Set charset
$conn->set_charset("utf8");

// Start session if not started
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}

// Site configuration
define('SITE_NAME', 'CampusEvents');
?>