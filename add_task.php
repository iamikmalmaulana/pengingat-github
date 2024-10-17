<?php
// add_task.php

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

// Mengambil data dari form
$task_name = $_POST['task_name'];
$due_time = $_POST['due_time'];

// Menyiapkan dan menjalankan query
$stmt = $conn->prepare("INSERT INTO tasks (task_name, due_time) VALUES (?, ?)");
$stmt->bind_param("ss", $task_name, $due_time);

if ($stmt->execute()) {
    header("Location: index.php");
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
