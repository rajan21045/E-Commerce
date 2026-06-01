<?php
session_start();
error_reporting(E_ALL); // Changed to E_ALL for development; use 0 for live production
include 'db.php';

// 1. Fetch Cart Count safely
if (isset($_SESSION['user_id'])) {
    $user_id = $_SESSION['user_id'];
    $sql_cart = "SELECT count(*) as total FROM carts WHERE user_id = '$user_id'";
    $res_cart = mysqli_query($conn, $sql_cart);
    if ($res_cart) {
        $row_cart = mysqli_fetch_assoc($res_cart);
        $count = $row_cart['total'];
    }
}

// 2. Handle Search and Product Fetching
$search = '';
if (isset($_GET['searchproduct']) && !empty(trim($_GET['searchproduct']))) {
    $search = mysqli_real_escape_string($conn, trim($_GET['searchproduct']));
    $sql = "SELECT * FROM products WHERE name LIKE '%$search%' OR description LIKE '%$search%'";
} else {
    $sql = "SELECT * FROM products";
}
$result = mysqli_query($conn, $sql);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Ecom | Premium Shopping</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; font-family: "Segoe UI", sans-serif; }
        body { background: #f4f6fb; color: #333; }

        /* ===== NAVBAR ===== */
        nav {
            background: linear-gradient(to right, #2f3375, #4b50d6);
            padding: 20px 70px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            position: sticky;
            top: 0;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        nav h2 { color: #fff; font-size: 24px; }
        nav ul { list-style: none; display: flex; align-items: center; gap: 25px; }
        nav ul li a { color: #fff; text-decoration: none; font-size: 15px; font-weight: 500; }
        .cart-icon { position: relative; color: white; text-decoration: none; }
        .cart-badge {
            background: #ff4757;
            color: white;
            font-size: 11px;
            padding: 2px 6px;
            border-radius: 50%;
            position: absolute;
            top: -10px;
            right: -10px;
        }

        /* ===== HERO ===== */
        .hero {
            background: linear-gradient(135deg, #6a5acd, #8b7bff);
            padding: 100px 20px;
            text-align: center;
            color: white;
        }
        .hero h1 { font-size: 50px; margin-bottom: 10px; }
        .hero p { font-size: 18px; opacity: 0.9; max-width: 600px; margin: 0 auto 30px; }
        .primary-btn { padding: 12px 30px; border-radius: 25px; border: none; cursor: pointer; font-weight: 600; transition: 0.3s; background: white; color: #3b3f8c; }
        .primary-btn:hover { background: #f0f0f0; transform: translateY(-2px); }

        /* ===== SEARCH SECTION ===== */
        .search-container {
            max-width: 600px;
            margin: -30px auto 50px;
            background: white;
            padding: 10px;
            border-radius: 50px;
            display: flex;
            box-shadow: 0 10px 25px rgba(0,0,0,0.1);
        }
        .search-container input {
            flex: 1;
            border: none;
            padding: 10px 20px;
            outline: none;
            font-size: 16px;
            border-radius: 50px;
        }
        .search-btn {
            background: #4b50d6;
            color: white;
            border: none;
            padding: 10px 25px;
            border-radius: 50px;
            cursor: pointer;
        }

        /* ===== PRODUCT GRID ===== */
        .products { padding: 50px 70px; }
        .products h2 { text-align: center; margin-bottom: 40px; font-size: 30px; }
        .product-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 30px;
        }
        .card {
            background: #fff;
            border-radius: 12px;
            padding: 20px;
            text-align: center;
            transition: 0.3s;
            border: 1px solid #eee;
        }
        .card:hover { transform: translateY(-5px); box-shadow: 0 15px 35px rgba(0,0,0,0.05); }
        .card img { width: 100%; height: 200px; object-fit: contain; margin-bottom: 15px; }
        .card h3 { font-size: 18px; margin-bottom: 8px; height: 45px; overflow: hidden; }
        .price { font-size: 20px; font-weight: bold; color: #4b50d6; margin: 15px 0; }
        .buy-btn {
            display: inline-block;
            width: 100%;
            padding: 10px;
            background: #3b3f8c;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 500;
        }

        /* ===== FOOTER ===== */
        footer { background: #0f0f1a; color: #ccc; padding: 60px 70px; margin-top: 50px; }
        .footer-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 40px;
            max-width: 1200px;
            margin: auto;
        }
        footer h4 { color: white; margin-bottom: 15px; }
        footer ul { list-style: none; }
        footer ul li { margin-bottom: 8px; font-size: 14px; }
        .copy { text-align: center; margin-top: 40px; border-top: 1px solid #222; padding-top: 20px; font-size: 13px; }

        @media(max-width: 768px) {
            nav { padding: 20px; flex-direction: column; gap: 15px; }
            .products { padding: 30px 20px; }
        }
    </style>
</head>
<body>

<nav>
    <h2>My Ecom</h2>
    <ul>
        <li><a href="index.php">Home</a></li>
        <li><a href="#">Products</a></li>
        <?php if(isset($_SESSION['user_email'])): ?>
            <li style="color: #ffcc00;">Hi, <?php echo htmlspecialchars($_SESSION['user_name']); ?></li>
            <li><a href="admin/dashboard.php">Dashboard</a></li>
            <li>
                <a href="view_cart.php" class="cart-icon">
                    <i class="fa-solid fa-cart-shopping"></i>
                    <span class="cart-badge"><?php echo $count; ?></span>
                </a>
            </li>
            <li><a href="logout.php">Logout</a></li>
        <?php else: ?>
            <li><a href="register.php">Register</a></li>
            <li><a href="login.php">Login</a></li>
        <?php endif; ?>
    </ul>
</nav>

<section class="hero">
    <h1>Online Shopping Made Easy</h1>
    <p>Discover premium products, trusted quality and unbeatable prices.</p>
    <button class="primary-btn">Shop Now</button>
</section>

<section class="products">
    <div class="search-container">
        <form action="" method="GET" style="display:contents;">
            <input type="text" name="searchproduct" placeholder="What are you looking for?" value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit" class="search-btn">Search</button>
        </form>
    </div>

    <h2><?php echo $search ? "Search Results for '$search'" : "Our Featured Products"; ?></h2>

    <div class="product-grid">
        <?php 
        if(mysqli_num_rows($result) > 0) {
            while($row = mysqli_fetch_assoc($result)) { 
        ?>
            <div class="card">
                <img src="image/<?php echo $row['image'] ?>" alt="<?php echo $row['name'] ?>" onerror="this.src='https://via.placeholder.com/200x200?text=No+Image'">
                <h3><?php echo htmlspecialchars($row['name']); ?></h3>
                <p style="font-size: 13px; color: #777;">
                    <?php echo substr(htmlspecialchars($row['description']), 0, 60) . '...'; ?>
                </p>
                <div class="price">$<?php echo number_format($row['price'], 2); ?></div>
                <a href="product_detail.php?product_id=<?php echo $row['id'] ?>" class="buy-btn">View Details</a>
            </div>
        <?php 
            } 
        } else {
            echo "<div style='grid-column: 1/-1; text-align: center; padding: 50px;'>
                    <i class='fa-solid fa-magnifying-glass' style='font-size: 40px; color: #ccc; margin-bottom: 10px;'></i>
                    <p>No products found matching your search.</p>
                    <a href='index.php' style='color: #4b50d6;'>Clear Search</a>
                  </div>";
        }
        ?>
    </div>
</section>

<footer>
    <div class="footer-grid">
        <div>
            <h4>Shop</h4>
            <ul>
                <li>New Arrivals</li>
                <li>Best Sellers</li>
                <li>Discount Deals</li>
            </ul>
        </div>
        <div>
            <h4>Support</h4>
            <ul>
                <li>Help Center</li>
                <li>Shipping Policy</li>
                <li>Returns & Refunds</li>
            </ul>
        </div>
        <div>
            <h4>Company</h4>
            <ul>
                <li>About Us</li>
                <li>Contact Us</li>
                <li>Privacy Policy</li>
            </ul>
        </div>
        <div>
            <h4>Contact</h4>
            <ul>
                <li><i class="fa-solid fa-location-dot"></i> Kathmandu, Nepal</li>
                <li><i class="fa-solid fa-envelope"></i> support@myecom.com</li>
                <li><i class="fa-solid fa-phone"></i> +977 98XXXXXXXX</li>
            </ul>
        </div>
    </div>
    <div class="copy">
        © <?php echo date("Y"); ?> My Ecom. All Rights Reserved.
    </div>
</footer>

</body>
</html>