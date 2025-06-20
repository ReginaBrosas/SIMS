<?php
require_once 'db.php';
session_start();

$mysqli = db_connect();

$course_code = $_GET['id'] ?? '';
$course_code = trim($course_code);

if ($course_code === '') {
    $_SESSION['success'] = 'Invalid course code.';
    header("Location: view_courses.php");
    exit;
}

// Check if course exists
$check = $mysqli->prepare("SELECT * FROM courses WHERE course_code = ?");
$check->bind_param("s", $course_code);
$check->execute();
$result = $check->get_result();

if ($result->num_rows === 0) {
    $_SESSION['success'] = 'Course not found.';
    header("Location: view_courses.php");
    exit;
}

// Delete the course
$stmt = $mysqli->prepare("DELETE FROM courses WHERE course_code = ?");
$stmt->bind_param("s", $course_code);

if ($stmt->execute()) {
    $_SESSION['success'] = 'Course successfully deleted.';
} else {
    $_SESSION['success'] = 'Failed to delete course. Please try again.';
}

header("Location: view_courses.php");
exit;
