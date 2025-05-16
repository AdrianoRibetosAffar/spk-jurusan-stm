<?php
// Bobot pertanyaan ini
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

// data jawaban siswa dari pernyataan
$jawabanSiswa = [];
for ($i = 1; $i <= 10; $i++) {
    $key = "q$i";
    $jawabanSiswa[$key] = isset($_POST[$key]) ? (int)$_POST[$key] : 0;
}

// Bobot jurusan berdasarkan kriteria
$jurusan = [
    "TKJ" => [
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
    "Elektro" => [
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
    "Listrik" => [
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
    // sa mo tambahkan jurusan lain disini nanti
];

// normalisasi nilai jawaban siswa
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

// hitung nilai normalisasi berdasarkan jawaban siswa
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

//Ini tentukan jurusan terbaik berdasarkan skor akhir
arsort($skorAkhir);
$jurusanTerbaik = key($skorAkhir);
session_start();

// Setelah hitung jurusan terbaik
$_SESSION['jurusanTerbaik'] = $jurusanTerbaik;
$_SESSION['skorAkhir'] = $skorAkhir;
$_SESSION['jawabanSiswa'] = $jawabanSiswa;

//ini untk ke halaman hasil
header("Location: hasil.php");
exit();
?>