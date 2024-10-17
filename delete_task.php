<?php
// delete_task.php

$servername = "localhost";
$username = "root"; // Ganti jika menggunakan username lain
$password = "";     // Ganti jika menggunakan password
$dbname = "task_reminder";

// Membuat koneksi
$conn = new mysqli($servername, $username, $password, $dbname);

// Memeriksa koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Mengambil ID tugas yang akan dihapus
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = $_GET['id'];

    // Menyiapkan dan menjalankan query
    $stmt = $conn->prepare("DELETE FROM tasks WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        header("Location: index.php");
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
} else {
    echo "ID tugas tidak valid.";
}

$conn->close();
?>
