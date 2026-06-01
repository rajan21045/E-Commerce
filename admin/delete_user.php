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
if(isset($_GET['id'])){
    $id = $_GET['id'];
    $sql = "delete from users where id = '$id'";
    $result = mysqli_query($conn, $sql);
    if($result){
        header("Location: user_detail.php");
    }

}
?>