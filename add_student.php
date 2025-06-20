<?php
require_once 'header.php';
require_once 'db.php';
$mysqli = db_connect();

// Fetch courses
$courses_result = $mysqli->query("SELECT course_code, course_name FROM courses");

// Initialize messages
$success = '';
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $phone = trim($_POST['phone'] ?? '');
    $course = trim($_POST['course'] ?? '');
    $year_level = trim($_POST['year_level'] ?? '');

    if ($name && $email && $course && $year_level) {
        $check_stmt = $mysqli->prepare("SELECT * FROM students WHERE full_name = ? AND email_address = ?");
        $check_stmt->bind_param("ss", $name, $email);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows > 0) {
            $error = "Student with the same name and email already exists.";
        } else {
            $new_id_stmt = $mysqli->query("SELECT MAX(CAST(student_id AS UNSIGNED)) + 1 AS next_id FROM students");
            $row = $new_id_stmt->fetch_assoc();
            $new_id = str_pad($row['next_id'] ?? 1, 9, '0', STR_PAD_LEFT);

            $stmt = $mysqli->prepare("INSERT INTO students (student_id, full_name, email_address, contact_no, course, year_level) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("ssssss", $new_id, $name, $email, $phone, $course, $year_level);

            if ($stmt->execute()) {
                $success = "Student successfully added!";
            } else {
                $error = "Failed to add student. Please try again.";
            }
        }
    } else {
        $error = "Please fill out all required fields.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Student</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS & Font Awesome -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f2f5;
        }
        .navbar {
            background: linear-gradient(to right, #1e3c72, #2a5298);
        }
    </style>
</head>
<body>

<!-- ✅ FORM -->
<main class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-7">
            <div class="card shadow border-0 rounded-4 overflow-hidden">
                <div class="card-header text-white text-center py-4" style="background: linear-gradient(135deg, #1e3c72, #2a5298);">
                    <h3 class="mb-0 fw-bold text-white"><i class="fas fa-user-plus me-2"></i>Add New Student</h3>
                </div>
                <div class="card-body p-5 bg-white">
                    <?php if ($success): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i><?= htmlspecialchars($success) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php elseif ($error): ?>
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i><?= htmlspecialchars($error) ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form method="POST" class="needs-validation" novalidate>
                        <div class="mb-4">
                            <label for="name" class="form-label fw-semibold">Full Name</label>
                            <input type="text" id="name" name="name" class="form-control form-control-lg rounded-3" placeholder="First Name   Middle Initial   Last Name " required />
                            <div class="invalid-feedback">Full name is required.</div>
                        </div>
                        <div class="mb-4">
                            <label for="email" class="form-label fw-semibold">Email Address</label>
                            <input type="email" id="email" name="email" class="form-control form-control-lg rounded-3" placeholder="e.g., juan@example.com" required />
                            <div class="invalid-feedback">Valid email is required.</div>
                        </div>
                        <div class="mb-4">
                            <label for="phone" class="form-label fw-semibold">Phone Number</label>
                            <input type="text" id="phone" name="phone" class="form-control form-control-lg rounded-3" placeholder="e.g., 09123456789" />
                        </div>  
                        <div class="mb-4">
                            <label for="course" class="form-label fw-semibold">Course</label>
                            <select id="course" name="course" class="form-select form-select-lg rounded-3" required>
                                <option value="">-- Select Course --</option>
                                <?php while ($course = $courses_result->fetch_assoc()): ?>
                                    <option value="<?= $course['course_code'] ?>"><?= htmlspecialchars($course['course_name']) ?></option>
                                <?php endwhile; ?>
                            </select>
                            <div class="invalid-feedback">Please select a course.</div>
                        </div>
                        <div class="mb-4">
                            <label for="year_level" class="form-label fw-semibold">Year Level</label>
                            <select id="year_level" name="year_level" class="form-select form-select-lg rounded-3" required>
                                <option value="">-- Select Year Level --</option>
                                <option value="1st Year">1st Year</option>
                                <option value="2nd Year">2nd Year</option>
                                <option value="3rd Year">3rd Year</option>
                                <option value="4th Year">4th Year</option>
                            </select>
                            <div class="invalid-feedback">Please select a year level.</div>
                        </div>
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg rounded-pill shadow-sm">
                                <i class="fas fa-plus me-2"></i>Add Student
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

</body>
</html>
