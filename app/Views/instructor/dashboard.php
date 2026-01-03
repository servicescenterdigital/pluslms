<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Instructor Dashboard - SchoolDream+</title>
    <link rel="stylesheet" href="<?= url('public/css/style.css') ?>">
</head>
<body>
    <?php include __DIR__ . '/../shared/navbar.php'; ?>
    
    <?php if (flash('success')): ?>
        <div class="alert alert-success"><?= flash('success') ?></div>
    <?php endif; ?>
    
    <div class="dashboard-container">
        <aside class="sidebar">
            <h3>Instructor Portal</h3>
            <ul class="sidebar-menu">
                <li class="active"><a href="<?= url('/instructor/dashboard') ?>">Dashboard</a></li>
                <li><a href="<?= url('/instructor/courses') ?>">My Courses</a></li>
                <li><a href="<?= url('/instructor/courses/create') ?>">Create Course</a></li>
                <li><a href="<?= url('/student/profile') ?>">Profile</a></li>
            </ul>
        </aside>
        
        <main class="dashboard-content">
            <h1>Welcome, <?= htmlspecialchars(auth()['name']) ?>!</h1>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <h3><?= $stats['total_courses'] ?></h3>
                    <p>Total Courses</p>
                </div>
                <div class="stat-card">
                    <h3><?= $stats['published_courses'] ?></h3>
                    <p>Published</p>
                </div>
                <div class="stat-card">
                    <h3><?= $stats['pending_courses'] ?></h3>
                    <p>Pending Approval</p>
                </div>
                <div class="stat-card">
                    <h3><?= $stats['total_students'] ?></h3>
                    <p>Total Students</p>
                </div>
            </div>
            
            <section class="dashboard-section">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem;">
                    <h2>My Courses</h2>
                    <a href="<?= url('/instructor/courses/create') ?>" class="btn-primary">+ Create New Course</a>
                </div>
                
                <?php if (empty($courses)): ?>
                    <p style="text-align: center; padding: 3rem; color: #6B7280;">
                        You haven't created any courses yet. <a href="<?= url('/instructor/courses/create') ?>" style="color: #4F46E5;">Create your first course</a> to get started!
                    </p>
                <?php else: ?>
                    <table style="width: 100%; border-collapse: collapse;">
                        <thead>
                            <tr style="background: #F9FAFB; text-align: left;">
                                <th style="padding: 1rem;">Course</th>
                                <th style="padding: 1rem;">Status</th>
                                <th style="padding: 1rem;">Students</th>
                                <th style="padding: 1rem;">Rating</th>
                                <th style="padding: 1rem;">Price</th>
                                <th style="padding: 1rem;">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($courses as $course): ?>
                                <tr style="border-bottom: 1px solid #E5E7EB;">
                                    <td style="padding: 1rem;">
                                        <strong><?= htmlspecialchars($course['title']) ?></strong>
                                        <br>
                                        <small style="color: #6B7280;"><?= htmlspecialchars($course['category']) ?></small>
                                    </td>
                                    <td style="padding: 1rem;">
                                        <?php
                                        $statusColors = [
                                            'published' => '#10B981',
                                            'pending' => '#F59E0B',
                                            'rejected' => '#EF4444',
                                            'draft' => '#6B7280'
                                        ];
                                        $color = $statusColors[$course['status']] ?? '#6B7280';
                                        ?>
                                        <span style="background: <?= $color ?>; color: white; padding: 0.25rem 0.75rem; border-radius: 0.25rem; font-size: 0.85rem;">
                                            <?= ucfirst($course['status']) ?>
                                        </span>
                                    </td>
                                    <td style="padding: 1rem;"><?= $course['enrollment_count'] ?? 0 ?></td>
                                    <td style="padding: 1rem;">
                                        <?php if (isset($course['average_rating']) && $course['average_rating']): ?>
                                            ⭐ <?= number_format($course['average_rating'], 1) ?>
                                        <?php else: ?>
                                            <span style="color: #6B7280;">No ratings</span>
                                        <?php endif; ?>
                                    </td>
                                    <td style="padding: 1rem;">
                                        <?= $course['price'] > 0 ? number_format($course['price']) . ' RWF' : 'FREE' ?>
                                    </td>
                                    <td style="padding: 1rem;">
                                        <a href="<?= url('/instructor/courses/' . $course['id'] . '/edit') ?>" class="btn-primary btn-small">
                                            Edit
                                        </a>
                                        <?php if (($course['enrollment_count'] ?? 0) > 0): ?>
                                            <a href="<?= url('/instructor/courses/' . $course['id'] . '/students') ?>" class="btn-outline btn-small">
                                                Students
                                            </a>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
            </section>
        </main>
    </div>
    
    <?php include __DIR__ . '/../shared/footer.php'; ?>
    <script src="<?= url('public/js/main.js') ?>"></script>
</body>
</html>
