<?php

require_once __DIR__ . '/Database.php';

class Student
{
    private mysqli $conn;

    // Properties
    public int $student_id = 0;
    public string $first_name = '';
    public string $last_name = '';
    public string $date_of_birth = '';
    public string $gender = '';
    public bool $is_active = true;

    public function __construct()
    {
        $this->conn = Database::getInstance()->getConnection();
    }

     // Get all active students.

    public function getAllActive(): array
    {
        $sql = "SELECT * FROM students WHERE is_active = 1";
        $result = $this->conn->query($sql);

        if (!$result) {
            die("Query failed: " . $this->conn->error);
        }

        $students = [];
        while ($row = $result->fetch_assoc()) {
            $students[] = $row;
        }

        return $students;
    }


     // Find a student by ID.

    public function findById(int $id): ?array
    {
        $stmt = $this->conn->prepare("SELECT * FROM students WHERE student_id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();

        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();

        return $row ?: null;
    }

    // Create a new student.

    public function create(): bool
    {
        $stmt = $this->conn->prepare(
            "INSERT INTO students (first_name, last_name, date_of_birth, gender)
             VALUES (?, ?, ?, ?)"
        );

        $stmt->bind_param(
            "ssss",
            $this->first_name,
            $this->last_name,
            $this->date_of_birth,
            $this->gender
        );

        $success = $stmt->execute();
        $this->student_id = (int) $this->conn->insert_id;
        $stmt->close();

        return $success;
    }

     // Update an existing student.

    public function update(): bool
    {
        $stmt = $this->conn->prepare(
            "UPDATE students SET first_name = ?, last_name = ?, date_of_birth = ?, gender = ?
             WHERE student_id = ?"
        );

        $stmt->bind_param(
            "ssssi",
            $this->first_name,
            $this->last_name,
            $this->date_of_birth,
            $this->gender,
            $this->student_id
        );

        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

     // Deactivate a student (soft delete).

    public function deactivate(int $id): bool
    {
        // Verify student exists and is active
        $stmt = $this->conn->prepare(
            "SELECT student_id FROM students WHERE student_id = ? AND is_active = 1"
        );
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            $stmt->close();
            return false;
        }
        $stmt->close();

        // Deactivate
        $stmt = $this->conn->prepare("UPDATE students SET is_active = 0 WHERE student_id = ?");
        $stmt->bind_param("i", $id);
        $success = $stmt->execute();
        $stmt->close();

        return $success;
    }

     // Validate student data and return errors array.

    public function validate(): array
    {
        $errors = [];

        if ($this->first_name === '') {
            $errors['first_name'] = 'First name is required.';
        }

        if ($this->last_name === '') {
            $errors['last_name'] = 'Last name is required.';
        }

        if ($this->date_of_birth === '') {
            $errors['date_of_birth'] = 'Date of birth is required.';
        }

        if (!in_array($this->gender, ['Male', 'Female'])) {
            $errors['gender'] = 'Invalid gender selected.';
        }

        return $errors;
    }

     // Get the full name of the student.

    public function getFullName(): string
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
