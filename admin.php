<?php
// Start session and include database connection
session_start();
include('database.php');

// Handle user role update
if (isset($_POST['update_role'])) {
    $user_id = $_POST['user_id'] ?? null;
    $new_role = $_POST['role'] ?? null;

    if ($user_id && $new_role) {
        $stmt = $conn->prepare("UPDATE users SET role = ? WHERE id = ?");
        $stmt->bind_param("si", $new_role, $user_id);

        if ($stmt->execute()) {
            echo "User role updated successfully!";
        } else {
            echo "Error updating role: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Invalid input for role update.";
    }
    exit;
}

// Handle product addition
if (isset($_POST['add_product'])) {
    $name = $_POST['name'] ?? '';
    $price = $_POST['price'] ?? 0;
    $description = $_POST['description'] ?? '';
    $quantity = $_POST['quantity'] ?? 0;
    $created_at = date('Y-m-d H:i:s');

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
        $image = $_FILES['image']['name'];
        $target = "uploads/" . basename($image);
        
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target)) {
            $stmt = $conn->prepare("INSERT INTO products (name, image, price, description, quantity, created_at) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssdssi", $name, $image, $price, $description, $quantity, $created_at);

            if ($stmt->execute()) {
                echo "Product added successfully!";
            } else {
                echo "Error adding product: " . $stmt->error;
            }
            $stmt->close();
        } else {
            echo "Failed to upload image.";
        }
    } else {
        echo "Invalid image file.";
    }
    exit;
}

// Fetch all users and products
$users = $conn->query("SELECT id, username, role FROM users");
$products = $conn->query("SELECT * FROM products");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container py-5">
        <h1 class="mb-4 text-center">Admin Panel</h1>

        <!-- User Role Management Section -->
        <section class="mb-5">
            <h2 class="text-primary">Manage User Roles</h2>
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Username</th>
                        <th>Role</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($user = $users->fetch_assoc()): ?>
                        <tr>
                            <td><?= $user['id']; ?></td>
                            <td><?= htmlspecialchars($user['username']); ?></td>
                            <td><?= htmlspecialchars($user['role']); ?></td>
                            <td>
                                <form method="POST" class="d-flex align-items-center">
                                    <input type="hidden" name="user_id" value="<?= $user['id']; ?>">
                                    <select name="role" class="form-select me-2">
                                        <option value="user" <?= $user['role'] === 'user' ? 'selected' : ''; ?>>User</option>
                                        <option value="admin" <?= $user['role'] === 'admin' ? 'selected' : ''; ?>>Admin</option>
                                    </select>
                                    <button type="button" onclick="updateRole(this)" class="btn btn-primary btn-sm">Update</button>
                                </form>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>

        <!-- Product Management Section -->
        <section>
            <h2 class="text-success">Add New Product</h2>
            <form id="product-form" enctype="multipart/form-data">
                <div class="mb-3">
                    <label for="name" class="form-label">Product Name</label>
                    <input type="text" class="form-control" id="name" name="name" required>
                </div>
                <div class="mb-3">
                    <label for="image" class="form-label">Product Image</label>
                    <input type="file" class="form-control" id="image" name="image" accept="image/*" required>
                </div>
                <div class="mb-3">
                    <label for="price" class="form-label">Price</label>
                    <input type="number" step="0.01" class="form-control" id="price" name="price" required>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Description</label>
                    <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
                </div>
                <div class="mb-3">
                    <label for="quantity" class="form-label">Quantity</label>
                    <input type="number" class="form-control" id="quantity" name="quantity" required>
                </div>
                <button type="button" onclick="addProduct()" class="btn btn-success">Add Product</button>
            </form>
        </section>

        <!-- Product List Section -->
        <section class="mt-5">
            <h2 class="text-warning">Existing Products</h2>
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Image</th>
                        <th>Price</th>
                        <th>Description</th>
                        <th>Quantity</th>
                        <th>Created At</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while ($product = $products->fetch_assoc()): ?>
                        <tr>
                            <td><?= $product['id']; ?></td>
                            <td><?= htmlspecialchars($product['name']); ?></td>
                            <td><img src="uploads/<?= htmlspecialchars($product['image']); ?>" alt="<?= htmlspecialchars($product['name']); ?>" width="50"></td>
                            <td>$<?= htmlspecialchars($product['price']); ?></td>
                            <td><?= htmlspecialchars($product['description']); ?></td>
                            <td><?= htmlspecialchars($product['quantity']); ?></td>
                            <td><?= htmlspecialchars($product['created_at']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </section>
    </div>

    <!-- Toasts -->
    <div id="toast-container" class="toast-container position-fixed bottom-0 end-0 p-3"></div>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function showToast(message, type) {
            const toast = `
                <div class="toast text-white bg-${type} border-0" role="alert" aria-live="assertive" aria-atomic="true">
                    <div class="toast-body">${message}</div>
                </div>`;
            const container = document.getElementById('toast-container');
            container.innerHTML = toast;
            new bootstrap.Toast(container.firstChild).show();
        }

        function updateRole(button) {
            const form = button.closest('form');
            const formData = new FormData(form);
            fetch('admin.php', {
                method: 'POST',
                body: formData,
            })
                .then((response) => response.text())
                .then((message) => showToast(message, 'success'))
                .catch(() => showToast('Error updating role', 'danger'));
        }

        function addProduct() {
            const form = document.getElementById('product-form');
            const formData = new FormData(form);
            fetch('admin.php', {
                method: 'POST',
                body: formData,
            })
                .then((response) => response.text())
                .then((message) => {
                    form.reset();
                    showToast(message, 'success');
                })
                .catch(() => showToast('Error adding product', 'danger'));
        }
    </script>
</body>
</html>
