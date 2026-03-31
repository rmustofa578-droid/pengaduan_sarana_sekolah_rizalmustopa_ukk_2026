<?php
require_once "models/koneksi.php";
$db = new Koneksi();
$conn = $db->koneksi;

// Ambil data dari form (Pastikan name di form registrasi.php sudah sesuai)
$user     = mysqli_real_escape_string($conn, $_POST['user']); // Tambahkan ini
$nis      = mysqli_real_escape_string($conn, $_POST['nis']); 
$password = $_POST['password']; // Jangan di-escape sebelum di-hash
$kelas    = mysqli_real_escape_string($conn, $_POST['kelas']);

// 1. CEK DULU: Apakah NIS sudah terdaftar?
$cek_nis = mysqli_query($conn, "SELECT nis FROM siswa WHERE nis = '$nis'");

if (mysqli_num_rows($cek_nis) > 0) {
    echo "<script>alert('Gagal! NIS $nis sudah terdaftar.'); window.history.back();</script>";
} else {
    // 2. Hash Password (Standar Keamanan)
    $pass_hash = password_hash($password, PASSWORD_DEFAULT);
    
    // 3. Insert ke database (Sesuai urutan kolom: user, nis, kelas, password, level)
    $sql = "INSERT INTO siswa (user, nis, kelas, password, level) 
            VALUES ('$user', '$nis', '$kelas', '$pass_hash', 'siswa')";

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Registrasi Berhasil! Silakan Login.'); window.location='login.php';</script>";
    } else {
        echo "Gagal Registrasi: " . mysqli_error($conn);
    }
}
?>