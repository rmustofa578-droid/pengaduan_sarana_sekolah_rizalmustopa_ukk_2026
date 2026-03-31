<?php
session_start();
include "config/koneksi.php"; // Sesuaikan path koneksi Anda

if (isset($_POST['update'])) {
    $db = new Koneksi();
    $conn = $db->koneksi;

    $id_aspirasi = $_POST['id_aspirasi'];
    $id_kategori = $_POST['id_kategori'];
    $lokasi      = mysqli_real_escape_string($conn, $_POST['lokasi']);
    $feedback    = mysqli_real_escape_string($conn, $_POST['feedback']);
    
    // Ambil data lama untuk hapus foto lama jika ada upload baru
    $query_lama = mysqli_query($conn, "SELECT foto FROM aspirasi WHERE id_aspirasi = '$id_aspirasi'");
    $data_lama = mysqli_fetch_assoc($query_lama);

    $nama_file = $_FILES['foto']['name'];
    $tmp_file  = $_FILES['foto']['tmp_name'];

    if (!empty($nama_file)) {
        // Jika upload foto baru
        $ekstensi = pathinfo($nama_file, PATHINFO_EXTENSION);
        $foto_baru = time() . "_" . $nama_file;
        $path = "assets/img/aspirasi/" . $foto_baru;

        if (move_uploaded_file($tmp_file, $path)) {
            // Hapus foto lama dari folder
            if (!empty($data_lama['foto']) && file_exists("assets/img/aspirasi/" . $data_lama['foto'])) {
                unlink("assets/img/aspirasi/" . $data_lama['foto']);
            }
            $sql = "UPDATE aspirasi SET id_kategori='$id_kategori', lokasi='$lokasi', feedback='$feedback', foto='$foto_baru' WHERE id_aspirasi='$id_aspirasi'";
        }
    } else {
        // Jika tidak upload foto baru
        $sql = "UPDATE aspirasi SET id_kategori='$id_kategori', lokasi='$lokasi', feedback='$feedback' WHERE id_aspirasi='$id_aspirasi'";
    }

    if (mysqli_query($conn, $sql)) {
        echo "<script>alert('Laporan berhasil diupdate!'); window.location='index.php?page=riwayat_aspirasi';</script>";
    } else {
        echo "<script>alert('Gagal update laporan!'); window.history.back();</script>";
    }
}
?>