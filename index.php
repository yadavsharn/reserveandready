<?php
// Include the database.php file to connect to the database and fetch data
include('database.php');

// Fetch products from the database
$products = getProducts();

session_start();

// Check if the user is logged in
$is_logged_in = isset($_SESSION['username']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grocery Store</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f8f9fa;
        }

        .navbar {
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .hero-section {
            background: linear-gradient(135deg, #6a11cb, #2575fc);
            color: #fff;
            text-align: center;
            padding: 100px 20px;
        }

        .hero-section h1 {
            font-size: 3rem;
            font-weight: bold;
        }

        .hero-section p {
            font-size: 1.25rem;
            margin: 20px 0;
        }

        .hero-section a {
            margin-top: 20px;
        }

        .card img {
            height: 200px;
            object-fit: cover;
            border-bottom: 1px solid #ddd;
        }

        .testimonials {
            background: #f9f9f9;
        }

        .testimonials blockquote {
            border-left: 5px solid #6a11cb;
            padding-left: 20px;
            font-style: italic;
        }

        .newsletter {
            background: #6a11cb;
            color: #fff;
            text-align: center;
            padding: 50px 20px;
        }

        .newsletter input {
            border-radius: 20px;
            padding: 10px;
            width: 70%;
            margin-right: 10px;
            border: 1px solid #ddd;
        }

        footer {
            background: #2575fc;
            color: #fff;
            text-align: center;
            padding: 10px 0;
        }

        footer a {
            color: #ffd700;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-success sticky-top">
        <div class="container">
            <a class="navbar-brand" href="#">GroceryStore</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item"><a class="nav-link" href="#home">Home</a></li>
                    <li class="nav-item"><a class="nav-link" href="#about">About</a></li>
                    <li class="nav-item"><a class="nav-link" href="#products" id="products-link">Products</a></li>
                    <li class="nav-item"><a class="nav-link" href="#testimonials">Testimonials</a></li>
                    <li class="nav-item"><a class="nav-link" href="cart.php">Cart</a></li>
                    <li class="nav-item"><a class="nav-link" href="#contact">Contact</a></li>
                    <?php if ($is_logged_in): ?>
                        <li class="nav-item"><span class="nav-link text-white">Hello, <?= htmlspecialchars($_SESSION['username']); ?></span></li>
                        <li class="nav-item"><a class="nav-link btn btn-outline-light btn-sm" href="logout.php">Logout</a></li>
                    <?php else: ?>
                        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
    <script>
    // Get a reference to the "Products" link
    const productsLink = document.getElementById("products-link");

    // Add a click event listener to the link
    productsLink.addEventListener("click", function(event) {
        event.preventDefault(); // Prevent default link behavior

        // Get the target section
        const productsSection = document.getElementById("products");

        // Scroll to the section smoothly
        productsSection.scrollIntoView({ behavior: "smooth" });
    });

    // You can add similar event listeners for other links to scroll to their respective sections
</script>

    <!-- Hero Section -->
    <section id="home" class="hero-section">
        <div class="container">
            <h1>Welcome to Grocery Store</h1>
            <p>Your one-stop shop for fresh and affordable groceries!</p>
            <a href="#products" class="btn btn-lg btn-light">Shop Now</a>
        </div>
    </section>

    <!-- About Section -->
    <section id="about" class="py-5">
        <div class="container text-center">
            <h2>About Us</h2>
            <p>We are dedicated to providing fresh, quality groceries at affordable prices. Our mission is to make your shopping experience easy and convenient.</p>
        </div>
    </section>

    <!-- Products Section -->
    <section id="products" class="py-5 bg-light">
        <div class="container">
            <h2 class="text-center">Our Products</h2>
            <div class="row g-4">
                <?php if ($products && $products->num_rows > 0): ?>
                    <?php while ($product = $products->fetch_assoc()): ?>
                        <div class="col-md-4">
                            <div class="card">
                                <img src="images/<?= htmlspecialchars($product['image']); ?>" class="card-img-top" alt="<?= htmlspecialchars($product['name']); ?>">
                                <div class="card-body">
                                    <h5 class="card-title"><?= htmlspecialchars($product['name']); ?></h5>
                                    <p class="card-text">$<?= htmlspecialchars($product['price']); ?></p>
                                    <form method="POST" action="add_to_cart.php">
                                        <input type="hidden" name="product_id" value="<?= $product['id']; ?>">
                                        <button type="submit" class="btn btn-success">Add to Cart</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    <?php endwhile; ?>
                <?php else: ?>
                    <p class="text-center">No products available at the moment.</p>
                <?php endif; ?>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="py-5 testimonials">
        <div class="container text-center">
            <h2>Testimonials</h2>
            <div class="row g-4">
                <div class="col-md-6">
                    <blockquote>
                        <p>"Excellent service and fresh products! Highly recommend!"</p>
                        <footer>- Customer A</footer>
                    </blockquote>
                </div>
                <div class="col-md-6">
                    <blockquote>
                        <p>"A wide variety of items at great prices."</p>
                        <footer>- Customer B</footer>
                    </blockquote>
                </div>
            </div>
        </div>
    </section>

    <!-- Newsletter Section -->
    <section class="newsletter">
        <div class="container">
            <h2>Subscribe to Our Newsletter</h2>
            <form action="subscribe.php" method="POST" class="d-flex justify-content-center">
                <input type="email" name="email" placeholder="Enter your email" required>
                <button type="submit" class="btn btn-warning">Subscribe</button>
            </form>
        </div>
    </section>

    <!-- Contact Section -->
    <section id="contact" class="py-5">
        <div class="container">
            <h2 class="text-center">Contact Us</h2>
            <form action="contact_process.php" method="POST" class="row g-3">
                <div class="col-md-6">
                    <input type="text" class="form-control" name="name" placeholder="Your Name" required>
                </div>
                <div class="col-md-6">
                    <input type="email" class="form-control" name="email" placeholder="Your Email" required>
                </div>
                <div class="col-12">
                    <textarea class="form-control" name="message" placeholder="Your Message" rows="5" required></textarea>
                </div>
                <div class="col-12 text-center">
                    <button type="submit" class="btn btn-success btn-lg">Send Message</button>
                </div>
            </form>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 Grocery Store. All rights reserved. <a href="#home">Back to top</a></p>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
