<?php
session_start();
// Memanggil file koneksi dari folder models
require_once "models/koneksi.php"; 

$db = new Koneksi();
$conn = $db->koneksi;

// Cek status login
if (!isset($_SESSION['status'])) {
    header("location:login.php");
    exit;
}

// Mengambil ID dari parameter URL secara aman
$id = isset($_GET['id']) ? mysqli_real_escape_string($conn, $_GET['id']) : '';
$id_user = $_SESSION['id_user'];

// Query mengambil data laporan yang ingin diedit (hanya milik user yang login)
$query = mysqli_query($conn, "SELECT * FROM aspirasi WHERE id_aspirasi = '$id' AND id_pelapor = '$id_user'");
$data = mysqli_fetch_assoc($query);

// Validasi: Jika data tidak ditemukan atau status sudah bukan 'Menunggu'
if (!$data || $data['status'] != 'Menunggu') {
    echo "<script>alert('Akses ditolak atau laporan sudah diproses!'); window.location='index.php?page=riwayat_aspirasi';</script>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Aspirasi - E-Aspirasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background-color: #f8f9fa; }
        .card-edit { border: none; border-radius: 12px; overflow: hidden; }
        .card-header { background: #ffc107; color: #000; border: none; }
        .img-preview { border-radius: 8px; border: 1px solid #ddd; padding: 4px; background: #fff; }
    </style>
</head>
<body>

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card card-edit shadow">
                <div class="card-header p-3 text-center">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i>Edit Laporan Aspirasi</h5>
                </div>
                <div class="card-body p-4">
                    <form action="proses_edit_aspirasi.php" method="POST" enctype="multipart/form-data">
                        <input type="hidden" name="id_aspirasi" value="<?= $data['id_aspirasi'] ?>">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Lokasi Kejadian</label>
                            <input type="text" name="lokasi" class="form-control" value="<?= $data['lokasi'] ?>" placeholder="Sebutkan lokasi..." required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Isi Laporan / Feedback</label>
                            <textarea name="feedback" class="form-control" rows="4" placeholder="Jelaskan detail aspirasi..." required><?= $data['feedback'] ?></textarea>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Kategori</label>
                            <select name="id_kategori" class="form-select" required>
                                <?php
                                $kat = mysqli_query($conn, "SELECT * FROM kategori");
                                while($k = mysqli_fetch_assoc($kat)) :
                                    $selected = ($k['id_kategori'] == $data['id_kategori']) ? 'selected' : '';
                                ?>
                                    <option value="<?= $k['id_kategori'] ?>" <?= $selected ?>>
                                        <?= $k['ket_kategori'] ?>
                                    </option>
                                <?php endwhile; ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold">Foto Bukti</label>
                            <?php if(!empty($data['foto'])): ?>
                                <div class="mb-2">
                                    <img src="assets/img/aspirasi/<?= $data['foto'] ?>" width="120" class="img-preview shadow-sm">
                                    <p class="text-muted small mt-1 italic">Foto saat ini</p>
                                </div>
                            <?php endif; ?>
                            <input type="file" name="foto" class="form-control">
                            <div class="form-text text-muted small">
                                <i class="bi bi-info-circle"></i> Biarkan kosong jika tidak ingin mengganti foto.
                            </div>
                        </div>

                        <hr>

                        <div class="d-flex gap-2 justify-content-end mt-4">
                            <a href="index.php?page=riwayat_aspirasi" class="btn btn-light rounded-pill px-4 border">Batal</a>
                            <button type="submit" name="update" class="btn btn-warning rounded-pill px-4 fw-bold shadow-sm">
                                <i class="bi bi-save me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="text-center mt-3">
                <p class="text-muted small">&copy; 2026 Pengaduan Sarana Sekolah</p>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>