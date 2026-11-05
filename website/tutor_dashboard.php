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
    <li>
        <a href="edit_tutor_profile.php">
            Manage Profile
        </a>
    </li>
    <li>
        <a href="manage_availability.php">
            Manage Availability
        </a>
    </li>
    <li>
        <a href="manage_subjects.php">
            Manage Subjects
        </a>
    </li>
    <li>View tutoring requests</li>
</ul>

<a href="logout.php">Logout</a>

</body>

</html>