<?php

include("../config/db.php");

$id = $_GET['id'];

$query = "SELECT * FROM patients WHERE id='$id'";
$result = mysqli_query($conn, $query);

$row = mysqli_fetch_assoc($result);

if (isset($_POST['update'])) {

    $patient_name = $_POST['patient_name'];
    $phone = $_POST['phone'];
    $age = $_POST['age'];
    $gender = $_POST['gender'];
    $diagnosis = $_POST['diagnosis'];

    $updateQuery = "UPDATE patients SET

    patient_name='$patient_name',
    phone='$phone',
    age='$age',
    gender='$gender',
    diagnosis='$diagnosis'

    WHERE id='$id'";

    mysqli_query($conn, $updateQuery);

    header("Location: list.php");
    exit;
}

include("../includes/header.php");

?>

<div class="form-container">

<form method="POST">

<div class="mb-3">

<label>Patient Name</label>

<input type="text"
       name="patient_name"
       class="form-control"
       value="<?php echo $row['patient_name']; ?>">

</div>

<div class="mb-3">

<label>Phone</label>

<input type="text"
       name="phone"
       class="form-control"
       value="<?php echo $row['phone']; ?>">

</div>

<div class="mb-3">

<label>Age</label>

<input type="number"
       name="age"
       class="form-control"
       value="<?php echo $row['age']; ?>">

</div>

<div class="mb-3">

<label>Gender</label>

<select name="gender" class="form-control">

<option value="Male"
<?php if ($row['gender'] == 'Male') echo 'selected'; ?>>
Male
</option>

<option value="Female"
<?php if ($row['gender'] == 'Female') echo 'selected'; ?>>
Female
</option>

<option value="Other"
<?php if ($row['gender'] == 'Other') echo 'selected'; ?>>
Other
</option>

</select>

</div>

<div class="mb-3">

<label>Diagnosis</label>

<textarea name="diagnosis"
          class="form-control"><?php echo $row['diagnosis']; ?></textarea>

</div>

<button type="submit"
        name="update"
        class="btn btn-primary">

Update Patient

</button>

</form>

</div>

<?php include("../includes/footer.php"); ?>