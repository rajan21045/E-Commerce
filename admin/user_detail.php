<?php
session_start();
include '../db.php';
if (!isset($_SESSION['user_email']) || $_SESSION['user_email'] == false) {
    header("Location: ../index.php");
    exit();
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}

$sql = "select * from users where user_type='user'";
$result = mysqli_query($conn, $sql);
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>View Users</title>

<link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

<style>

*{
margin:0;
padding:0;
box-sizing:border-box;
font-family:'Inter',sans-serif;
}

body{
background:#f4f6f9;
}

/* Layout */

.dashboard{
display:flex;
}

/* Sidebar */

.sidebar{
width:250px;
height:100vh;
background:#111827;
color:white;
position:fixed;
padding:30px 20px;
}

.logo{
font-size:20px;
font-weight:600;
margin-bottom:40px;
letter-spacing:1px;
}

.logo a{
color:white;
text-decoration:none;
}

.menu{
list-style:none;
}

.menu li{
margin-bottom:15px;
}

.menu li a{
text-decoration:none;
color:#9ca3af;
display:block;
padding:12px 15px;
border-radius:8px;
transition:0.3s;
}

.menu li a:hover{
background:#1f2937;
color:white;
}

/* Main */

.main{
margin-left:250px;
width:100%;
padding:30px;
}

/* Topbar */

.topbar{
background:white;
padding:20px;
border-radius:12px;
display:flex;
justify-content:space-between;
align-items:center;
box-shadow:0 4px 15px rgba(0,0,0,0.05);
}

.logout-btn{
background:#ef4444;
color:white;
padding:8px 16px;
border-radius:20px;
text-decoration:none;
}

/* Table */

.table-container{
margin-top:30px;
background:white;
padding:25px;
border-radius:12px;
box-shadow:0 5px 20px rgba(0,0,0,0.05);
overflow-x:auto;
}

table{
width:100%;
border-collapse:collapse;
}

thead{
background:#111827;
color:white;
}

th,td{
padding:14px;
text-align:left;
border-bottom:1px solid #eee;
font-size:14px;
}

tbody tr:hover{
background:#f9fafb;
}

.btn{
padding:6px 12px;
border:none;
border-radius:6px;
cursor:pointer;
font-size:14px;
margin-right:5px;
}

.delete{
background:#ef4444;
color:white;
}

</style>

</head>

<body>

<div class="dashboard">

<!-- Sidebar -->

<div class="sidebar">

<div class="logo"><a href="../index.php">ECOM ADMIN</a></div>

<ul class="menu">
<li><a href="dashboard.php">Dashboard</a></li>
<li><a href="#">Users</a></li>
<li><a href="addproduct.php">Add Products</a></li>
<li><a href="viewproduct.php">View Products</a></li>
<li><a href="vieworder.php">View Orders</a></li>

</ul>

</div>


<!-- Main -->

<div class="main">

<div class="topbar">
<h2>View Users</h2>
<a href="../logout.php" class="logout-btn">Logout</a>
</div>


<!-- Product Table -->

<div class="table-container">

<table>

<thead>
<tr>
<th>ID</th>
<th>User Name</th>
<th>Email</th>
<th>Phone</th>
<th>Address</th>
<th>Action</th>
</tr>
</thead>

<tbody>
<?php
while($row = mysqli_fetch_assoc($result)){
    echo '<tr>
    <td>#'.$row['id'].'</td>
    <td>'.$row['name'].'</td>
    <td>'.$row['email'].'</td>
    <td>'.$row['phone'].'</td>
    <td>'.$row['address'].'</td>
    <td>
    <a href="delete_user.php?id='.$row['id'].'" onclick="return confirm(\'Are you sure?\')"><button class="btn delete">Delete </button></a>
    </td>
    </tr>';
}
?>


</tbody>

</table>

</div>

</div>

</div>

</body>
</html>