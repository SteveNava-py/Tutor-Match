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

    $subjectId = $_POST['subject_id'];

    $checkStmt = $pdo->prepare("
        SELECT *
        FROM tutor_subjects
        WHERE tutor_id = ?
        AND subject_id = ?
    ");

    $checkStmt->execute([
        $tutorId,
        $subjectId
    ]);

    if (!$checkStmt->fetch()) {

        $insertStmt = $pdo->prepare("
            INSERT INTO tutor_subjects
            (
                tutor_id,
                subject_id
            )
            VALUES (?, ?)
        ");

        $insertStmt->execute([
            $tutorId,
            $subjectId
        ]);

        $message = "Subject added.";
    } else {

        $message = "Subject already assigned.";
    }
}

if (isset($_GET['delete'])) {

    $subjectId = $_GET['delete'];

    $deleteStmt = $pdo->prepare("
        DELETE FROM tutor_subjects
        WHERE tutor_id = ?
        AND subject_id = ?
    ");

    $deleteStmt->execute([
        $tutorId,
        $subjectId
    ]);
}

$subjectsStmt = $pdo->query("
    SELECT *
    FROM subjects
    ORDER BY subject_name
");

$subjects = $subjectsStmt->fetchAll();

$currentStmt = $pdo->prepare("
    SELECT
        subjects.subject_id,
        subjects.subject_name
    FROM tutor_subjects
    JOIN subjects
        ON tutor_subjects.subject_id = subjects.subject_id
    WHERE tutor_subjects.tutor_id = ?
");

$currentStmt->execute([$tutorId]);

$currentSubjects = $currentStmt->fetchAll();

?>

<!DOCTYPE html>
<html>
<head>
    <title>Manage Subjects</title>
</head>
<body>

<h1>Manage Subjects</h1>

<?php if(!empty($message)): ?>
<p style="color:green;">
    <?php echo $message; ?>
</p>
<?php endif; ?>

<form method="POST">

<label>Select Subject</label><br>

<select name="subject_id" required>

<?php foreach($subjects as $subject): ?>

<option value="<?php echo $subject['subject_id']; ?>">

    <?php echo htmlspecialchars(
        $subject['subject_name']
    ); ?>

</option>

<?php endforeach; ?>

</select>

<br><br>

<button type="submit">
    Add Subject
</button>

</form>

<hr>

<h2>Your Subjects</h2>

<ul>

<?php foreach($currentSubjects as $subject): ?>

<li>

<?php echo htmlspecialchars(
    $subject['subject_name']
); ?>

<a href="?delete=<?php echo $subject['subject_id']; ?>">
    Remove
</a>

</li>

<?php endforeach; ?>

</ul>

<br>

<a href="tutor_dashboard.php">
    Back to Dashboard
</a>

</body>
</html>