<?php

// db connection
$conn = new mysqli("localhost", "root", "", "pho_school");

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: /school/index.php");
    exit;
}

$student_id = (int) $_GET['id'];

// verify if student exists and is currently active
$stmt = $conn->prepare("SELECT student_id FROM students WHERE student_id = ? AND is_active = 1");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    $stmt->close();
    $conn->close();
    header("Location: /school/index.php");
    exit;
}
$stmt->close();

// way to deactivate the student 
$stmt = $conn->prepare("UPDATE students SET is_active = 0 WHERE student_id = ?");
$stmt->bind_param("i", $student_id);
$stmt->execute();
$stmt->close();
$conn->close();

header("Location: /school/index.php?success=deactivated");
exit;
