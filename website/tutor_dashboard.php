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
    <title>Tutor Match - Tutor Dashboard</title>
</head>

<body>

<h1>Tutor Dashboard</h1>

<p>
Welcome,
<?=
htmlspecialchars(
    $_SESSION['first_name']
    . ' ' .
    $_SESSION['last_name']
);
?>
</p>

<p>Role: <?= $_SESSION['role']; ?></p>

<p>
Future features:
</p>

<ul>
    <li>Manage subjects</li>
    <li>Manage availability</li>
    <li>View tutoring requests</li>
</ul>

<a href="logout.php">Logout</a>

</body>

</html>