<?php

include("../config/db.php");

$id = $_GET['id'];

$deleteQuery = "DELETE FROM patients WHERE id='$id'";

mysqli_query($conn, $deleteQuery);

header("Location: list.php");
exit;

?>