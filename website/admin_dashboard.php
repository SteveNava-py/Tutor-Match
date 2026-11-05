<?php

require 'db_connect.php';

if (
    !isset($_SESSION['role']) ||
    $_SESSION['role'] !== 'admin'
) {
    header("Location: login.php");
    exit();
}

$totalUsers =
$pdo->query(
    "SELECT COUNT(*) FROM users"
)->fetchColumn();

?>

<!DOCTYPE html>
<html>

<head>
    <title>Tutor Match Admin Dashboard</title>

    <style>

        body{
            font-family:Arial;
            background:#f4f6f9;
            padding:40px;
        }

        .card{
            background:white;
            padding:30px;
            border-radius:10px;
            max-width:800px;
            margin:auto;
        }

    </style>

</head>

<body>

<div class="card">

    <h1>Tutor Match Administration</h1>

    <p>
        Welcome,
        <?= htmlspecialchars($_SESSION['first_name']); ?>
    </p>

    <p>Total Users: <?= $totalUsers; ?></p>

    <p>
        This dashboard demonstrates authentication for Deliverable 2.
    </p>

    <a href="logout.php">
        Logout
    </a>

</div>

</body>

</html>