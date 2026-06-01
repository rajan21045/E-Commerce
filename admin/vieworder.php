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

$sql = "select * from orders";
$result = mysqli_query($conn, $sql);

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
        .orders-card {
            margin-top: 30px;
            background: white;
            padding: 25px;
            border-radius: 15px;
            box-shadow: 0 6px 20px rgba(0, 0, 0, 0.05);
        }

        .orders-card h3 {
            margin-bottom: 15px;
            font-size: 18px;
            color: #111827;
        }

        /* Table wrapper for scroll */
        .table-wrapper {
            overflow-x: auto;
        }

        /* Table */
        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 900px;
        }

        th {
            background: #111827;
            color: white;
            padding: 12px;
            font-size: 13px;
            text-transform: uppercase;
        }

        td {
            padding: 12px;
            border-bottom: 1px solid #eee;
            font-size: 14px;
            color: #374151;
        }

        /* Hover effect */
        tr:hover {
            background: #f9fafb;
        }

        /* Badges */
        .badge {
            padding: 4px 10px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
        }

        .badge.pending {
            background: #fee2e2;
            color: #dc2626;
        }

        .badge.paid {
            background: #dcfce7;
            color: #16a34a;
        }

        /* Buttons */
        .btn {
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 12px;
            text-decoration: none;
            color: white;
            margin-right: 5px;
            display: inline-block;
        }

        .btn.view {
            background: #3b82f6;
        }

        .btn.view:hover {
            background: #2563eb;
        }

        .btn.paid {
            background: #10b981;
        }

        .btn.paid:hover {
            background: #059669;
        }

        /* Address column */
        td:nth-child(5) {
            max-width: 180px;
            white-space: normal;
        }

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
                <li><a href="user_detail.php">Users</a></li>
                <li class="active"><a href="addproduct.php">Add Products</a></li>
                <li><a href="viewproduct.php">View Products</a></li>
                <li><a href="vieworder.php">View Orders</a></li>
            </ul>
        </aside>

        <!-- Main -->
        <div class="main">

            <div class="topbar">
                <h2>View Orders</h2>
                <div class="profile">
                    <span>Admin</span>
                    <a href="../logout.php" class="logout-btn">Logout</a>
                </div>
            </div>

            <!-- view orders -->

            <div class="orders-card">

                <h3>All Orders</h3>

                <div class="table-wrapper">

                    <table>

                        <tr>
                            <th>Order ID</th>
                            <th>User ID</th>
                            <th>Customer</th>
                            <th>Contact</th>
                            <th>Shipping Address</th>
                            <th>Amount</th>
                            <th>Payment method</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>

                        <!-- SAMPLE ROW (PHP loop yeta run garne) -->
                       <?php while($row = mysqli_fetch_assoc($result)){ ?>
                        
                        <tr>
                            <td>#<?php echo $row['id'] ?></td>
                            <td><?php echo $row['user_id'] ?></td>
                            <td><?php echo $row['name'] ?></td>
                            <td>
                               <?php echo $row['email'] ?> <br>
                                <?php echo $row['phone'] ?>
                            </td>
                            <td style="max-width:180px;">
                                <?php echo $row['address'] ?>
                            </td>
                            <td><?php echo $row['total_ammount'] ?></td>
                            <td><?php echo $row['payment_method'] ?></td>
                            <td>
                                <span class="badge pending"><?php echo $row['payment_status'] ?></span>
                            </td>
                            <td>
                                <a class="btn view" href="order_detail.php?id=<?php echo $row['id']; ?>">View</a>
                            
                              <?php if($row['payment_method']=="cod" && $row['payment_status']=="pending"){ ?>
    <a class="btn paid" href="update_status.php?id=<?php echo $row['id']; ?>">Mark Paid</a>
<?php } ?>
                            </td>
                        </tr>  
                        <?php } ?>    
                    </table>

                </div>

            </div>
        </div>

    </div>

</body>

</html>