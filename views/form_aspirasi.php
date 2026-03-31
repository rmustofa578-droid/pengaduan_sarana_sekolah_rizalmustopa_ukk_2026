<?php
// Memanggil model kategori untuk dropdown
$modelKtg = new m_kategori();
$kategori = $modelKtg->kategori();
?>

<div class="container-fluid py-4 animate__animated animate__fadeIn">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                <div class="card-header bg-primary text-white py-3 px-4">
                    <h5 class="mb-0 fw-bold"><i class="bi bi-pencil-square me-2"></i>Kirim Aspirasi Baru</h5>
                </div>
                <div class="card-body p-4">
                    <form action="proses_aspirasi.php" method="POST" enctype="multipart/form-data">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Kategori Fasilitas</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-tag text-primary"></i></span>
                                <select name="id_kategori" class="form-select bg-light border-start-0" required>
                                    <option value="">-- Pilih Kategori Fasilitas --</option>
                                    <?php foreach ($kategori as $ktg) : ?>
                                        <option value="<?= $ktg->id_kategori; ?>"><?= $ktg->ket_kategori; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Lokasi Kejadian</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-geo-alt text-danger"></i></span>
                                <input type="text" name="lokasi" class="form-control bg-light border-start-0" placeholder="Contoh: Toilet Lantai 2, Kantin, atau Ruang Kelas" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold text-muted">Detail Laporan / Aspirasi</label>
                            <textarea name="feedback" class="form-control bg-light" rows="5" placeholder="Tuliskan detail keluhan atau saran Anda secara lengkap..." required></textarea>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-bold text-muted">Foto Bukti (Gambar)</label>
                            <div class="input-group">
                                <span class="input-group-text bg-light border-end-0"><i class="bi bi-camera text-success"></i></span>
                                <input type="file" name="foto" class="form-control bg-light border-start-0" accept="image/*">
                            </div>
                            <div class="form-text text-muted small">Format yang didukung: JPG, JPEG, PNG (Maks. 2MB)</div>
                        </div>

                        <hr class="my-4 opacity-25">

                        <div class="d-flex justify-content-between align-items-center">
                            <a href="index.php?page=dashboard" class="btn btn-outline-secondary rounded-pill px-4">
                                <i class="bi bi-arrow-left me-2"></i>Batal
                            </a>
                            <button type="submit" class="btn btn-primary rounded-pill px-5 fw-bold shadow-sm">
                                Kirim Laporan Sekarang <i class="bi bi-send-fill ms-2"></i>
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <div class="alert alert-info border-0 rounded-4 shadow-sm mt-4 p-3 d-flex align-items-center">
                <i class="bi bi-info-circle-fill fs-4 me-3 text-primary"></i>
                <div class="small">
                    Laporan Anda akan segera masuk ke sistem dan diproses oleh admin sekolah. Pastikan data yang Anda kirimkan sesuai dengan fakta di lapangan.
                </div>
            </div>
        </div>
    </div>
</div>