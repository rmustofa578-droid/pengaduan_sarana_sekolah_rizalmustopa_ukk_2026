<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-header bg-warning text-dark">
                    <h5 class="mb-0">Edit Kategori Sarana</h5>
                </div>
                <div class="card-body">
                    <form action="index.php?page=proses_edit_kategori" method="POST">
                        <input type="hidden" name="id_kategori" value="<?= $kategori['id_kategori']; ?>">

                        <div class="mb-3">
                            <label class="form-label">Nama Kategori</label>
                            <input type="text" name="ket_kategori" class="form-control" 
                                   value="<?= $kategori['ket_kategori']; ?>" required>
                        </div>

                        <div class="d-flex justify-content-between">
                            <a href="index.php?page=kategori" class="btn btn-secondary">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>