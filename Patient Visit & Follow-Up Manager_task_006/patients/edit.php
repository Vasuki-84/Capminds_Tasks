<?php
require_once __DIR__ . '/../config/db.php';
checkPermission('edit');

$encoded_id = isset($_GET['id']) ? $_GET['id'] : 0;
$id = base64_decode($encoded_id);

if (!$id || !is_numeric($id)) {
    header("Location: list.php");
    exit();
}

$stmt = $pdo->prepare("SELECT * FROM patients WHERE patient_id = ?");
$stmt->execute([$id]);
$patient = $stmt->fetch();

if (!$patient) {
    header("Location: list.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = sanitizeInput($_POST['name']);
    $dob = $_POST['dob'];
    $phone = sanitizeInput($_POST['phone']);
    $address = sanitizeInput($_POST['address']);
    
    $stmt = $pdo->prepare("UPDATE patients SET name=?, dob=?, phone=?, address=? WHERE patient_id=?");
    if ($stmt->execute([$name, $dob, $phone, $address, $id])) {
        $_SESSION['success'] = "Patient updated successfully";
        header("Location: list.php");
        exit();
    }
}

include __DIR__ . '/../includes/header.php';
?>

<div class="card shadow">
    <div class="card-header bg-white">
        <h4 class="mb-0"><i class="bi bi-pencil-square"></i> Edit Patient</h4>
    </div>
    <div class="card-body">
        <?php if(isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php 
                echo $_SESSION['error']; 
                unset($_SESSION['error']);
                ?>
            </div>
        <?php endif; ?>
        
        <form method="POST" action="">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Full Name *</label>
                    <input type="text" name="name" class="form-control" value="<?php echo htmlspecialchars($patient['name']); ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Date of Birth *</label>
                    <input type="date" name="dob" class="form-control" value="<?php echo $patient['dob']; ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="<?php echo htmlspecialchars($patient['phone']); ?>">
                </div>
                <div class="col-12 mb-3">
                    <label class="form-label">Address</label>
                    <textarea name="address" class="form-control" rows="3"><?php echo htmlspecialchars($patient['address']); ?></textarea>
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Update Patient</button>
                    <a href="list.php" class="btn btn-secondary">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>