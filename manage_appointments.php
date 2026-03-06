<?php
require "connection.php";
$stmt = $pdo->query("SELECT * FROM appointments ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Manage Appointments</title>

<!-- Bootstrap 5 -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/jquery.dataTables.min.css">
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>

<script>
$(document).ready(function () {
    $('#appointmentsTable').DataTable();
});
</script>
</head>


<body>

<nav class="navbar navbar-dark bg-dark">
  <div class="container-fluid">
    <a href="dashboard.php" class="navbar-brand">← Dashboard</a>
  </div>
</nav>

<div class="container mt-4">

<h3 class="mb-3">Appointment Requests</h3>

<div class="table-responsive">
<table id="appointmentsTable" class="table table-bordered table-hover align-middle">
<thead class="table-dark">
<tr>
  <th>First Name</th>
  <th>Last Name</th>
  <th>Requested Service</th>
  <th>Preferred Date</th>
  <th>Actions</th>
</tr>
</thead>

<tbody>
<?php while($row = $stmt->fetch()): ?>
<tr>
<td><?= htmlspecialchars($row["first_name"]) ?></td>
<td><?= htmlspecialchars($row["last_name"]) ?></td>
<td><?= htmlspecialchars($row["requested_service"]) ?></td>
<td><?= $row["preferred_date"] ?></td>

<td>
  <a class="btn btn-info btn-sm"
     href="appointment_details.php?id=<?= $row["id"] ?>">Details</a>

  <a class="btn btn-warning btn-sm"
     href="edit_appointment.php?id=<?= $row["id"] ?>">Edit</a>

  <a class="btn btn-danger btn-sm"
     href="delete_appointment.php?id=<?= $row["id"] ?>"
     onclick="return confirm('Are you sure you want to delete this appointment request?')">
     Delete
  </a>

  <?php if($row["medical_file"]): ?>
<a class="btn btn-success btn-sm"
   href="/medical_clinic/HealthCare/uploads/<?= rawurlencode($row["medical_file"]) ?>"
   download="<?= htmlspecialchars($row["medical_file"]) ?>"
   type="<?= mime_content_type('/medical_clinic/HealthCare/uploads/' . $row["medical_file"]) ?>">
   Download
</a>
<?php endif; ?>

</td>
</tr>
<?php endwhile; ?>
</tbody>
</table>
</div>

</div>

</body>
</html>
