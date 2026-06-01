<?php
session_start();
if ($_SESSION['user_email'] == false) {
    header("Location: login.php");
}else if ($_SESSION['user_role'] != 'user') {
    header("Location: login.php");
}

?>
userpage

<a href="logout.php">Logout</a>