<?php

include("../config/db.php");

$error = "";
$success = "";

if (isset($_POST['submit'])) {

    $patient_name = trim($_POST['patient_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $age = trim($_POST['age']);
    $gender = trim($_POST['gender']);
    $diagnosis = trim($_POST['diagnosis']);

    // Validation

    if (
        empty($patient_name) ||
        empty($email) ||
        empty($phone) ||
        empty($age) ||
        empty($gender) ||
        empty($diagnosis)
    ) {

        $error = "All fields are required.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Invalid email format.";

    } elseif (!preg_match('/^[0-9]{10}$/', $phone)) {

        $error = "Phone number must contain 10 digits.";

    } else {

        // Check unique email

        $checkEmail = "SELECT * FROM patients WHERE email='$email'";
        $result = mysqli_query($conn, $checkEmail);

        if (mysqli_num_rows($result) > 0) {

            $error = "Email already exists.";

        } else {

            $sql = "INSERT INTO patients
            (patient_name, email, phone, age, gender, diagnosis)

            VALUES
            ('$patient_name', '$email', '$phone', '$age', '$gender', '$diagnosis')";

            if (mysqli_query($conn, $sql)) {

                $success = "Patient added successfully.";

            } else {

                $error = "Something went wrong.";
            }
        }
    }
}

include("../includes/header.php");

?>

<div class="form-container">

    <a href="list.php" class="btn btn-secondary mb-3">
        Back
    </a>

    <?php if ($error) { ?>
        <div class="alert alert-danger">
            <?php echo $error; ?>
        </div>
    <?php } ?>

    <?php if ($success) { ?>
        <div class="alert alert-success">
            <?php echo $success; ?>
        </div>
    <?php } ?>

    <form method="POST">

        <div class="mb-3">
            <label>Patient Name</label>

            <input type="text"
                   name="patient_name"
                   class="form-control">
        </div>

        <div class="mb-3">
            <label>Email</label>

            <input type="email"
                   name="email"
                   class="form-control">
        </div>

        <div class="mb-3">
            <label>Phone</label>

            <input type="text"
                   name="phone"
                   class="form-control">
        </div>

        <div class="mb-3">
            <label>Age</label>

            <input type="number"
                   name="age"
                   class="form-control">
        </div>

        <div class="mb-3">
            <label>Gender</label>

            <select name="gender" class="form-control">

                <option value="">Select</option>

                <option value="Male">Male</option>

                <option value="Female">Female</option>

                <option value="Other">Other</option>

            </select>
        </div>

        <div class="mb-3">
            <label>Diagnosis</label>

            <textarea name="diagnosis"
                      class="form-control"></textarea>
        </div>

        <button type="submit"
                name="submit"
                class="btn btn-primary">

            Add Patient

        </button>

    </form>

</div>

<?php include("../includes/footer.php"); ?>