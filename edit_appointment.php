<?php

require "connection.php";

$id = $_GET["id"] ?? null;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $stmt = $pdo->prepare("
        UPDATE appointments SET
        first_name = ?, last_name = ?, requested_service = ?,
        preferred_date = ?, preferred_time = ?
        WHERE id = ?
    ");

    $stmt->execute([
        $_POST["first_name"],
        $_POST["last_name"],
        $_POST["requested_service"],
        $_POST["preferred_date"],
        $_POST["preferred_time"],
        $_POST["id"]
    ]);

    header("Location: manage_appointments.php");
    exit;
}

$stmt = $pdo->prepare("SELECT * FROM appointments WHERE id = ?");
$stmt->execute([$id]);
$app = $stmt->fetch();
?>
<!DOCTYPE html>
<html>
<head>
<title>Edit Appointment</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="container mt-4">

<h3>Edit Appointment</h3>

<form method="POST">
<input type="hidden" name="id" value="<?= $app["id"] ?>">

<div class="mb-2">
<input class="form-control" name="first_name" value="<?= $app["first_name"] ?>" required>
</div>

<div class="mb-2">
<input class="form-control" name="last_name" value="<?= $app["last_name"] ?>" required>
</div>

<div class="mb-2">
<input class="form-control" name="requested_service" value="<?= $app["requested_service"] ?>" required>
</div>

<div class="mb-2">
<input type="date" class="form-control" name="preferred_date" value="<?= $app["preferred_date"] ?>" required>
</div>

<div class="mb-2">
<input type="time" class="form-control" name="preferred_time" value="<?= $app["preferred_time"] ?>" required>
</div>

<button class="btn btn-primary">Update</button>
<a href="manage_appointments.php" class="btn btn-secondary">Cancel</a>
</form>

</body>
</html>
