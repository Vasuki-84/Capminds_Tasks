<?php
require_once '../config/db.php';

$patient_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Get patient info
$stmt = $pdo->prepare("SELECT name FROM patients WHERE patient_id = ?");
$stmt->execute([$patient_id]);
$patient = $stmt->fetch();

if (!$patient) {
    redirect('../patients/list.php');
}

// SQL calculates total visits and days between first and last visit
$stmt = $pdo->prepare("
    SELECT 
        v.*,
        DATEDIFF(CURDATE(), v.visit_date) as days_since_visit,
        (SELECT COUNT(*) FROM visits WHERE patient_id = ?) as total_visits,
        DATEDIFF(MAX(v.visit_date), MIN(v.visit_date)) as days_between_first_last
    FROM visits v
    WHERE v.patient_id = ?
    GROUP BY v.visit_id
    ORDER BY v.visit_date DESC
");

$stmt->execute([$patient_id, $patient_id]);
$visits = $stmt->fetchAll();

$stats = $visits ? $visits[0] : null;

include '../includes/header.php';
?>

<div class="card shadow">
    <div class="card-header bg-white">
        <h4 class="mb-0"><i class="bi bi-clock-history"></i> Visit History: <?php echo htmlspecialchars($patient['name']); ?></h4>
    </div>
    <div class="card-body">
        <?php if ($stats): ?>
            <div class="alert alert-success mb-3">
                <strong>Summary:</strong> Total Visits: <?php echo $stats['total_visits']; ?> | 
                Days between first & last visit: <?php echo $stats['days_between_first_last']; ?> days
            </div>
        <?php endif; ?>
        
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>Visit Date</th>
                        <th>Consultation Fee</th>
                        <th>Lab Fee</th>
                        <th>Total</th>
                        <th>Days Since</th>
                        <th>Follow-up Due</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($visits as $visit): ?>
                    <tr>
                        <td><?php echo date('F d, Y', strtotime($visit['visit_date'])); ?></td>
                        <td>$<?php echo number_format($visit['consultation_fee'], 2); ?></td>
                        <td>$<?php echo number_format($visit['lab_fee'], 2); ?></td>
                        <td>$<?php echo number_format($visit['consultation_fee'] + $visit['lab_fee'], 2); ?></td>
                        <td><?php echo $visit['days_since_visit']; ?> days</td>
                        <td><?php echo $visit['follow_up_due']; ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="mt-3">
            <a href="../patients/view.php?id=<?php echo $patient_id; ?>" class="btn btn-secondary">Back to Patient</a>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>