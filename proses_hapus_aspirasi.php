<?php
session_start();
// PERBAIKAN: Sesuaikan path ini dengan letak file koneksi lo yang sebenarnya
// Jika file koneksi.php ada di folder yang sama, hapus 'config/' nya
if(file_exists("koneksi.php")) {
    include "koneksi.php";
} else if(file_exists("config/koneksi.php")) {
    include "config/koneksi.php";
}

// PERBAIKAN: Pastikan session ID user ada
if (!isset($_SESSION['id_user'])) {
    echo "<script>alert('Sesi berakhir, silakan login kembali'); window.location='login.php';</script>";
    exit;
}

$id = $_GET['id'];
$id_user = $_SESSION['id_user'];

// Cek apakah Class Koneksi ada
if (!class_exists('Koneksi')) {
    die("Error: Class 'Koneksi' tidak ditemukan. Pastikan file koneksi.php sudah benar.");
}

$db = new Koneksi();
$conn = $db->koneksi;

// Pastikan hanya bisa hapus milik sendiri dan status masih Menunggu
$cek = mysqli_query($conn, "SELECT foto, status FROM aspirasi WHERE id_aspirasi = '$id' AND id_pelapor = '$id_user'");
$data = mysqli_fetch_assoc($cek);

if ($data && $data['status'] == 'Menunggu') {
    // 1. Hapus file foto dari folder jika ada
    if (!empty($data['foto']) && file_exists("assets/img/aspirasi/" . $data['foto'])) {
        unlink("assets/img/aspirasi/" . $data['foto']);
    }

    // 2. Hapus data dari database
    $hapus = mysqli_query($conn, "DELETE FROM aspirasi WHERE id_aspirasi = '$id'");

    if ($hapus) {
        echo "<script>alert('Laporan berhasil dihapus!'); window.location='index.php?page=riwayat_aspirasi';</script>";
    } else {
        echo "<script>alert('Gagal menghapus data!'); window.history.back();</script>";
    }
} else {
    echo "<script>alert('Aksi ditolak! Laporan sudah diproses atau bukan milik Anda.'); window.location='index.php?page=riwayat_aspirasi';</script>";
}
?>