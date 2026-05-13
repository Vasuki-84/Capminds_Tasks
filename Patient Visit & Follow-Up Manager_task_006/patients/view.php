<?php
require_once '../config/db.php';

// Get encoded ID from URL and decode it
$encoded_id = isset($_GET['id']) ? $_GET['id'] : 0;
$id = base64_decode($encoded_id);

// Validate if decoding was successful and ID is numeric
if (!$id || !is_numeric($id)) {
    $_SESSION['error'] = "Invalid patient ID";
    redirect('../patients/list.php');
}

// Convert to integer for safety
$id = (int)$id;

// SQL calculates age, days since last visit, next follow-up, overdue status
$stmt = $pdo->prepare("
    SELECT 
        p.*,
        TIMESTAMPDIFF(YEAR, p.dob, CURDATE()) as age_years,
        CONCAT(
            TIMESTAMPDIFF(YEAR, p.dob, CURDATE()), ' years, ',
            TIMESTAMPDIFF(MONTH, p.dob, CURDATE()) % 12, ' months'
        ) as age_full,
        MAX(v.visit_date) as last_visit_date,
        DATEDIFF(CURDATE(), MAX(v.visit_date)) as days_since_last_visit,
        (SELECT follow_up_due FROM visits v2 
         WHERE v2.patient_id = p.patient_id 
         ORDER BY v2.visit_date DESC LIMIT 1) as next_follow_up,
        CASE 
            WHEN (SELECT follow_up_due FROM visits v2 
                  WHERE v2.patient_id = p.patient_id 
                  ORDER BY v2.visit_date DESC LIMIT 1) < CURDATE() 
            THEN 'Overdue'
            WHEN (SELECT follow_up_due FROM visits v2 
                  WHERE v2.patient_id = p.patient_id 
                  ORDER BY v2.visit_date DESC LIMIT 1) >= CURDATE() 
            THEN 'Upcoming'
            ELSE 'No Follow-up'
        END as follow_up_status
    FROM patients p
    LEFT JOIN visits v ON p.patient_id = v.patient_id
    WHERE p.patient_id = ?
    GROUP BY p.patient_id
");

$stmt->execute([$id]);
$patient = $stmt->fetch();

if (!$patient) {
    $_SESSION['error'] = "Patient not found";
    redirect('../patients/list.php');
}

include '../includes/header.php';
?>

<div class="card shadow">
    <div class="card-header bg-white">
        <h4 class="mb-0"><i class="bi bi-person-badge"></i> Patient Details</h4>
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
        
        <div class="row">
            <div class="col-md-6">
                <table class="table table-borderless">
                    <tr>
                        <th width="150">Name:</th>
                        <td><?php echo htmlspecialchars($patient['name']); ?></td>
                    </tr>
                    <tr>
                        <th>Age:</th>
                        <td><?php echo $patient['age_full']; ?></td>
                    </tr>
                    <tr>
                        <th>Date of Birth:</th>
                        <td><?php echo date('F d, Y', strtotime($patient['dob'])); ?></td>
                    </tr>
                    <tr>
                        <th>Join Date:</th>
                        <td><?php echo date('F d, Y', strtotime($patient['join_date'])); ?></p