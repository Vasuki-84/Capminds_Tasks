<?php

include("../config/db.php");
include("../includes/header.php");

// Pagination

$limit = 5;
$page = isset($_GET['page']) ? $_GET['page'] : 1;
$offset = ($page - 1) * $limit;

// Search

$search = isset($_GET['search']) ? $_GET['search'] : "";

// Sorting

$sort = isset($_GET['sort']) ? $_GET['sort'] : "patient_name ASC";

// Query -1
// LIKE-  LIKE - Pattern matching / partial search

$sql = "SELECT * FROM patients
WHERE patient_name LIKE '%$search%'  
OR diagnosis LIKE '%$search%'

ORDER BY $sort
LIMIT $offset, $limit";

$result = mysqli_query($conn, $sql);

// Total records

$totalQuery = "SELECT COUNT(*) as total FROM patients";
$totalResult = mysqli_query($conn, $totalQuery);
$totalRow = mysqli_fetch_assoc($totalResult);

$totalPages = ceil($totalRow['total'] / $limit);

?>

<div class="d-flex justify-content-between mb-3">

    <a href="create.php" class="btn btn-success">
        Add Patient
    </a>

    <form method="GET" class="d-flex">

        <input type="text"
               name="search"
               class="form-control me-2"
               placeholder="Search"
               value="<?php echo $search; ?>">

       
    <div class="col-md-4 col-sm-6 me-3">
    
    <select name="sort"
            class="form-select shadow-sm border-2 rounded-3 form-control me-2">

        <option selected disabled>
            Sort Patients
        </option>

        <option value="patient_name ASC">
            🔤 Name A-Z
        </option>

        <option value="patient_name DESC">
            🔠 Name Z-A
        </option>

        <option value="age ASC">
            👶 Age Low-High
        </option>

        <option value="age DESC">
            👴 Age High-Low
        </option>

    </select>

</div>

        <button class="btn btn-primary">
            Search
        </button>

    </form>

</div>

<table class="table table-bordered table-hover">

    <tr  class="table-secondary">

        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Age</th>
        <th>Gender</th>
        <th>Diagnosis</th>
        <th>Actions</th>

    </tr>

    <?php while ($row = mysqli_fetch_assoc($result)) { ?>

        <tr>

            <td><?php echo $row['id']; ?></td>

            <td><?php echo $row['patient_name']; ?></td>

            <td><?php echo $row['email']; ?></td>

            <td><?php echo $row['phone']; ?></td>

            <td><?php echo $row['age']; ?></td>

            <td><?php echo $row['gender']; ?></td>

            <td><?php echo $row['diagnosis']; ?></td>

            <td>
  <div class="d-flex flex-column flex-sm-row gap-2">
                <a href="edit.php?id=<?php echo $row['id']; ?>"
                   class="btn btn-warning btn-sm">

                    Edit

                </a>

                <a href="delete.php?id=<?php echo $row['id']; ?>"
                   class="btn btn-danger btn-sm"
                   onclick="return confirm('Delete this patient?')">

                    Delete

                </a>
</div>
</td>

        </tr>

    <?php } ?>

</table>

<!-- Pagination -->

<nav>

<ul class="pagination">

<?php if ($page > 1) { ?>

<li class="page-item">

<a class="page-link"
href="?page=<?php echo $page - 1; ?>">

Previous

</a>

</li>

<?php } ?>

<?php if ($page < $totalPages) { ?>

<li class="page-item">

<a class="page-link"
href="?page=<?php echo $page + 1; ?>">

Next

</a>

</li>

<?php } ?>

</ul>

</nav>

<?php include("../includes/footer.php"); ?>