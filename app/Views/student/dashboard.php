<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Student Dashboard - SchoolDream+</title>
    <link rel="stylesheet" href="<?= url('public/css/style.css') ?>">
</head>
<body>
    <?php include __DIR__ . '/../shared/navbar.php'; ?>
    
    <?php if (flash('success')): ?>
        <div class="alert alert-success"><?= flash('success') ?></div>
    <?php endif; ?>
    
    <div class="dashboard-container">
        <aside class="sidebar">
            <h3>Student Portal</h3>
            <ul class="sidebar-menu">
                <li class="active"><a href="<?= url('/student/dashboard') ?>">Dashboard</a></li>
                <li><a href="<?= url('/student/courses') ?>">My Courses</a></li>
                <li><a href="<?= url('/courses') ?>">Browse Courses</a></li>
                <li><a href="<?= url('/student/certificates') ?>">Certificates</a></li>
                <li><a href="<?= url('/student/profile') ?>">Profile</a></li>
            </ul>
        </aside>
        
        <main class="dashboard-content">
            <h1>Welcome, <?= htmlspecialchars(auth()['name']) ?>!</h1>
            
            <div class="stats-grid">
                <div class="stat-card">
                    <h3><?= count($enrollments) ?></h3>
                    <p>Enrolled Courses</p>
                </div>
                <div class="stat-card">
                    <h3><?= count(array_filter($enrollments, fn($e) => $e['status'] === 'completed')) ?></h3>
                    <p>Completed Courses</p>
                </div>
                <div class="stat-card">
                    <h3><?= count($certificates) ?></h3>
                    <p>Certificates Earned</p>
                </div>
            </div>
            
            <section class="dashboard-section">
                <h2>Continue Learning</h2>
                <?php if (!empty($enrollments)): ?>
                    <div class="courses-grid">
                        <?php foreach (array_slice($enrollments, 0, 4) as $enrollment): ?>
                            <div class="course-card">
                                <div class="course-image">
                                    <img src="<?= $enrollment['thumbnail'] ?? url('public/images/course-placeholder.jpg') ?>" alt="<?= htmlspecialchars($enrollment['title']) ?>">
                                </div>
                                <div class="course-content">
                                    <h3><?= htmlspecialchars($enrollment['title']) ?></h3>
                                    <p class="course-instructor">By <?= htmlspecialchars($enrollment['instructor_name']) ?></p>
                                    
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: <?= $enrollment['progress_percentage'] ?? 0 ?>%"></div>
                                    </div>
                                    <p class="progress-text"><?= round($enrollment['progress_percentage'] ?? 0) ?>% Complete</p>
                                    
                                    <a href="<?= url('/student/course/' . $enrollment['course_id']) ?>" class="btn-primary btn-small">Continue</a>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p>You haven't enrolled in any courses yet. <a href="<?= url('/courses') ?>">Browse courses</a> to get started!</p>
                <?php endif; ?>
            </section>
            
            <?php if (!empty($recommendations)): ?>
                <section class="dashboard-section">
                    <h2>Recommended for You</h2>
                    <div class="courses-grid">
                        <?php foreach (array_slice($recommendations, 0, 3) as $course): ?>
                            <div class="course-card">
                                <div class="course-image">
                                    <img src="<?= $course['thumbnail'] ?? url('public/images/course-placeholder.jpg') ?>" alt="<?= htmlspecialchars($course['title']) ?>">
                                    <span class="course-level"><?= ucfirst($course['level']) ?></span>
                                </div>
                                <div class="course-content">
                                    <h3><?= htmlspecialchars($course['title']) ?></h3>
                                    <p class="course-instructor">By <?= htmlspecialchars($course['instructor_name']) ?></p>
                                    <div class="course-meta">
                                        <span>⏱ <?= htmlspecialchars($course['duration']) ?></span>
                                        <span>👥 <?= $course['enrollment_count'] ?> students</span>
                                    </div>
                                    <div class="course-footer">
                                        <span class="course-price">
                                            <?php if ($course['price'] > 0): ?>
                                                <?= number_format($course['price']) ?> RWF
                                            <?php else: ?>
                                                <span class="free-badge">FREE</span>
                                            <?php endif; ?>
                                        </span>
                                        <a href="<?= url('/courses/' . $course['id']) ?>" class="btn-outline btn-small">View</a>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </section>
            <?php endif; ?>
            
            <?php if (!empty($certificates)): ?>
                <section class="dashboard-section">
                    <h2>Recent Certificates</h2>
                    <div class="certificates-list">
                        <?php foreach (array_slice($certificates, 0, 3) as $cert): ?>
                            <div class="certificate-item">
                                <div class="certificate-icon">🎓</div>
                                <div class="certificate-info">
                                    <h4><?= htmlspecialchars($cert['course_title']) ?></h4>
                                    <p>Certificate #<?= htmlspecialchars($cert['certificate_number']) ?></p>
                                    <p class="cert-date">Issued on <?= formatDate($cert['issued_at'], 'M d, Y') ?></p>
                                </div>
                                <a href="<?= url('/student/certificate/' . $cert['id'] . '/download') ?>" class="btn-outline btn-small">Download</a>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <a href="<?= url('/student/certificates') ?>" class="btn-link">View All Certificates →</a>
                </section>
            <?php endif; ?>
        </main>
    </div>
    
    <?php include __DIR__ . '/../shared/footer.php'; ?>
    <script src="<?= url('public/js/main.js') ?>"></script>
</body>
</html>
