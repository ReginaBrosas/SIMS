<?php
require_once 'config.php';
require_once 'functions.php';
require_once 'header.php';
$mysqli = db_connect();

$success = '';
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $course_name = sanitize($_POST['course_name'] ?? '');
    $course_code = sanitize($_POST['course_code'] ?? '');

    if ($course_name && preg_match('/^[A-Z0-9]{2,10}$/i', $course_code)) {
        // Check if course_code already exists
        $check = $mysqli->prepare("SELECT course_code FROM courses WHERE course_code = ?");
        $check->bind_param('s', $course_code);
        $check->execute();
        $check_result = $check->get_result();

        if ($check_result->num_rows > 0) {
            $error = "⚠️ Course code already exists.";
        } else {
            $stmt = $mysqli->prepare("INSERT INTO courses (course_code, course_name) VALUES (?, ?)");
            $stmt->bind_param('ss', $course_code, $course_name);
            if ($stmt->execute()) {
                header('Location: view_courses.php');
                exit;
            } else {
                $error = "❌ Failed to add course. Please try again.";
            }
            $stmt->close();
        }
        $check->close();
    } else {
        $error = "⚠️ Please enter a valid course name and course code (e.g., IT101).";
    }
}
?>

<main class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow border-0 rounded-4 overflow-hidden">
                <div class="card-header text-white text-center py-4" style="background: linear-gradient(135deg, #1e3c72, #2a5298);">
                    <h3 class="mb-0 fw-bold text-white"><i class="fas fa-book-open me-2"></i>Add New Course</h3>
                </div>
                <div class="card-body p-5 bg-white">
                    <?php if ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <?= htmlspecialchars($error) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" class="needs-validation" novalidate>
                        <div class="mb-4">
                            <label for="course_code" class="form-label fw-semibold">Course Code</label>
                            <input type="text" id="course_code" name="course_code" class="form-control form-control-lg rounded-3" pattern="[A-Za-z0-9]{2,10}" maxlength="10" required placeholder="e.g., BSIT" />
                            <div class="invalid-feedback">Please enter a valid course code.</div>
                        </div>

                        <div class="mb-4">
                            <label for="course_name" class="form-label fw-semibold">Course Name</label>
                            <input type="text" id="course_name" name="course_name" class="form-control form-control-lg rounded-3" required placeholder="e.g., Bachelor of Science in Information Technology" />
                            <div class="invalid-feedback">Course name is required.</div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success btn-lg rounded-pill shadow-sm">
                                <i class="fas fa-plus-circle me-2"></i>Add Course
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<script>
(() => {
    'use strict';
    const forms = document.querySelectorAll('.needs-validation');
    Array.from(forms).forEach(form => {
        form.addEventListener('submit', event => {
            if (!form.checkValidity()) {
                event.preventDefault();
                event.stopPropagation();
            }
            form.classList.add('was-validated');
        }, false);
    });
})();
</script>
