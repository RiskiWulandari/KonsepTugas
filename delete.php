<?php
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    
    $conn = new mysqli("localhost", "root", "", "voli_db");
    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    $sql = "DELETE FROM pendaftar WHERE id=$id";
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $conn->error;
    }

    $conn->close();
    }
?>
