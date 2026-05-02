<?php session_start();
if(!isset($_SESSION['username'])){ header('Location: login.php'); exit; }
$theme=$_SESSION['theme'];
?>
<!DOCTYPE html>
<html>
<head>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
<style>
body.dark{background:#121212;color:#fff}
body.warm{background:linear-gradient(135deg,#ffe0b2,#ffcc80)}
.card{border-radius:20px}
</style>
</head>
<body class="<?= $theme ?>">
<nav class="navbar navbar-expand-lg navbar-dark bg-primary px-3">
<span class="navbar-brand">Dashboard</span>
<div class="ms-auto">
<a href="logout.php" class="btn btn-light btn-sm">Logout</a>
</div>
</nav>
<div class="container mt-5">
<div class="row g-4">
<div class="col-12 col-md-6">
<div class="card p-4 shadow">
<h5 class="fw-bold">User Info</h5>
<p><strong>Username:</strong> <?= $_SESSION['username'] ?></p>
<p><strong>Email:</strong> <?= $_SESSION['email'] ?></p>
</div>
</div>
<div class="col-12 col-md-6">
<div class="card p-4 shadow">
<h5 class="fw-bold">Theme</h5>
<p>Current Theme: <span class="badge bg-secondary"><?= $theme ?></span></p>
</div>
</div>
</div>
</div>
</body>
</html>