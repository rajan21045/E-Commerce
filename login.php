<?php
include 'db.php';
session_start();

if (isset($_POST['submit'])) {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    // Fetch user by email only
    $sql = "SELECT * FROM users WHERE email = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        // Verify password (works for both hashed and plain-text passwords)
        $passwordValid = password_verify($password, $row['password']) || $password === $row['password'];

        if ($passwordValid) {
            if ($row['user_type'] == 'admin') {
                $_SESSION['user_email'] = $email;   // ✅ Fixed: was 'user_admin'
                $_SESSION['user_role']  = 'admin';
                $_SESSION['user_name']  = $row['name'];
                $_SESSION['user_id']  = $row['id'];

                header("Location: admin/dashboard.php");
                exit();
            } else if ($row['user_type'] == 'user') {
                $_SESSION['user_email'] = $email;
                $_SESSION['user_role']  = 'user';
                $_SESSION['user_name']  = $row['name'];
                $_SESSION['user_id']  = $row['id'];
                header("Location: index.php");
                exit();
            }
        } else {
            $error = "Invalid email or password.";
        }
    } else {
        $error = "Invalid email or password.";
    }

    mysqli_stmt_close($stmt);
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, Helvetica, sans-serif;
        }

        body {
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            background: linear-gradient(135deg, #667eea, #764ba2);
        }

        .login-box {
            background: white;
            padding: 40px;
            width: 350px;
            border-radius: 10px;
            box-shadow: 0 10px 25px rgba(0, 0, 0, 0.2);
            text-align: center;
        }

        .login-box h2 {
            margin-bottom: 25px;
            color: #333;
        }

        .input-box {
            margin-bottom: 20px;
            text-align: left;
        }

        .input-box label {
            font-size: 14px;
            color: #555;
        }

        .input-box input {
            width: 100%;
            padding: 10px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 5px;
            outline: none;
            transition: 0.3s;
        }

        .input-box input:focus {
            border-color: #764ba2;
        }

        .error-msg {
            background: #fee2e2;
            color: #dc2626;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        button {
            width: 100%;
            padding: 10px;
            border: none;
            background: #667eea;
            color: white;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s;
        }

        button:hover {
            background: #5a67d8;
        }

        @media(max-width: 400px) {
            .login-box {
                width: 90%;
                padding: 30px;
            }
        }
    </style>
</head>

<body>

    <div class="login-box">
        <h2>User Login</h2>

        <?php if (!empty($error)): ?>
            <div class="error-msg"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form action="login.php" method="post">

            <div class="input-box">
                <label>Email</label>
                <input type="email" name="email" placeholder="Enter your email"
                    value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>" required>
            </div>

            <div class="input-box">
                <label>Password</label>
                <input type="password" name="password" placeholder="Enter your password" required>
            </div>

            <button type="submit" name="submit">Login</button>

            <p style="margin-top: 10px;">
                Don't have an account?
                <a href="register.php">Register Here!!</a>
            </p>

        </form>
    </div>

</body>

</html>