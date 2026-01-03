<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - SchoolDream+</title>
    <link rel="stylesheet" href="<?= url('public/css/style.css') ?>">
</head>
<body>
    <?php include __DIR__ . '/../shared/navbar.php'; ?>
    
    <div class="auth-container">
        <div class="auth-card">
            <h2>Welcome Back</h2>
            <p class="auth-subtitle">Login to your SchoolDream+ account</p>
            
            <?php if (flash('error')): ?>
                <div class="alert alert-error"><?= flash('error') ?></div>
            <?php endif; ?>
            
            <?php if (flash('success')): ?>
                <div class="alert alert-success"><?= flash('success') ?></div>
            <?php endif; ?>
            
            <form action="<?= url('/login') ?>" method="POST" class="auth-form">
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required autofocus>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <button type="submit" class="btn-primary btn-full">Login</button>
            </form>
            
            <p class="auth-footer">
                Don't have an account? <a href="<?= url('/register') ?>">Register here</a>
            </p>
        </div>
    </div>
    
    <?php include __DIR__ . '/../shared/footer.php'; ?>
</body>
</html>
