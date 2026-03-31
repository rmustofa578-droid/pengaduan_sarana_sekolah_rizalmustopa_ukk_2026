<?php
// controller/c_admin.php

class AdminController {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    // 1. Menampilkan Daftar Kategori
    public function kategori_index() {
        $sql = "SELECT kategori.*, COUNT(aspirasi.id_aspirasi) as total_digunakan 
                FROM kategori 
                LEFT JOIN aspirasi ON kategori.id_kategori = aspirasi.id_kategori 
                GROUP BY kategori.id_kategori 
                ORDER BY kategori.id_kategori DESC";
        $result = mysqli_query($this->db, $sql);
        include "views/tampilan_kategori.php";
    }

    // 2. Menampilkan Form Edit (Halaman Terpisah)
    public function kategori_edit() {
        if(isset($_GET['id'])) {
            $id = mysqli_real_escape_string($this->db, $_GET['id']);
            $query = "SELECT * FROM kategori WHERE id_kategori = '$id'";
            $result = mysqli_query($this->db, $query);
            $kategori = mysqli_fetch_assoc($result);
            include "views/tampilan_kategori_edit.php";
        }
    }

    // 3. Proses Tambah Kategori Baru
    public function kategori_store() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $nama = mysqli_real_escape_string($this->db, $_POST['ket_kategori']);
            $query = "INSERT INTO kategori (ket_kategori) VALUES ('$nama')";
            if (mysqli_query($this->db, $query)) {
                echo "<script>alert('Kategori berhasil ditambah!'); window.location='index.php?page=kategori';</script>";
            } else {
                echo "<script>alert('Gagal menambah kategori!'); window.location='index.php?page=kategori';</script>";
            }
        }
    }

    // 4. Proses Update/Simpan Perubahan Kategori
    public function kategori_update() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $id = mysqli_real_escape_string($this->db, $_POST['id_kategori']);
            $nama = mysqli_real_escape_string($this->db, $_POST['ket_kategori']);
            $query = "UPDATE kategori SET ket_kategori = '$nama' WHERE id_kategori = '$id'";
            if (mysqli_query($this->db, $query)) {
                echo "<script>alert('Berhasil Update!'); window.location='index.php?page=kategori';</script>";
            } else {
                echo "<script>alert('Gagal update data!'); window.location='index.php?page=kategori';</script>";
            }
        }
    }

    // 5. Proses Hapus Kategori dengan Pengecekan Relasi Data
    public function kategori_delete() {
        if(isset($_GET['id'])) {
            $id = mysqli_real_escape_string($this->db, $_GET['id']);
            
            // Cek apakah kategori ini masih dipakai di tabel aspirasi
            $cek_query = "SELECT id_aspirasi FROM aspirasi WHERE id_kategori = '$id' LIMIT 1";
            $cek_result = mysqli_query($this->db, $cek_query);
            
            if (mysqli_num_rows($cek_result) > 0) {
                // Pesan jika kategori masih ada isinya (mencegah error database)
                echo "<script>
                    alert('Gagal Hapus! Kategori ini tidak bisa dihapus karena masih memiliki data aspirasi di dalamnya.'); 
                    window.location='index.php?page=kategori';
                </script>";
            } else {
                // Jika bersih, baru hapus
                $query = "DELETE FROM kategori WHERE id_kategori = '$id'";
                if (mysqli_query($this->db, $query)) {
                    echo "<script>alert('Berhasil Hapus!'); window.location='index.php?page=kategori';</script>";
                } else {
                    echo "<script>alert('Error: Gagal menghapus data dari database.'); window.location='index.php?page=kategori';</script>";
                }
            }
        }
    }
}