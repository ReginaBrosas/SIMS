<?php
require_once 'config.php';
require_once 'functions.php';
require_once 'header.php';
$mysqli = db_connect();

$course_code = $_GET['id'] ?? '';
$course_code = sanitize($course_code);

// Fetch course details
$stmt = $mysqli->prepare("SELECT * FROM courses WHERE course_code = ?");
$stmt->bind_param('s', $course_code);
$stmt->execute();
$result = $stmt->get_result();
$course = $result->fetch_assoc();
$stmt->close();

if (!$course): ?>
    <main class="container mt-5">
        <div class="alert alert-danger text-center fw-semibold rounded-pill py-3 px-4">
            <i class="fas fa-exclamation-triangle me-2"></i> Course not found.
        </div>
    </main>
<?php
    exit;
endif;

// Handle update
$error = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $new_course_name = sanitize($_POST['course_name'] ?? '');
    if ($new_course_name) {
        $update_stmt = $mysqli->prepare("UPDATE courses SET course_name = ? WHERE course_code = ?");
        $update_stmt->bind_param('ss', $new_course_name, $course_code);
        if ($update_stmt->execute()) {
            $_SESSION['success'] = "✅ Course updated successfully.";
            header("Location: view_courses.php");
            exit;
        } else {
            $error = "❌ Failed to update course.";
        }
        $update_stmt->close();
    } else {
        $error = "⚠️ Course name cannot be empty.";
    }
}
?>

<main class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow border-0 rounded-4 overflow-hidden">
                <div class="card-header text-white text-center py-4" style="background: linear-gradient(135deg, #1e3c72, #2a5298);">
                    <h3 class="mb-0 fw-bold"><i class="fas fa-edit me-2"></i>Edit Course</h3>
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
                            <label class="form-label fw-semibold">Course Code</label>
                            <input type="text" class="form-control form-control-lg rounded-3" value="<?= htmlspecialchars($course['course_code']) ?>" readonly>
                        </div>

                        <div class="mb-4">
                            <label for="course_name" class="form-label fw-semibold">Course Name</label>
                            <input type="text" id="course_name" name="course_name" class="form-control form-control-lg rounded-3" value="<?= htmlspecialchars($course['course_name']) ?>" required />
                            <div class="invalid-feedback">Course name is required.</div>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow-sm">
                                <i class="fas fa-save me-2"></i>Save Changes
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
