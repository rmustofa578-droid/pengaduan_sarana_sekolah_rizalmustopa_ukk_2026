<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Login - E-Aspirasi</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    <style>
        body { background: #0d6efd; height: 100vh; display: flex; align-items: center; justify-content: center; }
        .login-card { width: 400px; border-radius: 15px; border: none; }
    </style>
</head>
<body>

<div class="card login-card shadow-lg p-4">
    <div class="text-center mb-4">
        <div class="bg-primary text-white rounded-circle d-inline-block p-3 mb-2">
            <i class="bi bi-person-fill fs-1"></i>
        </div>
        <h3 class="fw-bold">Login</h3>
        <p class="text-muted small">Masuk untuk mengelola aspirasi</p>
    </div>

    <form action="proses_login.php" method="POST">
        <div class="mb-3">
            <label class="form-label small">Username</label>
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-person"></i></span>
                <input type="text" name="username" class="form-control" placeholder="Masukkan username" required>
            </div>
        </div>
        <div class="mb-3">
            <label class="form-label small">Password</label>
            <div class="input-group">
                <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                <input type="password" name="password" class="form-control" placeholder="Masukkan password" required>
            </div>
        </div>
        <button type="submit" class="btn btn-primary w-100 rounded-pill fw-bold py-2 mt-2">LOGIN</button>
    </form>

    <div class="text-center mt-4">
        <p class="small text-muted mb-1">Belum punya akun?</p>
        <a href="registrasi.php" class="btn btn-outline-secondary btn-sm w-100 rounded-pill mb-3">Daftar Akun Siswa</a>
        
        <a href="index.php" class="text-decoration-none small text-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Beranda
        </a>
    </div>
</div>

</body>
</html>