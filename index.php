<?php
// 1. Memulai Session
session_start();

// 2. Menampilkan Error (Hapus bagian ini jika sudah masuk tahap produksi/selesai)
error_reporting(E_ALL);
ini_set('display_errors', 1);

// 3. Import Model dan Controller
require_once "models/koneksi.php";     
require_once "models/kategori.php";    
require_once "controller/c_admin.php"; 

// 4. Inisialisasi Database dan Objek Controller
$database = new Koneksi(); 
$db = $database->koneksi; 
$admin = new AdminController($db);

// 5. Menentukan halaman yang diakses
$page = isset($_GET['page']) ? $_GET['page'] : 'home';
$user_level = isset($_SESSION['level']) ? $_SESSION['level'] : '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>E-Aspirasi Sekolah</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; min-height: 100vh; }
        .content-area { padding-top: 20px; padding-bottom: 50px; }
        .navbar { box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    </style>
</head>
<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top">
        <div class="container">
            <a class="navbar-brand fw-bold" href="index.php">
                <i class="bi bi-megaphone-fill me-2"></i>E-ASPIRASI
            </a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item"><a class="nav-link" href="index.php?page=home">Home</a></li>
                    
                    <?php if(isset($_SESSION['status']) && $_SESSION['status'] == 'login'): ?>
                        <?php if($user_level == 'admin'): ?>
                            <li class="nav-item"><a class="nav-link" href="index.php?page=dashboard">Dashboard</a></li>
                            <li class="nav-item"><a class="nav-link" href="index.php?page=kategori">Kategori</a></li>
                            <li class="nav-item"><a class="nav-link" href="index.php?page=laporan">Laporan</a></li>
                        <?php else: ?>
                            <li class="nav-item"><a class="nav-link" href="index.php?page=dashboard_siswa">Dashboard</a></li>
                            <li class="nav-item"><a class="nav-link" href="index.php?page=aspirasi">Lapor</a></li>
                            <li class="nav-item"><a class="nav-link" href="index.php?page=riwayat">Riwayat Saya</a></li>
                        <?php endif; ?>
                        
                        <li class="nav-item ms-lg-3">
                            <a class="btn btn-warning btn-sm rounded-pill px-3 fw-bold text-dark" href="logout.php">
                                <i class="bi bi-box-arrow-right"></i> Logout (<?= $_SESSION['username'] ?>)
                            </a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item"><a class="btn btn-light btn-sm px-4 rounded-pill fw-bold" href="login.php">Login</a></li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="container content-area">
        <?php 
        switch ($page) {
            case 'home':
                $user = isset($_SESSION['username']) ? $_SESSION['username'] : 'Tamu';
                echo "
                <div class='p-5 mb-4 bg-white rounded-4 shadow-sm text-center'>
                    <i class='bi bi-megaphone text-primary' style='font-size: 4rem;'></i>
                    <h1 class='display-5 fw-bold text-primary'>Halo, $user!</h1>
                    <p class='lead'>Platform resmi pengaduan sarana dan prasarana sekolah.</p>
                </div>";
                break;

            case 'dashboard':
                if($user_level !== 'admin') { header("location:index.php"); exit; }
                include "views/dashboard_admin.php";
                break;

            case 'dashboard_siswa':
                include "views/dashboard_siswa.php";
                break;

            // --- ROUTING KATEGORI ---
            case 'kategori':
                if($user_level !== 'admin') { header("location:index.php"); exit; }
                $admin->kategori_index(); 
                break;

            case 'edit_kategori':
                if($user_level !== 'admin') { header("location:index.php"); exit; }
                $admin->kategori_edit();
                break;

            case 'proses_tambah_kategori':
                $admin->kategori_store();
                break;

            case 'proses_edit_kategori':
                $admin->kategori_update();
                break;

            case 'hapus_kategori':
                $admin->kategori_delete();
                break;

            // --- ROUTING LAPORAN ---
            case 'laporan':
                if($user_level !== 'admin') { header("location:index.php"); exit; }
                include "views/laporan_aspirasi.php";
                break;

            case 'cetak_laporan':
                if($user_level !== 'admin') { header("location:index.php"); exit; }
                include "views/cetak_laporan.php";
                break;

            // --- ROUTING ASPIRASI ---
            case 'aspirasi':
                include "views/form_aspirasi.php";
                break;

            case 'riwayat':
                include "views/riwayat_aspirasi.php";
                break;

            default:
                echo "<div class='text-center py-5'>
                        <i class='bi bi-exclamation-triangle text-warning' style='font-size: 5rem;'></i>
                        <h1>404</h1>
                        <p>Halaman Tidak Ditemukan.</p>
                        <a href='index.php' class='btn btn-primary'>Kembali ke Home</a>
                      </div>";
                break;
        }
        ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body> 
</html>