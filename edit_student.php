<?php
require_once 'header.php';
require_once 'db.php';

$mysqli = db_connect();
$errors = [];
$success = '';

$student_id = $_GET['id'] ?? '';
$student_id = trim($student_id);

// Fetch student record
$stmt = $mysqli->prepare("SELECT * FROM students WHERE student_id = ?");
$stmt->bind_param("s", $student_id);
$stmt->execute();
$result = $stmt->get_result();
$student = $result->fetch_assoc();

if (!$student) {
    echo '<div class="alert alert-warning">Student not found.</div>';
    exit;
}

// Fetch course list
$courses = $mysqli->query("SELECT course_code, course_name FROM courses");

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $full_name = trim($_POST['full_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $contact = trim($_POST['contact'] ?? '');
    $year_level = trim($_POST['year_level'] ?? '');
    $course = trim($_POST['course'] ?? '');

    if (!$full_name) $errors[] = "Full name is required.";
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "A valid email is required.";
    if (!$course) $errors[] = "Course is required.";
    if (!$year_level) $errors[] = "Year level is required.";

    // Check for duplicate (excluding current)
    $check = $mysqli->prepare("SELECT * FROM students WHERE full_name = ? AND email_address = ? AND student_id != ?");
    $check->bind_param("sss", $full_name, $email, $student_id);
    $check->execute();
    $dup = $check->get_result();
    if ($dup->num_rows > 0) {
        $errors[] = "A student with the same name and email already exists.";
    }

    if (!$errors) {
        $update = $mysqli->prepare("UPDATE students SET full_name = ?, email_address = ?, contact_no = ?, year_level = ?, course = ? WHERE student_id = ?");
        $update->bind_param("ssssss", $full_name, $email, $contact, $year_level, $course, $student_id);

        if ($update->execute()) {
            header("Location: view_students.php?update=success");
            exit;
        } else {
            $errors[] = "Update failed: " . $mysqli->error;
        }
    }
}
?>

<main class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow border-0 rounded-4 overflow-hidden">
                <div class="card-header bg-primary text-white text-center py-3">
                    <h3 class="mb-0 fw-bold">Edit Student Record</h3>
                </div>
                <div class="card-body p-5 bg-white">
                    <?php if ($errors): ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($errors as $err): ?>
                                    <li><?= htmlspecialchars($err) ?></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST" class="needs-validation" novalidate>
                        <div class="mb-4">
                            <label for="full_name" class="form-label fw-semibold">Full Name *</label>
                            <input type="text" class="form-control form-control-lg rounded-3" id="full_name" name="full_name" required value="<?= htmlspecialchars($_POST['full_name'] ?? $student['full_name']) ?>">
                            <div class="invalid-feedback">Full name is required.</div>
                        </div>
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">Email Address *</label>
                            <input type="email" class="form-control form-control-lg rounded-3" id="email" name="email" required value="<?= htmlspecialchars($_POST['email'] ?? $student['email_address']) ?>">
                            <div class="invalid-feedback">Valid email is required.</div>
                        </div>
                        <div class="mb-4">
                            <label for="contact" class="form-label fw-semibold">Phone</label>
                            <input type="text" class="form-control form-control-lg rounded-3" id="contact" name="contact" value="<?= htmlspecialchars($_POST['contact'] ?? $student['contact_no']) ?>">
                        </div>
                        <div class="mb-4">
                            <label for="year_level" class="form-label fw-semibold">Year Level *</label>
                            <select id="year_level" name="year_level" class="form-select form-select-lg rounded-3" required>
                                <option value="">-- Select Year Level --</option>
                                <?php
                                $levels = ['1st Year', '2nd Year', '3rd Year', '4th Year'];
                                $selected_level = $_POST['year_level'] ?? $student['year_level'];
                                foreach ($levels as $level) {
                                    $selected = $selected_level === $level ? 'selected' : '';
                                    echo "<option value=\"$level\" $selected>$level</option>";
                                }
                                ?>
                            </select>
                            <div class="invalid-feedback">Please select a year level.</div>
                        </div>
                        <div class="mb-4">
                            <label for="course" class="form-label fw-semibold">Course *</label>
                            <select class="form-select form-select-lg rounded-3" id="course" name="course" required>
                                <option value="">-- Select Course --</option>
                                <?php
                                $selected_course = $_POST['course'] ?? $student['course'];
                                while ($c = $courses->fetch_assoc()):
                                    $sel = $selected_course === $c['course_code'] ? 'selected' : '';
                                    echo "<option value=\"{$c['course_code']}\" $sel>" . htmlspecialchars($c['course_name']) . "</option>";
                                endwhile;
                                ?>
                            </select>
                            <div class="invalid-feedback">Please select a course.</div>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill">
                                <i class="fas fa-save me-2"></i>Update Student
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
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
