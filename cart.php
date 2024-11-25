<?php
session_start();
include('database.php'); // Include database connection

$user_id = 1; // For now, assuming a logged-in user with ID 1

// Fetch cart items for the current user
$sql = "SELECT cart.quantity, products.name, products.price, products.image, cart.product_id FROM cart INNER JOIN products ON cart.product_id = products.id WHERE cart.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();

$total = 0;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Cart</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

    <div class="container py-5">
        <h2>Your Shopping Cart</h2>
        <table class="table">
            <thead>
                <tr>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Total</th>
                </tr>
            </thead>
            <tbody>
                <?php
                // Display cart items
                if ($result->num_rows > 0) {
                    while ($row = $result->fetch_assoc()) {
                        $product_total = $row['price'] * $row['quantity'];
                        $total += $product_total;
                        echo "<tr>
                                <td>{$row['name']}</td>
                                <td>{$row['quantity']}</td>
                                <td>\${$row['price']}</td>
                                <td>\${$product_total}</td>
                              </tr>";
                    }
                    echo "<tr><td colspan='3' class='text-end'><strong>Total</strong></td><td><strong>\${$total}</strong></td></tr>";
                } else {
                    echo "<tr><td colspan='4'>Your cart is empty.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <!-- Checkout Button -->
        <a href="checkout.php" class="btn btn-success">Proceed to Checkout</a>
    </div>

</body>
</html>
