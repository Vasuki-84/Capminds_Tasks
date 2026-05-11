<?php

include("../config/db.php");

$error = "";
$success = "";

if (isset($_POST['submit'])) {

    /*
    |--------------------------------------------------------------------------
    | GET FORM DATA
    |--------------------------------------------------------------------------
    */

    $patient_name = trim($_POST['patient_name']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $age = trim($_POST['age']);
    $gender = trim($_POST['gender']);
    $diagnosis = trim($_POST['diagnosis']);

    /*
    |--------------------------------------------------------------------------
    | VALIDATION
    |--------------------------------------------------------------------------
    */

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

        /*
        |--------------------------------------------------------------------------
        | CHECK UNIQUE EMAIL USING PREPARED STATEMENT
        |--------------------------------------------------------------------------
        */

        $checkEmailQuery = "SELECT id FROM patients WHERE email = ?";

        $checkStmt = mysqli_prepare($conn, $checkEmailQuery);

        mysqli_stmt_bind_param($checkStmt, "s", $email);

        mysqli_stmt_execute($checkStmt);

        $checkResult = mysqli_stmt_get_result($checkStmt);

        /*
        |--------------------------------------------------------------------------
        | EMAIL EXISTS CHECK
        |--------------------------------------------------------------------------
        */

        if (mysqli_num_rows($checkResult) > 0) {

            $error = "Email already exists.";

        } else {

            /*
            |--------------------------------------------------------------------------
            | INSERT PATIENT USING PREPARED STATEMENT
            |--------------------------------------------------------------------------
            */

            $insertQuery = "INSERT INTO patients

            (patient_name, email, phone, age, gender, diagnosis)

            VALUES (?, ?, ?, ?, ?, ?)";

            $insertStmt = mysqli_prepare($conn, $insertQuery);

            mysqli_stmt_bind_param(

                $insertStmt,
                "sssiss",
                $patient_name,
                $email,
                $phone,
                $age,
                $gender,
                $diagnosis

            );

            /*
            |--------------------------------------------------------------------------
            | EXECUTE INSERT QUERY
            |--------------------------------------------------------------------------
            */

            if (mysqli_stmt_execute($insertStmt)) {

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
                   class="form-control"
                   required>

        </div>

        <div class="mb-3">

            <label>Email</label>

            <input type="email"
                   name="email"
                   class="form-control"
                   required>

        </div>

        <div class="mb-3">

            <label>Phone</label>

            <input type="text"
                   name="phone"
                   class="form-control"
                   required>

        </div>

        <div class="mb-3">

            <label>Age</label>

            <input type="number"
                   name="age"
                   class="form-control"
                   required>

        </div>

        <div class="mb-3">

            <label>Gender</label>

            <select name="gender"
                    class="form-control"
                    required>

                <option value="">
                    Select
                </option>

                <option value="Male">
                    Male
                </option>

                <option value="Female">
                    Female
                </option>

                <option value="Other">
                    Other
                </option>

            </select>

        </div>

        <div class="mb-3">

            <label>Diagnosis</label>

            <textarea name="diagnosis"
                      class="form-control"
                      required></textarea>

        </div>

        <button type="submit"
                name="submit"
                class="btn btn-primary">

            Add Patient

        </button>

    </form>

</div>

<?php include("../includes/footer.php"); ?>