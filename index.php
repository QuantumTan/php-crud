<?php
$successType = $_GET['success'] ?? '';
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pho School</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
</head>

<body>
    <div class="container my-5">
        <?php if ($successType === 'created'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Student added successfully.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php elseif ($successType === 'updated'): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                Student updated successfully.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php elseif ($successType === 'deactivated'): ?>
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                Student deactivated successfully.
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
        <h1>List of Students</h1>
        <a href="/school/create.php" class="btn btn-primary" role="btn">New Student</a>
        <table class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Full Name</th>
                    <th>Date of Birth</th>
                    <th>Gender</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>

                <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $database = "pho_school";
                //    db connection
                $connection = new mysqli($servername, $username, $password, $database);
                // check the connection
                if ($connection->connect_error) {
                    die("connection failed: " . $connection->connect_error);
                }

                // read rows from the db (only active students)
                $sql = "select * from students WHERE is_active = 1";
                // store result
                $result = $connection->query($sql);
                // checking
                if (!$result) {
                    die("Connection failed: " . $connection->error);
                }


                // read the data using while loop
                while ($row = $result->fetch_assoc()):
                    $fullName = $row['first_name'] . ' ' . $row['last_name'];
                ?>
                    <tr>
                        <td><?= htmlspecialchars($row['student_id']) ?></td>
                        <td><?= htmlspecialchars($fullName) ?></td>
                        <td><?= htmlspecialchars($row['date_of_birth']) ?></td>
                        <td><?= htmlspecialchars($row['gender']) ?></td>
                        <td>
                            <a href="/school/edit.php?id=<?= $row['student_id'] ?>" class="btn btn-primary btn-sm">Edit</a>
                            <a href="/school/deactivate.php?id=<?= $row['student_id'] ?>" class="btn btn-danger btn-sm">Deactivate</a>
                        </td>
                    </tr>
                <?php endwhile;
                ?>

            </tbody>
        </table>
    </div>
</body>

</html>