<?php
session_start();
include('database.php'); // Include database connection file

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $product_id = intval($_POST['product_id']);
    $user_id = $_SESSION['user_id']; // Ensure the user is logged in and has a session

    if ($product_id) {
        $query = "INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1) 
                  ON DUPLICATE KEY UPDATE quantity = quantity + 1";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ii", $user_id, $product_id);

        if ($stmt->execute()) {
            header("Location: index.php?status=added");
        } else {
            echo "Error: " . $conn->error;
        }
    } else {
        echo "Invalid product ID";
    }
}
?>
