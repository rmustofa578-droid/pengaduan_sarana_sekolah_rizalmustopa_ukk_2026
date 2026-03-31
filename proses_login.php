<?php
session_start();
require_once "models/koneksi.php";
$db = new Koneksi();
$conn = $db->koneksi;

// Ambil input dari form login
$user_input = mysqli_real_escape_string($conn, $_POST['username']);
$password_input = $_POST['password']; 

// 1. CEK DI TABEL ADMIN
$cek_admin = mysqli_query($conn, "SELECT * FROM admin WHERE username='$user_input'");
if (mysqli_num_rows($cek_admin) > 0) {
    $data = mysqli_fetch_assoc($cek_admin);
    
    // Verifikasi Password Admin (Sesuaikan jika admin pakai hash atau plain text)
    // Di sini saya asumsikan admin pakai plain text, jika pakai hash ganti jadi password_verify
    if ($password_input == $data['password'] || password_verify($password_input, $data['password'])) { 
        $_SESSION['status']   = "login";
        $_SESSION['id_user']  = $data['id_admin'];
        $_SESSION['username'] = $data['username'];
        $_SESSION['level']    = 'admin';
        header("location:index.php?page=dashboard");
        exit;
    }
}

// 2. CEK DI TABEL SISWA (Cek berdasarkan NIS atau Username kolom 'user')
$cek_siswa = mysqli_query($conn, "SELECT * FROM siswa WHERE nis='$user_input' OR user='$user_input'");
if (mysqli_num_rows($cek_siswa) > 0) {
    $data = mysqli_fetch_assoc($cek_siswa);
    
    // VERIFIKASI PASSWORD HASH UNTUK SISWA
    if (password_verify($password_input, $data['password'])) {
        $_SESSION['status']   = "login";
        $_SESSION['id_user']  = $data['nis'];
        $_SESSION['username'] = $data['user']; // Menampilkan username siswa
        $_SESSION['level']    = 'siswa';
        header("location:index.php?page=dashboard_siswa");
        exit;
    }
}

// 3. JIKA SEMUA GAGAL (Username tidak ada atau Password salah)
echo "<script>alert('Login Gagal! Username/NIS atau Password salah.'); window.location='login.php';</script>";
?>