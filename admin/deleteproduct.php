<?php
include '../db.php';
session_start();
if (!isset($_SESSION['user_email']) || $_SESSION['user_email'] == false) {
    header("Location: ../index.php");
    exit();
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}
if(isset($_GET['product_id'])){
    $id = $_GET['product_id'];
    $sql = "delete from products where id = '$id'";
    $result = mysqli_query($conn, $sql);
    if($result){
        header("Location: viewproduct.php");
    }

}
?>