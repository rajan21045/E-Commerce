<?php
session_start();
include "db.php";
if(isset($_GET['cartid']))
    {
        $cart_id = $_GET['cartid'];
        $user_id = $_SESSION['user_id'];

        $sql = "delete from carts where id='$cart_id' AND  user_id='$user_id' ";

        $result = mysqli_query($conn,$sql);
        header("Location:view_cart.php");
    }




?>