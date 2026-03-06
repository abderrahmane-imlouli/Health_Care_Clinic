<?php
require 'connection.php';
session_start();

error_reporting(E_ALL);
ini_set('display_errors', 1);

$success = false;
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    /* ========== REQUIRED FIELDS ========== */
    $required = ['first_name', 'last_name', 'birthdate', 'email', 'phone'];
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            $errors[] = "All required fields must be filled.";
            break;
        }
    }

    /* ========== SANITIZE INPUTS ========== */
    $first_name = htmlspecialchars(trim($_POST['first_name']));
    $last_name  = htmlspecialchars(trim($_POST['last_name']));
    $birthdate  = $_POST['birthdate'];
    $gender     = $_POST['gender'] ?? null;
    $requested_service = htmlspecialchars(trim($_POST['requested_service'] ?? ''));
    $preferred_date = $_POST['preferred_date'] ?? null;
    $preferred_time = $_POST['preferred_time'] ?? null;
    $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
    $phone = htmlspecialchars(trim($_POST['phone']));
    $address = htmlspecialchars(trim($_POST['address'] ?? ''));
    $allergies_history = htmlspecialchars(trim($_POST['allergies_history'] ?? ''));
    $selected_doctor = htmlspecialchars(trim($_POST['selected_doctor'] ?? ''));

    if (!$email) {
        $errors[] = "Invalid email format.";
    }

    /* ========== FILE UPLOAD ========== */
    $medical_file = null;

    if (!empty($_FILES['medical_file']['name'])) {

        $allowed_ext = ['pdf', 'jpg', 'jpeg', 'png'];
        $allowed_mime = ['application/pdf', 'image/jpeg', 'image/png'];
        $max_size = 5 * 1024 * 1024;

        $tmp = $_FILES['medical_file']['tmp_name'];
        $size = $_FILES['medical_file']['size'];
        $ext = strtolower(pathinfo($_FILES['medical_file']['name'], PATHINFO_EXTENSION));

        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $mime = finfo_file($finfo, $tmp);
        finfo_close($finfo);

        if (!in_array($ext, $allowed_ext)) {
            $errors[] = "Invalid file extension.";
        } elseif (!in_array($mime, $allowed_mime)) {
            $errors[] = "Invalid file content.";
        } elseif ($size > $max_size) {
            $errors[] = "File too large (max 5MB).";
        } else {
            if (!is_dir("uploads")) {
                mkdir("uploads", 0755, true);
            }
            $medical_file = uniqid("med_", true) . "." . $ext;
            move_uploaded_file($tmp, "uploads/" . $medical_file);
        }
    }

    /* ========== INSERT DATABASE ========== */
    if (empty($errors)) {
        $stmt = $pdo->prepare("
        INSERT INTO appointments
        (first_name, last_name, birthdate, gender, requested_service,
         preferred_date, preferred_time, email, phone, address,
         allergies_history, selected_doctor, medical_file, created_at)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,NOW())
        ");

        $stmt->execute([
            $first_name, $last_name, $birthdate, $gender, $requested_service,
            $preferred_date, $preferred_time, $email, $phone, $address,
            $allergies_history, $selected_doctor, $medical_file
        ]);

        $appointment_id = $pdo->lastInsertId();
        $success = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Appointment Status</title>
<style>
body { font-family: Arial; background:#f4f6f8; }
.box { max-width:700px; margin:60px auto; background:#fff; padding:30px; border-radius:8px; }
.success { color:green; }
.error { color:red; }
</style>
</head>
<body>

<div class="box">

<?php if ($success): ?>

    <h2 class="success">✔ Appointment Submitted Successfully</h2>
    <p><strong>Reference ID:</strong> #<?= $appointment_id ?></p>
    <p>We will contact you soon by email or phone.</p>

<?php else: ?>

    <h2 class="error">❌ Submission Failed</h2>
    <ul>
        <?php foreach ($errors as $e): ?>
            <li><?= $e ?></li>
        <?php endforeach; ?>
    </ul>

<?php endif; ?>

<a href="appointment.html">← Back to Appointment</a>

</div>

</body>
</html>
