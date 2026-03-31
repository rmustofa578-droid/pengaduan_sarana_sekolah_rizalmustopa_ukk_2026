<?php
// 1. Koneksi
if (!isset($db)) {
    require_once "../models/koneksi.php";
    $database = new Koneksi();
    $db = $database->koneksi;
}

// 2. Ambil Tanggal dari URL
$tgl_awal  = isset($_GET['tgl_awal']) ? $_GET['tgl_awal'] : date('Y-m-d');
$tgl_akhir = isset($_GET['tgl_akhir']) ? $_GET['tgl_akhir'] : date('Y-m-d');

$tgl_a = mysqli_real_escape_string($db, $tgl_awal);
$tgl_b = mysqli_real_escape_string($db, $tgl_akhir);

// 3. Query - Mengambil data lengkap termasuk lokasi & foto
$sql = "SELECT aspirasi.*, kategori.ket_kategori 
        FROM aspirasi 
        LEFT JOIN kategori ON aspirasi.id_kategori = kategori.id_kategori 
        WHERE DATE(aspirasi.tgl_aspirasi) BETWEEN '$tgl_a' AND '$tgl_b'
        ORDER BY aspirasi.id_aspirasi DESC";

$query = mysqli_query($db, $sql);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Cetak Laporan - SMK Sangkuriang 1</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { font-family: 'Times New Roman', serif; background-color: white; color: black; }
        .line-title { border: 0; border-style: inset; border-top: 2px solid #000; margin-top: 10px; }
        @media print {
            .btn-print { display: none; } 
            @page { margin: 1cm; size: landscape; } /* Landscape biar tabel lebar muat */
        }
        table { width: 100%; border-collapse: collapse; table-layout: fixed; word-wrap: break-word; }
        th, td { border: 1px solid black !important; padding: 8px; vertical-align: middle; }
        .text-center { text-align: center; }
        .img-report { max-width: 80px; height: auto; border-radius: 4px; }
    </style>
</head>
<body onload="window.print()"> 
    <div class="container-fluid mt-4">
        <div class="text-center">
            <h2 class="mb-0">SMK SANGKURIANG 1 CIMAHI</h2>
            <p class="mb-0">Jl. Sangkuriang No. 76, Kelurahan Cipageran, Kecamatan Cimahi Utara</p>
            <p>Kota Cimahi, Jawa Barat 40511</p>
            <hr class="line-title">
        </div>

        <h4 class="text-center mt-4 mb-2">LAPORAN PENGADUAN ASPIRASI SISWA</h4>
        <p class="text-center">Periode: <strong><?= date('d/m/Y', strtotime($tgl_awal)) ?></strong> s/d <strong><?= date('d/m/Y', strtotime($tgl_akhir)) ?></strong></p>

        <table class="table table-bordered mt-4">
            <thead class="text-center table-light">
                <tr>
                    <th style="width: 40px;">No</th>
                    <th style="width: 100px;">Tgl Lapor</th>
                    <th style="width: 120px;">Kategori</th>
                    <th style="width: 120px;">Lokasi</th>
                    <th>Isi Laporan</th>
                    <th style="width: 100px;">Foto</th>
                    <th style="width: 90px;">Status</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                if(mysqli_num_rows($query) > 0) {
                    while($d = mysqli_fetch_assoc($query)): 
                ?>
                <tr>
                    <td class="text-center"><?= $no++ ?></td>
                    <td class="text-center"><?= date('d-m-Y', strtotime($d['tgl_aspirasi'])) ?></td>
                    <td><?= $d['ket_kategori'] ?? 'Tanpa Kategori' ?></td>
                    <td><?= $d['lokasi'] ?? '-' ?></td>
                    <td><?= $d['feedback'] ?></td>
                    <td class="text-center">
                        <?php if(!empty($d['foto'])): ?>
                            <img src="../assets/img/aspirasi/<?= $d['foto'] ?>" class="img-report">
                        <?php else: ?>
                            <small class="text-muted">Tidak ada foto</small>
                        <?php endif; ?>
                    </td>
                    <td class="text-center"><?= ucfirst($d['status']) ?></td>
                </tr>
                <?php 
                    endwhile; 
                } else {
                    echo "<tr><td colspan='7' class='text-center py-4'>Data aspirasi tidak ditemukan untuk periode ini.</td></tr>";
                }
                ?>
            </tbody>
        </table>

        <div class="row mt-5">
            <div class="col-8"></div>
            <div class="col-4 text-center">
                <p>Cimahi, <?= date('d F Y') ?></p>
                <p>Kepala Admin,</p>
                <br><br><br>
                <p class="fw-bold text-decoration-underline">Admin E-Aspirasi</p>
            </div>
        </div>
    </div>
</body>
</html>