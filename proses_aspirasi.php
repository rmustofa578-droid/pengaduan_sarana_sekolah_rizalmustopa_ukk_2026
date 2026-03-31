<?php
session_start();
require_once "models/koneksi.php";

$db = new Koneksi();
$conn = $db->koneksi;

// 1. Cek login user
if (!isset($_SESSION['id_user'])) {
    echo "<script>alert('Sesi habis, silakan login kembali'); window.location='login.php';</script>";
    exit;
}

// 2. Ambil data dari FORM (Pastikan atribut 'name' di form_aspirasi.php sudah sesuai)
$id_user     = $_SESSION['id_user']; 
$id_kategori = mysqli_real_escape_string($conn, $_POST['id_kategori']);
$lokasi      = mysqli_real_escape_string($conn, $_POST['lokasi']); 
$deskripsi   = mysqli_real_escape_string($conn, $_POST['feedback']); 

// 3. Logika Upload Foto ke folder assets/img/aspirasi/
$nama_foto = ""; 
if (isset($_FILES['foto']) && $_FILES['foto']['name'] != "") {
    $ekstensi_boleh = array('png', 'jpg', 'jpeg');
    $nama_asli      = $_FILES['foto']['name'];
    $x              = explode('.', $nama_asli);
    $ekstensi       = strtolower(end($x));
    $ukuran         = $_FILES['foto']['size'];
    $file_tmp       = $_FILES['foto']['tmp_name'];

    // Membuat nama file unik berdasarkan waktu agar tidak ada file yang tertimpa
    $nama_foto = date('YmdHis') . "_" . preg_replace("/[^a-zA-Z0-9.]/", "_", $nama_asli);

    if (in_array($ekstensi, $ekstensi_boleh)) {
        if ($ukuran < 2000000) { // Limit maksimal 2MB
            // Jalur folder disesuaikan dengan struktur assets kamu
            $target_dir = "assets/img/aspirasi/"; 
            
            // Cek jika folder belum ada di server, buat otomatis
            if (!is_dir($target_dir)) {
                mkdir($target_dir, 0777, true);
            }
            
            // Proses pindah file dari folder sementara (tmp) ke folder project
            if (!move_uploaded_file($file_tmp, $target_dir . $nama_foto)) {
                echo "<script>alert('Gagal simpan foto ke folder assets/img/aspirasi/! Cek izin folder.'); window.history.back();</script>";
                exit;
            }
        } else {
            echo "<script>alert('Ukuran foto terlalu besar! Maksimal 2MB'); window.history.back();</script>";
            exit;
        }
    } else {
        echo "<script>alert('Format file salah! Gunakan hanya JPG atau PNG.'); window.history.back();</script>";
        exit;
    }
}

// 4. Proses Simpan Data ke Database
// Nonaktifkan pengecekan foreign key sementara (opsional)
mysqli_query($conn, "SET FOREIGN_KEY_CHECKS=0");

/**
 * Query INSERT ke tabel aspirasi
 * Mencakup: status, id_kategori, lokasi, feedback, foto, id_pelapor, dan tgl_aspirasi (Otomatis NOW)
 */
$sql = "INSERT INTO aspirasi (status, id_kategori, lokasi, feedback, foto, id_pelapor, tgl_aspirasi) 
        VALUES ('Menunggu', '$id_kategori', '$lokasi', '$deskripsi', '$nama_foto', '$id_user', NOW())";

if (mysqli_query($conn, $sql)) {
    // Aktifkan kembali pengecekan foreign key
    mysqli_query($conn, "SET FOREIGN_KEY_CHECKS=1");
    
    echo "<script>
            alert('Aspirasi Berhasil dikirim!'); 
            window.location='index.php?page=riwayat';
          </script>";
} else {
    echo "Gagal menyimpan ke database! Error: " . mysqli_error($conn);
}
?>