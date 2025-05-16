<?php
// Step 1: Definisikan Bobot untuk Setiap Pernyataan (10 Pernyataan)
$bobot = [
    "q1" => 0.1, 
    "q2" => 0.1,
    "q3" => 0.1, 
    "q4" => 0.1, 
    "q5" => 0.1, 
    "q6" => 0.1,
    "q7" => 0.1,
    "q8" => 0.1,
    "q9" => 0.1,
    "q10" => 0.1,
];

// Step 2: Data Jawaban Siswa (Nilai dari 1-5 untuk setiap pernyataan)
$jawabanSiswa = [];
for ($i = 1; $i <= 10; $i++) {
    $key = "q$i";
    $jawabanSiswa[$key] = isset($_POST[$key]) ? (int)$_POST[$key] : 0;
}

// Step 3: Definisikan Jurusan dan Nilai Kriteria Setiap Jurusan
$jurusan = [
    "Teknik Informatika" => [
        "q1" => 5,
        "q2" => 5,
        "q3" => 3,
        "q4" => 2,
        "q5" => 3,
        "q6" => 2,
        "q7" => 4,
        "q8" => 1,
        "q9" => 5,
        "q10" => 3,
    ],
    "Seni Rupa" => [
        "q1" => 2,
        "q2" => 3,
        "q3" => 4,
        "q4" => 5,
        "q5" => 2,
        "q6" => 2,
        "q7" => 3,
        "q8" => 4,
        "q9" => 1,
        "q10" => 4,
    ],
    "Biologi" => [
        "q1" => 3,
        "q2" => 3,
        "q3" => 2,
        "q4" => 2,
        "q5" => 5,
        "q6" => 4,
        "q7" => 3,
        "q8" => 2,
        "q9" => 3,
        "q10" => 2,
    ],
    // Tambahkan jurusan lainnya sesuai kebutuhan
];

// Step 4: Normalisasi Nilai
function normalisasi($jurusan) {
    $normalisasi = [];
    foreach ($jurusan as $namaJurusan => $kriteria) {
        $normalisasi[$namaJurusan] = [];
        foreach ($kriteria as $pernyataan => $nilai) {
            $maxNilai = max(array_column($jurusan, $pernyataan));
            $normalisasi[$namaJurusan][$pernyataan] = $nilai / $maxNilai;
        }
    }
    return $normalisasi;
}

$normalisasiNilai = normalisasi($jurusan);

// Step 5: Hitung Skor Akhir
function hitungSkor($bobot, $normalisasi) {
    $skor = [];
    foreach ($normalisasi as $namaJurusan => $kriteria) {
        $skor[$namaJurusan] = 0;
        foreach ($kriteria as $pernyataan => $nilaiNormalisasi) {
            $skor[$namaJurusan] += $nilaiNormalisasi * $bobot[$pernyataan];
        }
    }
    return $skor;
}

$skorAkhir = hitungSkor($bobot, $normalisasiNilai);

// Step 6: Tentukan Jurusan Terbaik
arsort($skorAkhir);
$jurusanTerbaik = key($skorAkhir);
session_start();

// Setelah hitung jurusan terbaik
$_SESSION['jurusanTerbaik'] = $jurusanTerbaik;
$_SESSION['skorAkhir'] = $skorAkhir;
$_SESSION['jawabanSiswa'] = $jawabanSiswa;

// Redirect ke halaman hasil
header("Location: hasil.php");
exit();
?>