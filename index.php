<?php
// index.php

// Koneksi ke database
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

// Mengambil semua tugas
$sql = "SELECT * FROM tasks ORDER BY due_time ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengingat Tugas</title>
    <link rel="stylesheet" href="assets/style.css">
</head>
<body>
    <div class="container">
        <h1>Pengingat Tugas</h1>
        <form action="add_task.php" method="POST">
            <input type="text" name="task_name" placeholder="Nama Tugas" required>
            <input type="datetime-local" name="due_time" required>
            <button type="submit">Tambah Tugas</button>
        </form>

        <h2>Daftar Tugas</h2>
        <ul id="taskList">
            <?php
            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<li data-due-time='" . $row['due_time'] . "'>";
                    echo htmlspecialchars($row['task_name']) . " | " . date("d.m.Y | H:i", strtotime($row['due_time']));
                    echo " <a href='delete_task.php?id=" . $row['id'] . "' onclick=\"return confirm('Apakah Anda yakin ingin menghapus tugas ini?');\">Hapus</a>";
                    echo "</li>";
                }
            } else {
                echo "<li>Tidak ada tugas.</li>";
            }
            ?>
        </ul>
    </div>

    <!-- Audio Notifikasi -->
    <audio id="notificationSound" src="assets/notif alarm.mp3" preload="auto"></audio>

    <script>
        // Mengambil daftar tugas dari HTML
        const tasks = document.querySelectorAll('#taskList li');
        const notificationSound = document.getElementById('notificationSound');

        tasks.forEach(task => {
            const dueTime = new Date(task.getAttribute('data-due-time')).getTime();
            const currentTime = new Date().getTime();
            const timeDifference = dueTime - currentTime;

            if (timeDifference > 0) {
                // Mengatur timeout hanya jika waktu belum lewat
                setTimeout(() => {
                    notificationSound.play().catch(error => {
                        console.log("Autoplay diblokir. Interaksi pengguna diperlukan untuk memutar audio.");
                    });
                }, timeDifference);
            } else {
                // Tugas sudah lewat, menandai sebagai overdue
                task.classList.add('overdue');
            }
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>
