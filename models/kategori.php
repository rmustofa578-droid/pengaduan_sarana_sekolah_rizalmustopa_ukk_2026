<?php
/**
 * Model Kategori - Enterprise Version
 * Menangani semua manipulasi data pada tabel 'kategori'
 */

class m_kategori {
    private $db_conn;

    function __construct() {
        // Inisialisasi koneksi satu kali saat objek dibuat
        $konek = new Koneksi();
        $this->db_conn = $konek->koneksi;
    }

    // 1. READ: Mengambil semua data kategori
    function kategori() {
        $sql = "SELECT * FROM kategori ORDER BY id_kategori DESC";
        $query = mysqli_query($this->db_conn, $sql);
        $result = [];

        if ($query && mysqli_num_rows($query) > 0) {
            while($data = mysqli_fetch_object($query)){
                $result[] = $data;
            }
        }
        return $result;
    }

    // 2. READ SINGLE: Mengambil satu data berdasarkan ID (untuk Edit)
    function detail_kategori($id) {
        $id_clean = mysqli_real_escape_string($this->db_conn, $id);
        $sql = "SELECT * FROM kategori WHERE id_kategori = '$id_clean'";
        $query = mysqli_query($this->db_conn, $sql);
        return mysqli_fetch_object($query);
    }

    // 3. CREATE: Menambah kategori baru
    function tambah_kategori($nama_kategori) {
        $nama = mysqli_real_escape_string($this->db_conn, $nama_kategori);
        $sql = "INSERT INTO kategori (ket_kategori) VALUES ('$nama')";
        return mysqli_query($this->db_conn, $sql);
    }

    // 4. UPDATE: Mengubah nama kategori
    function ubah_kategori($id, $nama_kategori) {
        $id_clean = mysqli_real_escape_string($this->db_conn, $id);
        $nama = mysqli_real_escape_string($this->db_conn, $nama_kategori);
        $sql = "UPDATE kategori SET ket_kategori = '$nama' WHERE id_kategori = '$id_clean'";
        return mysqli_query($this->db_conn, $sql);
    }

    // 5. DELETE: Menghapus kategori
    function hapus_kategori($id) {
        $id_clean = mysqli_real_escape_string($this->db_conn, $id);
        // Pastikan lo hati-hati, kalau kategori dihapus, aspirasi terkait bisa error (tergantung FK)
        $sql = "DELETE FROM kategori WHERE id_kategori = '$id_clean'";
        return mysqli_query($this->db_conn, $sql);
    }

    // 6. COUNT: Menghitung total kategori (untuk statistik Dashboard)
    function total_kategori() {
        $sql = "SELECT COUNT(*) as total FROM kategori";
        $query = mysqli_query($this->db_conn, $sql);
        $data = mysqli_fetch_assoc($query);
        return $data['total'];
    }
}