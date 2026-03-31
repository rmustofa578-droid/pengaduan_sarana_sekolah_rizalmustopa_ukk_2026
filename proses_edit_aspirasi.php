<?php
session_start();
require_once "models/koneksi.php"; 

$db = new Koneksi();
$conn = $db->koneksi;

if (isset($_POST['update'])) {
    $id_aspirasi = $_POST['id_aspirasi'];
    $id_kategori = $_POST['id_kategori'];
    $lokasi      = mysqli_real_escape_string($conn, $_POST['lokasi']);
    $feedback    = mysqli_real_escape_string($conn, $_POST['feedback']);
    
    // Ambil foto lama
    $sql_lama = mysqli_query($conn, "SELECT foto FROM aspirasi WHERE id_aspirasi = '$id_aspirasi'");
    $data_lama = mysqli_fetch_assoc($sql_lama);

    if (!empty($_FILES['foto']['name'])) {
        $foto_nama = time() . "_" . $_FILES['foto']['name'];
        $tujuan = "assets/img/aspirasi/" . $foto_nama;

        if (move_uploaded_file($_FILES['foto']['tmp_name'], $tujuan)) {
            if (!empty($data_lama['foto']) && file_exists("assets/img/aspirasi/" . $data_lama['foto'])) {
                unlink("assets/img/aspirasi/" . $data_lama['foto']);
            }
            $query = "UPDATE aspirasi SET id_kategori='$id_kategori', lokasi='$lokasi', feedback='$feedback', foto='$foto_nama' WHERE id_aspirasi='$id_aspirasi'";
        }
    } else {
        $query = "UPDATE aspirasi SET id_kategori='$id_kategori', lokasi='$lokasi', feedback='$feedback' WHERE id_aspirasi='$id_aspirasi'";
    }

    if (mysqli_query($conn, $query)) {
        echo "<script>alert('Berhasil!'); window.location='index.php?page=riwayat_aspirasi';</script>";
    } else {
        echo "Error: " . mysqli_error($conn);
    }
}
?>