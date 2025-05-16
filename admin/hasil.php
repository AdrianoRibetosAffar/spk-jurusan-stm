<?php
session_start();

// Ambil data dari session
$jurusanTerbaik = $_SESSION['jurusanTerbaik'] ?? 'Belum Ada';
$skorAkhir = $_SESSION['skorAkhir'] ?? [];
$jawabanSiswa = $_SESSION['jawabanSiswa'] ?? [];
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Hasil Rekomendasi Jurusan</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container my-5">
    <!-- Judul Hasil -->
    <div class="alert alert-success text-center">
        <h2>Jurusan Terbaik untuk Siswa</h2>
        <h3><strong><?= htmlspecialchars($jurusanTerbaik) ?></strong></h3>
    </div>

    <!-- Skor Jurusan -->
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            <h5>Skor Masing-Masing Jurusan</h5>
        </div>
        <div class="card-body">
            <ul class="list-group">
                <?php foreach ($skorAkhir as $jur => $nilai): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?= htmlspecialchars($jur) ?>
                        <span class="badge bg-success rounded-pill"><?= round($nilai, 4) ?></span>
                    </li>
                <?php endforeach; ?>
            </ul>
        </div>
    </div>

    <!-- Tombol Kembali -->
    <div class="text-center">
        <a href="../spk-jurusan-stm.html" class="btn btn-outline-dark">🔙 Kembali ke Formulir</a>
    </div>
</div>
</body>
</html>
