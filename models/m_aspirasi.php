<?php

class AspirasiModel {
    private $db;

    public function __construct($dbConnection) {
        $this->db = $dbConnection;
    }

    // ==========================================
    // BAGIAN KATEGORI (CRUD)
    // ==========================================

    public function getAllKategori() {
        $query = "SELECT * FROM kategori ORDER BY id_kategori DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getKategoriById($id) {
        $query = "SELECT * FROM kategori WHERE id_kategori = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function simpanKategori($ket) {
        $query = "INSERT INTO kategori (ket_kategori) VALUES (:ket)";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':ket', $ket);
        return $stmt->execute();
    }

    public function updateKategori($id, $ket) {
        $query = "UPDATE kategori SET ket_kategori = :ket WHERE id_kategori = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->bindParam(':ket', $ket);
        return $stmt->execute();
    }

    public function deleteKategori($id) {
        $query = "DELETE FROM kategori WHERE id_kategori = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id);
        return $stmt->execute();
    }

    // ==========================================
    // BAGIAN ASPIRASI (UNTUK SISWA)
    // ==========================================

    public function simpanAspirasi($nis, $id_kategori, $lokasi, $ket) {
        try {
            $this->db->beginTransaction();

            // 1. Insert ke input_aspirasi
            $q1 = "INSERT INTO input_aspirasi (nis, id_kategori, lokasi, ket) VALUES (:nis, :id_kategori, :lokasi, :ket)";
            $s1 = $this->db->prepare($q1);
            $s1->execute([
                ':nis' => $nis,
                ':id_kategori' => $id_kategori,
                ':lokasi' => $lokasi,
                ':ket' => $ket
            ]);

            $id_pelaporan = $this->db->lastInsertId();

            // 2. Insert ke tabel aspirasi (inisialisasi status)
            $q2 = "INSERT INTO aspirasi (status, id_kategori, feedback, id_pelaporan) VALUES ('Menunggu', :id_kategori, NULL, :id_pelaporan)";
            $s2 = $this->db->prepare($q2);
            $s2->execute([
                ':id_kategori' => $id_kategori,
                ':id_pelaporan' => $id_pelaporan
            ]);

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function getHistoriBySiswa($nis) {
        $query = "SELECT ia.*, k.ket_kategori, a.status, a.feedback, a.id_aspirasi 
                  FROM input_aspirasi ia
                  JOIN kategori k ON ia.id_kategori = k.id_kategori
                  JOIN aspirasi a ON ia.id_pelaporan = a.id_pelaporan
                  WHERE ia.nis = :nis ORDER BY ia.id_pelaporan DESC";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':nis', $nis);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // ==========================================
    // BAGIAN ADMIN (FEEDBACK & STATUS)
    // ==========================================

    public function getAllAspirasi() {
        $query = "SELECT ia.*, k.ket_kategori, a.status, a.feedback, a.id_aspirasi, s.kelas
                  FROM input_aspirasi ia
                  JOIN kategori k ON ia.id_kategori = k.id_kategori
                  JOIN aspirasi a ON ia.id_pelaporan = a.id_pelaporan
                  JOIN siswa s ON ia.nis = s.nis
                  ORDER BY a.status ASC, ia.id_pelaporan DESC";
        $stmt = $this->db->prepare($query);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAspirasiById($id_aspirasi) {
        $query = "SELECT ia.*, a.status, a.feedback, a.id_aspirasi 
                  FROM aspirasi a 
                  JOIN input_aspirasi ia ON a.id_pelaporan = ia.id_pelaporan 
                  WHERE a.id_aspirasi = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $id_aspirasi);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateStatusAspirasi($id_aspirasi, $status, $feedback) {
        $query = "UPDATE aspirasi SET status = :status, feedback = :feedback WHERE id_aspirasi = :id";
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':status', $status);
        $stmt->bindParam(':feedback', $feedback);
        $stmt->bindParam(':id', $id_aspirasi);
        return $stmt->execute();
    }
}