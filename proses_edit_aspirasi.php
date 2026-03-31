<?php
session_start();
require_once "models/koneksi.php"; 

$db = new Koneksi();
$conn = $db->koneksi;

// Cek apakah user sudah login
if (!isset($_SESSION['status'])) {
    header("location:login.php");
    exit;
}

if (isset($_POST['update'])) {
    $id_aspirasi = $_POST['id_aspirasi'];
    $id_user     = $_SESSION['id_user'];
    $id_kategori = $_POST['id_kategori'];
    $lokasi      = mysqli_real_escape_string($conn, $_POST['lokasi']);
    $feedback    = mysqli_real_escape_string($conn, $_POST['feedback']);
    
    // 1. Validasi Kepemilikan (Keamanan Tambahan)
    $cek_milik = mysqli_query($conn, "SELECT foto FROM aspirasi WHERE id_aspirasi = '$id_aspirasi' AND id_pelapor = '$id_user'");
    $data_lama = mysqli_fetch_assoc($cek_milik);

    if (!$data_lama) {
        echo "<script>alert('Anda tidak memiliki akses untuk mengedit ini!'); window.location='index.php?page=riwayat_aspirasi';</script>";
        exit;
    }

    $foto_final = $data_lama['foto']; // Default pakai foto lama

    // 2. Proses Jika Ada Foto Baru
    if (!empty($_FILES['foto']['name'])) {
        $nama_file = $_FILES['foto']['name'];
        $ukuran    = $_FILES['foto']['size'];
        $tmp_name  = $_FILES['foto']['tmp_name'];
        
        $ekstensi_boleh = ['jpg', 'jpeg', 'png'];
        $ekstensi_file  = strtolower(pathinfo($nama_file, PATHINFO_EXTENSION));

        // Cek Ekstensi
        if (!in_array($ekstensi_file, $ekstensi_boleh)) {
            echo "<script>alert('Format file harus JPG, JPEG, atau PNG!'); window.history.back();</script>";
            exit;
        }

        // Cek Ukuran (Maks 2MB)
        if ($ukuran > 2000000) {
            echo "<script>alert('Ukuran file terlalu besar! Maksimal 2MB'); window.history.back();</script>";
            exit;
        }

        // Buat nama unik dan tentukan folder tujuan
        $foto_baru = time() . "_" . $nama_file;
        $tujuan    = "assets/img/aspirasi/" . $foto_baru;

        if (move_uploaded_file($tmp_name, $tujuan)) {
            // Hapus foto lama dari folder jika ada dan jika bukan default/kosong
            if (!empty($data_lama['foto']) && file_exists("assets/img/aspirasi/" . $data_lama['foto'])) {
                unlink("assets/img/aspirasi/" . $data_lama['foto']);
            }
            $foto_final = $foto_baru;
        } else {
            echo "<script>alert('Gagal mengupload foto ke folder tujuan!'); window.history.back();</script>";
            exit;
        }
    }

    // 3. Jalankan Query Update
    $query = "UPDATE aspirasi SET 
                id_kategori = '$id_kategori', 
                lokasi      = '$lokasi', 
                feedback    = '$feedback', 
                foto        = '$foto_final' 
              WHERE id_aspirasi = '$id_aspirasi' AND id_pelapor = '$id_user'";

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Laporan berhasil diperbarui!'); window.location='index.php?page=riwayat_aspirasi';</script>";
    } else {
        echo "<script>alert('Gagal update database: " . mysqli_error($conn) . "'); window.history.back();</script>";
    }

} else {
    // Jika diakses tanpa submit form
    header("location:index.php");
    exit;
}
?>