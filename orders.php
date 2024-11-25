<?php
$query = "SELECT * FROM orders WHERE status='Pending'";
$result = mysqli_query($conn, $query);

while ($row = mysqli_fetch_assoc($result)) {
    echo "<div class='order'>
            <p>Order ID: {$row['id']}</p>
            <p>Total Price: {$row['total_price']}</p>
            <button onclick='packOrder({$row['id']})'>Pack Order</button>
          </div>";
}
?>
