<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="fw-bold">Manajemen Kategori</h2>
        <button class="btn btn-primary rounded-pill" data-bs-toggle="modal" data-bs-target="#modalTambah">
            <i class="bi bi-plus-circle"></i> Tambah Baru
        </button>
    </div>

    <div class="card border-0 shadow-sm">
        <table class="table table-hover align-middle">
            <thead class="table-light">
                <tr>
                    <th>ID</th>
                    <th>Nama Kategori</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php while($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <td>#<?= $row['id_kategori'] ?></td>
                    <td><?= $row['ket_kategori'] ?></td>
                    <td>
                        <a href="index.php?page=edit_kategori&id=<?= $row['id_kategori'] ?>" class="btn btn-warning btn-sm text-white">
                            <i class="bi bi-pencil"></i>
                        </a>
                        
                        <a href="index.php?page=hapus_kategori&id=<?= $row['id_kategori'] ?>" 
                           class="btn btn-danger btn-sm" onclick="return confirm('Hapus?')">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="modalTambah" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
          <form action="index.php?page=proses_tambah_kategori" method="POST">
    <div class="modal-body">
        <div class="mb-3">
            <label class="form-label small fw-bold">Nama Kategori</label>
            <input type="text" name="ket_kategori" class="form-control rounded-3" placeholder="Contoh: Sarana & Prasarana" required>
        </div>
    </div>
    <div class="modal-footer border-0 pt-0">
        <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Batal</button>
        <button type="submit" class="btn btn-primary rounded-pill px-4">Tambah Sekarang</button>
    </div>
</form>