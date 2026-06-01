<?php
session_start();
require_once __DIR__ . '/../db.php';
if (!isset($_SESSION['user_email']) || $_SESSION['user_email'] == false) {
    header("Location: ../index.php");
    exit();
} else if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] != 'admin') {
    header("Location: ../index.php");
    exit();
}
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = intval($_GET['id']);
    $sql = "SELECT * FROM orders WHERE id=$id LIMIT 1";
    $result = mysqli_query($conn, $sql);
    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
    } else {
        header("Location: vieworder.php");
        exit();
    }
} else {
    header("Location: vieworder.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View Orders</title>

    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Inter', sans-serif;
        }

        body {
            background: #f4f6f9;
        }
        ontainer */
.container{
    width:90%;
    margin:auto;
    padding:30px 0;
}

/* Title */
.page-title{
    font-size:22px;
    font-weight:bold;
    margin-bottom:20px;
    color:#111827;
}

/* Grid */
.grid{
    display:grid;
    grid-template-columns:1fr 1fr;
    gap:20px;
}

/* Card */
.card{
    background:white;
    padding:20px;
    border-radius:12px;
    box-shadow:0 6px 20px rgba(0,0,0,0.05);
}

/* Labels */
.label{
    font-size:12px;
    color:#6b7280;
    margin-top:10px;
}

.value{
    font-size:14px;
    font-weight:500;
    color:#111827;
    margin-bottom:8px;
}

/* Badge */
.badge{
    display:inline-block;
    padding:5px 12px;
    border-radius:20px;
    font-size:12px;
    font-weight:bold;
}

.pending{
    background:#fee2e2;
    color:#dc2626;
}

.paid{
    background:#dcfce7;
    color:#16a34a;
}

/* Table */
.table-card{
    margin-top:20px;
}

table{
    width:100%;
    border-collapse:collapse;
    margin-top:10px;
}

th{
    background:#111827;
    color:white;
    padding:12px;
}

td{
    padding:12px;
    border-bottom:1px solid #eee;
}

tr:hover{
    background:#f9fafb;
}


        /* Layout */
        .dashboard {
            display: flex;
            min-height: 100vh;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: #111827;
            color: white;
            padding: 30px 20px;
            position: fixed;
            height: 100%;
        }

        .logo {
            font-size: 20px;
            font-weight: 600;
            margin-bottom: 40px;
        }

        .logo a {
            color: white;
            text-decoration: none;
        }

        .menu {
            list-style: none;
        }

        .menu li {
            margin-bottom: 15px;
        }

        .menu li a {
            text-decoration: none;
            color: #9ca3af;
            display: block;
            padding: 12px 15px;
            border-radius: 8px;
            transition: 0.3s;
        }

        .menu li a:hover,
        .menu li.active a {
            background: #1f2937;
            color: white;
        }

        /* Main */
        .main {
            margin-left: 260px;
            flex: 1;
            padding: 30px;
        }

        /* Topbar */
        .topbar {
            background: white;
            padding: 20px 30px;
            border-radius: 12px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.05);
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .logout-btn {
            background: #ef4444;
            color: white;
            padding: 8px 18px;
            border-radius: 20px;
            text-decoration: none;
            font-size: 14px;
        }

        /* Form Card */
        .form-card {
            margin-top: 30px;
            background: white;
            padding: 40px;
            border-radius: 15px;
            box-shadow: 0 5px 25px rgba(0, 0, 0, 0.05);
            max-width: 700px;
        }

        /* Input Group */
        .input-group {
            margin-bottom: 20px;
        }

        .input-group label {
            display: block;
            margin-bottom: 8px;
            font-size: 14px;
            color: #374151;
            font-weight: 500;
        }

        .input-group input,
        .input-group textarea {
            width: 100%;
            padding: 12px 15px;
            border: 1px solid #d1d5db;
            border-radius: 8px;
            outline: none;
            font-size: 14px;
            transition: 0.3s;
        }

        .input-group input:focus,
        .input-group textarea:focus {
            border-color: #2563eb;
            box-shadow: 0 0 0 3px rgba(37, 99, 235, 0.1);
        }

        textarea {
            resize: none;
            height: 100px;
        }

        /* Button */
        .btn {
            width: 100%;
            padding: 14px;
            background: #2563eb;
            color: white;
            border: none;
            border-radius: 8px;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            transition: 0.3s;
        }

        .btn:hover {
            background: #1e40af;
        }

        /*order table*/
       

        /* Responsive */
        @media(max-width:768px) {
            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
            }

            .main {
                margin-left: 0;
                padding: 20px;
            }
        }
    </style>
</head>

<body>

    <div class="dashboard">

        <!-- Sidebar -->
        <aside class="sidebar">
            <div class="logo"><a href="../index.php">ECOM ADMIN</a></div>
            <ul class="menu">
                <li><a href="dashboard.php">Dashboard</a></li>
                <li><a href="#">Users</a></li>
                <li class="active"><a href="addproduct.php">Add Products</a></li>
                <li><a href="viewproduct.php">View Products</a></li>
                <li><a href="vieworder.php">View Orders</a></li>
            </ul>
        </aside>

        <!-- Main -->
        <div class="main">

            <div class="topbar">
                <h2>Order Details</h2>
                <div class="profile">
                    <span>Admin</span>
                    <a href="../logout.php" class="logout-btn">Logout</a>
                </div>
            </div>

            <!-- order details -->
             <div class="container">


    <!-- TOP GRID -->
    <div class="grid">

        <!-- CUSTOMER INFO -->
        <div class="card">
            <div class="label">Customer Name</div>
            <div class="value"><?php echo $row['name']; ?></div>

            <div class="label">Email</div>
            <div class="value"><?php echo $row['email']; ?></div>

            <div class="label">Phone</div>
            <div class="value"><?php echo $row['phone'];?></div>
        </div>

        <!-- SHIPPING INFO -->
        <div class="card">
            <div class="label">Shipping Address</div>
            <div class="value"><?php echo $row['address']; ?></div>

            <div class="label">Payment Method</div>
            <div class="value"><?php echo $row['payment_method']; ?></div>

            <div class="label">Payment Status</div>
            <span class="badge pending"><?php echo $row['payment_status']; ?></span>
        </div>

    </div>

    <!-- PRODUCTS -->
    <div class="card table-card">

        <h3>Ordered Products</h3>

        <table>

            <tr>
                <th>Product ID</th>
                <th>Name</th>
                <th>Price</th>
                <th>Qty</th>
                <th>Total Price</th>
            </tr>

            <tr>
                <td> 14</td>
                <td>iPhone 14</td>
                <td>Rs. 60</td>
                <td>3</td>
                <td>1800</td>
            </tr>

        </table>

    </div>

</div>

        </div>

    </div>

</body>

</html>