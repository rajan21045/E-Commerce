<?php
session_start();
error_reporting(0);

include 'db.php';
$sql = "SELECT * FROM products";
$result = mysqli_query($conn, $sql);

$user_id = $_SESSION['user_id'];

$sql1 = "select count(*) as total from carts where user_id = '$user_id' ";
$res1 = mysqli_query($conn, $sql1);
$row = mysqli_fetch_assoc($res1);
$count = $row['total'];

$sql2 = "select * from carts where user_id='$user_id' ";
$result2 = mysqli_query($conn,$sql2);








?>





<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Ecom</title>

    <link rel="stylesheet"
        href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: "Segoe UI", sans-serif;
        }

        /* IMPORTANT — keeps footer at bottom */
        body {
            background: #f4f6fb;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* ===== NAVBAR ===== */

        nav {
            background: linear-gradient(to right, #2f3375, #4b50d6);
            padding: 28px 70px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
        }

        nav h2 {
            color: #fff;
            font-size: 28px;
        }

        nav ul {
            list-style: none;
            display: flex;
            gap: 32px;
        }

        nav ul li a {
            color: #fff;
            text-decoration: none;
            font-size: 16px;
            font-weight: 500;
        }

        .logout_btn {
            background-color: orangered;
            color: white;
            padding: 8px 18px;
            font-weight: bold;
            text-decoration: none;
            border-radius: 20px;
        }

        /* ===== CART ===== */

        .cart-container {
            width: 85%;
            margin: auto;
            margin-top: 80px;
            margin-bottom: 40px;

            flex: 1;
            /* pushes footer down */
        }

        .cart-title {
            font-size: 35px;
            margin-bottom: 25px;
        }

        /* TABLE */

        .cart-table {
            width: 100%;
            border-collapse: collapse;
            background: white;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .cart-table th {
            background: #f3f3f3;
            padding: 15px;
            text-align: left;
        }

        .cart-table td {
            padding: 15px;
            border-top: 1px solid #ddd;
        }

        .cart-table img {
            width: 70px;
        }

        /* QUANTITY */

        .quantity-box {
            width: 55px;
            padding: 6px;
            margin-right: 10px;
        }

        /* BUTTONS */

        .update-btn {
            background: #3498db;
            border: none;
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
        }

        .remove-btn {
            background: #e74c3c;
            border: none;
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
        }

        /* TOTAL */

        .total-row {
            font-weight: bold;
            font-size: 18px;
        }

        /* CHECKOUT */

        .checkout-section {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .checkout-btn {
            background: #27ae60;
            border: none;
            color: white;
            padding: 12px 25px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        /* ===== FOOTER ===== */

        footer {
            background: #0f0f1a;
            color: #ccc;
            padding: 80px 70px;
            margin-top: auto;
            /* keeps footer bottom */
        }

        .footer-grid {
            max-width: 1200px;
            margin: auto;
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 45px;
        }

        footer h4 {
            color: white;
            margin-bottom: 20px;
        }

        footer ul {
            list-style: none;
        }

        footer ul li {
            margin-bottom: 12px;
            font-size: 14px;
        }

        .copy {
            text-align: center;
            margin-top: 55px;
            font-size: 14px;
            color: #aaa;
        }

        /* ===== RESPONSIVE ===== */

        @media(max-width:768px) {

            nav {
                justify-content: center;
                gap: 20px;
            }

            nav ul {
                flex-wrap: wrap;
                justify-content: center;
            }

            .footer-grid {
                grid-template-columns: 1fr 1fr;
            }

        }

        @media(max-width:500px) {

            .footer-grid {
                grid-template-columns: 1fr;
            }

        }
    </style>
</head>

<body>

    <!-- NAVBAR -->

    <nav>

        <h2>My Ecom</h2>

        <ul>

         <li><a href="#">Home</a></li>
        <li><a href="#">Products</a></li>
        <li><a href="#">Contact</a></li>
        <?php
        if($_SESSION['user_email'] == true){
            echo '<li style="color: white; font-weight: bold; font-size: 16px;">Welcome, ' . $_SESSION['user_name'] . '</li>';
            echo   '<li><a href="admin/dashboard.php">Dashboard</a></li>';
            echo '<a href="view_cart.php" style = "color: white; font-weight: bold; font-size: 16px; text-decoration:none;">
            <li class= "fa-solid fa-cart-shopping"></li>
                       '.$count.'
            </a>';
            
            echo '<li><a href="logout.php">Logout</a></li>';

        }else{
            echo '<li><a href="register.php">Register</a></li>';
            echo '<li><a href="login.php">Login</a></li>';
        }
        ?>

        </ul>

    </nav>

    <!-- CART -->

    <div class="cart-container">

        <h2 class="cart-title">
            Your Cart
        </h2>

        <table class="cart-table">

            <tr>
                <th>Image</th>
                <th>Title</th>
                <th>Price</th>
                <th>Quantity</th>
                <th>Total</th>
                <th>Action</th>
            </tr>

            <?php

            $grand_total = 0;
           while( $row2 = mysqli_fetch_assoc($result2))
            {
                $total = $row2['product_price'] * $row2['quantity'];
                
                $grand_total = $grand_total + $total;


            ?>

            <tr>

                <td>
                    <img src="image/<?php echo $row2['product_image']?>">
                </td>

                <td><?php echo $row2['product_name']?></td>

                <td>Rs <?php echo $row2['product_price']?></td>

                <td>
                    <form action="update_cart.php" method="Post">
                    <input type="number" name="updatequantity"
                        value="<?php echo $row2['quantity']?>"
                        class="quantity-box">

                        <input type="hidden" name="cart_id" value="<?php echo $row2['id'] ?>">

                    <button name="update" class="update-btn">
                        Update
                    </button>
                    </form>
                </td>

                <td><?php echo $total ?></td>

                <td>
                    <a href="delete_cart.php?cartid=<?php 
                    echo $row2['id'] ?>">
                    <button  class="remove-btn">
                        Remove
                    </button>
                     </a>
                </td>

            </tr>

            <?php } ?>
          

            <tr class="total-row">

                <td colspan="4">
                    Grand Total
                </td>

                <td>
                   Rs <?php echo $grand_total ?>
                </td>

                <td></td>

            </tr>

        </table>

        <div class="checkout-section">

            <a href="checkout.php">
                <button class="checkout-btn">
                Proceed to Checkout
            </button></a>

        </div>

    </div>

    <!-- FOOTER -->

    <footer>

        <div class="footer-grid">

            <div>
                <h4>Services</h4>
                <ul>
                    <li>Web Development</li>
                    <li>App Development</li>
                    <li>Digital Marketing</li>
                </ul>
            </div>

            <div>
                <h4>Social</h4>
                <ul>
                    <li>Facebook</li>
                    <li>Instagram</li>
                    <li>Twitter</li>
                </ul>
            </div>

            <div>
                <h4>Quick Links</h4>
                <ul>
                    <li>Home</li>
                    <li>Products</li>
                    <li>Contact</li>
                </ul>
            </div>

            <div>
                <h4>Contact</h4>
                <ul>
                    <li>Kathmandu, Nepal</li>
                    <li>info@gmail.com</li>
                    <li>98XXXXXXXX</li>
                </ul>
            </div>

        </div>

        <div class="copy">
            © 2026 My Ecom. All Rights Reserved.
        </div>

    </footer>

</body>

</html>