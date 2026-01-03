<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SchoolDream+ - Online Learning Management System</title>
    <link rel="stylesheet" href="<?= url('public/css/style.css') ?>">
</head>
<body>
    <?php include __DIR__ . '/shared/navbar.php'; ?>
    
    <?php if (flash('success')): ?>
        <div class="alert alert-success"><?= flash('success') ?></div>
    <?php endif; ?>
    
    <?php if (flash('error')): ?>
        <div class="alert alert-error"><?= flash('error') ?></div>
    <?php endif; ?>
    
    <!-- Hero Section -->
    <section class="hero">
        <div class="container">
            <div class="hero-content">
                <h1>Transform Your Future with <span>SchoolDream+</span></h1>
                <p>Professional online learning platform with expert instructors, comprehensive courses, and industry-recognized certificates.</p>
                <div class="hero-buttons">
                    <a href="<?= url('/courses') ?>" class="btn-primary btn-large">Explore Courses</a>
                    <a href="<?= url('/register') ?>" class="btn-outline btn-large">Get Started Free</a>
                </div>
            </div>
            
            <div class="hero-stats">
                <div class="stat-card">
                    <h3><?= $stats['total_courses'] ?></h3>
                    <p>Active Courses</p>
                </div>
                <div class="stat-card">
                    <h3><?= $stats['total_students'] ?></h3>
                    <p>Students Enrolled</p>
                </div>
                <div class="stat-card">
                    <h3><?= $stats['total_instructors'] ?></h3>
                    <p>Expert Instructors</p>
                </div>
            </div>
        </div>
    </section>
    
    <!-- Featured Courses -->
    <section class="featured-courses">
        <div class="container">
            <h2>Featured Courses</h2>
            <p class="section-subtitle">Start learning with our most popular courses</p>
            
            <div class="courses-grid">
                <?php foreach ($courses as $course): ?>
                    <div class="course-card">
                        <div class="course-image">
                            <img src="<?= $course['thumbnail'] ?? url('public/images/course-placeholder.jpg') ?>" alt="<?= htmlspecialchars($course['title']) ?>">
                            <span class="course-level"><?= ucfirst($course['level']) ?></span>
                        </div>
                        
                        <div class="course-content">
                            <h3><?= htmlspecialchars($course['title']) ?></h3>
                            <p class="course-instructor">By <?= htmlspecialchars($course['instructor_name']) ?></p>
                            <p class="course-description"><?= substr(htmlspecialchars($course['description']), 0, 100) ?>...</p>
                            
                            <div class="course-meta">
                                <span class="course-duration">⏱ <?= htmlspecialchars($course['duration']) ?></span>
                                <span class="course-students">👥 <?= $course['enrollment_count'] ?> students</span>
                            </div>
                            
                            <div class="course-footer">
                                <span class="course-price">
                                    <?php if ($course['price'] > 0): ?>
                                        <?= number_format($course['price']) ?> RWF
                                    <?php else: ?>
                                        <span class="free-badge">FREE</span>
                                    <?php endif; ?>
                                </span>
                                <a href="<?= url('/courses/' . $course['id']) ?>" class="btn-primary btn-small">View Course</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
            
            <div class="text-center" style="margin-top: 2rem;">
                <a href="<?= url('/courses') ?>" class="btn-outline">View All Courses →</a>
            </div>
        </div>
    </section>
    
    <!-- Features Section -->
    <section class="features">
        <div class="container">
            <h2>Why Choose SchoolDream+?</h2>
            
            <div class="features-grid">
                <div class="feature-card">
                    <div class="feature-icon">📚</div>
                    <h3>Expert Instructors</h3>
                    <p>Learn from industry professionals with years of experience</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">🎓</div>
                    <h3>Recognized Certificates</h3>
                    <p>Earn certificates upon completion to boost your career</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">⚡</div>
                    <h3>Learn at Your Pace</h3>
                    <p>Access courses anytime, anywhere on any device</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">💼</div>
                    <h3>Practical Projects</h3>
                    <p>Apply your knowledge with hands-on exercises and projects</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">🎯</div>
                    <h3>Track Progress</h3>
                    <p>Monitor your learning journey with detailed analytics</p>
                </div>
                
                <div class="feature-card">
                    <div class="feature-icon">🌟</div>
                    <h3>Quality Content</h3>
                    <p>High-quality course materials in multiple formats</p>
                </div>
            </div>
        </div>
    </section>
    
    <?php include __DIR__ . '/shared/footer.php'; ?>
    
    <script src="<?= url('public/js/main.js') ?>"></script>
</body>
</html>
