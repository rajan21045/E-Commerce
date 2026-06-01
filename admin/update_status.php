<?php
Session_start();
include"../db.php";

if(!isset($_SESSION['user_email'])){
    header('location:../index.php');
}
else if($_SESSION['user_role'] == "user"){
    header('location:../index.php');
}

if(isset($_GET['id'])){
    $id=$_GET['id'];
    $sql="update orders set payment_status='paid' where id='$id' ";
    $result=mysqli_query($conn,$sql);
    header("Location:vieworder.php");
    exit();
}

?>