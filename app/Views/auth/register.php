<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register - SchoolDream+</title>
    <link rel="stylesheet" href="<?= url('public/css/style.css') ?>">
</head>
<body>
    <?php include __DIR__ . '/../shared/navbar.php'; ?>
    
    <div class="auth-container">
        <div class="auth-card">
            <h2>Create Account</h2>
            <p class="auth-subtitle">Join SchoolDream+ and start learning today</p>
            
            <?php if (flash('error')): ?>
                <div class="alert alert-error"><?= flash('error') ?></div>
            <?php endif; ?>
            
            <form action="<?= url('/register') ?>" method="POST" class="auth-form">
                <div class="form-group">
                    <label for="name">Full Name</label>
                    <input type="text" id="name" name="name" required>
                </div>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required minlength="6">
                    <small>Minimum 6 characters</small>
                </div>
                
                <div class="form-group">
                    <label for="confirm_password">Confirm Password</label>
                    <input type="password" id="confirm_password" name="confirm_password" required>
                </div>
                
                <div class="form-group">
                    <label for="role">I want to register as:</label>
                    <select id="role" name="role" required>
                        <option value="student">Student</option>
                        <option value="instructor">Instructor</option>
                    </select>
                    <small>Instructor accounts require admin approval</small>
                </div>
                
                <button type="submit" class="btn-primary btn-full">Create Account</button>
            </form>
            
            <p class="auth-footer">
                Already have an account? <a href="<?= url('/login') ?>">Login here</a>
            </p>
        </div>
    </div>
    
    <?php include __DIR__ . '/../shared/footer.php'; ?>
</body>
</html>
