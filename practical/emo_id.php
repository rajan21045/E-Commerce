<?php
session_start();

$_SESSION["emp_id"] ="E101";
$_SESSION["designation"] = "Manager";

echo "session variables are set. <br>";

echo "Employee ID:";
$_SESSION["emp_id"] . "<br>";
echo "Designation:";
$_SESSION["designation"];

?>