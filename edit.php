<?php

require_once __DIR__ . '/classes/Student.php';

$errors = [];
$student = new Student();

if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        header("Location: /school/index.php");
        exit;
    }

    $row = $student->findById((int) $_GET['id']);

    if (!$row) {
        header("Location: /school/index.php");
        exit;
    }

    $student->student_id = $row['student_id'];
    $student->first_name = $row['first_name'];
    $student->last_name = $row['last_name'];
    $student->date_of_birth = $row['date_of_birth'];
    $student->gender = $row['gender'];
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $student->student_id = (int) ($_POST['student_id'] ?? 0);
    $student->first_name = trim($_POST['first_name'] ?? '');
    $student->last_name = trim($_POST['last_name'] ?? '');
    $student->date_of_birth = $_POST['date_of_birth'] ?? '';
    $student->gender = $_POST['gender'] ?? '';

    $errors = $student->validate();

    if (empty($errors)) {
        $student->update();

        header("Location: index.php?success=updated");
        exit;
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">

                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Edit Student</h4>
                    </div>

                    <div class="card-body">
                        <form method="post" novalidate>


                            <input type="hidden" name="student_id" value="<?= $student->student_id ?>">

                            <div class="mb-3">
                                <label class="form-label">First Name</label>
                                <input
                                    type="text"
                                    name="first_name"
                                    class="form-control <?= isset($errors['first_name']) ? 'is-invalid' : '' ?>"
                                    value="<?= htmlspecialchars($student->first_name) ?>"
                                    maxlength="50">
                                <div class="invalid-feedback">
                                    <?= $errors['first_name'] ?? '' ?>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Last Name</label>
                                <input
                                    type="text"
                                    name="last_name"
                                    class="form-control <?= isset($errors['last_name']) ? 'is-invalid' : '' ?>"
                                    value="<?= htmlspecialchars($student->last_name) ?>"
                                    maxlength="50">
                                <div class="invalid-feedback">
                                    <?= $errors['last_name'] ?? '' ?>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Date of Birth</label>
                                <input
                                    type="date"
                                    name="date_of_birth"
                                    class="form-control <?= isset($errors['date_of_birth']) ? 'is-invalid' : '' ?>"
                                    value="<?= htmlspecialchars($student->date_of_birth) ?>">
                                <div class="invalid-feedback">
                                    <?= $errors['date_of_birth'] ?? '' ?>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label">Gender</label>
                                <select
                                    name="gender"
                                    class="form-select <?= isset($errors['gender']) ? 'is-invalid' : '' ?>">
                                    <option value="">Select Gender</option>
                                    <option value="Male" <?= $student->gender === 'Male' ? 'selected' : '' ?>>Male</option>
                                    <option value="Female" <?= $student->gender === 'Female' ? 'selected' : '' ?>>Female</option>
                                </select>
                                <div class="invalid-feedback">
                                    <?= $errors['gender'] ?? '' ?>
                                </div>
                            </div>

                            <div class="d-grid">
                                <button type="submit" class="btn btn-primary">
                                    Update Student
                                </button>
                            </div>

                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>
</body>

</html>