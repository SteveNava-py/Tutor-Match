<?php

require 'db_connect.php';

if (!isset($_SESSION['user_id'])) {

    header("Location: login.php");
    exit();

}

?>

<!DOCTYPE html>
<html>

<head>
    <title>Tutor Match - Student Dashboard</title>

    <style>

        body{
            font-family:Arial;
            margin:40px;
            background:#f5f7fa;
        }

        .card{
            background:white;
            padding:30px;
            border-radius:10px;
            max-width:700px;
            margin:auto;
            box-shadow:0 0 15px rgba(0,0,0,.1);
        }

        .logout{
            display:inline-block;
            margin-top:20px;
            text-decoration:none;
            background:#e74c3c;
            color:white;
            padding:10px 15px;
            border-radius:5px;
        }

    </style>

</head>

<body>

<div class="card">

    <h1>Welcome Student!</h1>

    <p>
        Logged in as:
        <strong>
            <?=
            htmlspecialchars(
                $_SESSION['first_name'] . ' ' .
                $_SESSION['last_name']
            );
            ?>
        </strong>
    </p>

    <p>Your role is: <?= $_SESSION['role']; ?></p>

    <p>
        Deliverable 2 Walking Skeleton:
        Login → Database → Session → Dashboard
    </p>

    <a href="logout.php" class="logout">
        Logout
    </a>

</div>

</body>

</html>