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

$stmt = $pdo->prepare("
    SELECT bio,
           hourly_rate,
           learning_style
    FROM tutor_profiles
    WHERE tutor_id = ?
");

$stmt->execute([$tutorId]);

$profile = $stmt->fetch();

if (!$profile) {

    $profile = [
        'bio' => '',
        'hourly_rate' => '',
        'learning_style' => ''
    ];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $bio = trim($_POST['bio']);
    $hourlyRate = trim($_POST['hourly_rate']);
    $learningStyle = trim($_POST['learning_style']);

    $checkStmt = $pdo->prepare("
        SELECT tutor_id
        FROM tutor_profiles
        WHERE tutor_id = ?
    ");

    $checkStmt->execute([$tutorId]);

    if ($checkStmt->fetch()) {

        $updateStmt = $pdo->prepare("
            UPDATE tutor_profiles
            SET bio = ?,
                hourly_rate = ?,
                learning_style = ?
            WHERE tutor_id = ?
        ");

        $updateStmt->execute([
            $bio,
            $hourlyRate,
            $learningStyle,
            $tutorId
        ]);

    } else {

        $insertStmt = $pdo->prepare("
            INSERT INTO tutor_profiles
            (
                tutor_id,
                bio,
                hourly_rate,
                learning_style
            )
            VALUES (?, ?, ?, ?)
        ");

        $insertStmt->execute([
            $tutorId,
            $bio,
            $hourlyRate,
            $learningStyle
        ]);
    }

    $message = "Profile updated successfully.";

    $profile = [
        'bio' => $bio,
        'hourly_rate' => $hourlyRate,
        'learning_style' => $learningStyle
    ];
}

?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Tutor Profile</title>
</head>
<body>

<h2>Edit Tutor Profile</h2>

<?php if(!empty($message)): ?>
    <p style="color:green;">
        <?php echo $message; ?>
    </p>
<?php endif; ?>

<form method="POST">

    <label>Bio</label><br>
    <textarea
        name="bio"
        rows="5"
        cols="50"><?php
            echo htmlspecialchars($profile['bio']);
        ?></textarea>

    <br><br>

    <label>Hourly Rate</label><br>

    <input
        type="number"
        step="0.01"
        name="hourly_rate"
        value="<?php echo htmlspecialchars($profile['hourly_rate']); ?>">

    <br><br>

    <label>Learning Style</label><br>

    <input
        type="text"
        name="learning_style"
        value="<?php echo htmlspecialchars($profile['learning_style']); ?>">

    <br><br>

    <button type="submit">
        Save Profile
    </button>

</form>

<br>

<a href="tutor_dashboard.php">
    Back to Dashboard
</a>

</body>
</html>