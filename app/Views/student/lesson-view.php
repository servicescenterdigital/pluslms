<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($lesson['title']) ?> - SchoolDream+</title>
    <link rel="stylesheet" href="<?= url('public/css/style.css') ?>">
</head>
<body>
    <?php include __DIR__ . '/../shared/navbar.php'; ?>
    
    <div class="container" style="padding: 2rem 0;">
        <div style="max-width: 900px; margin: 0 auto;">
            <nav style="margin-bottom: 2rem;">
                <a href="<?= url('/student/course/' . $lesson['course_id']) ?>" style="color: #4F46E5; text-decoration: none;">
                    ← Back to Course
                </a>
            </nav>
            
            <div style="background: white; padding: 2rem; border-radius: 0.5rem;">
                <h1><?= htmlspecialchars($lesson['title']) ?></h1>
                
                <?php if ($lesson['description']): ?>
                    <p style="color: #6B7280; margin: 1rem 0 2rem;"><?= htmlspecialchars($lesson['description']) ?></p>
                <?php endif; ?>
                
                <div class="lesson-content" data-lesson-id="<?= $lesson['id'] ?>" style="margin: 2rem 0;">
                    <?php if ($lesson['content_type'] === 'video'): ?>
                        <video controls style="width: 100%; max-height: 500px; background: #000;">
                            <source src="<?= url($lesson['content_url']) ?>" type="video/mp4">
                            Your browser does not support the video tag.
                        </video>
                        
                    <?php elseif ($lesson['content_type'] === 'pdf'): ?>
                        <iframe src="<?= url($lesson['content_url']) ?>" style="width: 100%; height: 600px; border: 1px solid #E5E7EB;"></iframe>
                        <p style="margin-top: 1rem;">
                            <a href="<?= url($lesson['content_url']) ?>" download class="btn-outline">Download PDF</a>
                        </p>
                        
                    <?php elseif ($lesson['content_type'] === 'image'): ?>
                        <img src="<?= url($lesson['content_url']) ?>" alt="<?= htmlspecialchars($lesson['title']) ?>" style="width: 100%; max-width: 800px; display: block; margin: 0 auto;">
                        
                    <?php elseif ($lesson['content_type'] === 'text'): ?>
                        <div style="line-height: 1.8; color: #374151;">
                            <?= $lesson['content_text'] ?>
                        </div>
                        
                    <?php elseif ($lesson['content_type'] === 'exercise'): ?>
                        <div style="background: #F9FAFB; padding: 2rem; border-radius: 0.5rem; border-left: 4px solid #4F46E5;">
                            <h3>Exercise</h3>
                            <div style="line-height: 1.8; color: #374151;">
                                <?= $lesson['content_text'] ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                
                <div style="display: flex; justify-content: space-between; padding-top: 2rem; border-top: 1px solid #E5E7EB;">
                    <div>
                        <?php if ($prevLesson): ?>
                            <a href="<?= url('/student/lesson/' . $prevLesson['id']) ?>" class="btn-outline">
                                ← Previous Lesson
                            </a>
                        <?php endif; ?>
                    </div>
                    
                    <div style="display: flex; gap: 1rem;">
                        <?php if ($lesson['progress_status'] !== 'completed'): ?>
                            <form action="<?= url('/student/lesson/' . $lesson['id'] . '/complete') ?>" method="POST">
                                <button type="submit" class="btn-primary">Mark as Complete</button>
                            </form>
                        <?php else: ?>
                            <span style="color: #10B981; display: flex; align-items: center;">
                                ✓ Completed
                            </span>
                        <?php endif; ?>
                        
                        <?php if ($nextLesson): ?>
                            <a href="<?= url('/student/lesson/' . $nextLesson['id']) ?>" class="btn-primary">
                                Next Lesson →
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include __DIR__ . '/../shared/footer.php'; ?>
    <script src="<?= url('public/js/main.js') ?>"></script>
</body>
</html>
