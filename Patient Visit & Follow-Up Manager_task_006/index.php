<?php
// index.php - Main dashboard
require_once __DIR__ . '/config/db.php';
include __DIR__ . '/includes/header.php';

// All SQL calculations for dashboard stats
$stats = [];    // Creates empty array to store dashboard statistics

// Total patients
$stmt = $pdo->query("SELECT COUNT(*) as total FROM patients");
$stats['total_patients'] = $stmt->fetch()['total'];

// Total visits
$stmt = $pdo->query("SELECT COUNT(*) as total FROM visits");
$stats['total_visits'] = $stmt->fetch()['total'];

// Overdue follow-ups
$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM visits WHERE follow_up_due < CURDATE()");
$stmt->execute();
$stats['overdue'] = $stmt->fetch()['total'];

// Upcoming follow-ups (next 7 days)
$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM visits WHERE follow_up_due BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)");
$stmt->execute();
$stats['upcoming'] = $stmt->fetch()['total'];

// Recent visits (last 30 days)
$stmt = $pdo->prepare("SELECT COUNT(*) as total FROM visits WHERE visit_date >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)");
$stmt->execute();
$stats['recent_visits'] = $stmt->fetch()['total'];

 ?>

<div class="row mb-4">
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="card card-stats bg-primary text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Total Patients</h6>
                        <h2 class="mb-0"><?php echo $stats['total_patients']; ?></h2>
                    </div>
                    <i class="bi bi-people fs-1"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="card card-stats bg-success text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Total Visits</h6>
                        <h2 class="mb-0"><?php echo $stats['total_visits']; ?></h2>
                    </div>
                    <i class="bi bi-calendar-heart fs-1"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="card card-stats bg-warning text-dark">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Upcoming Follow-ups</h6>
                        <h2 class="mb-0"><?php echo $stats['upcoming']; ?></h2>
                    </div>
                    <i class="bi bi-bell-fill fs-1"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-lg-3 mb-3">
        <div class="card card-stats bg-danger text-white">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="card-title">Overdue Follow-ups</h6>
                        <h2 class="mb-0"><?php echo $stats['overdue']; ?></h2>
                    </div>
                    <i class="bi bi-exclamation-triangle-fill fs-1"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header bg-white">
                <h5 class="mb-0">📊 Monthly Visits (Last 6 Months)</h5>
            </div>
            <div class="card-body">
                <canvas id="visitsChart" height="250"></canvas>
            </div>
        </div>
    </div>
    <div class="col-lg-6 mb-4">
        <div class="card shadow">
            <div class="card-header bg-white">
                <h5 class="mb-0">⚠️ Critical Alerts</h5>
            </div>
            <div class="card-body">
                <?php
                // Inactive patients (180+ days)
                $stmt = $pdo->prepare("
                    SELECT p.name, MAX(v.visit_date) as last_visit, 
                    DATEDIFF(CURDATE(), IFNULL(MAX(v.visit_date), '1900-01-01')) as days_inactive
                    FROM patients p 
                    LEFT JOIN visits v ON p.patient_id = v.patient_id 
                    GROUP BY p.patient_id 
                    HAVING days_inactive >= 180 OR MAX(v.visit_date) IS NULL
                    LIMIT 5
                ");
                $stmt->execute();
                $inactive = $stmt->fetchAll();
                
                if(count($inactive) > 0): ?>
                    <div class="alert alert-warning">
                        <strong><i class="bi bi-hourglass-split"></i> Inactive Patients (180+ days):</strong>
                        <ul class="mb-0 mt-2">
                            <?php foreach($inactive as $patient): ?>
                                <li><?php echo htmlspecialchars($patient['name']); ?> - 
                                    <?php echo $patient['days_inactive'] >= 180 ? $patient['days_inactive'] . ' days ago' : 'Never visited'; ?>
                                </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Monthly visits chart data from SQL
<?php

$stmt = $pdo->query("
    SELECT 
        DATE_FORMAT(visit_date, '%Y-%m') as month,
        COUNT(*) as count
    FROM visits 
    WHERE visit_date >= DATE_SUB(CURDATE(), INTERVAL 3 YEAR)
    GROUP BY DATE_FORMAT(visit_date, '%Y-%m')
    ORDER BY month ASC
");

$chartData = $stmt->fetchAll();
$months = [];
$counts = [];
foreach($chartData as $data) {
    $months[] = $data['month'];
    $counts[] = $data['count'];
}
?>

const ctx = document.getElementById('visitsChart').getContext('2d');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: <?php echo json_encode($months); ?>,
        datasets: [{
            label: 'Number of Visits',
            data: <?php echo json_encode($counts); ?>,
            borderColor: '#667eea',
            backgroundColor: 'rgba(102, 126, 234, 0.1)',
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: true
    }
});
</script>

<?php include __DIR__ . '/includes/footer.php'; ?>


<!-- http://localhost/Capminds-Tasks/Patient%20Visit%20&%20Follow-Up%20Manager_task_006/index.php -->