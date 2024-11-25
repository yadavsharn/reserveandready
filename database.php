<?php
// Database configuration
$servername = "localhost"; // Your database host (usually localhost)
$username = "root"; // Database username
$password = ""; // Database password (empty by default for local server)
$dbname = "grocery_store"; // Name of your database

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to fetch all products from the database
function getProducts() {
    global $conn;
    $sql = "SELECT * FROM products"; // Query to fetch all products
    $result = $conn->query($sql);

    // Check if there are any results
    if ($result->num_rows > 0) {
        return $result; // Return the result object
    } else {
        return null; // No products found
    }
}

// Close the connection
// $conn->close(); // You can close the connection after calling the function, or you can leave it open if needed later.
?>
