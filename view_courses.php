<?php
require_once 'header.php';
require_once 'db.php';
$mysqli = db_connect();

// Pagination setup
$limit = 5;
$page = max(1, intval($_GET['page'] ?? 1));
$offset = ($page - 1) * $limit;

// Search
$search_query = trim($_GET['search'] ?? '');
$search_param = '%' . $search_query . '%';

// Count total results
if ($search_query !== '') {
    $count_stmt = $mysqli->prepare("SELECT COUNT(*) AS total FROM courses WHERE course_name LIKE ?");
    $count_stmt->bind_param('s', $search_param);
} else {
    $count_stmt = $mysqli->prepare("SELECT COUNT(*) AS total FROM courses");
}
$count_stmt->execute();
$count_result = $count_stmt->get_result();
$total_rows = $count_result ? $count_result->fetch_assoc()['total'] : 0;
$total_pages = ceil($total_rows / $limit);

// Fetch paginated results
$sql = "SELECT course_code, course_name FROM courses";
$sql .= $search_query !== '' ? " WHERE course_name LIKE ?" : "";
$sql .= " ORDER BY course_name ASC LIMIT ? OFFSET ?";
$stmt = $mysqli->prepare($sql);

if ($search_query !== '') {
    $stmt->bind_param('sii', $search_param, $limit, $offset);
} else {
    $stmt->bind_param('ii', $limit, $offset);
}

$stmt->execute();
$result = $stmt->get_result();
?>

<main class="container mt-5 mb-5">
    <?php if (!empty($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <?= htmlspecialchars($_SESSION['success']) ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
        <?php unset($_SESSION['success']); ?>
    <?php endif; ?>

    <div class="row justify-content-center">
        <div class="col-lg-10">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="card-header text-white text-center py-4" style="background: linear-gradient(135deg, #1e3c72, #2a5298);">
                    <h3 class="mb-0 fw-bold"><i class="fas fa-list me-2"></i>Course List</h3>
                </div>

                <!-- Top Controls -->
                <div class="bg-light p-4 border-bottom">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-8">
                            <form method="GET">
                                <div class="input-group">
                                    <input type="search" class="form-control form-control-lg rounded-start-pill" name="search"
                                           placeholder="Search courses by name"
                                           value="<?= htmlspecialchars($search_query) ?>">
                                    <button class="btn btn-primary btn-lg rounded-end-pill" type="submit">
                                        <i class="fas fa-search me-1"></i>Search
                                    </button>
                                    <?php if ($search_query): ?>
                                        <a href="view_courses.php" class="btn btn-secondary btn-lg ms-2 rounded-pill">
                                            <i class="fas fa-times"></i>
                                        </a>
                                    <?php endif; ?>
                                </div>
                            </form>
                        </div>
                        <div class="col-md-4 text-md-end">
                            <a href="add_course.php" class="btn btn-success btn-lg rounded-pill w-100 w-md-auto">
                                <i class="fas fa-plus me-2"></i>Add New Course
                            </a>
                        </div>
                    </div>
                </div>

                <div class="card-body bg-white px-5 py-4">
                    <?php if ($result->num_rows === 0): ?>
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i>No courses found.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle shadow-sm">
                                <thead class="table-dark text-center">
                                    <tr>
                                        <th class="text-start">Course Code</th>
                                        <th class="text-start">Course Name</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($row = $result->fetch_assoc()): ?>
                                        <tr class="text-center">
                                            <td class="text-start"><?= htmlspecialchars($row['course_code']) ?></td>
                                            <td class="text-start"><?= htmlspecialchars($row['course_name']) ?></td>
                                            <td>
                                                <a href="edit_course.php?id=<?= urlencode($row['course_code']) ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3 me-1">
                                                    <i class="fas fa-edit me-1"></i>Edit
                                                </a>
                                                <a href="delete_course.php?id=<?= urlencode($row['course_code']) ?>"
                                                   onclick="return confirm('Are you sure you want to delete this course?');"
                                                   class="btn btn-sm btn-outline-danger rounded-pill px-3">
                                                    <i class="fas fa-trash me-1"></i>Delete
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination Controls -->
                        <nav class="mt-4">
                            <ul class="pagination justify-content-center">
                                <?php if ($page > 1): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?search=<?= urlencode($search_query) ?>&page=<?= $page - 1 ?>">Previous</a>
                                    </li>
                                <?php endif; ?>

                                <?php for ($i = 1; $i <= $total_pages; $i++): ?>
                                    <li class="page-item <?= ($i === $page ? 'active' : '') ?>">
                                        <a class="page-link" href="?search=<?= urlencode($search_query) ?>&page=<?= $i ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>

                                <?php if ($page < $total_pages): ?>
                                    <li class="page-item">
                                        <a class="page-link" href="?search=<?= urlencode($search_query) ?>&page=<?= $page + 1 ?>">Next</a>
                                    </li>
                                <?php endif; ?>
                            </ul>
                        </nav>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</main>
