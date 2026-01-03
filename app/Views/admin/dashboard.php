<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - SchoolDream+</title>
    <link rel="stylesheet" href="<?= url('public/css/style.css') ?>">
</head>
<body>
    <?php include __DIR__ . '/../shared/navbar.php'; ?>
    
    <?php if (flash('success')): ?>
        <div class="alert alert-success"><?= flash('success') ?></div>
    <?php endif; ?>
    
    <div class="dashboard-container">
        <aside class="sidebar">
            <h3>Admin Portal</h3>
            <ul class="sidebar-menu">
                <li class="active"><a href="<?= url('/admin/dashboard') ?>">Dashboard</a></li>
                <li><a href="<?= url('/admin/users') ?>">Users</a></li>
                <li><a href="<?= url('/admin/instructors') ?>">Instructors</a></li>
                <li><a href="<?= url('/admin/courses') ?>">Courses</a></li>
                <li><a href="<?= url('/admin/enrollments') ?>">Enrollments</a></li>
                <li><a href="<?= url('/admin/certificates') ?>">Certificates</a></li>
                <li><a href="<?= url('/admin/reports') ?>">Reports</a></li>
            </ul>
        </aside>
        
        <main class="dashboard-content">
            <h1>Admin Dashboard</h1>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <h3><?= $userStats['total_students'] ?? 0 ?></h3>
                    <p>Total Students</p>
                </div>
                <div class="stat-card">
                    <h3><?= $userStats['total_instructors'] ?? 0 ?></h3>
                    <p>Total Instructors</p>
                </div>
                <div class="stat-card">
                    <h3><?= $courseStats['published_courses'] ?? 0 ?></h3>
                    <p>Published Courses</p>
                </div>
                <div class="stat-card">
                    <h3><?= $enrollmentStats['total_enrollments'] ?? 0 ?></h3>
                    <p>Total Enrollments</p>
                </div>
            </div>
            
            <?php if (!empty($pendingInstructors)): ?>
                <section class="dashboard-section">
                    <h2>Pending Instructor Approvals (<?= count($pendingInstructors) ?>)</h2>
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #F9FAFB; text-align: left;">
                                <th style="padding: 1rem;">Name</th>
                                <th style="padding: 1rem;">Email</th>
                                <th style="padding: 1rem;">Registered</th>
                                <th style="padding: 1rem;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pendingInstructors as $instructor): ?>
                                <tr style="border-bottom: 1px solid #E5E7EB;">
                                    <td style="padding: 1rem;"><?= htmlspecialchars($instructor['name']) ?></td>
                                    <td style="padding: 1rem;"><?= htmlspecialchars($instructor['email']) ?></td>
                                    <td style="padding: 1rem;"><?= formatDate($instructor['created_at'], 'M d, Y') ?></td>
                                    <td style="padding: 1rem;">
                                        <form action="<?= url('/admin/instructors/' . $instructor['id'] . '/approve') ?>" method="POST" style="display: inline;">
                                            <button type="submit" class="btn-primary btn-small">Approve</button>
                                        </form>
                                        <form action="<?= url('/admin/instructors/' . $instructor['id'] . '/reject') ?>" method="POST" style="display: inline;">
                                            <button type="submit" class="btn-danger btn-small" onclick="return confirm('Reject this instructor?')">Reject</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </section>
            <?php endif; ?>
            
            <?php if (!empty($pendingCourses)): ?>
                <section class="dashboard-section">
                    <h2>Pending Course Approvals (<?= count($pendingCourses) ?>)</h2>
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #F9FAFB; text-align: left;">
                                <th style="padding: 1rem;">Course Title</th>
                                <th style="padding: 1rem;">Instructor</th>
                                <th style="padding: 1rem;">Category</th>
                                <th style="padding: 1rem;">Price</th>
                                <th style="padding: 1rem;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pendingCourses as $course): ?>
                                <tr style="border-bottom: 1px solid #E5E7EB;">
                                    <td style="padding: 1rem;"><?= htmlspecialchars($course['title']) ?></td>
                                    <td style="padding: 1rem;">
                                        <?php
                                        $userModel = new \App\Models\User();
                                        $instructor = $userModel->find($course['instructor_id']);
                                        echo htmlspecialchars($instructor['name'] ?? 'Unknown');
                                        ?>
                                    </td>
                                    <td style="padding: 1rem;"><?= htmlspecialchars($course['category']) ?></td>
                                    <td style="padding: 1rem;">
                                        <?= $course['price'] > 0 ? number_format($course['price']) . ' RWF' : 'FREE' ?>
                                    </td>
                                    <td style="padding: 1rem;">
                                        <form action="<?= url('/admin/courses/' . $course['id'] . '/approve') ?>" method="POST" style="display: inline;">
                                            <button type="submit" class="btn-primary btn-small">Approve</button>
                                        </form>
                                        <form action="<?= url('/admin/courses/' . $course['id'] . '/reject') ?>" method="POST" style="display: inline;">
                                            <button type="submit" class="btn-danger btn-small" onclick="return confirm('Reject this course?')">Reject</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </section>
            <?php endif; ?>
            
            <section class="dashboard-section">
                <h2>Revenue Overview</h2>
                <div class="stats-grid">
                    <div class="stat-card">
                        <h3><?= number_format($revenueStats['total_revenue'] ?? 0) ?> RWF</h3>
                        <p>Total Revenue</p>
                    </div>
                    <div class="stat-card">
                        <h3><?= $revenueStats['paid_enrollments'] ?? 0 ?></h3>
                        <p>Paid Enrollments</p>
                    </div>
                    <div class="stat-card">
                        <h3><?= number_format($revenueStats['average_price'] ?? 0) ?> RWF</h3>
                        <p>Average Course Price</p>
                    </div>
                </div>
            </section>
        </main>
    </div>
    
    <?php include __DIR__ . '/../shared/footer.php'; ?>
    <script src="<?= url('public/js/main.js') ?>"></script>
</body>
</html>
