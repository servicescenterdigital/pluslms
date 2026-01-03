<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Browse Courses - SchoolDream+</title>
    <link rel="stylesheet" href="<?= url('public/css/style.css') ?>">
</head>
<body>
    <?php include __DIR__ . '/../shared/navbar.php'; ?>
    
    <div class="container" style="padding: 2rem 0;">
        <h1>Browse Courses</h1>
        
        <div style="display: flex; gap: 1rem; margin: 2rem 0;">
            <form action="<?= url('/courses') ?>" method="GET" style="flex: 1; display: flex; gap: 0.5rem;">
                <input type="text" name="search" placeholder="Search courses..." value="<?= htmlspecialchars($search ?? '') ?>" style="flex: 1; padding: 0.75rem; border: 1px solid #E5E7EB; border-radius: 0.375rem;">
                <button type="submit" class="btn-primary">Search</button>
            </form>
            
            <select name="category" onchange="window.location.href='<?= url('/courses') ?>?category=' + this.value" style="padding: 0.75rem; border: 1px solid #E5E7EB; border-radius: 0.375rem;">
                <option value="">All Categories</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= htmlspecialchars($cat['category']) ?>" <?= ($selectedCategory === $cat['category']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['category']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>
        
        <?php if (empty($courses)): ?>
            <div class="alert" style="background: #FEF3C7; color: #92400E; border: 1px solid #F59E0B;">
                No courses found matching your criteria.
            </div>
        <?php else: ?>
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
                            <p class="course-description"><?= substr(htmlspecialchars($course['description']), 0, 120) ?>...</p>
                            
                            <div class="course-meta">
                                <span>⏱ <?= htmlspecialchars($course['duration']) ?></span>
                                <span>👥 <?= $course['enrollment_count'] ?? 0 ?> students</span>
                                <?php if (isset($course['average_rating']) && $course['average_rating']): ?>
                                    <span>⭐ <?= number_format($course['average_rating'], 1) ?></span>
                                <?php endif; ?>
                            </div>
                            
                            <div class="course-footer">
                                <span class="course-price">
                                    <?php if ($course['price'] > 0): ?>
                                        <?= number_format($course['price']) ?> RWF
                                    <?php else: ?>
                                        <span class="free-badge">FREE</span>
                                    <?php endif; ?>
                                </span>
                                <a href="<?= url('/courses/' . $course['id']) ?>" class="btn-primary btn-small">View Details</a>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
    
    <?php include __DIR__ . '/../shared/footer.php'; ?>
</body>
</html>
