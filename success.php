<?php
session_start();
include "db.php";

// Check login
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

// Check if data exists
if (!isset($_GET['data'])) {
    die("Invalid access");
}

// Decode base64 JSON
$data_json = base64_decode($_GET['data']);

// Convert JSON to associative array
$data = json_decode($data_json, true);

// Check decoded data
if (!$data) {
    die("No data found");
}

// Get payment data
$amt = $data['total_amount'];
$oid = $data['transaction_uuid'];
$refId = $data['transaction_code'];
$status = $data['status'];

// Session data
$user_id = $_SESSION['user_id'];
$name = $_SESSION['name'];
$email = $_SESSION['email'];
$phone = $_SESSION['phone'];
$address = $_SESSION['address'];
$transaction_uuid = $_SESSION['transaction_uuid'];
$total_amount = $_SESSION['total_amount'];

// Verify transaction
if ($amt != $total_amount || $oid != $transaction_uuid) {
    die("Transaction mismatched");
}

// Check duplicate transaction
$check = "SELECT * FROM orders WHERE transaction_uuid = '$oid'";
$check_res = mysqli_query($conn, $check);

if (mysqli_num_rows($check_res) > 0) {
    die("Transaction already exists");
}

// Payment status
$payment_status = "paid";

// Insert into orders table
$insert = "INSERT INTO orders(
    user_id,
    name,
    email,
    phone,
    address,
    transaction_uuid,
    total_amount,
    payment_method,
    payment_status
) VALUES(
    '$user_id',
    '$name',
    '$email',
    '$phone',
    '$address',
    '$oid',
    '$total_amount',
    'Esewa',
    '$payment_status'
)";

$insert_res = mysqli_query($conn, $insert);

// Check order insertion
if (!$insert_res) {
    die("Order Insert Error: " . mysqli_error($conn));
}

// Get inserted order ID
$order_id = mysqli_insert_id($conn);

// Get cart items
$get_cart = "SELECT * FROM carts WHERE user_id = '$user_id'";
$get_cart_res = mysqli_query($conn, $get_cart);

// Insert order items
while ($row = mysqli_fetch_assoc($get_cart_res)) {

    $product_id = $row['product_id'];
    $quantity = $row['quantity'];
    $price = $row['product_price'];

    $insert_item = "INSERT INTO order_items(
        order_id,
        product_id,
        quantity,
        price
    ) VALUES(
        '$order_id',
        '$product_id',
        '$quantity',
        '$price'
    )";

    $insert_item_res = mysqli_query($conn, $insert_item);

    if (!$insert_item_res) {
        die("Order Item Insert Error: " . mysqli_error($conn));
    }
}

// Clear cart
$delete_cart = "DELETE FROM carts WHERE user_id = '$user_id'";
$delete_cart_res = mysqli_query($conn, $delete_cart);

if (!$delete_cart_res) {
    die("Cart Delete Error: " . mysqli_error($conn));
}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Payment Success</title>
</head>

<body>

    <h2>Payment Successful</h2>

    <p><strong>Order ID:</strong> <?php echo $order_id; ?></p>

    <p><strong>Amount:</strong> Rs. <?php echo $total_amount; ?></p>

    <p><strong>Payment Method:</strong> Esewa</p>

    <p><strong>Payment Status:</strong> <?php echo $payment_status; ?></p>

    <p><strong>Transaction ID:</strong> <?php echo $refId; ?></p>

    <a href="index.php">Go to Home</a>

</body>

</html>