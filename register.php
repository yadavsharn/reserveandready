<?php
// Include the database connection
include('database.php');
session_start();

if (isset($_POST['register'])) {
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT); // Hash the password before saving

    // Insert user data into the database
    $query = "INSERT INTO users (username, email, password, role, created_at) 
              VALUES ('$username', '$email', '$password', 'user', NOW())";
    
    if ($conn->query($query) === TRUE) {
        $_SESSION['success_message'] = "Registration successful. Please log in.";
        header("Location: login.php");
    } else {
        $_SESSION['error_message'] = "Error: " . $conn->error;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: #fff;
            font-family: 'Arial', sans-serif;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        .container {
            background: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.2);
            padding: 30px;
            max-width: 400px;
            width: 100%;
            color: #333;
        }
        h2 {
            text-align: center;
            margin-bottom: 20px;
            font-weight: bold;
            color: #6a11cb;
        }
        .form-control {
            border-radius: 20px;
        }
        .btn-primary {
            background: #6a11cb;
            border: none;
            border-radius: 20px;
            padding: 10px 20px;
            transition: background 0.3s ease;
        }
        .btn-primary:hover {
            background: #2575fc;
        }
        .alert {
            border-radius: 10px;
        }
        p {
            text-align: center;
            margin-top: 15px;
        }
        a {
            color: #6a11cb;
            text-decoration: none;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
    <div class="container">
        <h2>Create an Account</h2>
        <?php if (isset($_SESSION['error_message'])) { ?>
            <div class="alert alert-danger">
                <?= $_SESSION['error_message']; unset($_SESSION['error_message']); ?>
            </div>
        <?php } ?>
        <form method="POST" action="register.php">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" class="form-control" id="username" name="username" placeholder="Enter your username" required>
            </div>
            <div class="mb-3">
                <label for="email" class="form-label">Email</label>
                <input type="email" class="form-control" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" class="form-control" id="password" name="password" placeholder="Enter a secure password" required>
            </div>
            <button type="submit" class="btn btn-primary w-100" name="register">Register</button>
        </form>
        <p>Already have an account? <a href="login.php">Login here</a></p>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
