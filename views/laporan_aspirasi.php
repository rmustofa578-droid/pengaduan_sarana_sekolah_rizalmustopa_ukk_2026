<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card border-0 shadow-sm rounded-4">
                <div class="card-header bg-primary text-white p-3 rounded-top-4">
                    <h5 class="mb-0"><i class="bi bi-printer me-2"></i>Filter Laporan Aspirasi</h5>
                </div>
                <div class="card-body p-4">
                    <form action="index.php" method="GET" target="_blank">
                        <input type="hidden" name="page" value="cetak_laporan">
                        
                        <div class="mb-3">
                            <label class="form-label fw-bold">Dari Tanggal</label>
                            <input type="date" name="tgl_awal" class="form-control" required>
                        </div>
                        
                        <div class="mb-4">
                            <label class="form-label fw-bold">Sampai Tanggal</label>
                            <input type="date" name="tgl_akhir" class="form-control" required>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success btn-lg rounded-pill">
                                <i class="bi bi-file-earmark-pdf me-2"></i>Generate Laporan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>