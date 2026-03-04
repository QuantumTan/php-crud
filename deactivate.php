<?php

require_once __DIR__ . '/classes/Student.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header("Location: /school/index.php");
    exit;
}

$student = new Student();
$success = $student->deactivate((int) $_GET['id']);

if (!$success) {
    header("Location: /school/index.php");
    exit;
}

header("Location: /school/index.php?success=deactivated");
exit;
