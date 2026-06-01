<?php
session_start();
include "db.php";

if(isset($_POST['update']))
    {
        $quantity = $_POST['updatequantity'];
        $cartid = $_POST['cart_id'];
        $user_id=$_SESSION['user_id'];

        $sql = "update carts set quantity='$quantity' where id='$cartid' AND user_id='$user_id' ";

        $result = mysqli_query($conn,$sql);
        header("Location:view_cart.php");
    }





?>