<?php
$conn = new mysqli("localhost", "root", "*rajan12345#", "shoppingstore");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}