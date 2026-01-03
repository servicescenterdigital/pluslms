<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pending Approval - SchoolDream+</title>
    <link rel="stylesheet" href="<?= url('public/css/style.css') ?>">
</head>
<body>
    <?php include __DIR__ . '/../shared/navbar.php'; ?>
    
    <div class="container" style="padding: 4rem 0; text-align: center;">
        <div style="max-width: 600px; margin: 0 auto; background: white; padding: 3rem; border-radius: 0.5rem; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
            <div style="font-size: 4rem; margin-bottom: 1rem;">⏳</div>
            <h1>Instructor Application Pending</h1>
            <p style="color: #6B7280; line-height: 1.8; margin: 2rem 0;">
                Thank you for registering as an instructor on SchoolDream+!
                Your account is currently under review by our admin team.
                You will be able to create and manage courses once your account is approved.
            </p>
            <p style="color: #6B7280;">
                This process usually takes 24-48 hours. We'll notify you once your account is approved.
            </p>
            <a href="<?= url('/') ?>" class="btn-primary" style="margin-top: 2rem;">Back to Home</a>
        </div>
    </div>
    
    <?php include __DIR__ . '/../shared/footer.php'; ?>
</body>
</html>
