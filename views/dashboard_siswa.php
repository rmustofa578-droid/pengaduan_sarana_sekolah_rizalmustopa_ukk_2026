<?php
if (!isset($_SESSION['status']) || $_SESSION['level'] != 'siswa') {
    echo "<script>window.location='login.php';</script>"; exit;
}

$username = $_SESSION['username'];
$id_user  = $_SESSION['id_user'];
$db       = new Koneksi();
$conn     = $db->koneksi;

// --- AMBIL DATA RINGKASAN DARI DATABASE ---
$query_total   = mysqli_query($conn, "SELECT COUNT(*) as total FROM aspirasi WHERE id_pelapor = '$id_user'");
$query_proses  = mysqli_query($conn, "SELECT COUNT(*) as total FROM aspirasi WHERE id_pelapor = '$id_user' AND status = 'Proses'");
$query_selesai = mysqli_query($conn, "SELECT COUNT(*) as total FROM aspirasi WHERE id_pelapor = '$id_user' AND status = 'Selesai'");

$total   = mysqli_fetch_assoc($query_total)['total'];
$proses  = mysqli_fetch_assoc($query_proses)['total'];
$selesai = mysqli_fetch_assoc($query_selesai)['total'];

// --- AMBIL DATA TABEL (Limit 5 dengan kolom Lokasi & Foto) ---
$sql_tabel = "SELECT aspirasi.*, kategori.ket_kategori 
              FROM aspirasi 
              LEFT JOIN kategori ON aspirasi.id_kategori = kategori.id_kategori 
              WHERE aspirasi.id_pelapor = '$id_user' 
              ORDER BY aspirasi.id_aspirasi DESC LIMIT 5";
$query_tabel = mysqli_query($conn, $sql_tabel);
?>

<div class="container-fluid py-4 animate__animated animate__fadeIn">
    <div class="row g-4 mb-4">
        <div class="col-lg-8">
            <div class="card border-0 rounded-4 shadow-sm bg-primary text-white p-4 h-100">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h2 class="fw-bold">Selamat Datang, <?= ucfirst($username) ?>!</h2>
                        <p class="opacity-75">Ada keluhan mengenai fasilitas sekolah? Sampaikan sekarang agar segera diperbaiki oleh pihak sekolah.</p>
                        <a href="index.php?page=aspirasi" class="btn btn-light text-primary fw-bold rounded-pill px-4 shadow-sm">
                            <i class="bi bi-pencil-square me-2"></i>Buat Laporan Baru
                        </a>
                    </div>
                    <div class="col-md-4 d-none d-md-block text-end">
                        <i class="bi bi-megaphone display-1 opacity-25"></i>
                    </div>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card border-0 shadow-sm rounded-4 p-4 h-100 bg-white">
                <h6 class="fw-bold text-muted mb-3">Ringkasan Laporan Anda</h6>
                <div class="d-flex justify-content-between mb-3 align-items-center">
                    <span class="text-secondary"><i class="bi bi-archive me-2"></i>Total:</span>
                    <span class="badge bg-primary rounded-pill px-3"><?= $total ?></span>
                </div>
                <div class="d-flex justify-content-between mb-3 align-items-center">
                    <span class="text-secondary"><i class="bi bi-arrow-repeat me-2"></i>Proses:</span>
                    <span class="badge bg-warning text-dark rounded-pill px-3"><?= $proses ?></span>
                </div>
                <div class="d-flex justify-content-between align-items-center">
                    <span class="text-secondary"><i class="bi bi-check-circle me-2"></i>Selesai:</span>
                    <span class="badge bg-success rounded-pill px-3"><?= $selesai ?></span>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3 px-4 border-bottom d-flex justify-content-between align-items-center">
            <h5 class="fw-bold mb-0 text-dark"><i class="bi bi-clock-history me-2 text-primary"></i>Riwayat Laporan Terbaru</h5>
            <a href="index.php?page=riwayat" class="btn btn-sm btn-outline-primary rounded-pill">Lihat Semua</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light text-muted small text-uppercase">
                        <tr>
                            <th class="ps-4">No</th>
                            <th>Kategori</th>
                            <th>Lokasi</th>
                            <th>Isi Aspirasi</th>
                            <th>Foto</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1;
                        if (mysqli_num_rows($query_tabel) > 0) {
                            while ($row = mysqli_fetch_assoc($query_tabel)) : 
                                $status = $row['status'];
                                $badge  = ($status == 'Selesai') ? 'success' : (($status == 'Proses') ? 'warning' : 'secondary');
                        ?>
                        <tr>
                            <td class="ps-4 text-muted"><?= $no++ ?></td>
                            <td>
                                <div class="fw-bold text-primary"><?= $row['ket_kategori'] ?></div>
                                <small class="text-muted"><?= date('d/m/Y', strtotime($row['tgl_aspirasi'])) ?></small>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark border fw-normal">
                                    <i class="bi bi-geo-alt me-1 text-danger"></i><?= $row['lokasi'] ?? '-' ?>
                                </span>
                            </td>
                            <td class="text-truncate" style="max-width: 200px;"><?= $row['feedback'] ?></td>
                            <td>
                                <?php if(!empty($row['foto'])): ?>
                                    <a href="assets/img/aspirasi/<?= $row['foto'] ?>" target="_blank">
                                        <img src="assets/img/aspirasi/<?= $row['foto'] ?>" class="rounded-3 shadow-sm border" style="width: 45px; height: 45px; object-fit: cover;" alt="Bukti">
                                    </a>
                                <?php else: ?>
                                    <span class="text-muted x-small">No Photo</span>
                                <?php endif; ?>
                            </td>
                            <td><span class="badge bg-<?= $badge ?> text-<?= ($badge == 'warning' ? 'dark' : 'white') ?> px-3 rounded-pill"><?= $status ?></span></td>
                        </tr>
                        <?php 
                            endwhile; 
                        } else {
                            echo "<tr><td colspan='6' class='text-center py-5 text-muted'>Belum ada laporan yang Anda kirimkan.</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="card-footer bg-light text-center py-3">
            <p class="small mb-0 text-muted italic">Sistem Aspirasi Siswa &copy; 2026</p>
        </div>
    </div>
</div>