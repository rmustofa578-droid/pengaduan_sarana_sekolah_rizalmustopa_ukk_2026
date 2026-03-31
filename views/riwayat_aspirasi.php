<?php
// views/riwayat_aspirasi.php
$id_user = $_SESSION['id_user'];
$db = new Koneksi();
$conn = $db->koneksi;

// Query ambil data aspirasi milik user yang sedang login
$sql = "SELECT aspirasi.*, kategori.ket_kategori 
        FROM aspirasi 
        LEFT JOIN kategori ON aspirasi.id_kategori = kategori.id_kategori 
        WHERE aspirasi.id_pelapor = '$id_user' 
        ORDER BY aspirasi.id_aspirasi DESC";

$query = mysqli_query($conn, $sql);
?>

<style>
    .img-riwayat {
        width: 60px;
        height: 60px;
        object-fit: cover;
        border-radius: 8px;
        cursor: pointer;
        transition: 0.3s;
    }
    .img-riwayat:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 8px rgba(0,0,0,0.2);
    }
    .badge-status {
        padding: 8px 12px;
        border-radius: 8px;
        font-size: 0.75rem;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .btn-action {
        padding: 5px 10px;
        font-size: 0.8rem;
        border-radius: 8px;
    }
</style>

<div class="card shadow border-0 mt-4" style="border-radius: 15px; overflow: hidden;">
    <div class="card-header bg-success text-white py-3">
        <h5 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2"></i>Riwayat Laporan Saya</h5>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr>
                        <th class="ps-4">No</th>
                        <th>Foto</th>
                        <th>Kategori & Lokasi</th>
                        <th>Isi Laporan</th>
                        <th class="text-center">Status</th>
                        <th class="text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $no = 1;
                    while ($row = mysqli_fetch_assoc($query)) : 
                        $s = $row['status'];
                        if($s == 'Selesai') { $badge = 'success'; }
                        elseif($s == 'Proses') { $badge = 'warning text-dark'; }
                        else { $badge = 'secondary'; }
                    ?>
                    <tr>
                        <td class="ps-4 fw-bold text-muted"><?= $no++; ?></td>
                        <td>
                            <?php if(!empty($row['foto'])): ?>
                                <img src="assets/img/aspirasi/<?= $row['foto'] ?>" 
                                     class="img-riwayat border" 
                                     data-bs-toggle="modal" 
                                     data-bs-target="#modalFoto<?= $row['id_aspirasi'] ?>">
                                
                                <div class="modal fade" id="modalFoto<?= $row['id_aspirasi'] ?>" tabindex="-1" aria-hidden="true">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content border-0 shadow-lg">
                                            <div class="modal-body p-0 text-center bg-dark rounded">
                                                <img src="assets/img/aspirasi/<?= $row['foto'] ?>" class="img-fluid rounded">
                                            </div>
                                            <div class="modal-footer border-0 p-2">
                                                <button type="button" class="btn btn-sm btn-secondary rounded-pill" data-bs-dismiss="modal">Tutup</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php else: ?>
                                <small class="text-muted italic">No Photo</small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <div class="fw-bold text-dark"><?= $row['ket_kategori']; ?></div>
                            <div class="text-danger small">
                                <i class="bi bi-geo-alt-fill me-1"></i><?= $row['lokasi'] ?? '-'; ?>
                            </div>
                        </td>
                        <td>
                            <div class="text-wrap" style="max-width: 250px; font-size: 0.9rem;">
                                <?= $row['feedback']; ?>
                            </div>
                        </td>
                        <td class="text-center">
                            <span class="badge badge-status bg-<?= $badge ?> shadow-sm">
                                <?= $s; ?>
                            </span>
                        </td>
                        <td class="text-center">
                            <?php if($s == 'Menunggu'): ?>
                                <div class="d-flex justify-content-center gap-1">
                                    <a href="edit_aspirasi.php?id=<?= $row['id_aspirasi']; ?>" class="btn btn-warning btn-action shadow-sm">
                                        <i class="bi bi-pencil-square"></i> Edit
                                    </a>
                                    <a href="proses_hapus_aspirasi.php?id=<?= $row['id_aspirasi']; ?>" 
                                       class="btn btn-danger btn-action shadow-sm" 
                                       onclick="return confirm('Yakin ingin menghapus laporan ini?')">
                                        <i class="bi bi-trash"></i> Hapus
                                    </a>
                                </div>
                            <?php else: ?>
                                <span class="text-muted small italic"><i class="bi bi-lock-fill"></i> Terkunci</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endwhile; ?>

                    <?php if(mysqli_num_rows($query) == 0): ?>
                    <tr>
                        <td colspan="6" class="text-center py-5 text-muted">
                            <i class="bi bi-inbox display-4"></i>
                            <p class="mt-2">Kamu belum pernah mengirim laporan.</p>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>