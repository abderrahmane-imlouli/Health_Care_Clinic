<?php

require "connection.php";

$id = $_GET["id"] ?? null;

$stmt = $pdo->prepare("DELETE FROM appointments WHERE id = ?");
$stmt->execute([$id]);

header("Location: manage_appointments.php");
exit;
?>