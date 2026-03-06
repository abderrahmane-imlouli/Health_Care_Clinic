<?php

require "connection.php";

$id = $_GET["id"] ?? null;
$stmt = $pdo->prepare("SELECT * FROM appointments WHERE id = ?");
$stmt->execute([$id]);
$app = $stmt->fetch();

if (!$app) {
    die("Appointment not found");
}
?>

<!DOCTYPE html>
<html>
<head>
<title>Appointment Details</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-4">

<h3>Appointment Details</h3>

<ul class="list-group">
  <li class="list-group-item"><b>Name:</b> <?= htmlspecialchars($app["first_name"]) ?> <?= htmlspecialchars($app["last_name"]) ?></li>
  <li class="list-group-item"><b>Birthdate:</b> <?= $app["birthdate"] ?></li>
  <li class="list-group-item"><b>Gender:</b> <?= $app["gender"] ?></li>
  <li class="list-group-item"><b>Service:</b> <?= htmlspecialchars($app["requested_service"]) ?></li>
  <li class="list-group-item"><b>Date:</b> <?= $app["preferred_date"] ?></li>
  <li class="list-group-item"><b>Time:</b> <?= $app["preferred_time"] ?></li>
  <li class="list-group-item"><b>Email:</b> <?= htmlspecialchars($app["email"]) ?></li>
  <li class="list-group-item"><b>Phone:</b> <?= htmlspecialchars($app["phone"]) ?></li>
  <li class="list-group-item"><b>Address:</b> <?= htmlspecialchars($app["address"]) ?></li>
  <li class="list-group-item"><b>Allergies:</b> <?= htmlspecialchars($app["allergies_history"]) ?></li>
  <li class="list-group-item"><b>Doctor:</b> <?= htmlspecialchars($app["selected_doctor"]) ?></li>
</ul>

<?php if ($app["medical_file"]): ?>
<a class="btn btn-success mt-3" href="../uploads/<?= $app["medical_file"] ?>" download>
Download Medical File
</a>
<?php endif; ?>

<a href="manage_appointments.php" class="btn btn-secondary mt-3">Back</a>

</body>
</html>
