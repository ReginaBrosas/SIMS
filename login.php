<?php
require_once 'config.php';
require_once 'functions.php';

$login_error = '';
if (isset($_POST['login_submit'])) {
    $username = trim($_POST['username'] ?? 'admin');
    $password = trim($_POST['password'] ?? 'admin1234');
    if (login($username, $password)) {
        header("Location: index.php");
        exit;
    } else {
        $login_error = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>SIMS - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css2?family=Inter&display=swap" rel="stylesheet" />
    <style>
        body {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            background: linear-gradient(135deg, #5e60ce, #7400b8);
            font-family: 'Inter', sans-serif;
            margin: 0;
        }
        .login-container {
            background: #fff;
            padding: 2.5rem;
            border-radius: 16px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 100%;
            max-width: 400px;
        }
        .login-container h2 {
            color: #5e60ce;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        .form-label {
            font-weight: 500;
        }
        .btn-primary {
            background-color: #5e60ce;
            border-color: #5e60ce;
        }
        .btn-primary:hover {
            background-color: #4e4fc0;
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Welcome to SIMS</h2>
        <?php if ($login_error): ?>
            <div class="alert alert-danger text-center"><?= htmlspecialchars($login_error) ?></div>
        <?php endif; ?>
        <form method="POST">
            <div class="mb-3">
                <label for="username" class="form-label">Username</label>
                <input type="text" id="username" name="username" class="form-control" required autofocus />
            </div>
            <div class="mb-3">
                <label for="password" class="form-label">Password</label>
                <input type="password" id="password" name="password" class="form-control" required />
            </div>
            <div class="d-grid">
                <button type="submit" name="login_submit" class="btn btn-primary">Login</button>
            </div>
        </form>
    </div>
</body>
</html>