<?php
session_start();
require 'db_connect.php';

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['login_email'] ?? '');
    $password = trim($_POST['login_password'] ?? '');

    if (!empty($email) && !empty($password)) {

        $stmt = $pdo->prepare("
            SELECT user_id,
                   first_name,
                   last_name,
                   email,
                   password_hash,
                   role
            FROM users
            WHERE email = ?
        ");

        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {

            $_SESSION['user_id'] = $user['user_id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['first_name'] = $user['first_name'];
            $_SESSION['last_name'] = $user['last_name'];

            // Redirect based on role
            switch ($user['role']) {

                case 'student':
                    header("Location: student_dashboard.php");
                    break;

                case 'tutor':
                    header("Location: tutor_dashboard.php");
                    break;

                case 'admin':
                    header("Location: admin_dashboard.php");
                    break;

                default:
                    header("Location: dashboard.php");
                    break;
            }

            exit();

        } else {
            $error = "Invalid email or password.";
        }

    } else {
        $error = "Please fill out all required fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Tutor Match | Login</title>

<style>

body{
    font-family: Arial, sans-serif;
    background:#f4f7fb;
    display:flex;
    justify-content:center;
    align-items:center;
    height:100vh;
    margin:0;
}

.login-box{
    background:white;
    padding:40px;
    width:400px;
    border-radius:10px;
    box-shadow:0 4px 12px rgba(0,0,0,.15);
}

h2{
    text-align:center;
    color:#2c3e50;
}

.subtitle{
    text-align:center;
    color:#666;
    margin-bottom:25px;
}

.input-group{
    margin-bottom:20px;
}

label{
    display:block;
    margin-bottom:8px;
}

input{
    width:100%;
    padding:12px;
    box-sizing:border-box;
}

.btn{
    width:100%;
    padding:12px;
    background:#3498db;
    color:white;
    border:none;
    cursor:pointer;
    border-radius:5px;
}

.btn:hover{
    background:#2980b9;
}

.error{
    background:#ffe5e5;
    color:#c0392b;
    padding:10px;
    margin-bottom:15px;
}

.footer-links{
    margin-top:20px;
    text-align:center;
}

.footer-links a{
    text-decoration:none;
    color:#3498db;
}

</style>
</head>

<body>

<div class="login-box">

    <h2>Tutor Match</h2>
    <p class="subtitle">Find the Right Tutor Faster</p>

    <?php if(!empty($error)): ?>
        <div class="error"><?php echo $error; ?></div>
    <?php endif; ?>

    <form method="POST">

        <div class="input-group">
            <label>Email</label>
            <input type="email"
                   name="login_email"
                   required>
        </div>

        <div class="input-group">
            <label>Password</label>
            <input type="password"
                   name="login_password"
                   required>
        </div>

        <button type="submit" class="btn">
            Login
        </button>

    </form>

    <div class="footer-links">
        <a href="register.php">Create Account</a>
    </div>

</div>

</body>
</html>