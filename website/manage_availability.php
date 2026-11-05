<?php

require 'db_connect.php';

if (
    !isset($_SESSION['role']) ||
    $_SESSION['role'] !== 'tutor'
) {
    header("Location: login.php");
    exit();
}

$tutorId = $_SESSION['user_id'];

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $date = $_POST['available_date'];
    $startTime = $_POST['start_time'];
    $endTime = $_POST['end_time'];

    if ($endTime <= $startTime) {

        $message = "End time must be later than start time.";

    } else {

        $stmt = $pdo->prepare("
            INSERT INTO tutor_availability
            (
                tutor_id,
                available_date,
                start_time,
                end_time
            )
            VALUES (?, ?, ?, ?)
        ");

        $stmt->execute([
            $tutorId,
            $date,
            $startTime,
            $endTime
        ]);

        $message = "Availability added.";
    }
}

if (isset($_GET['delete'])) {

    $availabilityId = $_GET['delete'];

    $stmt = $pdo->prepare("
        DELETE FROM tutor_availability
        WHERE availability_id = ?
        AND tutor_id = ?
    ");

    $stmt->execute([
        $availabilityId,
        $tutorId
    ]);
}

$stmt = $pdo->prepare("
    SELECT *
    FROM tutor_availability
    WHERE tutor_id = ?
    ORDER BY available_date
");

$stmt->execute([$tutorId]);

$availability = $stmt->fetchAll();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Availability</title>
</head>
<body>

<h1>Manage Availability</h1>

<?php if(!empty($message)): ?>

<p style="
    color:
    <?php
        echo str_contains(
            strtolower($message),
            'must'
        )
        ? 'red'
        : 'green';
    ?>
">
    <?php echo $message; ?>
</p>

<?php endif; ?>

<form method="POST">

    <label>Date</label><br>

    <input
        type="date"
        name="available_date"
        required>

    <br><br>

    <label>Start Time</label><br>

    <input
        type="time"
        name="start_time"
        required>

    <br><br>

    <label>End Time</label><br>

    <input
        type="time"
        name="end_time"
        required>

    <br><br>

    <button type="submit">
        Add Availability
    </button>

</form>

<hr>

<h2>Your Availability</h2>

<table border="1" cellpadding="10">

<tr>
    <th>Date</th>
    <th>Start</th>
    <th>End</th>
    <th>Action</th>
</tr>

<?php foreach ($availability as $slot): ?>

<tr>

<td>
    <?php echo $slot['available_date']; ?>
</td>

<td>
    <?php echo $slot['start_time']; ?>
</td>

<td>
    <?php echo $slot['end_time']; ?>
</td>

<td>

<a href="?delete=<?php echo $slot['availability_id']; ?>">
    Delete
</a>

</td>

</tr>

<?php endforeach; ?>

</table>

<br>

<a href="tutor_dashboard.php">
    Back to Dashboard
</a>

</body>
</html>