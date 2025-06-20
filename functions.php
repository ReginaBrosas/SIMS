<?php
// functions.php - Enhanced
session_start();

function is_logged_in() {
    return isset($_SESSION['user']);
}

function login($username, $password) {
    $valid_users = ['admin' => 'admin123'];
    if (isset($valid_users[$username]) && $valid_users[$username] === $password) {
        $_SESSION['user'] = $username;
        return true;
    }
    return false;
}

function logout() {
    session_destroy();
    header("Location: login.php");
    exit;
}

function sanitize($data) {
    return htmlspecialchars(trim($data));
}

function fetch_courses($mysqli) {
    $courses = [];
    $stmt = $mysqli->prepare("SELECT course_id, course_name FROM courses ORDER BY course_name ASC");
    $stmt->execute();
    $result = $stmt->get_result();
    while ($row = $result->fetch_assoc()) {
        $courses[] = $row;
    }
    $stmt->close();
    return $courses;
}

function get_student($mysqli, $id) {
    $stmt = $mysqli->prepare("SELECT s.*, c.course_name FROM students s LEFT JOIN courses c ON s.course_id = c.course_id WHERE student_id=?");
    $stmt->bind_param('i', $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $student = $result->fetch_assoc();
    $stmt->close();
    return $student;
}


// header.php - Enhanced
require_once 'config.php';
require_once 'functions.php';
$mysqli = db_connect();
$current_nav = basename($_SERVER['PHP_SELF'], ".php");

?>