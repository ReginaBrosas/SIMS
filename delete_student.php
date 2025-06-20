<?php
require_once 'db.php';
$mysqli = db_connect();

// Get student_id from URL
$student_id = $_GET['id'] ?? '';
$student_id = trim($student_id);

if ($student_id === '') {
    echo "Invalid student ID.";
    exit;
}

// Check if student exists
$check_stmt = $mysqli->prepare("SELECT * FROM students WHERE student_id = ?");
$check_stmt->bind_param("s", $student_id);
$check_stmt->execute();
$result = $check_stmt->get_result();

if ($result->num_rows === 0) {
    echo "Student not found.";
    exit;
}

// Delete the student
$stmt = $mysqli->prepare("DELETE FROM students WHERE student_id = ?");
if ($stmt) {
    $stmt->bind_param("s", $student_id);
    if ($stmt->execute()) {
        header("Location: view_students.php?msg=deleted");
        exit;
    } else {
        echo "Failed to delete student: " . $mysqli->error;
    }
    $stmt->close();
} else {
    echo "Failed to prepare delete query.";
}
?>
