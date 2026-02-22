<?php

$errors = [];
$data = [
    'first_name' => '',
    'last_name' => '',
    'date_of_birth' => '',
    'gender' => ''
];
// connection to db
$conn = new mysqli("localhost", "root", "", "pho_school");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $data['first_name'] = trim($_POST['first_name'] ?? '');
    $data['last_name'] = trim($_POST['last_name'] ?? '');
    $data['date_of_birth'] = $_POST['date_of_birth'] ?? '';
    $data['gender'] = $_POST['gender'] ?? '';

    // checking
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

    // insert if no errors
    if (empty($errors)) {

        $stmt = $conn->prepare("
            INSERT INTO students (first_name, last_name, date_of_birth, gender)
            VALUES (?, ?, ?, ?)
        ");

        $stmt->bind_param(
            "ssss",
            $data['first_name'],
            $data['last_name'],
            $data['date_of_birth'],
            $data['gender']
        );

        $stmt->execute();

        $stmt->close();
        $conn->close();

        // Post-Redirect-Get
        header("Location: index.php?success=created");
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Student</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <div class="container my-5">
        <div class="row justify-content-center">
            <div class="col-md-6">

                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h4 class="mb-0">Add Student</h4>
                    </div>

                    <div class="card-body">
                        <form method="post" novalidate>

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
                                    Add Student
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