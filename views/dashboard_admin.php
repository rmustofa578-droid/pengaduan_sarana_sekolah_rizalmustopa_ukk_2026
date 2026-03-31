<?php
if (!isset($_SESSION['status']) || $_SESSION['level'] != 'admin') {
    echo "<script>window.location='login.php';</script>"; exit;
}

$db = new Koneksi();
$conn = $db->koneksi;

// 1. STATISTIK UTAMA
$total_laporan  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM aspirasi"))['total'];
$total_pending  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM aspirasi WHERE status = 'Menunggu'"))['total'];
$total_proses   = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM aspirasi WHERE status = 'Proses'"))['total'];
$total_selesai  = mysqli_fetch_assoc(mysqli_query($conn, "SELECT COUNT(*) as total FROM aspirasi WHERE status = 'Selesai'"))['total'];

// 2. QUERY DATA ASPIRASI (DIPERBAIKI AGAR BISA BACA DARI TABEL SISWA JUGA)
$sql = "SELECT aspirasi.*, kategori.ket_kategori, siswa.nis as pelapor_siswa, admin.username as pelapor_admin
        FROM aspirasi 
        LEFT JOIN kategori ON aspirasi.id_kategori = kategori.id_kategori
        LEFT JOIN siswa ON aspirasi.id_pelapor = siswa.nis
        LEFT JOIN admin ON aspirasi.id_pelapor = admin.id_admin 
        ORDER BY aspirasi.id_aspirasi DESC";
$query = mysqli_query($conn, $sql);
?>

