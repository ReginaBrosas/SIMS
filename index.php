<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Student Information Management System</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"/>
  <style>
    body {
      font-family: 'Inter', sans-serif;
      background: #f4f6fc;
      min-height: 100vh;
    }
    nav.navbar {
      background: #384269;
    }
    nav.navbar .nav-link {
      color: #ffffff;
      font-weight: 600;
      padding: 0.75rem 1.2rem;
      transition: background 0.3s ease;
    }
    nav.navbar .nav-link.active,
    nav.navbar .nav-link:hover {
      background-color: rgba(255, 255, 255, 0.2);
      border-radius: 0.5rem;
    }
    .card-custom {
      border: none;
      border-radius: 1.5rem;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.08);
      background: #fff;
    }
    .btn-custom {
      background-color: #667eea;
      color: #fff;
      border: none;
    }
    .btn-custom:hover {
      background-color: #5a67d8;
    }
    footer {
      background: #384269;
      color: white;
      padding: 1rem;
      text-align: center;
      font-size: 0.9rem;
      margin-top: auto;
    }
    .table thead {
      background-color: #667eea;
      color: white;
    }
    .table-hover tbody tr:hover {
      background-color: #f1f4ff;
    }
  </style>
</head>
<body>
  <nav class="navbar navbar-expand-lg navbar-dark sticky-top">
    <div class="container-fluid">
      <a class="navbar-brand fw-bold" href="#">SIMS</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav me-auto mb-2 mb-lg-0">
          <li class="nav-item">
            <a class="nav-link active" href="index.php"><i class="fas fa-home"></i> Home</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="add_student.php"><i class="fas fa-user-plus"></i> Add Student</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="view_students.php"><i class="fas fa-users"></i> View Students</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="add_course.php"><i class="fas fa-book"></i> Add Course</a>
          </li>
          <li class="nav-item">
            <a class="nav-link" href="view_courses.php"><i class="fas fa-list"></i> View Courses</a>
          </li>
        </ul>
        <a href="logout.php" class="btn btn-outline-light ms-auto"><i class="fas fa-sign-out-alt"></i> Logout</a>
      </div>
    </div>
  </nav>

  <main class="container py-5">
    <div class="text-center mb-5">
      <h1 class="fw-bold text-primary">Student Information Management System</h1>
      <p class="lead">Easily manage student records and courses with a clean and user-friendly interface.</p>
    </div>
    <div class="row g-4">
      <div class="col-md-6 col-lg-3">
        <div class="card card-custom text-center py-4">
          <i class="fas fa-user-graduate fa-3x text-primary mb-3"></i>
          <h5>Add Student</h5>
          <p class="text-muted">Register new students into the system.</p>
          <a href="add_student.php" class="btn btn-custom">Add</a>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="card card-custom text-center py-4">
          <i class="fas fa-users fa-3x text-primary mb-3"></i>
          <h5>View Students</h5>
          <p class="text-muted">Browse and manage enrolled students.</p>
          <a href="view_students.php" class="btn btn-custom">View</a>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="card card-custom text-center py-4">
          <i class="fas fa-book fa-3x text-primary mb-3"></i>
          <h5>Add Course</h5>
          <p class="text-muted">Create new course offerings.</p>
          <a href="add_course.php" class="btn btn-custom">Add</a>
        </div>
      </div>
      <div class="col-md-6 col-lg-3">
        <div class="card card-custom text-center py-4">
          <i class="fas fa-clipboard-list fa-3x text-primary mb-3"></i>
          <h5>View Courses</h5>
          <p class="text-muted">Manage existing course information.</p>
          <a href="view_courses.php" class="btn btn-custom">View</a>
        </div>
      </div>
    </div>
  </main>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
