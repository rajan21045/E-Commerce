<?php
session_start();
error_reporting(error_level: 0);
include "db.php";

$user_id=$_SESSION['user_id'];

$sql1="select count(*) as total from carts where user_id='$user_id' ";
$result1=mysqli_query($conn,$sql1);
// total
// 6

$row=mysqli_fetch_assoc($result1);
//$row=[
// 'total'=>6,
//]

$count=$row['total'];

$sql2="select * from carts where user_id='$user_id' ";
$result2=mysqli_query($conn,$sql2);



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

        /* CHECKOUT CONTAINER */

        .checkout-container {
            width: 85%;
            margin: auto;
            margin-top: 130px;
            display: flex;
            gap: 40px;
            margin-bottom: 120px;
        }

        /* SHIPPING FORM */

        .checkout-form {
            width: 50%;
            background: white;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .checkout-form h2 {
            margin-bottom: 20px;
        }

        .checkout-form label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        .checkout-form input,
        .checkout-form textarea {
            width: 100%;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }

        /* ORDER SUMMARY */

        .order-summary {
            width: 50%;
            background: white;
            padding: 30px;
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .order-summary h2 {
            margin-bottom: 20px;
        }

        .order-summary table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .order-summary th,
        .order-summary td {
            padding: 10px;
            border-bottom: 1px solid #ddd;
            text-align: left;
        }

        .total {
            font-size: 20px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        /* PAYMENT */

        .payment-method h3 {
            margin-bottom: 10px;
        }

        .payment-method label {
            display: block;
            margin-bottom: 8px;
        }

        /* BUTTON */

        .place-btn {
            background: #3498db;
            border: none;
            color: white;
            padding: 12px;
            width: 150px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }

        .place-btn:hover {
            background: #2980b9;
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

        <?php if ($_SESSION['user_email']){ ?>
        <li style= "color:white; font-weight:bold;  font-size: 20px;">Welcome <?php echo $_SESSION ['user_name']; ?>
        </li>
         <li><a href="admin/dashboard.php">dashboard</a></li>

        <a href="view_cart.php" style="color: white; text-decoration:none; font-size: 30px;">
        <i class="fa-solid fa-cart-shopping"></i> 
        <?php echo $count ?>

        </a>
         
        <a class="logout_btn" href="logout.php">logout</a>
        <?php } else{ ?>
        <li><a href="register.php">Register</a></li>
        <li><a href="login.php">Login</a></li>
        <?php } ?>

        </ul>
      

    </nav>

    <!-- CHECKOUT SECTION -->

    <div class="checkout-container">

        <!-- SHIPPING DETAILS -->
        <form action="shipping.php" method="POST" style="display: flex; width: 100%; gap: 40px;">
            <div class="checkout-form">

                <h2>Shipping Details</h2>

                <label>Full Name</label>
                <input name="user_name" type="text" required>

                <label>Email</label>
                <input name="user_email" type="email" required>

                <label>Phone</label>
                <input name="user_phone" type="text" required>

                <label>Address</label>
                <input name="user_address" type="text" required>

            </div>


            <!-- ORDER SUMMARY -->

            <div class="order-summary">

                <h2>Order Summary</h2>

                <table>

                    <tr>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Total</th>
                    </tr>
                  <?php 
                   $grand_total=0;
                   while($row2=mysqli_fetch_assoc($result2)){
                    $total=$row2['product_price']*$row2['quantity'];
                  $grand_total=$grand_total + $total;
           
           ?>



                        <tr>
                            <td><?php echo $row2['product_name'] ?></td>
                            <td><?php echo $row2['quantity'] ?></td>
                            <td> RS <?php echo $total ?></td>
                        </tr>
                        <?php }?>
                    
                </table>
                <div class="total">
                    Grand Total :RS <?php echo $grand_total ?>
                </div>
                  <?php
                  $transaction_uuid=uniqid();
                  $total_amount=$grand_total;

                  ?>
                <input type="hidden" name="total_amount" value="<?php echo $total_amount ?>">
                <input type="hidden" name=" transaction_uuid" value="<?php echo $transaction_uuid ?>">
                
                

                <div class="payment-method">

                    <h3>Payment Method</h3>

                    <label>
                        <BUTTON type="submit" name="payment" value = "cod"> Cash on Delivery (COD)</BUTTON>
                    </label>

                   <label>
                        <button type="submit" name="payment" value="esewa"></button>
                        <img src="productimage/esewa.png" alt="eSewa" style="height: 50px; width: 100px; cursor: pointer;">
                        <!-- <input height="50px" width="100px" type="image" src="productimage/esewa.png" alt=""> -->
                    </label>

                </div>

                <br>

            </div>

        </form>

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