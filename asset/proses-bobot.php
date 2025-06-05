<?php
// Bobot kriteria yang sudah disesuaikan untuk keseimbangan
$bobot = [
    "q1" => 0.15,  // Mampu memecahkan masalah
    "q2" => 0.12,  // Ketelitian
    "q3" => 0.10,  // Kemampuan berkomunikasi
    "q4" => 0.08,  // Bekerja sama
    "q5" => 0.15,  // Programming
    "q6" => 0.10,  // Menggambar
    "q7" => 0.12,  // Keterampilan Pengoperasian Mesin
    "q8" => 0.08,  // Pelajaran Matematika
    "q9" => 0.05,  // Pelajaran Fisika
    "q10" => 0.05, // Pelajaran Kimia
];

// Data jawaban siswa dari form
$jawabanSiswa = [];
for ($i = 1; $i <= 10; $i++) {
    $key = "q$i";
    $jawabanSiswa[$key] = isset($_POST[$key]) ? (int)$_POST[$key] : 0;
}

// Bobot jurusan yang sudah diperbaiki dan diseimbangkan
$jurusan = [
    "TKJ" => [
        "q1" => 5,    // Problem solving tinggi
        "q2" => 4,    // Ketelitian tinggi
        "q3" => 4,    // Komunikasi penting
        "q4" => 4,    // Kerja tim
        "q5" => 5,    // Programming sangat penting
        "q6" => 2,    // Menggambar kurang penting
        "q7" => 3,    // Operasi mesin sedang
        "q8" => 4,    // Matematika penting
        "q9" => 3,    // Fisika sedang
        "q10" => 2,   // Kimia kurang penting
    ],
    "Teknik Listrik" => [
        "q1" => 4,
        "q2" => 5,    // Ketelitian sangat penting
        "q3" => 3,
        "q4" => 4,
        "q5" => 3,
        "q6" => 3,
        "q7" => 5,    // Operasi mesin sangat penting
        "q8" => 4,
        "q9" => 5,    // Fisika sangat penting
        "q10" => 3,
    ],
    "Elektro" => [
        "q1" => 4,
        "q2" => 5,
        "q3" => 3,
        "q4" => 4,
        "q5" => 4,
        "q6" => 3,
        "q7" => 4,
        "q8" => 4,
        "q9" => 5,    // Fisika sangat penting
        "q10" => 4,   // Kimia penting
    ],
    "DPIB" => [
        "q1" => 4,
        "q2" => 4,
        "q3" => 4,
        "q4" => 4,
        "q5" => 3,
        "q6" => 5,    // Menggambar sangat penting
        "q7" => 3,
        "q8" => 4,
        "q9" => 4,
        "q10" => 3,
    ],
    "Teknik Mesin" => [
        "q1" => 4,
        "q2" => 5,
        "q3" => 3,
        "q4" => 4,
        "q5" => 2,
        "q6" => 4,
        "q7" => 5,    // Operasi mesin sangat penting
        "q8" => 4,
        "q9" => 5,
        "q10" => 4,
    ],
    "TKR" => [
        "q1" => 4,
        "q2" => 4,
        "q3" => 3,
        "q4" => 5,    // Kerja tim sangat penting
        "q5" => 2,
        "q6" => 3,
        "q7" => 5,
        "q8" => 3,
        "q9" => 4,
        "q10" => 3,
    ],
    "GT" => [
        "q1" => 4,
        "q2" => 5,
        "q3" => 3,
        "q4" => 3,
        "q5" => 3,
        "q6" => 4,
        "q7" => 4,
        "q8" => 5,    // Matematika sangat penting
        "q9" => 5,
        "q10" => 5,   // Kimia sangat penting
    ],
    "BKP" => [
        "q1" => 3,
        "q2" => 4,
        "q3" => 5,    // Komunikasi sangat penting
        "q4" => 5,    // Kerja tim sangat penting
        "q5" => 2,
        "q6" => 3,
        "q7" => 3,
        "q8" => 3,
        "q9" => 3,
        "q10" => 3,
    ],
];

// Fungsi untuk menghitung skor dengan metode SAW yang diperbaiki
function hitungSkorSAW($jawabanSiswa, $bobot, $jurusan) {
    $skor = [];
    
    // Hitung skor untuk setiap jurusan
    foreach ($jurusan as $namaJurusan => $kriteria) {
        $totalSkor = 0;
        
        foreach ($kriteria as $pernyataan => $bobotJurusan) {
            $jawabanSiswaValue = isset($jawabanSiswa[$pernyataan]) ? $jawabanSiswa[$pernyataan] : 0;
            $bobotKriteria = isset($bobot[$pernyataan]) ? $bobot[$pernyataan] : 0;
            
            // Rumus SAW: (jawaban siswa × bobot jurusan × bobot kriteria)
            $nilaiKriteria = ($jawabanSiswaValue * $bobotJurusan * $bobotKriteria);
            $totalSkor += $nilaiKriteria;
        }
        
        $skor[$namaJurusan] = round($totalSkor, 4);
    }
    
    return $skor;
}

// Hitung skor akhir
$skorAkhir = hitungSkorSAW($jawabanSiswa, $bobot, $jurusan);

// Urutkan berdasarkan skor tertinggi
arsort($skorAkhir);
$jurusanTerbaik = array_keys($skorAkhir)[0];

// Hitung persentase untuk visualisasi
$skorTertinggi = max($skorAkhir);
$persentase = [];
foreach ($skorAkhir as $nama => $skor) {
    $persentase[$nama] = round(($skor / $skorTertinggi) * 100, 1);
}

// Simpan ke session
session_start();
$_SESSION['jurusanTerbaik'] = $jurusanTerbaik;
$_SESSION['skorAkhir'] = $skorAkhir;
$_SESSION['persentase'] = $persentase;
$_SESSION['jawabanSiswa'] = $jawabanSiswa;

// Uncomment untuk redirect ke halaman hasil
header("Location: hasil.php");
exit();
?>