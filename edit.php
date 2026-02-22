<?php

// db connection
$conn = new mysqli("localhost", "root", "", "pho_school");

$errors = [];
$data = [
    'student_id' => '',
    'first_name' => '',
    'last_name' => '',
    'date_of_birth' => '',
    'gender' => ''
];


if ($_SERVER['REQUEST_METHOD'] === 'GET') {

    if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
        header("Location: /school/index.php");
        exit;
    }

    $student_id = (int) $_GET['id'];

    $stmt = $conn->prepare("SELECT * FROM students WHERE student_id = ?");
    $stmt->bind_param("i", $student_id);
    $stmt->execute();

    $result = $stmt->get_result();
    $row = $result->fetch_assoc();

    if (!$row) {
        header("Location: /school/index.php");
        exit;
    }

    $data['student_id'] = $row['student_id'];
    $data['first_name'] = $row['first_name'];
    $data['last_name'] = $row['last_name'];
    $data['date_of_birth'] = $row['date_of_birth'];
    $data['gender'] = $row['gender'];

    $stmt->close();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data['student_id'] = (int) ($_POST['student_id'] ?? 0);
    $data['first_name'] = trim($_POST['first_name'] ?? '');
    $data['last_name'] = trim($_POST['last_name'] ?? '');
    $data['date_of_birth'] = $_POST['date_of_birth'] ?? '';
    $data['gender'] = $_POST['gender'] ?? '';

    if ($data['first_name'] === '') {
        $errors['first_name'] = 'First name is required.';
    }
    if ($data['last_name'] === '') {
        $errors['last_name'] = 'Last name is required.';
    }
    if ($data['date_of_birth'] === '') {
        $errors['date_of_birth'] = 'Date of birth is required.';
    }
    if (!in_array($data['gender'], ['Male', 'Female'])) {
        $errors['gender'] = 'Invalid gender selected.';
    }

    if (empty($errors)) {
        $stmt = $conn->prepare("
            UPDATE students SET first_name = ?, last_name = ?, date_of_birth = ?, gender = ?
            WHERE student_id = ?
        ");
        $stmt->bind_param(
            "ssssi",
            $data['first_name'],
            $data['last_name'],
            $data['date_of_birth'],
            $data['gender'],
            $data['student_id']
        );
        $stmt->execute();
        $stmt->close();
        $conn->close();

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


                            <input type="hidden" name="student_id" value="<?= $data['student_id'] ?>">

                            <div class="mb-3">
                                <label class="form-label">First Name</label>
                                <input
                                    type="text"
                                    name="first_name"
                                    class="form-control <?= isset($errors['first_name']) ? 'is-invalid' : '' ?>"
                                    value="<?= htmlspecialchars($data['first_name']) ?>"
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
                                    value="<?= htmlspecialchars($data['last_name']) ?>"
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
                                    value="<?= htmlspecialchars($data['date_of_birth']) ?>">
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
                                    <option value="Male" <?= $data['gender'] === 'Male' ? 'selected' : '' ?>>Male</option>
                                    <option value="Female" <?= $data['gender'] === 'Female' ? 'selected' : '' ?>>Female</option>
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