<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Course - SchoolDream+</title>
    <link rel="stylesheet" href="<?= url('public/css/style.css') ?>">
</head>
<body>
    <?php include __DIR__ . '/../shared/navbar.php'; ?>
    
    <?php if (flash('error')): ?>
        <div class="alert alert-error"><?= flash('error') ?></div>
    <?php endif; ?>
    
    <div class="dashboard-container">
        <aside class="sidebar">
            <h3>Instructor Portal</h3>
            <ul class="sidebar-menu">
                <li><a href="<?= url('/instructor/dashboard') ?>">Dashboard</a></li>
                <li><a href="<?= url('/instructor/courses') ?>">My Courses</a></li>
                <li class="active"><a href="<?= url('/instructor/courses/create') ?>">Create Course</a></li>
                <li><a href="<?= url('/student/profile') ?>">Profile</a></li>
            </ul>
        </aside>
        
        <main class="dashboard-content">
            <h1>Create New Course</h1>
            
            <div style="background: white; padding: 2rem; border-radius: 0.5rem;">
                <form action="<?= url('/instructor/courses') ?>" method="POST" class="auth-form">
                    <div class="form-group">
                        <label for="title">Course Title *</label>
                        <input type="text" id="title" name="title" required>
                        <small>Choose a clear, descriptive title</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="category">Category *</label>
                        <select id="category" name="category" required>
                            <option value="">Select a category</option>
                            <option value="Information Technology">Information Technology</option>
                            <option value="Web Development">Web Development</option>
                            <option value="Mobile Development">Mobile Development</option>
                            <option value="Data Science">Data Science</option>
                            <option value="Cloud Computing">Cloud Computing</option>
                            <option value="Programming">Programming</option>
                            <option value="Marketing">Marketing</option>
                            <option value="Business">Business</option>
                            <option value="Design">Design</option>
                            <option value="Other">Other</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="description">Description *</label>
                        <textarea id="description" name="description" rows="6" required></textarea>
                        <small>Provide a comprehensive description of what students will learn</small>
                    </div>
                    
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label for="price">Price (RWF)</label>
                            <input type="number" id="price" name="price" value="0" min="0" step="100">
                            <small>Set to 0 for a free course</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="duration">Duration</label>
                            <input type="text" id="duration" name="duration" placeholder="e.g., 4 weeks, 20 hours">
                            <small>Estimated time to complete</small>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label for="level">Difficulty Level *</label>
                        <select id="level" name="level" required>
                            <option value="beginner">Beginner</option>
                            <option value="intermediate">Intermediate</option>
                            <option value="advanced">Advanced</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="prerequisites">Prerequisites</label>
                        <textarea id="prerequisites" name="prerequisites" rows="3"></textarea>
                        <small>What students should know before taking this course (optional)</small>
                    </div>
                    
                    <div class="form-group">
                        <label for="learning_outcomes">Learning Outcomes</label>
                        <textarea id="learning_outcomes" name="learning_outcomes" rows="5"></textarea>
                        <small>What will students learn? (one per line)</small>
                    </div>
                    
                    <div style="background: #FEF3C7; padding: 1rem; border-radius: 0.5rem; margin: 1.5rem 0;">
                        <strong>📝 Note:</strong> After creating your course, you'll be able to add lessons, quizzes, and other content. Your course will be submitted for admin approval before being published.
                    </div>
                    
                    <div style="display: flex; gap: 1rem;">
                        <button type="submit" class="btn-primary">Create Course</button>
                        <a href="<?= url('/instructor/dashboard') ?>" class="btn-outline">Cancel</a>
                    </div>
                </form>
            </div>
        </main>
    </div>
    
    <?php include __DIR__ . '/../shared/footer.php'; ?>
    <script src="<?= url('public/js/main.js') ?>"></script>
</body>
</html>
