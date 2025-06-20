<!DOCTYPE html>
<html lang="en">
<head>

<style>
  body {
    background-color: #fff0f5;
    font-family: 'Segoe UI', sans-serif;
  }
  header, .navbar {
    background: linear-gradient(to right, #ffe6f0, #fff0f5);
    box-shadow: 0 2px 5px rgba(0,0,0,0.05);
  }
  .btn-primary, .btn-pink {
    background-color: #ffb6c1;
    border-color: #ffb6c1;
    color: white;
  }
  .btn-primary:hover, .btn-pink:hover {
    background-color: #ff9eb2;
    border-color: #ff9eb2;
  }
  .card, .modal-content {
    background-color: #fffafd;
    border: 1px solid #ffe6f0;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
  }
  .form-control:focus {
    border-color: #ffb6c1;
    box-shadow: 0 0 0 0.2rem rgba(255, 182, 193, 0.25);
  }
</style>

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
