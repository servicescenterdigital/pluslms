<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $title ?? 'SchoolDream+' ?> - Online Learning Management System</title>
    <link rel="stylesheet" href="<?= url('public/css/style.css') ?>">
</head>
<body>
    <?php include __DIR__ . '/navbar.php'; ?>
    
    <?php if (flash('success')): ?>
        <div class="alert alert-success">
            <?= flash('success') ?>
        </div>
    <?php endif; ?>
    
    <?php if (flash('error')): ?>
        <div class="alert alert-error">
            <?= flash('error') ?>
        </div>
    <?php endif; ?>
    
    <main class="main-content">
        <?= $content ?? '' ?>
    </main>
    
    <?php include __DIR__ . '/footer.php'; ?>
    
    <script src="<?= url('public/js/main.js') ?>"></script>
</body>
</html>
