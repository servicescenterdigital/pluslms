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
        <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
            <div>
                <h1><?= htmlspecialchars($course['title']) ?></h1>
                <p style="color: #6B7280; margin: 1rem 0;">By <?= htmlspecialchars($course['instructor_name']) ?></p>
                
                <div style="display: flex; gap: 2rem; margin: 1rem 0;">
                    <span>⏱ <?= htmlspecialchars($course['duration']) ?></span>
                    <span>📊 <?= ucfirst($course['level']) ?></span>
                    <span>👥 <?= $course['enrollment_count'] ?? 0 ?> enrolled</span>
                    <?php if (isset($course['average_rating']) && $course['average_rating']): ?>
                        <span>⭐ <?= number_format($course['average_rating'], 1) ?> (<?= $course['review_count'] ?> reviews)</span>
                    <?php endif; ?>
                </div>
                
                <div style="background: white; padding: 2rem; border-radius: 0.5rem; margin: 2rem 0;">
                    <h2>About This Course</h2>
                    <p style="line-height: 1.8; color: #374151;"><?= nl2br(htmlspecialchars($course['description'])) ?></p>
                </div>
                
                <?php if ($course['learning_outcomes']): ?>
                    <div style="background: white; padding: 2rem; border-radius: 0.5rem; margin: 2rem 0;">
                        <h2>What You'll Learn</h2>
                        <ul style="line-height: 2;">
                            <?php foreach (explode("\n", $course['learning_outcomes']) as $outcome): ?>
                                <?php if (trim($outcome)): ?>
                                    <li>✓ <?= htmlspecialchars(trim($outcome)) ?></li>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
                
                <?php if ($course['prerequisites']): ?>
                    <div style="background: white; padding: 2rem; border-radius: 0.5rem; margin: 2rem 0;">
                        <h2>Prerequisites</h2>
                        <p style="line-height: 1.8; color: #374151;"><?= nl2br(htmlspecialchars($course['prerequisites'])) ?></p>
                    </div>
                <?php endif; ?>
                
                <div style="background: white; padding: 2rem; border-radius: 0.5rem; margin: 2rem 0;">
                    <h2>About the Instructor</h2>
                    <h3><?= htmlspecialchars($course['instructor_name']) ?></h3>
                    <p style="line-height: 1.8; color: #374151;"><?= nl2br(htmlspecialchars($course['instructor_bio'] ?? 'No bio available')) ?></p>
                </div>
            </div>
            
            <div>
                <div style="background: white; padding: 2rem; border-radius: 0.5rem; position: sticky; top: 100px;">
                    <h3 style="margin-bottom: 1rem;">
                        <?php if ($course['price'] > 0): ?>
                            <?= number_format($course['price']) ?> RWF
                        <?php else: ?>
                            <span class="free-badge">FREE COURSE</span>
                        <?php endif; ?>
                    </h3>
                    
                    <?php if (isLoggedIn()): ?>
                        <?php
                        $enrollmentModel = new \App\Models\Enrollment();
                        $isEnrolled = $enrollmentModel->isEnrolled(auth()['id'], $course['id']);
                        ?>
                        
                        <?php if ($isEnrolled): ?>
                            <a href="<?= url('/student/course/' . $course['id']) ?>" class="btn-primary btn-full">
                                Go to Course →
                            </a>
                        <?php else: ?>
                            <form action="<?= url('/student/enroll/' . $course['id']) ?>" method="POST">
                                <button type="submit" class="btn-primary btn-full">
                                    Enroll Now
                                </button>
                            </form>
                        <?php endif; ?>
                    <?php else: ?>
                        <a href="<?= url('/login') ?>" class="btn-primary btn-full">
                            Login to Enroll
                        </a>
                        <p style="text-align: center; margin-top: 1rem; color: #6B7280;">
                            Don't have an account? <a href="<?= url('/register') ?>" style="color: #4F46E5;">Sign up</a>
                        </p>
                    <?php endif; ?>
                    
                    <div style="margin-top: 2rem; padding-top: 2rem; border-top: 1px solid #E5E7EB;">
                        <h4>This course includes:</h4>
                        <ul style="list-style: none; line-height: 2; color: #374151;">
                            <li>✓ Full lifetime access</li>
                            <li>✓ Certificate of completion</li>
                            <li>✓ Progress tracking</li>
                            <li>✓ Quizzes and exercises</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <?php include __DIR__ . '/../shared/footer.php'; ?>
</body>
</html>