<style>
    :root {
        --primary-gradient: linear-gradient(135deg, #4e73df 0%, #224abe 100%);
        --success-gradient: linear-gradient(135deg, #1cc88a 0%, #13855c 100%);
        --warning-gradient: linear-gradient(135deg, #f6c23e 0%, #dda20a 100%);
        --danger-gradient: linear-gradient(135deg, #e74a3b 0%, #be2617 100%);
    }

    .dashboard-container { animation: fadeIn 0.8s ease-in-out; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }

    .stat-card {
        border: none;
        border-radius: 15px;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        overflow: hidden;
    }

    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1) !important;
    }

    .stat-icon {
        position: absolute;
        right: 15px;
        top: 50%;
        transform: translateY(-50%);
        font-size: 3rem;
        opacity: 0.2;
    }

    .table-container {
        background: white;
        border-radius: 20px;
        overflow: hidden;
    }

    .status-select {
        border-radius: 20px;
        padding: 2px 10px;
        font-weight: 600;
        cursor: pointer;
        transition: 0.3s;
    }

    .status-select:hover { border-color: #4e73df; }

    .badge-status {
        padding: 8px 12px;
        border-radius: 8px;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }

    .img-preview-admin {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 8px;
        cursor: pointer;
        transition: 0.3s;
    }

    .img-preview-admin:hover {
        transform: scale(1.1);
    }

    /* Custom Scrollbar */
    ::-webkit-scrollbar { width: 8px; }
    ::-webkit-scrollbar-track { background: #f1f1f1; }
    ::-webkit-scrollbar-thumb { background: #4e73df; border-radius: 10px; }
</style>

<div class="container-fluid py-4 dashboard-container">
    
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold text-dark mb-0">Panel Kendali Admin</h2>
            <p class="text-muted">Selamat datang kembali! Berikut ringkasan laporan hari ini.</p>
        </div>
        <div class="d-flex gap-2">
            <button class="btn btn-outline-primary rounded-pill px-4 shadow-sm bg-white" onclick="window.print()">
                <i class="bi bi-printer me-2"></i> Cetak Laporan
            </button>
            <button class="btn btn-primary rounded-pill px-4 shadow-sm" data-bs-toggle="modal" data-bs-target="#modalFilter">
                <i class="bi bi-filter me-2"></i> Filter
            </button>
        </div>
    </div>

    <div class="row g-3 mb-5">
        <div class="col-xl-3 col-md-6">
            <div class="card stat-card shadow-sm h-100 p-3" style="background: var(--primary-gradient); color: white;">
                <div class="card-body position-relative">
                    <div class="text-uppercase small fw-bold opacity-75">Total Aspirasi</div>
                    <h2 class="display-6 fw-bold my-2"><?= $total_laporan ?></h2>
                    <div class="small"><i class="bi bi-arrow-up"></i> Semua Laporan Masuk</div>
                    <i class="bi bi-megaphone stat-icon"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card shadow-sm h-100 p-3" style="background: var(--warning-gradient); color: #333;">
                <div class="card-body position-relative">
                    <div class="text-uppercase small fw-bold opacity-75">Menunggu Respon</div>
                    <h2 class="display-6 fw-bold my-2"><?= $total_pending ?></h2>
                    <div class="small"><i class="bi bi-clock-history"></i> Perlu Tindakan Segera</div>
                    <i class="bi bi-hourglass-split stat-icon"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card shadow-sm h-100 p-3" style="background: var(--danger-gradient); color: white;">
                <div class="card-body position-relative">
                    <div class="text-uppercase small fw-bold opacity-75">Sedang Diproses</div>
                    <h2 class="display-6 fw-bold my-2"><?= $total_proses ?></h2>
                    <div class="small"><i class="bi bi-gear-fill"></i> Dalam Pengerjaan</div>
                    <i class="bi bi-tools stat-icon"></i>
                </div>
            </div>
        </div>

        <div class="col-xl-3 col-md-6">
            <div class="card stat-card shadow-sm h-100 p-3" style="background: var(--success-gradient); color: white;">
                <div class="card-body position-relative">
                    <div class="text-uppercase small fw-bold opacity-75">Aspirasi Selesai</div>
                    <h2 class="display-6 fw-bold my-2"><?= $total_selesai ?></h2>
                    <div class="small"><i class="bi bi-check-circle-fill"></i> Berhasil Ditangani</div>
                    <i class="bi bi-check-all stat-icon"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="card border-0 shadow-sm table-container">
        <div class="card-header bg-white py-4 px-4 d-flex justify-content-between align-items-center border-0">
            <div>
                <h5 class="mb-0 fw-bold text-dark">Data Pengaduan Terkini</h5>
                <small class="text-muted">Kelola setiap aspirasi yang masuk dengan bijak</small>
            </div>
            <div class="search-box">
                <div class="input-group">
                    <span class="input-group-text bg-light border-0"><i class="bi bi-search"></i></span>
                    <input type="text" id="searchInput" class="form-control bg-light border-0" placeholder="Cari laporan..." style="width: 250px;">
                </div>
            </div>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0" id="aspirasiTable">
                    <thead class="bg-light">
                        <tr>
                            <th class="ps-4 py-3 border-0">#</th>
                            <th class="border-0">Identitas Pelapor & Lokasi</th>
                            <th class="border-0">Foto</th>
                            <th class="border-0">Kategori</th>
                            <th class="border-0">Isi Laporan</th>
                            <th class="border-0 text-center">Status Saat Ini</th>
                            <th class="border-0 text-center">Tindakan Admin</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $no = 1; 
                        while($row = mysqli_fetch_assoc($query)): 
                            $pelapor = !empty($row['pelapor_siswa']) ? $row['pelapor_siswa'] : $row['pelapor_admin'];
                            
                            $s = $row['status'];
                            if($s == 'Selesai') { $badge = 'success'; }
                            elseif($s == 'Proses') { $badge = 'warning'; }
                            else { $badge = 'secondary'; }
                        ?>
                        <tr style="background-color: transparent; transition: 0.3s;">
                            <td class="ps-4 text-muted fw-bold"><?= $no++ ?></td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <div class="avatar me-3 bg-primary text-white rounded-circle d-flex align-items-center justify-content-center" style="width: 35px; height: 35px;">
                                        <i class="bi bi-person-fill"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold text-dark"><?= $pelapor ?></div>
                                        <div class="text-danger small fw-bold">
                                            <i class="bi bi-geo-alt-fill me-1"></i><?= $row['lokasi'] ?? '-' ?>
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <?php if(!empty($row['foto'])): ?>
                                    <img src="assets/img/aspirasi/<?= $row['foto'] ?>" class="img-preview-admin border shadow-sm" data-bs-toggle="modal" data-bs-target="#imgModal<?= $row['id_aspirasi'] ?>">
                                    
                                    <div class="modal fade" id="imgModal<?= $row['id_aspirasi'] ?>" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content border-0 shadow-lg">
                                                <div class="modal-body p-0">
                                                    <img src="assets/img/aspirasi/<?= $row['foto'] ?>" class="img-fluid rounded">
                                                </div>
                                                <div class="modal-footer border-0 p-2">
                                                    <button type="button" class="btn btn-secondary btn-sm rounded-pill px-3" data-bs-dismiss="modal">Tutup</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <span class="text-muted small">No Photo</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge rounded-pill px-3 py-2" style="background: #e3f2fd; color: #0d47a1; border: 1px solid #bbdefb;">
                                    <?= $row['ket_kategori'] ?>
                                </span>
                            </td>
                            <td>
                                <div class="text-wrap" style="max-width: 250px; font-size: 0.9rem;" title="<?= $row['feedback'] ?>">
                                    <?= $row['feedback'] ?>
                                </div>
                            </td>
                            <td class="text-center">
                                <span class="badge badge-status bg-<?= $badge ?> shadow-sm">
                                    <?= $s ?>
                                </span>
                            </td>
                            <td class="text-center">
                                <form action="proses_update_status.php" method="POST" class="d-inline">
                                    <input type="hidden" name="id_aspirasi" value="<?= $row['id_aspirasi'] ?>">
                                    <div class="input-group input-group-sm justify-content-center">
                                        <select name="status" class="form-select status-select shadow-sm" onchange="confirmUpdate(this)">
                                            <option value="Menunggu" <?= ($s == 'Menunggu'?'selected':'') ?>>Menunggu</option>
                                            <option value="Proses" <?= ($s == 'Proses'?'selected':'') ?>>Proses</option>
                                            <option value="Selesai" <?= ($s == 'Selesai'?'selected':'') ?>>Selesai</option>
                                        </select>
                                    </div>
                                </form>
                            </td>
                        </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <?php if(mysqli_num_rows($query) == 0): ?>
            <div class="text-center py-5">
                <i class="bi bi-folder-x display-1 text-muted"></i>
                <p class="mt-3 text-muted">Belum ada aspirasi yang masuk.</p>
            </div>
            <?php endif; ?>
        </div>
        
        <div class="card-footer bg-white py-3 px-4 border-0">
            <div class="row align-items-center">
                <div class="col">
                    <p class="mb-0 text-muted small">Menampilkan <b><?= mysqli_num_rows($query) ?></b> data aspirasi terbaru</p>
                </div>
                <div class="col text-end">
                    <nav aria-label="Page navigation">
                        <ul class="pagination pagination-sm mb-0 justify-content-end">
                            <li class="page-item disabled"><a class="page-link shadow-none border-0" href="#">Prev</a></li>
                            <li class="page-item active"><a class="page-link shadow-none border-0" href="#">1</a></li>
                            <li class="page-item disabled"><a class="page-link shadow-none border-0" href="#">Next</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="modalFilter" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg rounded-4">
            <div class="modal-header">
                <h5 class="fw-bold">Filter Laporan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Berdasarkan Status</label>
                        <select class="form-select">
                            <option>Semua Status</option>
                            <option>Menunggu</option>
                            <option>Proses</option>
                            <option>Selesai</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label small fw-bold">Urutkan</label>
                        <select class="form-select">
                            <option>Terbaru</option>
                            <option>Terlama</option>
                        </select>
                    </div>
                    <button type="button" class="btn btn-primary w-100 rounded-pill py-2">Terapkan Filter</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Fitur Search Real-time
    document.getElementById('searchInput').addEventListener('keyup', function() {
        let value = this.value.toLowerCase();
        let rows = document.querySelectorAll('#aspirasiTable tbody tr');
        
        rows.forEach(row => {
            let text = row.textContent.toLowerCase();
            row.style.display = text.includes(value) ? '' : 'none';
        });
    });

    // Konfirmasi Sebelum Update
    function confirmUpdate(select) {
        if(confirm('Apakah Anda yakin ingin mengubah status laporan ini menjadi "' + select.value + '"?')) {
            select.form.submit();
        } else {
            location.reload();
        }
    }
</script>