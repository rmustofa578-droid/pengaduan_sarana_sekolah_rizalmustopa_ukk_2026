<?php
session_start();
require_once "models/koneksi.php";

// Pastikan yang akses cuma Admin
if ($_SESSION['level'] !== 'admin') {
    header("location:index.php");
    exit;
}

$db = new Koneksi();
$conn = $db->koneksi;

// Ambil data dari form
$id_aspirasi = $_POST['id_aspirasi'];
$status      = $_POST['status'];

// Query Update
$sql = "UPDATE aspirasi SET status = '$status' WHERE id_aspirasi = '$id_aspirasi'";

if (mysqli_query($conn, $sql)) {
    echo "<script>
            alert('Status berhasil diperbarui!');
            window.location='index.php?page=dashboard';
          </script>";
} else {
    echo "Error: " . mysqli_error($conn);
}
?>