<?php
// Test file to check if PHP and XAMPP are working
echo "<h1>✅ PHP is Working!</h1>";
echo "<p>PHP Version: " . phpversion() . "</p>";

// Test database connection
$conn = new mysqli('localhost', 'root', '', 'event_portal');
if ($conn->connect_error) {
    echo "<p style='color:red;'>❌ Database Connection Failed: " . $conn->connect_error . "</p>";
} else {
    echo "<p style='color:green;'>✅ Database Connection Successful!</p>";
    $conn->close();
}

// Show server information
echo "<h2>Server Information:</h2>";
echo "<ul>";
echo "<li><strong>Server Software:</strong> " . $_SERVER['SERVER_SOFTWARE'] . "</li>";
echo "<li><strong>Document Root:</strong> " . $_SERVER['DOCUMENT_ROOT'] . "</li>";
echo "<li><strong>Script Path:</strong> " . $_SERVER['SCRIPT_FILENAME'] . "</li>";
echo "<li><strong>Current Time:</strong> " . date('Y-m-d H:i:s') . "</li>";
echo "</ul>";
?>