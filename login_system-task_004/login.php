<?php session_start();
$u = $_COOKIE['remember_username'] ?? '';
$theme = $_COOKIE['user_theme'] ?? 'light';
$err = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body{min-height:100vh;display:flex;align-items:center;justify-content:center}
body.dark{background:#121212;color:#fff}
body.warm{background:linear-gradient(135deg,#ffe0b2,#ffcc80)}
.card{border-radius:20px}
</style>
</head>
<body class="<?= $theme ?>">
    <div class="container">
<div class="row justify-content-center">
<div class="col-12 col-sm-10 col-md-6 col-lg-4">
<form class="card shadow-lg p-4" method="POST" action="auth.php">
<div class="text-center mb-3">
<h4 class="fw-bold">Welcome Back</h4>
<p class="text-muted small">Login to continue</p>
</div>
<?php if($err): ?><div class="alert alert-danger text-center"><?= $err ?></div><?php endif; ?>
<input name="username" value="<?= $u ?>" class="form-control mb-3" placeholder="Username" required>
<input name="email" class="form-control mb-3" placeholder="Email" required>
<input name="password" type="password" class="form-control mb-3" placeholder="Password" required>
<div class="d-flex justify-content-between align-items-center mb-3">
<div class="form-check">
<input type="checkbox" name="remember" class="form-check-input"> 
<label class="form-check-label">Remember</label>
</div>
</div>
<button class="btn btn-primary w-100 rounded-pill">Login</button>
</form>
</div>
</div>
</div>
</body>
</html>

// http://localhost/Capminds-Tasks/login_system-task_004/login.php
