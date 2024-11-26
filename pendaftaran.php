<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['full-name'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $position = $_POST['position'];
    $gender = $_POST['gender'];
    $dob = $_POST['dob'];

    $stmt = $conn->prepare("INSERT INTO pendaftar (name, position, email, phone, gender, date_of_birth) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("ssssss", $name, $position, $email, $phone, $gender, $dob);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Pendaftaran berhasil!";
        header("Location: index.html");
        exit;
    } else {
        $_SESSION['error'] = "Terjadi kesalahan saat mendaftar!";
    }
}
?>
