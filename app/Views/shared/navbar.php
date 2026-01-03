<nav class="navbar">
    <div class="container">
        <div class="navbar-brand">
            <a href="<?= url('/') ?>" class="logo">
                <h1>SchoolDream<span>+</span></h1>
            </a>
        </div>
        
        <ul class="navbar-menu">
            <li><a href="<?= url('/') ?>">Home</a></li>
            <li><a href="<?= url('/courses') ?>">Courses</a></li>
            
            <?php if (isLoggedIn()): ?>
                <?php $user = auth(); ?>
                
                <?php if ($user['role'] === 'admin'): ?>
                    <li><a href="<?= url('/admin/dashboard') ?>">Admin Dashboard</a></li>
                <?php elseif ($user['role'] === 'instructor'): ?>
                    <li><a href="<?= url('/instructor/dashboard') ?>">Instructor Dashboard</a></li>
                <?php else: ?>
                    <li><a href="<?= url('/student/dashboard') ?>">My Dashboard</a></li>
                    <li><a href="<?= url('/student/courses') ?>">My Courses</a></li>
                    <li><a href="<?= url('/student/certificates') ?>">Certificates</a></li>
                <?php endif; ?>
                
                <li class="dropdown">
                    <a href="#" class="user-menu"><?= htmlspecialchars($user['name']) ?> ▼</a>
                    <ul class="dropdown-menu">
                        <li><a href="<?= url('/student/profile') ?>">Profile</a></li>
                        <li><a href="<?= url('/logout') ?>">Logout</a></li>
                    </ul>
                </li>
            <?php else: ?>
                <li><a href="<?= url('/login') ?>" class="btn-outline">Login</a></li>
                <li><a href="<?= url('/register') ?>" class="btn-primary">Register</a></li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
