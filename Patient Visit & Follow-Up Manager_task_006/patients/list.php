<?php
require_once '../config/db.php';

// SQL calculates age, years+months, join details, total visits
$stmt = $pdo->prepare("
    SELECT 
        p.patient_id,
        p.name,
        p.dob,
        p.join_date,
        p.phone,
        TIMESTAMPDIFF(YEAR, p.dob, CURDATE()) as age_years,
        CONCAT(
            TIMESTAMPDIFF(YEAR, p.dob, CURDATE()), ' years, ',
            TIMESTAMPDIFF(MONTH, p.dob, CURDATE()) % 12, ' months'
        ) as age_full,
        DATE_FORMAT(p.join_date, '%Y') as join_year,
        DATE_FORMAT(p.join_date, '%M') as join_month,
        DATE_FORMAT(p.join_date, '%d') as join_day,
        COUNT(v.visit_id) as total_visits
    FROM patients p
    LEFT JOIN visits v ON p.patient_id = v.patient_id
    GROUP BY p.patient_id
    ORDER BY p.name
");

$stmt->execute();
$patients = $stmt->fetchAll();

include '../includes/header.php';
?>

<div class="card shadow">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h4 class="mb-0"><i class="bi bi-people"></i> Patient List</h4>
        <a href="add.php" class="btn btn-primary btn-custom">
            <i class="bi bi-plus-circle"></i> Add Patient
        </a>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead class="table-light">
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Age (Years)</th>
                        <th>Full Age</th>
                        <th>Joined</th>
                        <th>Total Visits</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($patients as $patient): ?>
                    <tr>
                        <td><?php echo $patient['patient_id']; ?></td>
                        <td><?php echo htmlspecialchars($patient['name']); ?></td>
                        <td><?php echo $patient['age_years']; ?> years</td>
                        <td><?php echo $patient['age_full']; ?></td>
                        <td><?php echo $patient['join_month'] . ' ' . $patient['join_year']; ?></td>
                        <td><span class="badge bg-info"><?php echo $patient['total_visits']; ?></span></td>
                        <td>
                            <a href="view.php?id=<?php echo $patient['patient_id']; ?>" class="btn btn-sm btn-outline-info">
                                <i class="bi bi-eye"></i>
                            </a>
                            <a href="edit.php?id=<?php echo $patient['patient_id']; ?>" class="btn btn-sm btn-outline-warning">
                                <i class="bi bi-pencil"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php include '../includes/footer.php'; ?>