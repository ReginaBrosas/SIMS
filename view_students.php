<?php
require_once 'header.php';
require_once 'db.php';

$mysqli = db_connect();

$search_query = trim($_GET['search'] ?? '');
$page = max(1, intval($_GET['page'] ?? 1));
$limit = 5;
$offset = ($page - 1) * $limit;
$params = [];

$where = '';
if ($search_query !== '') {
    $where = "WHERE (s.full_name LIKE CONCAT('%', ?, '%') OR c.course_name LIKE CONCAT('%', ?, '%'))";
    $params[] = $search_query;
    $params[] = $search_query;
}

// Count total
$count_sql = "SELECT COUNT(*) AS total FROM students s LEFT JOIN courses c ON s.course = c.course_code $where";
$count_stmt = $mysqli->prepare($count_sql);
if ($params) $count_stmt->bind_param(str_repeat('s', count($params)), ...$params);
$count_stmt->execute();
$total_rows = $count_stmt->get_result()->fetch_assoc()['total'];
$total_pages = ceil($total_rows / $limit);

// Fetch students
$sql = "SELECT s.student_id, s.full_name, s.email_address, s.contact_no, s.year_level, s.course, c.course_name 
        FROM students s 
        LEFT JOIN courses c ON s.course = c.course_code 
        $where
        ORDER BY s.student_id ASC LIMIT ? OFFSET ?";

$stmt = $mysqli->prepare($sql);
if ($params) {
    $types = str_repeat('s', count($params)) . 'ii';
$all_params = array_merge($params, [$limit, $offset]);
$stmt->bind_param($types, ...$all_params);

} else {
    $stmt->bind_param('ii', $limit, $offset);
}
$stmt->execute();
$result = $stmt->get_result();

$msg = $_GET['msg'] ?? '';
$update = $_GET['update'] ?? '';
?>

<main class="container mt-5 mb-5">
    <div class="row justify-content-center">
        <div class="col-lg-12">
            <div class="card shadow-lg border-0 rounded-4 overflow-hidden">
                <div class="card-header text-white text-center py-4" style="background: linear-gradient(135deg, #1e3c72, #2a5298);">
                    <h3 class="mb-0 fw-bold"><i class="fas fa-users me-2"></i>Student Records</h3>
                </div>
                <div class="card-body bg-white px-5 py-4">

                    <?php if ($msg === 'deleted'): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>Student record successfully deleted.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php elseif ($update === 'success'): ?>
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>Student record updated successfully.
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <form method="GET" class="mb-4">
                        <div class="input-group">
                            <input type="search" class="form-control form-control-lg rounded-start-pill" name="search"
                                   placeholder="Search students by name or course" value="<?= htmlspecialchars($search_query) ?>">
                            <button class="btn btn-primary btn-lg rounded-end-pill" type="submit">
                                <i class="fas fa-search me-1"></i>Search
                            </button>
                        </div>
                    </form>

                    <?php if ($result->num_rows === 0): ?>
                        <div class="alert alert-info text-center">
                            <i class="fas fa-info-circle me-2"></i>No student records found.
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped table-hover align-middle shadow-sm">
                                <thead class="table-dark text-center">
                                    <tr>
                                        <th>Student ID</th>
                                        <th>Full Name</th>
                                        <th>Email</th>
                                        <th>Phone</th>
                                        <th>Year Level</th>
                                        <th>Course</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody class="text-center">
                                    <?php while ($row = $result->fetch_assoc()): ?>
                                        <tr>
                                            <td><?= htmlspecialchars($row['student_id']) ?></td>
                                            <td class="text-start"><?= htmlspecialchars($row['full_name']) ?></td>
                                            <td><?= htmlspecialchars($row['email_address']) ?></td>
                                            <td><?= htmlspecialchars($row['contact_no']) ?></td>
                                            <td><?= htmlspecialchars($row['year_level']) ?></td>
                                            <td><?= htmlspecialchars($row['course_name'] ?? '-') ?></td>
                                            <td>
                                                <a href="edit_student.php?id=<?= urlencode($row['student_id']) ?>" class="btn btn-sm btn-outline-primary rounded-pill px-3 me-1">
                                                    <i class="fas fa-edit"></i> Edit
                                                </a>
                                                <a href="delete_student.php?id=<?= urlencode($row['student_id']) ?>" class="btn btn-sm btn-outline-danger rounded-pill px-3"
                                                   onclick="return confirm('Are you sure you want to delete this student?');">
                                                    <i class="fas fa-trash-alt"></i> Delete
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
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
