<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($course['title']) ?> - SchoolDream+</title>
    <link rel="stylesheet" href="<?= url('public/css/style.css') ?>">
</head>
<body>
    <?php include __DIR__ . '/../shared/navbar.php'; ?>
    
    <?php if (flash('success')): ?>
        <div class="alert alert-success"><?= flash('success') ?></div>
    <?php endif; ?>
    
    <div class="container" style="padding: 2rem 0;">
        <h1><?= htmlspecialchars($course['title']) ?></h1>
        <p style="color: #6B7280;">By <?= htmlspecialchars($course['instructor_name']) ?></p>
        
        <div style="background: white; padding: 1.5rem; border-radius: 0.5rem; margin: 2rem 0;">
            <h3>Your Progress</h3>
            <div class="progress-bar" style="height: 20px;">
                <div class="progress-fill" style="width: <?= $progressPercentage ?>%"></div>
            </div>
            <p style="margin-top: 0.5rem;">
                <?= $progress['completed_lessons'] ?> of <?= $progress['total_lessons'] ?> lessons completed (<?= $progressPercentage ?>%)
            </p>
            
            <?php if ($canGetCertificate && $certificate): ?>
                <div style="background: #D1FAE5; padding: 1rem; border-radius: 0.5rem; margin-top: 1rem;">
                    <strong>🎓 Congratulations!</strong> You've completed this course!
                    <br>
                    <a href="<?= url('/student/certificate/' . $certificate['id'] . '/download') ?>" class="btn-primary btn-small" style="margin-top: 0.5rem;">
                        Download Certificate
                    </a>
                </div>
            <?php endif; ?>
        </div>
        
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
            <div>
                <div style="background: white; padding: 2rem; border-radius: 0.5rem; margin-bottom: 2rem;">
                    <h2>Course Content</h2>
                    
                    <?php if (empty($lessons)): ?>
                        <p style="color: #6B7280;">No lessons available yet.</p>
                    <?php else: ?>
                        <div style="margin-top: 1.5rem;">
                            <?php foreach ($lessons as $index => $lesson): ?>
                                <div style="border-bottom: 1px solid #E5E7EB; padding: 1rem 0; display: flex; justify-content: space-between; align-items: center;">
                                    <div>
                                        <h4 style="margin-bottom: 0.25rem;">
                                            <?= $index + 1 ?>. <?= htmlspecialchars($lesson['title']) ?>
                                            <?php if ($lesson['progress_status'] === 'completed'): ?>
                                                <span style="color: #10B981;">✓</span>
                                            <?php endif; ?>
                                        </h4>
                                        <p style="color: #6B7280; font-size: 0.9rem; margin: 0;">
                                            <?= ucfirst($lesson['content_type']) ?>
                                            <?php if ($lesson['duration']): ?>
                                                • <?= $lesson['duration'] ?> min
                                            <?php endif; ?>
                                        </p>
                                    </div>
                                    <a href="<?= url('/student/lesson/' . $lesson['id']) ?>" class="btn-primary btn-small">
                                        <?= $lesson['progress_status'] === 'completed' ? 'Review' : ($lesson['progress_status'] === 'in_progress' ? 'Continue' : 'Start') ?>
                                    </a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
                
                <?php if (!empty($quizzes)): ?>
                    <div style="background: white; padding: 2rem; border-radius: 0.5rem; margin-bottom: 2rem;">
                        <h2>Quizzes</h2>
                        <div style="margin-top: 1.5rem;">
                            <?php foreach ($quizzes as $quiz): ?>
                                <div style="border: 1px solid #E5E7EB; padding: 1.5rem; border-radius: 0.5rem; margin-bottom: 1rem;">
                                    <h4><?= htmlspecialchars($quiz['title']) ?></h4>
                                    <p style="color: #6B7280;"><?= htmlspecialchars($quiz['description']) ?></p>
                                    <p style="font-size: 0.9rem; color: #6B7280;">
                                        Passing score: <?= $quiz['passing_score'] ?>%
                                        <?php if ($quiz['time_limit']): ?>
                                            • Time limit: <?= $quiz['time_limit'] ?> minutes
                                        <?php endif; ?>
                                    </p>
                                    <a href="<?= url('/student/quiz/' . $quiz['id']) ?>" class="btn-primary btn-small">Take Quiz</a>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <div>
                <?php if (!empty($announcements)): ?>
                    <div style="background: white; padding: 1.5rem; border-radius: 0.5rem; margin-bottom: 2rem;">
                        <h3>Announcements</h3>
                        <?php foreach (array_slice($announcements, 0, 5) as $announcement): ?>
                            <div style="border-bottom: 1px solid #E5E7EB; padding: 1rem 0;">
                                <h5 style="margin-bottom: 0.5rem;"><?= htmlspecialchars($announcement['title']) ?></h5>
                                <p style="color: #6B7280; font-size: 0.9rem;"><?= nl2br(htmlspecialchars($announcement['content'])) ?></p>
                                <p style="color: #9CA3AF; font-size: 0.8rem; margin-top: 0.5rem;">
                                    <?= timeAgo($announcement['created_at']) ?>
                                </p>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>
                
                <div style="background: white; padding: 1.5rem; border-radius: 0.5rem;">
                    <h3>About This Course</h3>
                    <p style="color: #6B7280; line-height: 1.6;"><?= nl2br(htmlspecialchars(substr($course['description'], 0, 200))) ?>...</p>
                    <a href="<?= url('/courses/' . $course['id']) ?>" style="color: #4F46E5; text-decoration: none;">View full details →</a>
                </div>
            </div>
        </div>
    </div>
    
    <?php include __DIR__ . '/../shared/footer.php'; ?>
    <script src="<?= url('public/js/main.js') ?>"></script>
</body>
</html>
