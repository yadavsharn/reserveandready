<?php
session_start();
include('database.php'); // Include database connection

$user_id = 1; // For now, assuming a logged-in user with ID 1

// Get cart items for the user
$sql = "SELECT cart.quantity, products.name, products.price, products.product_id FROM cart INNER JOIN products ON cart.product_id = products.id WHERE cart.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$total = 0;
$order_items = [];

// Calculate total price
while ($row = $result->fetch_assoc()) {
    $product_total = $row['price'] * $row['quantity'];
    $total += $product_total;
    $order_items[] = [
        'product_id' => $row['product_id'],
        'quantity' => $row['quantity'],
        'price' => $row['price']
    ];
}

// Create a new order in the orders table
$sql = "INSERT INTO orders (user_id, total_price) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("id", $user_id, $total);
$stmt->execute();
$order_id = $stmt->insert_id; // Get the last inserted order ID

// Insert order items
foreach ($order_items as $item) {
    $sql = "INSERT INTO order_items (order_id, product_id, quantity, price) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiid", $order_id, $item['product_id'], $item['quantity'], $item['price']);
    $stmt->execute();
}

// Clear cart after placing the order
$sql = "DELETE FROM cart WHERE user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();

echo "Order placed successfully! Your order ID is $order_id. Thank you for shopping with us.";
?>
