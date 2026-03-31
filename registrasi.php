<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registrasi Siswa - E-Aspirasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background: #f4f7fe; height: 100vh; display: flex; align-items: center; }
        .reg-card { max-width: 450px; width: 100%; border: none; border-radius: 15px; }
        .input-group-text { border-right: none; background-color: #fff; }
        .form-control { border-left: none; }
        .form-control:focus { box-shadow: none; border-color: #dee2e6; }
    </style>
</head>
<body>

<div class="container d-flex justify-content-center">
    <div class="card reg-card shadow-lg p-4">
        <div class="text-center mb-4">
            <i class="bi bi-person-plus-fill text-primary" style="font-size: 3rem;"></i>
            <h3 class="fw-bold mt-2">Daftar Akun Siswa</h3>
            <p class="text-muted">Lengkapi data untuk membuat akun laporan</p>
        </div>

        <form action="proses_registrasi.php" method="POST">
            <div class="mb-3">
                <label class="form-label small fw-bold">Username</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-person"></i></span>
                    <input type="text" name="user" class="form-control" placeholder="Contoh: izal_ganteng" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold">NIS (Nomor Induk Siswa)</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-card-list"></i></span>
                    <input type="number" name="nis" class="form-control" placeholder="Masukkan NIS Anda" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold">Kelas</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-door-closed"></i></span>
                    <input type="text" name="kelas" class="form-control" placeholder="Contoh: XII RPL 1" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label small fw-bold">Password</label>
                <div class="input-group">
                    <span class="input-group-text"><i class="bi bi-lock"></i></span>
                    <input type="password" name="password" class="form-control" placeholder="Buat password aman" required>
                </div>
            </div>

            <input type="hidden" name="level" value="siswa">

            <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold py-2 mt-3 shadow-sm">
                DAFTAR SEKARANG
            </button>
        </form>

        <div class="text-center mt-4">
            <p class="small text-muted">Sudah punya akun? <a href="login.php" class="text-decoration-none fw-bold">Login</a></p>
            <a href="index.php" class="text-decoration-none small text-secondary"><i class="bi bi-house-door"></i> Kembali ke Home</a>
        </div>
    </div>
</div>

</body>
</html>